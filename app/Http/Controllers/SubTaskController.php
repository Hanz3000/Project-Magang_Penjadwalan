<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubTask;
use App\Models\Task;

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

        // Hitung kembali jumlah subtask yang selesai dan total untuk tugas induk
        $leafSubTasks = $task->subTasks->filter(function($st) use ($task) {
            return $task->subTasks->where('parent_id', $st->id)->count() == 0;
        });
        
        $subtaskCompleted = $leafSubTasks->where('completed', true)->count();
        $subtaskTotal = $leafSubTasks->count();

        // Hitung persentase progres subtask
        $progressPercentage = $subtaskTotal > 0 ? round(($subtaskCompleted / $subtaskTotal) * 100) : 0;
        
        // Cek apakah semua subtask sudah selesai untuk menentukan status tugas utama
        $mainTaskCompleted = ($subtaskTotal > 0 && $subtaskCompleted === $subtaskTotal);

        // Perbarui status 'completed' pada tugas induk
        $task->completed = $mainTaskCompleted;
        $task->save();

        // Hitung total tasks dan completed tasks untuk summary global
        $totalTasks = Task::count(); // Asumsi semua task dihitung, sesuaikan jika ada filter user
        $completedTasks = Task::where('completed', true)->count(); // Asumsi semua task dihitung, sesuaikan jika ada filter user
        $overallProgress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        // Mengembalikan respons JSON sesuai format yang diharapkan frontend
        return response()->json([
            'success' => true,
            'subtask' => [
                'id' => $subtask->id,
                'completed' => $subtask->completed,
                'title' => $subtask->title // Pastikan model SubTask memiliki atribut 'title'
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
    }
}
