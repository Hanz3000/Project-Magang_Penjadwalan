<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use App\Models\SubTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['category', 'subTasks'])->latest()->get();
        $categories = Category::withCount('tasks')->get();

        $priorityCounts = [
            'urgent' => Task::where('priority', 'urgent')->count(),
            'high' => Task::where('priority', 'high')->count(),
            'medium' => Task::where('priority', 'medium')->count(),
            'low' => Task::where('priority', 'low')->count(),
        ];

        $totalTasks = Task::count();

        return view('tasks.index', compact('tasks', 'categories', 'priorityCounts', 'totalTasks'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('tasks.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:urgent,high,medium,low',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'subtasks' => 'nullable|array',
            'subtasks.*' => 'string|max:255'
        ]);

        $task = Task::create($request->only([
            'title',
            'description',
            'category_id',
            'priority',
            'start_date',
            'end_date'
        ]));

        if ($request->has('subtasks')) {
            foreach ($request->subtasks as $subtaskTitle) {
                if (!empty($subtaskTitle)) {
                    $task->subTasks()->create(['title' => $subtaskTitle]);
                }
            }
        }

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil ditambahkan!');
    }

    public function edit(Task $task)
    {
        $categories = Category::all();
        return view('tasks.edit', compact('task', 'categories'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:urgent,high,medium,low',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'subtasks' => 'nullable|array',
            'subtasks.*' => 'string|max:255'
        ]);

        $task->update($request->only([
            'title',
            'description',
            'category_id',
            'priority',
            'start_date',
            'end_date',
            'completed'
        ]));

        // Update sub tasks
        if ($request->has('subtasks')) {
            $task->subTasks()->delete(); // Hapus yang lama
            foreach ($request->subtasks as $subtaskTitle) {
                if (!empty($subtaskTitle)) {
                    $task->subTasks()->create(['title' => $subtaskTitle]);
                }
            }
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully');
    }

    public function destroy(Task $task)
    {
        try {
            DB::beginTransaction();

            $task->subTasks()->delete();
            $task->delete();

            DB::commit();

            return redirect()->route('tasks.index')
                ->with('success', 'Task berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('tasks.index')
                ->with('error', 'Gagal menghapus task: ' . $e->getMessage());
        }
    }

    public function toggle(Task $task)
    {
        $task->update(['completed' => !$task->completed]);
        return back();
    }
}
