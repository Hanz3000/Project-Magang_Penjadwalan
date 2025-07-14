<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubTask;


class SubTaskController extends Controller
{

    
   public function toggle(Request $request, $id)
{
    $request->validate([
        'completed' => 'required|boolean',
    ]);

    $subtask = SubTask::findOrFail($id);
    $subtask->completed = $request->completed;
    $subtask->save();

    $task = $subtask->task;
    $completedSubtasks = $task->subTasks()->where('completed', true)->count();
    $totalSubtasks = $task->subTasks()->count();
    $progress = $totalSubtasks > 0 ? round(($completedSubtasks / $totalSubtasks) * 100) : 0;

    return response()->json([
        'success' => true,
        'completed' => $subtask->completed,
        'task_id' => $task->id,
        'subtasksCompleted' => $completedSubtasks,
        'subtasksTotal' => $totalSubtasks,
        'progress' => $progress
    ]);
}


}
