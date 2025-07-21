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
        $tasks = Task::with(['subTasks' => function ($query) {
            $query->orderBy('parent_id')->orderBy('created_at');
        }, 'category'])->get();

        // Calculate duration for each task and prepare calendar data
        $tasks->each(function ($task) {
            $task->durationDays = $task->start_date && $task->end_date
                ? $task->start_date->diffInDays($task->end_date) + 1
                : 0;

            // Calculate progress for calendar
            $leafSubTasks = $task->subTasks->filter(function ($subTask) use ($task) {
                return !$task->subTasks->where('parent_id', $subTask->id)->count();
            });

            $task->calendarProgress = $leafSubTasks->count() > 0
                ? round(($leafSubTasks->where('completed', true)->count() / $leafSubTasks->count()) * 100)
                : ($task->completed ? 100 : 0);
        });

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



    public function edit(Task $task)
    {
        $categories = Category::all();
        return view('tasks.edit', compact('task', 'categories'));
    }


    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'priority' => 'required|in:urgent,high,medium,low',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'due_date' => 'nullable|date',
        ]);

        $task->update($request->only([
            'title',
            'description',
            'category_id',
            'priority',
            'start_time',
            'end_time',
            'due_date',
            'start_date',
            'end_date'
        ]));

        // Handle existing subtasks updates
        if ($request->has('subtasks')) {
            foreach ($request->subtasks as $subtaskData) {
                if (isset($subtaskData['id'])) {
                    $subtask = $task->subTasks()->find($subtaskData['id']);
                    if ($subtask) {
                        $subtask->update([
                            'title' => $subtaskData['title'],
                            'completed' => isset($subtaskData['completed']) ? true : false,
                        ]);
                    }
                }
            }
        }

        // Handle new subtasks
        if ($request->has('new_subtasks')) {
            foreach ($request->new_subtasks as $newSubtaskData) {
                if (!empty($newSubtaskData['title'])) {
                    $task->subTasks()->create([
                        'title' => $newSubtaskData['title'],
                        'completed' => isset($newSubtaskData['completed']) ? true : false,
                        'task_id' => $task->id,
                        'parent_id' => null, // tidak nested dulu
                        'is_group' => false
                    ]);
                }
            }
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task berhasil diperbarui!');
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

    /**
     * Toggle task completion status and all its subtasks
     */
    public function toggle(Request $request, Task $task)
    {
        $request->validate([
            'completed' => 'required|boolean',
        ]);

        $isCompleted = $request->completed;

        // Use a transaction to ensure all updates are atomic
        return DB::transaction(function () use ($task, $isCompleted) {
            // Update the main task's completion status
            $task->completed = $isCompleted;
            $task->save();

            // Update all subtasks for this task to match the main task status
            $task->subTasks()->update(['completed' => $isCompleted]);

            // Get leaf subtasks for progress calculation
            $leafSubTasks = $task->subTasks->filter(function ($st) use ($task) {
                return $task->subTasks->where('parent_id', $st->id)->count() == 0;
            });

            $subtaskCompleted = $isCompleted ? $leafSubTasks->count() : 0;
            $subtaskTotal = $leafSubTasks->count();

            // Calculate progress percentage
            $progressPercentage = $subtaskTotal > 0 ? round(($subtaskCompleted / $subtaskTotal) * 100) : ($isCompleted ? 100 : 0);

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

    /**
     * Toggle all subtasks for a task at once (alternative endpoint)
     */
    public function toggleAll(Request $request, $taskId)
    {
        $request->validate([
            'completed' => 'required|boolean',
        ]);

        $task = Task::findOrFail($taskId);
        $isCompleted = $request->completed;

        // Use a transaction to ensure all updates are atomic
        return DB::transaction(function () use ($task, $isCompleted) {
            // Update all subtasks for this task
            $task->subTasks()->update(['completed' => $isCompleted]);

            // Update the task's completion status
            $task->completed = $isCompleted;
            $task->save();

            // Get leaf subtasks for progress calculation
            $leafSubTasks = $task->subTasks->filter(function ($st) use ($task) {
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
