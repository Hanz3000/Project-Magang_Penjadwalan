<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use App\Models\SubTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Models\TaskRevision;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            // 1. Get tasks: owned by user OR approved collaborations
            $tasksQuery = Task::with([
                'user',
                'category',
                'subTasks' => function ($query) {
                    $query->orderBy('parent_id')->orderBy('created_at');
                },
                'collaborators' => function ($query) {
                    $query->where('status', 'approved')->with('user');
                }
            ])
            ->where(function ($query) {
                $query->where('user_id', Auth::id())
                      ->orWhereHas('collaborators', function ($q) {
                          $q->where('user_id', Auth::id())->where('status', 'approved');
                      });
            })
            ->orderBy('start_date', 'asc')
            ->get();

            // 2. Transform data with proper null checks
            $tasks = $tasksQuery->map(function ($task) {
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
                    'start_date' => $task->start_date?->format('Y-m-d'),
                    'end_date' => $task->end_date?->format('Y-m-d'),
                    'start_date_formatted' => $task->start_date?->format('M d'),
                    'end_date_formatted' => $task->end_date?->format('M d'),
                    'start_time' => $task->start_time ? $task->start_time->format('H:i') : null,
                    'end_time' => $task->end_time ? $task->end_time->format('H:i') : null,
                    'is_all_day' => $task->is_all_day,
                    'completed' => $task->completed,
                    'durationDays' => $durationDays,
                    'calendarProgress' => $calendarProgress,
                    'category_id' => $task->category_id,
                    'category_name' => $task->category?->name ?? null,
                    'category_color' => $task->category?->color ?? '#6B7280',
                    'is_owner' => $task->user_id === Auth::id(),
                    'owner_name' => $task->user?->name ?? 'Unknown',
                    'sub_tasks' => $task->subTasks->map(function ($subtask) {
                        return [
                            'id' => $subtask->id,
                            'title' => $subtask->title,
                            'completed' => $subtask->completed,
                            'parent_id' => $subtask->parent_id,
                            'is_group' => $subtask->is_group,
                            'start_date' => $subtask->start_date?->format('Y-m-d'),
                            'end_date' => $subtask->end_date?->format('Y-m-d'),
                        ];
                    })->all(),
                    'collaborators' => $task->collaborators->map(function ($collab) {
                        return [
                            'id' => $collab->id,
                            'user_id' => $collab->user?->id,
                            'name' => $collab->user?->name ?? 'Deleted User',
                            'email' => $collab->user?->email ?? '',
                            'can_edit' => $collab->can_edit,
                            'status' => $collab->status
                        ];
                    })->all()
                ];
            });

            // 3. Calendar events
            $calendarEvents = $tasksQuery->map(function ($task) {
                if (!$task->start_date || !$task->end_date) {
                    return null; // Skip invalid dates
                }

                $isAllDay = $task->is_all_day || !$task->start_time || !$task->end_time;
                $eventStart = $task->start_date->format('Y-m-d');
                $eventEnd = $task->end_date->copy()->addDay()->format('Y-m-d'); // FullCalendar uses exclusive end

                return [
                    'title' => $task->title,
                    'start' => $isAllDay ? $eventStart : $task->start_date->format('Y-m-d\TH:i:s'),
                    'end' => $isAllDay ? $eventEnd : $task->end_date->format('Y-m-d\TH:i:s'),
                    'allDay' => $isAllDay,
                    'extendedProps' => [
                        'taskId' => $task->id,
                        'priority' => $task->priority,
                        'completed' => $task->completed,
                        'is_owner' => $task->user_id === Auth::id(),
                    ],
                    'backgroundColor' => $this->getPriorityColor($task->priority),
                    'borderColor' => $task->completed ? '#9CA3AF' : $this->getPriorityColor($task->priority),
                    'textColor' => '#FFFFFF'
                ];
            })->filter(); // Remove null events

            // 4. Pending revisions
            $pendingRevisions = TaskRevision::with(['task', 'collaborator'])
                ->whereHas('task', function ($q) {
                    $q->where('user_id', Auth::id());
                })
                ->orWhere('collaborator_id', Auth::id())
                ->where('status', 'pending')
                ->get();

            // 5. Statistics
            $categories = Category::withCount(['tasks' => function ($query) {
                $query->where('user_id', Auth::id())
                      ->orWhereHas('collaborators', function ($q) {
                          $q->where('user_id', Auth::id())->where('status', 'approved');
                      });
            }])->get();

            $totalTasks = $tasks->count();
            $completedTasks = $tasks->where('completed', true)->count();
            $overallProgress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

            $priorityCounts = [
                'urgent' => $tasks->where('priority', 'urgent')->count(),
                'high' => $tasks->where('priority', 'high')->count(),
                'medium' => $tasks->where('priority', 'medium')->count(),
                'low' => $tasks->where('priority', 'low')->count(),
            ];

            return view('tasks.index', compact(
                'tasks',
                'calendarEvents',
                'pendingRevisions',
                'categories',
                'priorityCounts',
                'totalTasks',
                'completedTasks',
                'overallProgress'
            ));

        } catch (\Exception $e) {
            \Log::error('TaskController Error', [
                'user' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => request()->all()
            ]);

            return response()->view('errors.custom', [
                'message' => 'Error loading task data.',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    private function getPriorityColor($priority)
    {
        switch ($priority) {
            case 'urgent':
                return '#DC2626'; // red-600
            case 'high':
                return '#EA580C'; // orange-600
            case 'medium':
                return '#059669'; // emerald-600
            case 'low':
                return '#0891B2'; // sky-600
            default:
                return '#6B7280'; // gray-500
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
                'description' => $validated['description'],
                'priority' => $validated['priority'],
                'category_id' => $validated['category_id'] ?? null,
                'start_date' => Carbon::parse($validated['start_date']),
                'end_date' => Carbon::parse($validated['end_date']),
                'start_time' => isset($validated['full_day']) && $validated['full_day'] ? null : $validated['start_time'],
                'end_time' => isset($validated['full_day']) && $validated['full_day'] ? null : $validated['end_time'],
                'is_all_day' => $validated['full_day'] ?? false,
                'user_id' => Auth::id(),
                'completed' => false,
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
        // Check if user can edit this task
        if (!$task->canView(Auth::id())) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

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
        // Check if user can edit this task
        if (!$task->canView(Auth::id())) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

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
            'subtasks.*.is_group' => 'nullable|boolean',
            'subtasks.*.start_date' => 'required|date',
            'subtasks.*.end_date' => 'required|date|after_or_equal:subtasks.*.start_date',
            'deleted_subtasks' => 'nullable|string',
        ]);

        // Validasi tanggal subtask
        if ($request->has('subtasks')) {
            $this->validateSubtaskDatesRecursive($validated['subtasks'], $task, null);
        }

        // Cek apakah user adalah pemilik tugas
        if ($task->user_id === Auth::id()) {
            // ✅ Pemilik: update langsung
            return $this->updateTaskDirectly($request, $task, $validated);
        } 
        else {
            // 🟡 Cek apakah user adalah kolaborator yang diizinkan edit
            $collaborator = $task->collaborators()
                ->where('user_id', Auth::id())
                ->where('status', 'approved')
                ->first();

            if (!$collaborator || !$collaborator->can_edit) {
                return back()->withErrors('Anda tidak memiliki izin untuk mengedit tugas ini.')->withInput();
            }

            // 🟡 Kolaborator: kirim revisi untuk review
            return $this->submitRevisionForReview($request, $task, $validated);
        }
    }

    private function updateTaskDirectly(Request $request, Task $task, array $validated)
    {
        return DB::transaction(function () use ($request, $task, $validated) {
            // Update tugas utama
            $task->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'category_id' => $validated['category_id'],
                'priority' => $validated['priority'],
                'start_date' => Carbon::parse($validated['start_date']),
                'end_date' => Carbon::parse($validated['end_date']),
                'start_time' => $validated['full_day'] ? null : ($validated['start_time'] ?? null),
                'end_time' => $validated['full_day'] ? null : ($validated['end_time'] ?? null),
                'is_all_day' => $validated['full_day'] ?? false,
            ]);

            // Hapus subtask yang dihapus
            if ($request->has('deleted_subtasks') && $request->deleted_subtasks) {
                $deletedIds = explode(',', $request->deleted_subtasks);
                $task->subTasks()->whereIn('id', $deletedIds)->delete();
            }

            // Proses subtask
            if ($request->has('subtasks')) {
                $subtasks = $validated['subtasks'];
                $existingIds = $task->subTasks->pluck('id')->toArray();
                $processedIds = [];
                $map = []; // Mapping ID sementara ke ID database

                foreach ($subtasks as $tempId => $subtaskData) {
                    if (isset($subtaskData['id']) && in_array($subtaskData['id'], $existingIds)) {
                        // Update subtask yang sudah ada
                        $subtask = SubTask::find($subtaskData['id']);
                        $subtask->update([
                            'title' => $subtaskData['title'],
                            'parent_id' => $subtaskData['parent_id'] !== '' ? ($map[$subtaskData['parent_id']] ?? $subtaskData['parent_id']) : null,
                            'start_date' => Carbon::parse($subtaskData['start_date']),
                            'end_date' => Carbon::parse($subtaskData['end_date']),
                            'is_group' => $subtaskData['is_group'] ?? false,
                        ]);
                        $processedIds[] = $subtask->id;
                        $map[$tempId] = $subtask->id;
                    } else {
                        // Buat subtask baru
                        $newSubtask = $task->subTasks()->create([
                            'title' => $subtaskData['title'],
                            'parent_id' => $subtaskData['parent_id'] !== '' ? ($map[$subtaskData['parent_id']] ?? null) : null,
                            'start_date' => Carbon::parse($subtaskData['start_date']),
                            'end_date' => Carbon::parse($subtaskData['end_date']),
                            'is_group' => $subtaskData['is_group'] ?? false,
                        ]);
                        $processedIds[] = $newSubtask->id;
                        $map[$tempId] = $newSubtask->id;
                    }
                }

                // Hapus subtask yang tidak diproses
                $toDelete = array_diff($existingIds, $processedIds);
                if (!empty($toDelete)) {
                    $task->subTasks()->whereIn('id', $toDelete)->delete();
                }
            }

            return redirect()->route('tasks.index')->with('success', 'Tugas berhasil diperbarui!');
        });
    }

    private function submitRevisionForReview(Request $request, Task $task, array $validated)
    {
        return DB::transaction(function () use ($request, $task, $validated) {
            // Data asli task
            $originalTaskData = [
                'title' => $task->title,
                'description' => $task->description,
                'priority' => $task->priority,
                'category_id' => $task->category_id,
                'start_date' => $task->start_date?->format('Y-m-d'),
                'end_date' => $task->end_date?->format('Y-m-d'),
                'start_time' => $task->start_time?->format('H:i'),
                'end_time' => $task->end_time?->format('H:i'),
                'is_all_day' => $task->is_all_day,
            ];

            // Data usulan task
            $proposedTaskData = [
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'priority' => $validated['priority'],
                'category_id' => $validated['category_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'start_time' => isset($validated['full_day']) && $validated['full_day'] ? null : ($validated['start_time'] ?? null),
                'end_time' => isset($validated['full_day']) && $validated['full_day'] ? null : ($validated['end_time'] ?? null),
                'is_all_day' => isset($validated['full_day']) && $validated['full_day'] ?? false,
            ];

            // Data asli subtasks
            $originalSubtasks = [];
            $proposedSubtasks = [];
            $deletedSubtasks = [];

            // Get current subtasks
            foreach ($task->subTasks as $subtask) {
                $originalSubtasks[$subtask->id] = [
                    'id' => $subtask->id,
                    'title' => $subtask->title,
                    'parent_id' => $subtask->parent_id,
                    'start_date' => $subtask->start_date?->format('Y-m-d'),
                    'end_date' => $subtask->end_date?->format('Y-m-d'),
                    'is_group' => $subtask->is_group,
                ];
            }

            // Process deleted subtasks
            if ($request->has('deleted_subtasks') && $request->deleted_subtasks) {
                $deletedIds = array_filter(explode(',', $request->deleted_subtasks));
                foreach ($deletedIds as $deletedId) {
                    if (isset($originalSubtasks[$deletedId])) {
                        $deletedSubtasks[$deletedId] = $originalSubtasks[$deletedId];
                    }
                }
            }

            // Process updated/new subtasks
            if ($request->has('subtasks')) {
                foreach ($validated['subtasks'] as $tempId => $subtaskData) {
                    $proposedSubtasks[$tempId] = [
                        'id' => $subtaskData['id'] ?? null,
                        'title' => $subtaskData['title'],
                        'parent_id' => $subtaskData['parent_id'] ?: null,
                        'start_date' => $subtaskData['start_date'],
                        'end_date' => $subtaskData['end_date'],
                        'is_group' => $subtaskData['is_group'] ?? false,
                        'is_new' => !isset($subtaskData['id']),
                        'temp_id' => $tempId,
                    ];
                }
            }

            // Combine all data
            $originalData = [
                'task' => $originalTaskData,
                'subtasks' => $originalSubtasks,
            ];

            $proposedData = [
                'action' => 'update_task_with_subtasks',
                'task' => $proposedTaskData,
                'subtasks' => $proposedSubtasks,
                'deleted_subtasks' => $deletedSubtasks,
            ];

            Log::info('Submitting revision for review', [
                'task_id' => $task->id,
                'collaborator_id' => Auth::id(),
                'original_data' => $originalData,
                'proposed_data' => $proposedData,
                'validated_data' => $validated
            ]);

            // Simpan revisi
            $revision = TaskRevision::create([
                'task_id' => $task->id,
                'collaborator_id' => Auth::id(),
                'revision_type' => 'update_task_with_subtasks',
                'original_data' => $originalData,
                'proposed_data' => $proposedData,
                'status' => 'pending'
            ]);

            return redirect()->route('tasks.index')->with('success', 'Perubahan Anda telah diajukan dan menunggu persetujuan pemilik tugas.');
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
            // Check if user can delete this task
            if (!$task->canEdit(Auth::id())) {
                return redirect()->route('tasks.index')
                    ->with('error', 'Anda tidak memiliki izin untuk menghapus tugas ini.');
            }

            DB::beginTransaction();

            $task->subTasks()->delete();
            $task->collaborators()->delete(); // Remove collaborators
            $task->revisions()->delete(); // Remove revisions
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
        // Check if user can edit this task
        if (!$task->canEdit(Auth::id())) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengubah status tugas ini.'
            ], 403);
        }

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
        $task = Task::findOrFail($taskId);
        
        // Check if user can edit this task
        if (!$task->canEdit(Auth::id())) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengubah status tugas ini.'
            ], 403);
        }

        $request->validate([
            'completed' => 'required|boolean',
        ]);

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
        // Check if user can edit the parent task
        if (!$subtask->task->canEdit(Auth::id())) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengubah status subtask ini.'
            ], 403);
        }

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