<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubTask;
use App\Models\Task; // Pastikan Anda mengimpor model Task

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

        // Ambil tugas induk dari subtask
        $task = $subtask->task;

        // Hitung kembali jumlah subtask yang selesai dan total
        $completedSubtasks = $task->subTasks()->where('completed', true)->count();
        $totalSubtasks = $task->subTasks()->count();

        // Hitung persentase progres
        $progress = $totalSubtasks > 0 ? round(($completedSubtasks / $totalSubtasks) * 100) : 0;
        
        // Cek apakah semua subtask sudah selesai
        $mainTaskCompleted = ($totalSubtasks > 0 && $completedSubtasks === $totalSubtasks);

        // Perbarui status 'completed' pada tugas induk
        $task->completed = $mainTaskCompleted;
        $task->save();

        // Mengembalikan respons JSON
        return response()->json([
            'success' => true,
            'completed' => $subtask->completed,
            'task_id' => $task->id,
            'subtasksCompleted' => $completedSubtasks,
            'subtasksTotal' => $totalSubtasks,
            'progress' => $progress,
            'mainTaskCompleted' => $mainTaskCompleted // Kirim status tugas utama
        ]);
    }
}