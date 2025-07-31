<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\TaskCollaborator;
use App\Models\TaskRevision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CollaborationController extends Controller
{
    /**
     * Invite user to collaborate on a task
     */
    public function invite(Request $request, Task $task)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'can_edit' => 'boolean'
    ]);

    // Pastikan user adalah pemilik task
    if ($task->user_id !== Auth::id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $user = User::where('email', $request->email)->first();
    
    // Cek tidak mengundang diri sendiri
    if ($user->id === Auth::id()) {
        return response()->json(['error' => 'Tidak dapat mengundang diri sendiri'], 400);
    }

    // Check if already invited
    $existingInvite = TaskCollaborator::where('task_id', $task->id)
        ->where('user_id', $user->id)
        ->first();

    if ($existingInvite) {
        if ($existingInvite->status === 'pending') {
            return response()->json(['error' => 'Undangan sudah dikirim dan menunggu respons'], 400);
        } elseif ($existingInvite->status === 'approved') {
            return response()->json(['error' => 'User sudah berkolaborasi pada tugas ini'], 400);
        }
    }

    $collaborator = TaskCollaborator::create([
        'task_id' => $task->id,
        'user_id' => $user->id,
        'status' => 'pending',
        'can_edit' => $request->boolean('can_edit', false),
        'invited_by' => Auth::id(),
        'invited_at' => now(),
        'responded_at' => null
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Undangan kolaborasi berhasil dikirim',
        'collaborator' => $collaborator->load('user')
    ]);
}
    /**
     * Respond to collaboration invite
     */
    public function respondToInvite(Request $request, TaskCollaborator $collaborator)
    {
        $request->validate([
            'action' => 'required|in:accept,reject'
        ]);

        if ($collaborator->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($collaborator->status !== 'pending') {
            return response()->json(['error' => 'Undangan sudah direspons'], 400);
        }

        $collaborator->update([
            'status' => $request->action === 'accept' ? 'approved' : 'rejected',
            'responded_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => $request->action === 'accept' 
                ? 'Undangan kolaborasi diterima' 
                : 'Undangan kolaborasi ditolak'
        ]);
    }

    /**
     * Get collaboration invites for current user
     */
    public function getInvites()
    {
        $invites = TaskCollaborator::with(['task', 'inviter'])
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->orderBy('invited_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'invites' => $invites
        ]);
    }

    /**
     * Submit revision for task
     */
    public function submitRevision(Request $request, Task $task)
    {
        $request->validate([
            'revision_type' => 'required|in:create,update,delete',
            'proposed_data' => 'required|array'
        ]);

        // Check if user is collaborator
        $collaborator = TaskCollaborator::where('task_id', $task->id)
            ->where('user_id', Auth::id())
            ->where('status', 'approved')
            ->first();

        if (!$collaborator) {
            return response()->json(['error' => 'Anda tidak memiliki akses kolaborasi pada tugas ini'], 403);
        }

        if (!$collaborator->can_edit) {
            return response()->json(['error' => 'Anda tidak memiliki izin edit pada tugas ini'], 403);
        }

        // Get current task data
        $originalData = [
            'title' => $task->title,
            'description' => $task->description,
            'priority' => $task->priority,
            'start_date' => $task->start_date,
            'end_date' => $task->end_date,
            'start_time' => $task->start_time,
            'end_time' => $task->end_time,
            'is_all_day' => $task->is_all_day,
            'category_id' => $task->category_id
        ];

        $revision = TaskRevision::create([
            'task_id' => $task->id,
            'collaborator_id' => Auth::id(),
            'revision_type' => $request->revision_type,
            'original_data' => $originalData,
            'proposed_data' => $request->proposed_data,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Usulan perubahan berhasil dikirim untuk review',
            'revision' => $revision
        ]);
    }

    /**
     * Get pending revisions for task owner
     */
    public function getPendingRevisions()
    {
        $revisions = TaskRevision::with(['task', 'collaborator'])
            ->whereHas('task', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'revisions' => $revisions
        ]);
    }

    /**
     * Review revision (approve/reject)
     */
    public function reviewRevision(Request $request, TaskRevision $revision)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Check if user owns the task
        if ($revision->task->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($revision->status !== 'pending') {
            return response()->json(['error' => 'Revisi sudah di-review'], 400);
        }

        DB::transaction(function () use ($request, $revision) {
            $revision->update([
                'status' => $request->action === 'approve' ? 'approved' : 'rejected',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'review_notes' => $request->notes
            ]);

            // If approved, apply changes to the task
            if ($request->action === 'approve') {
                $task = $revision->task;
                $proposedData = $revision->proposed_data;

                $task->update([
                    'title' => $proposedData['title'] ?? $task->title,
                    'description' => $proposedData['description'] ?? $task->description,
                    'priority' => $proposedData['priority'] ?? $task->priority,
                    'start_date' => $proposedData['start_date'] ?? $task->start_date,
                    'end_date' => $proposedData['end_date'] ?? $task->end_date,
                    'start_time' => $proposedData['start_time'] ?? $task->start_time,
                    'end_time' => $proposedData['end_time'] ?? $task->end_time,
                    'is_all_day' => $proposedData['is_all_day'] ?? $task->is_all_day,
                    'category_id' => $proposedData['category_id'] ?? $task->category_id
                ]);

                // Handle subtasks if provided
                if (isset($proposedData['subtasks'])) {
                    // Process subtasks updates here
                    // Similar to the existing subtask logic in TaskController
                }
            }
        });

        return response()->json([
            'success' => true,
            'message' => $request->action === 'approve' 
                ? 'Revisi disetujui dan perubahan telah diterapkan' 
                : 'Revisi ditolak'
        ]);
    }

    /**
     * Get collaboration status for task
     */
    public function getCollaborationStatus(Task $task)
    {
        $collaborators = TaskCollaborator::with('user')
            ->where('task_id', $task->id)
            ->get();

        $pendingRevisions = TaskRevision::with('collaborator')
            ->where('task_id', $task->id)
            ->where('status', 'pending')
            ->count();

        return response()->json([
            'success' => true,
            'collaborators' => $collaborators,
            'pending_revisions_count' => $pendingRevisions,
            'is_owner' => $task->user_id === Auth::id(),
            'is_collaborator' => $collaborators->where('user_id', Auth::id())->where('status', 'approved')->isNotEmpty()
        ]);
    }

    /**
     * Remove collaborator
     */
    public function removeCollaborator(Task $task, TaskCollaborator $collaborator)
    {
        if ($task->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($collaborator->task_id !== $task->id) {
            return response()->json(['error' => 'Invalid collaborator'], 400);
        }

        $collaborator->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kolaborator berhasil dihapus'
        ]);
    }

    /**
     * Get my collaborated tasks
     */
    public function getMyCollaboratedTasks()
    {
        $tasks = Task::with(['category', 'subTasks', 'collaborators'])
            ->whereHas('collaborators', function ($query) {
                $query->where('user_id', Auth::id())
                      ->where('status', 'approved');
            })
            ->get();

        // Transform tasks data similar to TaskController@index
        $transformedTasks = $tasks->map(function ($task) {
            $durationDays = $task->start_date && $task->end_date
                ? $task->start_date->diffInDays($task->end_date) + 1
                : 0;

            $leafSubTasks = $task->subTasks->filter(function ($subTask) use ($task) {
                return !$task->subTasks->where('parent_id', $subTask->id)->count();
            });

            $completedCount = $leafSubTasks->where('completed', true)->count();
            $totalCount = $leafSubTasks->count();
            $calendarProgress = $totalCount > 0 
                ? round(($completedCount / $totalCount) * 100)
                : ($task->completed ? 100 : 0);

            return [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'priority' => $task->priority,
                'start_date' => $task->start_date->format('Y-m-d'),
                'end_date' => $task->end_date->format('Y-m-d'),
                'start_date_formatted' => $task->start_date->format('M d'),
                'end_date_formatted' => $task->end_date->format('M d'),
                'start_time' => $task->start_time ? $task->start_time->format('H:i') : null,
                'end_time' => $task->end_time ? $task->end_time->format('H:i') : null,
                'completed' => $task->completed,
                'durationDays' => $durationDays,
                'calendarProgress' => $calendarProgress,
                'is_all_day' => $task->is_all_day,
                'is_collaborator' => true,
                'owner' => $task->user,
                'sub_tasks' => $task->subTasks->map(function ($subtask) {
                    return [
                        'id' => $subtask->id,
                        'title' => $subtask->title,
                        'completed' => $subtask->completed,
                        'parent_id' => $subtask->parent_id,
                        'is_group' => $subtask->is_group,
                        'start_date' => $subtask->start_date ? $subtask->start_date->format('Y-m-d') : null,
                        'end_date' => $subtask->end_date ? $subtask->end_date->format('Y-m-d') : null,
                    ];
                })->all()
            ];
        });

        return response()->json([
            'success' => true,
            'tasks' => $transformedTasks
        ]);
    }
}