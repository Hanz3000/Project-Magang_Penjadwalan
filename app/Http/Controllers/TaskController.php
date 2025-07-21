<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use App\Models\SubTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TaskController extends Controller
{
   public function index()
{
    $rawTasks = Task::with([
        'subTasks' => function ($query) {
            $query->orderBy('parent_id')->orderBy('created_at');
        },
        'category'
    ])->get();

    $tasks = $rawTasks->map(function ($task) {
        $durationDays = $task->start_date && $task->end_date
            ? $task->start_date->diffInDays($task->end_date) + 1
            : 0;

        $leafSubTasks = $task->subTasks->filter(function ($subTask) use ($task) {
            return !$task->subTasks->where('parent_id', $subTask->id)->count();
        });

        $calendarProgress = $leafSubTasks->count() > 0
            ? round(($leafSubTasks->where('completed', true)->count() / $leafSubTasks->count()) * 100)
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

            // Jadikan sub_tasks sebagai Collection
            'sub_tasks' => collect($task->subTasks->map(function ($subtask) {
                return [
                    'id' => $subtask->id,
                    'title' => $subtask->title,
                    'completed' => $subtask->completed,
                    'parent_id' => $subtask->parent_id,
                    'is_group' => $subtask->is_group,
                    'start_date' => $subtask->start_date ? $subtask->start_date->format('Y-m-d') : null,
                    'end_date' => $subtask->end_date ? $subtask->end_date->format('Y-m-d') : null,
                ];
            }))
        ];
    });

    // Tambahan info
    $categories = Category::withCount('tasks')->get();

    $priorityCounts = [
        'urgent' => Task::where('priority', 'urgent')->count(),
        'high' => Task::where('priority', 'high')->count(),
        'medium' => Task::where('priority', 'medium')->count(),
        'low' => Task::where('priority', 'low')->count(),
    ];

    $totalTasks = $tasks->count();

    return view('tasks.index', [
        'tasks' => $tasks->all(), // tetap array agar aman di Blade
        'categories' => $categories,
        'priorityCounts' => $priorityCounts,
        'totalTasks' => $totalTasks
    ]);
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
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|required_with:start_time',
            'subtasks' => 'nullable|array',
            'subtasks.*.title' => 'required|string|max:255',
            'subtasks.*.parent_id' => 'nullable',
            'subtasks.*.is_group' => 'nullable|boolean',
        ]);

        // Additional time validation
        if ($request->start_time && $request->end_time) {
            $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->start_date . ' ' . $request->start_time);
            $endDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->end_date . ' ' . $request->end_time);
            
            if ($startDateTime->gte($endDateTime)) {
                return back()->withErrors(['end_time' => 'Waktu selesai harus setelah waktu mulai.'])->withInput();
            }
        }

        $task = Task::create($request->only([
            'title',
            'description',
            'category_id',
            'priority',
            'start_date',
            'end_date',
            'start_time',
            'end_time'
        ]));

        // Simpan semua subtasks
        if ($request->has('subtasks')) {
            $map = []; // untuk menyimpan id sementara dari front-end ke ID DB

            foreach ($request->subtasks as $tempId => $subtask) {
                $newSub = new \App\Models\SubTask();
                $newSub->task_id = $task->id;
                $newSub->title = $subtask['title'];
                $newSub->is_group = isset($subtask['is_group']) ? true : false;
                $newSub->parent_id = isset($subtask['parent_id']) && $subtask['parent_id'] !== ''
                    ? $map[$subtask['parent_id']] ?? null
                    : null;
                $newSub->save();

                $map[$tempId] = $newSub->id;
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

        // Additional time validation for updates
        if ($request->start_time && $request->end_time && $request->start_date && $request->end_date) {
            $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->start_date . ' ' . $request->start_time);
            $endDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->end_date . ' ' . $request->end_time);
            
            if ($startDateTime->gte($endDateTime)) {
                return back()->withErrors(['end_time' => 'Waktu selesai harus setelah waktu mulai.'])->withInput();
            }
        }

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