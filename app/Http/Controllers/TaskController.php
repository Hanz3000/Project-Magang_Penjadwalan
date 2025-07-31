<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use App\Models\SubTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks.
     *
     * @return \Illuminate\Http\Response
     */
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

        // Fixed the ternary operation - removed extra parenthesis
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

    // Prepare calendar events
    $calendarEvents = $rawTasks->map(function ($task) {
        return [
            'title' => $task->title,
            'start' => $task->start_date->format('Y-m-d'),
            'end' => $task->end_date ? $task->end_date->addDay()->format('Y-m-d') : null,
            'extendedProps' => [
                'completed' => $task->completed,
                'priority' => $task->priority,
                'taskId' => $task->id
            ],
            'color' => $this->getPriorityColor($task->priority),
            'allDay' => $task->is_all_day
        ];
    })->all();

    $categories = Category::withCount('tasks')->get();

    $priorityCounts = [
        'urgent' => Task::where('priority', 'urgent')->count(),
        'high' => Task::where('priority', 'high')->count(),
        'medium' => Task::where('priority', 'medium')->count(),
        'low' => Task::where('priority', 'low')->count(),
    ];

    $totalTasks = $tasks->count();

    return view('tasks.index', [
        'tasks' => $tasks,
        'categories' => $categories,
        'priorityCounts' => $priorityCounts,
        'totalTasks' => $totalTasks,
        'calendarEvents' => $calendarEvents
    ]);
}

private function getPriorityColor($priority)
{
    switch ($priority) {
        case 'urgent': return '#ff0000';
        case 'high': return '#ff6b00';
        case 'medium': return '#ffcc00';
        case 'low': return '#00b300';
        default: return '#3a87ad';
    }
}
    /**
     * Show the form for creating a new task.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('tasks.create', compact('categories'));
    }

    /**
     * Store a newly created task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:urgent,high,medium,low',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|required_with:start_time',
            'description' => 'nullable|string',
            'full_day' => 'nullable|boolean',
            'subtasks' => 'nullable|array',
            'subtasks.*.title' => 'required|string|max:255',
            'subtasks.*.parent_id' => 'nullable',
            'subtasks.*.is_group' => 'nullable|boolean',
            'subtasks.*.start_date' => 'required|date',
            'subtasks.*.end_date' => 'required|date|after_or_equal:subtasks.*.start_date',
        ]);

        // Additional time validation
        if ($request->start_time && $request->end_time) {
            $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->start_date . ' ' . $request->start_time);
            $endDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->end_date . ' ' . $request->end_time);

            if ($startDateTime->gte($endDateTime)) {
                return back()->withErrors(['end_time' => 'Waktu selesai harus setelah waktu mulai.'])->withInput();
            }
        }

        // Validasi tanggal subtask
        if ($request->has('subtasks')) {
            $taskDummy = new Task($validated);
            $taskDummy->start_date = Carbon::parse($validated['start_date']);
            $taskDummy->end_date = Carbon::parse($validated['end_date']);
            $taskDummy->subTasks = collect();

            $this->validateSubtaskDatesRecursive($validated['subtasks'], $taskDummy, null);
        }

        return DB::transaction(function () use ($request, $validated) {
            $task = Task::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'category_id' => $validated['category_id'],
                'priority' => $validated['priority'],
                'start_date' => Carbon::parse($validated['start_date']),
                'end_date' => Carbon::parse($validated['end_date']),
                'start_time' => isset($validated['full_day']) && $validated['full_day'] ? null : ($validated['start_time'] ?? null),
                'end_time' => isset($validated['full_day']) && $validated['full_day'] ? null : ($validated['end_time'] ?? null),
                'is_all_day' => $validated['full_day'] ?? false,
            ]);

            // Simpan semua subtasks
            if ($request->has('subtasks')) {
                $map = []; // untuk menyimpan id sementara dari front-end ke ID DB
                foreach ($validated['subtasks'] as $tempId => $subtask) {
                    $newSub = new SubTask();
                    $newSub->task_id = $task->id;
                    $newSub->title = $subtask['title'];
                    $newSub->is_group = isset($subtask['is_group']) ? true : false;
                    $newSub->start_date = Carbon::parse($subtask['start_date']);
                    $newSub->end_date = Carbon::parse($subtask['end_date']);
                    $newSub->parent_id = isset($subtask['parent_id']) && $subtask['parent_id'] !== ''
                        ? $map[$subtask['parent_id']] ?? null
                        : null;
                    $newSub->save();

                    $map[$tempId] = $newSub->id;
                }
            }

            return redirect()->route('tasks.index')->with('success', 'Tugas berhasil ditambahkan!');
        });
    }

    /**
     * Show the form for editing the specified task.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $categories = Category::all();
        return view('tasks.edit', compact('task', 'categories'));
    }

    /**
     * Update the specified task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:urgent,high,medium,low',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'description' => 'nullable|string',
            'full_day' => 'nullable|boolean',
            'subtasks' => 'nullable|array',
            'subtasks.*.title' => 'required|string|max:255',
            'subtasks.*.parent_id' => 'nullable',
            'subtasks.*.id' => 'nullable|exists:sub_tasks,id',
            'subtasks.*.is_group' => 'nullable|boolean', // Added is_group validation
            'subtasks.*.start_date' => 'required|date',
            'subtasks.*.end_date' => 'required|date|after_or_equal:subtasks.*.start_date',
            'deleted_subtasks' => 'nullable|string',
        ]);

        // Validasi tanggal subtask
        if ($request->has('subtasks')) {
            $this->validateSubtaskDatesRecursive($validated['subtasks'], $task, null);
        }

        return DB::transaction(function () use ($request, $task, $validated) {
            // Update main task
            $task->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'category_id' => $validated['category_id'],
                'priority' => $validated['priority'],
                'start_date' => Carbon::parse($validated['start_date']),
                'end_date' => Carbon::parse($validated['end_date']),
                'start_time' => isset($validated['full_day']) && $validated['full_day'] ? null : ($validated['start_time'] ?? null),
                'end_time' => isset($validated['full_day']) && $validated['full_day'] ? null : ($validated['end_time'] ?? null),
                'is_all_day' => $validated['full_day'] ?? false,
            ]);

            // Handle deleted subtasks
            if ($request->has('deleted_subtasks') && $request->deleted_subtasks) {
                $deletedIds = explode(',', $request->deleted_subtasks);
                SubTask::whereIn('id', $deletedIds)->where('task_id', $task->id)->delete(); // Ensure only task's subtasks are deleted
            }

            // Process subtasks
            if ($request->has('subtasks')) {
                $subtasks = $validated['subtasks'];
                $existingIds = $task->subTasks->pluck('id')->toArray();
                $processedIds = [];
                $map = []; // Map temporary IDs to database IDs for parent_id resolution

                foreach ($subtasks as $tempId => $subtaskData) {
                    // Handle existing subtasks
                    if (isset($subtaskData['id']) && in_array($subtaskData['id'], $existingIds)) {
                        $subtask = SubTask::find($subtaskData['id']);
                        $subtask->update([
                            'title' => $subtaskData['title'],
                            'parent_id' => isset($subtaskData['parent_id']) && $subtaskData['parent_id'] !== ''
                                ? ($map[$subtaskData['parent_id']] ?? $subtaskData['parent_id'])
                                : null,
                            'start_date' => Carbon::parse($subtaskData['start_date']),
                            'end_date' => Carbon::parse($subtaskData['end_date']),
                            'is_group' => isset($subtaskData['is_group']) ? true : false,
                        ]);
                        $processedIds[] = $subtask->id;
                        $map[$tempId] = $subtask->id;
                    } 
                    // Handle new subtasks
                    else {
                        $newSubtask = $task->subTasks()->create([
                            'title' => $subtaskData['title'],
                            'parent_id' => isset($subtaskData['parent_id']) && $subtaskData['parent_id'] !== ''
                                ? ($map[$subtaskData['parent_id']] ?? null)
                                : null,
                            'start_date' => Carbon::parse($subtaskData['start_date']),
                            'end_date' => Carbon::parse($subtaskData['end_date']),
                            'is_group' => isset($subtaskData['is_group']) ? true : false,
                        ]);
                        $processedIds[] = $newSubtask->id;
                        $map[$tempId] = $newSubtask->id;
                    }
                }

                // Delete only subtasks that were not processed and not explicitly deleted
                $toDelete = array_diff($existingIds, $processedIds);
                if (!empty($toDelete)) {
                    SubTask::whereIn('id', $toDelete)->where('task_id', $task->id)->delete();
                }
            }

            return redirect()->route('tasks.index')->with('success', 'Tugas berhasil diperbarui!');
        });
    }

    /**
     * Remove the specified task from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
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
     * Validate subtask dates recursively against parent task or subtask.
     *
     * @param  array  $subtasks
     * @param  \App\Models\Task  $task
     * @param  int|null  $parentId
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validateSubtaskDatesRecursive($subtasks, $task, $parentId = null)
    {
        foreach ($subtasks as $tempId => $subtask) {
            // Determine parent dates
            $parentStart = $parentId
                ? ($task->subTasks->where('id', $parentId)->first()->start_date ?? $task->start_date)
                : $task->start_date;
            $parentEnd = $parentId
                ? ($task->subTasks->where('id', $parentId)->first()->end_date ?? $task->end_date)
                : $task->end_date;

            $parentStart = Carbon::parse($parentStart);
            $parentEnd = Carbon::parse($parentEnd);

            // Validate subtask dates
            if (!empty($subtask['start_date'])) {
                $childStart = Carbon::parse($subtask['start_date']);
                if ($childStart->lt($parentStart)) {
                    throw ValidationException::withMessages([
                        "subtasks.{$tempId}.start_date" => "Tanggal mulai subtask harus pada atau setelah tanggal mulai parent ({$parentStart->format('d/m/Y')}).",
                    ]);
                }
            }

            if (!empty($subtask['end_date'])) {
                $childEnd = Carbon::parse($subtask['end_date']);
                if ($childEnd->gt($parentEnd)) {
                    throw ValidationException::withMessages([
                        "subtasks.{$tempId}.end_date" => "Tanggal selesai subtask harus pada atau sebelum tanggal selesai parent ({$parentEnd->format('d/m/Y')}).",
                    ]);
                }
            }

            if (!empty($subtask['start_date']) && !empty($subtask['end_date'])) {
                $childStart = Carbon::parse($subtask['start_date']);
                $childEnd = Carbon::parse($subtask['end_date']);
                if ($childEnd->lt($childStart)) {
                    throw ValidationException::withMessages([
                        "subtasks.{$tempId}.end_date" => "Tanggal selesai subtask harus pada atau setelah tanggal mulai subtask.",
                    ]);
                }
            }

            // Recursively validate child subtasks
            if (isset($subtask['subtasks'])) {
                $this->validateSubtaskDatesRecursive($subtask['subtasks'], $task, $subtask['id'] ?? $tempId);
            }
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

    /**
     * Toggle subtask completion status
     */
    public function toggleSubtask(Request $request, SubTask $subtask)
    {
        $request->validate([
            'completed' => 'required|boolean',
        ]);

        $isCompleted = $request->completed;

        return DB::transaction(function () use ($subtask, $isCompleted) {
            // Update the subtask
            $subtask->completed = $isCompleted;
            $subtask->save();

            // Get the parent task
            $task = $subtask->task;

            // Check if all leaf subtasks are completed
            $leafSubTasks = $task->subTasks->filter(function ($st) use ($task) {
                return $task->subTasks->where('parent_id', $st->id)->count() == 0;
            });

            $subtaskCompleted = $leafSubTasks->where('completed', true)->count();
            $subtaskTotal = $leafSubTasks->count();

            // Update task completion status if all leaf subtasks are completed
            $task->completed = ($subtaskTotal > 0 && $subtaskCompleted === $subtaskTotal);
            $task->save();

            // Calculate progress percentage
            $progressPercentage = $subtaskTotal > 0 ? round(($subtaskCompleted / $subtaskTotal) * 100) : ($task->completed ? 100 : 0);

            // Calculate global summary stats
            $totalTasks = Task::count();
            $completedTasks = Task::where('completed', true)->count();
            $overallProgress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

            return response()->json([
                'success' => true,
                'subtask' => [
                    'id' => $subtask->id,
                    'completed' => $subtask->completed,
                    'task_id' => $task->id
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
}