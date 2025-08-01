<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskCollaborator;
use App\Models\TaskRevision;
use App\Models\User;
use App\Models\SubTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class CollaborationController extends Controller
{
    public function getInvites()
    {
        try {
            $invites = TaskCollaborator::with(['task', 'inviter'])
                ->where('user_id', Auth::id())
                ->where('status', 'pending')
                ->get();

            return response()->json([
                'success' => true,
                'invites' => $invites->map(function($invite) {
                    return [
                        'id' => $invite->id,
                        'task' => [
                            'id' => $invite->task->id,
                            'title' => $invite->task->title,
                            'description' => $invite->task->description,
                            'start_date' => $invite->task->start_date?->format('Y-m-d'),
                            'end_date' => $invite->task->end_date?->format('Y-m-d'),
                        ],
                        'inviter' => [
                            'name' => $invite->inviter->name,
                            'email' => $invite->inviter->email,
                        ],
                        'can_edit' => $invite->can_edit,
                        'invited_at' => $invite->invited_at?->format('Y-m-d H:i:s'),
                    ];
                })
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting invites: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to load invites'
            ], 500);
        }
    }

    public function invite(Request $request, Task $task)
    {
        try {
            // Check if user is owner
            if ($task->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Only task owner can send invites'
                ], 403);
            }

            $request->validate([
                'email' => 'required|email|exists:users,email',
                'can_edit' => 'boolean',
            ]);

            $user = User::where('email', $request->email)->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'User not found'
                ], 404);
            }

            if ($user->id === Auth::id()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Cannot invite yourself'
                ], 400);
            }

            // Check if already invited
            $existing = TaskCollaborator::where('task_id', $task->id)
                ->where('user_id', $user->id)
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'error' => 'User already invited or collaborating'
                ], 400);
            }

            // Create invitation
            TaskCollaborator::create([
                'task_id' => $task->id,
                'user_id' => $user->id,
                'can_edit' => $request->boolean('can_edit', false),
                'status' => 'pending',
                'invited_by' => Auth::id(),
                'invited_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Invitation sent successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error sending invite: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to send invitation'
            ], 500);
        }
    }

    public function respondToInvite(Request $request, TaskCollaborator $collaborator)
    {
        try {
            // Check if user is the invited user
            if ($collaborator->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Unauthorized'
                ], 403);
            }

            if ($collaborator->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'error' => 'Invitation already responded to'
                ], 400);
            }

            $request->validate([
                'action' => 'required|in:accept,reject'
            ]);

            $collaborator->update([
                'status' => $request->action === 'accept' ? 'approved' : 'rejected',
                'responded_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => $request->action === 'accept' ? 'Invitation accepted' : 'Invitation rejected'
            ]);

        } catch (\Exception $e) {
            Log::error('Error responding to invite: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to respond to invitation'
            ], 500);
        }
    }

    public function getCollaborationStatus(Task $task)
    {
        try {
            // Check if user can view this task
            if (!$task->canView(Auth::id())) {
                return response()->json([
                    'success' => false,
                    'error' => 'Access denied'
                ], 403);
            }

            $collaborators = $task->collaborators()->with('user')->get();
            $pendingRevisionsCount = $task->revisions()->where('status', 'pending')->count();

            return response()->json([
                'success' => true,
                'is_owner' => $task->user_id === Auth::id(),
                'collaborators' => $collaborators->map(function($collab) {
                    return [
                        'id' => $collab->id,
                        'user' => [
                            'id' => $collab->user->id,
                            'name' => $collab->user->name,
                            'email' => $collab->user->email,
                        ],
                        'can_edit' => $collab->can_edit,
                        'status' => $collab->status,
                        'invited_at' => $collab->invited_at?->format('Y-m-d H:i:s'),
                    ];
                }),
                'pending_revisions_count' => $pendingRevisionsCount,
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting collaboration status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to load collaboration status'
            ], 500);
        }
    }

    public function removeCollaborator(Task $task, TaskCollaborator $collaborator)
    {
        try {
            // Check if user is task owner
            if ($task->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,       
                    'error' => 'Only task owner can remove collaborators'
                ], 403);
            }

            // Check if collaborator belongs to this task
            if ($collaborator->task_id !== $task->id) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid collaborator'
                ], 400);
            }

            $collaborator->delete();

            return response()->json([
                'success' => true,
                'message' => 'Collaborator removed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error removing collaborator: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to remove collaborator'
            ], 500);
        }
    }

    public function getPendingRevisions()
    {
        try {
            $revisions = TaskRevision::with(['task', 'collaborator'])
                ->whereHas('task', function($q) {
                    $q->where('user_id', Auth::id());
                })
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'revisions' => $revisions->map(function($revision) {
                    return [
                        'id' => $revision->id,
                        'task' => [
                            'id' => $revision->task->id,
                            'title' => $revision->task->title,
                        ],
                        'collaborator' => [
                            'id' => $revision->collaborator->id,
                            'name' => $revision->collaborator->name,
                            'email' => $revision->collaborator->email,
                        ],
                        'revision_type' => $revision->revision_type,
                        'original_data' => $revision->original_data,
                        'proposed_data' => $revision->proposed_data,
                        'created_at' => $revision->created_at->toISOString(),
                    ];
                })
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting pending revisions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to load pending revisions'
            ], 500);
        }
    }

    public function reviewRevision(Request $request, TaskRevision $revision)
    {
        try {
            Log::info('ReviewRevision called', [
                'revision_id' => $revision->id,
                'action' => $request->action,
                'user_id' => Auth::id()
            ]);

            $request->validate([
                'action' => 'required|in:approve,reject',
                'notes' => 'nullable|string|max:500'
            ]);

            // Check if user is task owner
            if ($revision->task->user_id !== Auth::id()) {
                Log::warning('Unauthorized revision review attempt', [
                    'revision_id' => $revision->id,
                    'task_owner' => $revision->task->user_id,
                    'current_user' => Auth::id()
                ]);
                
                return response()->json([
                    'success' => false,
                    'error' => 'Only task owner can review revisions'
                ], 403);
            }

            if ($revision->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'error' => 'Revision already reviewed'
                ], 400);
            }

            return DB::transaction(function () use ($request, $revision) {
                $action = $request->action;
                
                // Update revision status
                $revision->update([
                    'status' => $action === 'approve' ? 'approved' : 'rejected',
                    'reviewed_by' => Auth::id(),
                    'reviewed_at' => now(),
                    'review_notes' => $request->notes,
                ]);

                Log::info('Revision status updated', [
                    'revision_id' => $revision->id,
                    'new_status' => $revision->status
                ]);

                if ($action === 'approve') {
                    $this->applyRevisionChanges($revision);
                }

                return response()->json([
                    'success' => true,
                    'message' => $action === 'approve' ? 'Changes approved and applied' : 'Changes rejected'
                ]);
            });

        } catch (\Exception $e) {
            Log::error('Error reviewing revision', [
                'revision_id' => $revision->id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to review revision: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Apply approved revision changes
     */
    private function applyRevisionChanges(TaskRevision $revision)
    {
        $proposedData = $revision->proposed_data;
        
        if (isset($proposedData['action'])) {
            switch ($proposedData['action']) {
                case 'create_subtask':
                    $this->createSubtaskFromRevision($revision, $proposedData['subtask_data']);
                    break;
                    
                case 'update_subtask':
                    $this->updateSubtaskFromRevision($revision, $proposedData);
                    break;
                    
                default:
                    // Handle task updates
                    $this->updateTaskFromRevision($revision, $proposedData);
                    break;
            }
        } else {
            // Legacy task update
            $this->updateTaskFromRevision($revision, $proposedData);
        }
    }

    /**
     * Create subtask from approved revision
     */
    private function createSubtaskFromRevision(TaskRevision $revision, array $subtaskData)
    {
        SubTask::create([
            'task_id' => $revision->task_id,
            'title' => $subtaskData['title'],
            'description' => $subtaskData['description'] ?? null,
            'parent_id' => $subtaskData['parent_id'] ?? null,
            'is_group' => $subtaskData['is_group'] ?? false,
            'start_date' => Carbon::parse($subtaskData['start_date']),
            'end_date' => Carbon::parse($subtaskData['end_date']),
            'start_time' => $subtaskData['start_time'] ? Carbon::createFromFormat('H:i', $subtaskData['start_time']) : null,
            'end_time' => $subtaskData['end_time'] ? Carbon::createFromFormat('H:i', $subtaskData['end_time']) : null,
            'completed' => false,
        ]);

        Log::info('Subtask created from revision', [
            'revision_id' => $revision->id,
            'task_id' => $revision->task_id
        ]);
    }

    /**
     * Update subtask from approved revision
     */
    private function updateSubtaskFromRevision(TaskRevision $revision, array $proposedData)
    {
        $subtaskId = $proposedData['subtask_id'];
        $subtaskData = $proposedData['subtask_data'];
        
        $subtask = SubTask::find($subtaskId);
        if ($subtask) {
            $subtask->update([
                'title' => $subtaskData['title'],
                'description' => $subtaskData['description'] ?? null,
                'start_date' => Carbon::parse($subtaskData['start_date']),
                'end_date' => Carbon::parse($subtaskData['end_date']),
                'start_time' => $subtaskData['start_time'] ? Carbon::createFromFormat('H:i', $subtaskData['start_time']) : null,
                'end_time' => $subtaskData['end_time'] ? Carbon::createFromFormat('H:i', $subtaskData['end_time']) : null,
            ]);

            Log::info('Subtask updated from revision', [
                'revision_id' => $revision->id,
                'subtask_id' => $subtaskId
            ]);
        }
    }

    /**
     * Update task from approved revision
     */
    private function updateTaskFromRevision(TaskRevision $revision, array $proposedData)
    {
        $task = $revision->task;
        
        // Filter out any fields that don't exist in the database
        $validFields = [
            'title',
            'description', 
            'priority',
            'category_id',
            'start_date',
            'end_date',
            'start_time',
            'end_time',
            'is_all_day'
        ];
        
        $updateData = [];
        foreach ($proposedData as $field => $value) {
            if (in_array($field, $validFields)) {
                // Handle date fields
                if (in_array($field, ['start_date', 'end_date']) && $value) {
                    $updateData[$field] = Carbon::parse($value);
                } 
                // Handle time fields  
                else if (in_array($field, ['start_time', 'end_time']) && $value) {
                    $updateData[$field] = Carbon::createFromFormat('H:i', $value);
                }
                // Handle boolean fields
                else if ($field === 'is_all_day') {
                    $updateData[$field] = (bool) $value;
                }
                // Handle other fields
                else {
                    $updateData[$field] = $value;
                }
            }
        }

        Log::info('Applying task updates', [
            'task_id' => $task->id,
            'update_data' => $updateData
        ]);

        $task->update($updateData);
        
        Log::info('Task updated successfully', [
            'task_id' => $task->id
        ]);
    }

    public function getMyCollaboratedTasks()
    {
        try {
            $tasks = Task::with(['user', 'category', 'subTasks', 'collaborators'])
                ->whereHas('collaborators', function($q) {
                    $q->where('user_id', Auth::id())
                      ->where('status', 'approved');
                })
                ->get();

            return response()->json([
                'success' => true,
                'tasks' => $tasks
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting collaborated tasks: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to load collaborated tasks'
            ], 500);
        }
    }
}