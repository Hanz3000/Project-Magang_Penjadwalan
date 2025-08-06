<?php

namespace App\Http\Controllers;

use App\Models\SubTask;
use App\Models\Task;
use App\Models\TaskRevision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SubTaskController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'task_id' => 'required|exists:tasks,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'parent_id' => 'nullable|exists:sub_tasks,id',
                'is_group' => 'boolean',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'start_time' => 'nullable|date_format:H:i',
                'end_time' => 'nullable|date_format:H:i|required_with:start_time',
            ]);

            $task = Task::findOrFail($validated['task_id']);

            if (!$task->canEdit(Auth::id())) {
                return response()->json(['success' => false, 'error' => 'Anda tidak memiliki izin untuk menambah subtask'], 403);
            }

            if ($task->user_id === Auth::id()) {
                return $this->createSubtaskDirectly($task, $validated);
            } else {
                $collaborator = $task->collaborators()
                    ->where('user_id', Auth::id())
                    ->where('status', 'approved')
                    ->where('can_edit', true)
                    ->first();

                if (!$collaborator) {
                    return response()->json(['success' => false, 'error' => 'Anda tidak memiliki izin edit untuk task ini'], 403);
                }

                return $this->createSubtaskRevision($task, $validated);
            }
        } catch (\Exception $e) {
            Log::error('Error creating subtask:', ['error' => $e->getMessage(), 'user_id' => Auth::id(), 'request_data' => $request->all()]);
            return response()->json(['success' => false, 'error' => 'Gagal menambah subtask: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store multiple subtasks with nested structure
     */
    public function storeMultiple(Request $request)
    {
        try {
            $validated = $request->validate([
                'task_id' => 'required|exists:tasks,id',
                'subtasks' => 'required|array',
                'subtasks.*.title' => 'required|string|max:255',
                'subtasks.*.description' => 'nullable|string',
                'subtasks.*.parent_id' => 'nullable',
                'subtasks.*.is_group' => 'boolean',
                'subtasks.*.start_date' => 'required|date',
                'subtasks.*.end_date' => 'required|date|after_or_equal:subtasks.*.start_date',
                'subtasks.*.start_time' => 'nullable|date_format:H:i',
                'subtasks.*.end_time' => 'nullable|date_format:H:i',
                'subtasks.*.level' => 'nullable|integer',
            ]);

            $task = Task::findOrFail($validated['task_id']);

            if (!$task->canEdit(Auth::id())) {
                return response()->json(['success' => false, 'error' => 'Anda tidak memiliki izin untuk menambah subtask'], 403);
            }

            if ($task->user_id === Auth::id()) {
                return $this->createMultipleSubtasksDirectly($task, $validated['subtasks']);
            } else {
                $collaborator = $task->collaborators()
                    ->where('user_id', Auth::id())
                    ->where('status', 'approved')
                    ->where('can_edit', true)
                    ->first();

                if (!$collaborator) {
                    return response()->json(['success' => false, 'error' => 'Anda tidak memiliki izin edit untuk task ini'], 403);
                }

                return $this->createMultipleSubtasksRevision($task, $validated['subtasks']);
            }
        } catch (\Exception $e) {
            Log::error('Error creating multiple subtasks:', ['error' => $e->getMessage(), 'user_id' => Auth::id(), 'request_data' => $request->all()]);
            return response()->json(['success' => false, 'error' => 'Gagal menambah subtasks: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, SubTask $subtask)
    {
        try {
            if (!$subtask->canEdit(Auth::id())) {
                return response()->json(['success' => false, 'error' => 'Anda tidak memiliki izin untuk mengedit subtask ini'], 403);
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'start_time' => 'nullable|date_format:H:i',
                'end_time' => 'nullable|date_format:H:i|required_with:start_time',
            ]);

            $task = $subtask->task;

            if ($task->user_id === Auth::id()) {
                return $this->updateSubtaskDirectly($subtask, $validated);
            } else {
                return $this->updateSubtaskRevision($subtask, $validated);
            }
        } catch (\Exception $e) {
            Log::error('Error updating subtask:', ['error' => $e->getMessage(), 'subtask_id' => $subtask->id, 'user_id' => Auth::id()]);
            return response()->json(['success' => false, 'error' => 'Gagal mengupdate subtask: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(SubTask $subtask)
    {
        try {
            $task = $subtask->task;

            if ($task->user_id !== Auth::id()) {
                return response()->json(['success' => false, 'error' => 'Hanya pemilik task yang dapat menghapus subtask'], 403);
            }

            DB::transaction(function () use ($subtask) {
                $this->deleteSubtaskRecursively($subtask);
            });

            return response()->json(['success' => true, 'message' => 'Subtask berhasil dihapus']);
        } catch (\Exception $e) {
            Log::error('Error deleting subtask:', ['error' => $e->getMessage(), 'subtask_id' => $subtask->id, 'user_id' => Auth::id()]);
            return response()->json(['success' => false, 'error' => 'Gagal menghapus subtask: ' . $e->getMessage()], 500);
        }
    }

    private function createSubtaskDirectly(Task $task, array $validated)
    {
        return DB::transaction(function () use ($task, $validated) {
            $subtask = SubTask::create([...$validated, 'task_id' => $task->id, 'completed' => false]);
            return response()->json(['success' => true, 'message' => 'Subtask berhasil ditambahkan', 'subtask' => $subtask->load('task')]);
        });
    }

    private function createMultipleSubtasksDirectly(Task $task, array $subtasksData)
    {
        return DB::transaction(function () use ($task, $subtasksData) {
            $idMapping = [];
            $createdSubtasks = [];
            
            // Sort by level to create parents before children
            $sortedSubtasks = collect($subtasksData)->sortBy(function($subtask) {
                return $subtask['level'] ?? 0;
            });

            foreach ($sortedSubtasks as $tempId => $subtaskData) {
                // Resolve parent_id
                $parentId = null;
                if (!empty($subtaskData['parent_id'])) {
                    $parentId = $idMapping[$subtaskData['parent_id']] ?? $subtaskData['parent_id'];
                }

                $subtask = SubTask::create([
                    'task_id' => $task->id,
                    'title' => $subtaskData['title'],
                    'description' => $subtaskData['description'] ?? null,
                    'parent_id' => $parentId,
                    'is_group' => $subtaskData['is_group'] ?? false,
                    'start_date' => Carbon::parse($subtaskData['start_date']),
                    'end_date' => Carbon::parse($subtaskData['end_date']),
                    'start_time' => $subtaskData['start_time'] ? Carbon::createFromFormat('H:i', $subtaskData['start_time']) : null,
                    'end_time' => $subtaskData['end_time'] ? Carbon::createFromFormat('H:i', $subtaskData['end_time']) : null,
                    'completed' => false,
                ]);

                $idMapping[$tempId] = $subtask->id;
                $createdSubtasks[] = $subtask;
            }

            return response()->json([
                'success' => true, 
                'message' => 'Subtasks berhasil ditambahkan', 
                'subtasks' => $createdSubtasks,
                'id_mapping' => $idMapping
            ]);
        });
    }

    private function createSubtaskRevision(Task $task, array $validated)
    {
        return DB::transaction(function () use ($task, $validated) {
            $revision = TaskRevision::create([
                'task_id' => $task->id,
                'collaborator_id' => Auth::id(),
                'revision_type' => 'create_subtask',
                'original_data' => null,
                'proposed_data' => ['action' => 'create_subtask', 'subtask_data' => $validated],
                'status' => 'pending'
            ]);
            
            Log::info('Subtask creation revision created', [
                'revision_id' => $revision->id,
                'task_id' => $task->id,
                'collaborator_id' => Auth::id(),
                'subtask_data' => $validated
            ]);
            
            return response()->json([
                'success' => true, 
                'message' => '✅ Usulan penambahan subtask telah dikirim dan menunggu persetujuan pemilik tugas', 
                'revision_id' => $revision->id
            ]);
        });
    }

    private function createMultipleSubtasksRevision(Task $task, array $subtasksData)
    {
        return DB::transaction(function () use ($task, $subtasksData) {
            $revision = TaskRevision::create([
                'task_id' => $task->id,
                'collaborator_id' => Auth::id(),
                'revision_type' => 'create_multiple_subtasks',
                'original_data' => null,
                'proposed_data' => ['action' => 'create_multiple_subtasks', 'subtasks_data' => $subtasksData],
                'status' => 'pending'
            ]);
            
            Log::info('Multiple subtasks creation revision created', [
                'revision_id' => $revision->id,
                'task_id' => $task->id,
                'collaborator_id' => Auth::id(),
                'subtasks_count' => count($subtasksData)
            ]);
            
            return response()->json([
                'success' => true, 
                'message' => '✅ Usulan penambahan subtasks telah dikirim dan menunggu persetujuan pemilik tugas', 
                'revision_id' => $revision->id
            ]);
        });
    }

    private function updateSubtaskDirectly(SubTask $subtask, array $validated)
    {
        return DB::transaction(function () use ($subtask, $validated) {
            $subtask->update($validated);
            return response()->json(['success' => true, 'message' => 'Subtask berhasil diperbarui', 'subtask' => $subtask->fresh()->load('task')]);
        });
    }

    private function updateSubtaskRevision(SubTask $subtask, array $validated)
    {
        return DB::transaction(function () use ($subtask, $validated) {
            $originalData = [
                'title' => $subtask->title,
                'description' => $subtask->description,
                'start_date' => $subtask->start_date->format('Y-m-d'),
                'end_date' => $subtask->end_date->format('Y-m-d'),
                'start_time' => $subtask->start_time ? $subtask->start_time->format('H:i') : null,
                'end_time' => $subtask->end_time ? $subtask->end_time->format('H:i') : null,
            ];
            
            $revision = TaskRevision::create([
                'task_id' => $subtask->task_id,
                'collaborator_id' => Auth::id(),
                'revision_type' => 'update_subtask',
                'original_data' => [
                    'action' => 'update_subtask', 
                    'subtask_id' => $subtask->id, 
                    'subtask_data' => $originalData
                ],
                'proposed_data' => [
                    'action' => 'update_subtask', 
                    'subtask_id' => $subtask->id, 
                    'subtask_data' => $validated
                ],
                'status' => 'pending'
            ]);
            
            Log::info('Subtask update revision created', [
                'revision_id' => $revision->id,
                'subtask_id' => $subtask->id,
                'task_id' => $subtask->task_id,
                'collaborator_id' => Auth::id(),
                'original_data' => $originalData,
                'proposed_data' => $validated
            ]);
            
            return response()->json([
                'success' => true, 
                'message' => '✅ Usulan perubahan subtask telah dikirim dan menunggu persetujuan pemilik tugas', 
                'revision_id' => $revision->id
            ]);
        });
    }

    private function deleteSubtaskRecursively(SubTask $subtask)
    {
        foreach ($subtask->children as $child) {
            $this->deleteSubtaskRecursively($child);
        }
        $subtask->delete();
    }

    public function getTaskSubtasks($taskId)
    {
        try {
            $task = Task::findOrFail($taskId);

            if (!$task->canView(Auth::id())) {
                return response()->json(['success' => false, 'error' => 'Anda tidak memiliki akses ke task ini'], 403);
            }

            $subtasks = $task->subTasks()->orderBy('parent_id')->orderBy('created_at')->get();
            return response()->json(['success' => true, 'subtasks' => $subtasks, 'can_edit' => $task->canEdit(Auth::id()), 'is_owner' => $task->user_id === Auth::id()]);
        } catch (\Exception $e) {
            Log::error('Error getting subtasks:', ['error' => $e->getMessage(), 'task_id' => $taskId, 'user_id' => Auth::id()]);
            return response()->json(['success' => false, 'error' => 'Gagal memuat subtasks'], 500);
        }
    }

    public function toggle(Request $request, $id)
    {
        $request->validate(['completed' => 'required|boolean']);
        $subtask = SubTask::findOrFail($id);
        $isCompleted = $request->completed;

        return DB::transaction(function() use ($subtask, $isCompleted) {
            $subtask->completed = $isCompleted;
            $subtask->save();
            $task = $subtask->task;

            $leafSubTasks = $task->subTasks->filter(fn($st) => $task->subTasks->where('parent_id', $st->id)->count() == 0);
            $subtaskCompleted = $leafSubTasks->where('completed', true)->count();
            $subtaskTotal = $leafSubTasks->count();
            $progressPercentage = $subtaskTotal > 0 ? round(($subtaskCompleted / $subtaskTotal) * 100) : 0;
            $mainTaskCompleted = $isCompleted ? ($subtaskTotal > 0 && $subtaskCompleted === $subtaskTotal) : false;

            $task->completed = $mainTaskCompleted;
            $task->save();

            $totalTasks = Task::count();
            $completedTasks = Task::where('completed', true)->count();
            $overallProgress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

            return response()->json([
                'success' => true,
                'subtask' => ['id' => $subtask->id, 'completed' => $subtask->completed, 'title' => $subtask->title],
                'task' => ['id' => $task->id, 'completed' => $task->completed],
                'progressPercentage' => $progressPercentage,
                'subtaskCompleted' => $subtaskCompleted,
                'subtaskTotal' => $subtaskTotal,
                'totalTasks' => $totalTasks,
                'completedTasks' => $completedTasks,
                'overallProgress' => $overallProgress
            ]);
        });
    }

    public function toggleAll(Request $request, $taskId)
    {
        $request->validate(['completed' => 'required|boolean']);
        $task = Task::findOrFail($taskId);
        $isCompleted = $request->completed;

        return DB::transaction(function() use ($task, $isCompleted) {
            $task->subTasks()->update(['completed' => $isCompleted]);
            $task->completed = $isCompleted;
            $task->save();

            $leafSubTasks = $task->subTasks->filter(fn($st) => $task->subTasks->where('parent_id', $st->id)->count() == 0);
            $subtaskCompleted = $isCompleted ? $leafSubTasks->count() : 0;
            $subtaskTotal = $leafSubTasks->count();
            $progressPercentage = $subtaskTotal > 0 ? round(($subtaskCompleted / $subtaskTotal) * 100) : 0;

            $totalTasks = Task::count();
            $completedTasks = Task::where('completed', true)->count();
            $overallProgress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

            return response()->json([
                'success' => true,
                'task' => ['id' => $task->id, 'completed' => $task->completed],
                'progressPercentage' => $progressPercentage,
                'subtaskCompleted' => $subtaskCompleted,
                'subtaskTotal' => $subtaskTotal,
                'totalTasks' => $totalTasks,
                'completedTasks' => $completedTasks,
                'overallProgress' => $overallProgress
            ]);
        });
    }
}