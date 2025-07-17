<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubTask;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class SubTaskController extends Controller
{
    /**
     * Toggle the completion status of a single subtask
     * MODIFIED: Now also updates main task status when subtask is unchecked
     */
    public function toggle(Request $request, $id)
    {
        $request->validate([
            'completed' => 'required|boolean',
        ]);

        $subtask = SubTask::findOrFail($id);
        $isCompleted = $request->completed;
        
        // Use transaction to ensure atomicity
        return DB::transaction(function() use ($subtask, $isCompleted) {
            // Update the subtask
            $subtask->completed = $isCompleted;
            $subtask->save();

            // Ambil tugas induk dari subtask
            $task = $subtask->task;

            // Hitung kembali jumlah subtask yang selesai dan total untuk tugas induk
            $leafSubTasks = $task->subTasks->filter(function($st) use ($task) {
                return $task->subTasks->where('parent_id', $st->id)->count() == 0;
            });
            
            $subtaskCompleted = $leafSubTasks->where('completed', true)->count();
            $subtaskTotal = $leafSubTasks->count();

            // Hitung persentase progres subtask
            $progressPercentage = $subtaskTotal > 0 ? round(($subtaskCompleted / $subtaskTotal) * 100) : 0;
            
            // MODIFIED LOGIC: Update main task status based on subtask completion
            if ($isCompleted) {
                // If subtask is checked, only mark main task as completed if ALL subtasks are completed
                $mainTaskCompleted = ($subtaskTotal > 0 && $subtaskCompleted === $subtaskTotal);
            } else {
                // If subtask is unchecked, ALWAYS uncheck the main task
                $mainTaskCompleted = false;
            }

            // Perbarui status 'completed' pada tugas induk
            $task->completed = $mainTaskCompleted;
            $task->save();

            // Hitung total tasks dan completed tasks untuk summary global
            $totalTasks = Task::count();
            $completedTasks = Task::where('completed', true)->count();
            $overallProgress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

            // Mengembalikan respons JSON sesuai format yang diharapkan frontend
            return response()->json([
                'success' => true,
                'subtask' => [
                    'id' => $subtask->id,
                    'completed' => $subtask->completed,
                    'title' => $subtask->title
                ],
                'task' => [
                    'id' => $task->id,
                    'completed' => $task->completed
                ],
                'progressPercentage' => $progressPercentage,
                'subtaskCompleted' => $subtaskCompleted,
                'subtaskTotal' => $subtaskTotal,
                'totalTasks' => $totalTasks,
                'completedTasks' => $completedTasks,
                'overallProgress' => $overallProgress
            ]);
        });
    }

    /**
     * Toggle all subtasks for a task at once
     */
    public function toggleAll(Request $request, $taskId)
    {
        $request->validate([
            'completed' => 'required|boolean',
        ]);

        $task = Task::findOrFail($taskId);
        $isCompleted = $request->completed;

        // Use a transaction to ensure all updates are atomic
        return DB::transaction(function() use ($task, $isCompleted) {
            // Update all subtasks for this task
            $task->subTasks()->update(['completed' => $isCompleted]);
            
            // Update the task's completion status
            $task->completed = $isCompleted;
            $task->save();

            // Get leaf subtasks for progress calculation
            $leafSubTasks = $task->subTasks->filter(function($st) use ($task) {
                return $task->subTasks->where('parent_id', $st->id)->count() == 0;
            });
            
            $subtaskCompleted = $isCompleted ? $leafSubTasks->count() : 0;
            $subtaskTotal = $leafSubTasks->count();
            
            // Calculate progress percentage
            $progressPercentage = $subtaskTotal > 0 ? round(($subtaskCompleted / $subtaskTotal) * 100) : 0;
            
            // Calculate global summary stats
            $totalTasks = Task::count();
            $completedTasks = Task::where('completed', true)->count();
            $overallProgress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

            // Return response with all necessary data
            return response()->json([
                'success' => true,
                'task' => [
                    'id' => $task->id,
                    'completed' => $task->completed
                ],
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

