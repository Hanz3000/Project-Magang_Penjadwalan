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
    public function index()
    {
        try {
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

            $calendarEvents = $tasksQuery->map(function ($task) {
                if (!$task->start_date || !$task->end_date) {
                    return null;
                }

                $isAllDay = $task->is_all_day || !$task->start_time || !$task->end_time;
                $eventStart = $task->start_date->format('Y-m-d');
                $eventEnd = $task->end_date->copy()->addDay()->format('Y-m-d');

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
            })->filter();

            $pendingRevisions = TaskRevision::with(['task', 'collaborator'])
                ->whereHas('task', function ($q) {
                    $q->where('user_id', Auth::id());
                })
                ->orWhere('collaborator_id', Auth::id())
                ->where('status', 'pending')
                ->get();

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

    public function create()
    {
        $categories = Category::all();
        return view('tasks.create', compact('categories'));
    }

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

        if ($request->start_time && $request->end_time) {
            $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->start_date . ' ' . $request->start_time);
            $endDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->end_date . ' ' . $request->end_time);

            if ($startDateTime->gte($endDateTime)) {
                return back()->withErrors(['end_time' => 'Waktu selesai harus setelah waktu mulai.'])->withInput();
            }
        }

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

            if ($request->has('subtasks')) {
                $map = [];
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

    public function edit(Task $task)
    {
        if (!$task->canView(Auth::id())) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

        $categories = Category::all();
        return view('tasks.edit', compact('task', 'categories'));
    }

    public function update(Request $request, Task $task)
    {
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

        // Validasi tanggal subtask menggunakan tanggal tugas utama yang baru
        if ($request->has('subtasks')) {
            $this->validateSubtaskDatesRecursive(
                $validated['subtasks'],
                $task,
                null,
                $validated['start_date'], // Gunakan tanggal mulai tugas utama yang baru
                $validated['end_date']    // Gunakan tanggal selesai tugas utama yang baru
            );
        }

        if ($task->user_id === Auth::id()) {
            return $this->updateTaskDirectly($request, $task, $validated);
        } else {
            $collaborator = $task->collaborators()
                ->where('user_id', Auth::id())
                ->where('status', 'approved')
                ->first();

            if (!$collaborator || !$collaborator->can_edit) {
                return back()->withErrors('Anda tidak memiliki izin untuk mengedit tugas ini.')->withInput();
            }

            return $this->submitRevisionForReview($request, $task, $validated);
        }
    }

    private function updateTaskDirectly(Request $request, Task $task, array $validated)
    {
        return DB::transaction(function () use ($request, $task, $validated) {
            // Simpan tanggal tugas utama yang baru
            $newStartDate = Carbon::parse($validated['start_date']);
            $newEndDate = Carbon::parse($validated['end_date']);

            // Update tugas utama
            $task->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'category_id' => $validated['category_id'],
                'priority' => $validated['priority'],
                'start_date' => $newStartDate,
                'end_date' => $newEndDate,
                'start_time' => isset($validated['full_day']) && $validated['full_day'] ? null : ($validated['start_time'] ?? null),
                'end_time' => isset($validated['full_day']) && $validated['full_day'] ? null : ($validated['end_time'] ?? null),
                'is_all_day' => isset($validated['full_day']) ? $validated['full_day'] : false,
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
                $map = [];

                foreach ($subtasks as $tempId => $subtaskData) {
                    // Validasi dan sesuaikan tanggal subtask
                    $subtaskStartDate = Carbon::parse($subtaskData['start_date']);
                    $subtaskEndDate = Carbon::parse($subtaskData['end_date']);

                    // Sesuaikan tanggal subtask jika di luar rentang
                    if ($subtaskStartDate->lt($newStartDate)) {
                        $subtaskData['start_date'] = $newStartDate->format('Y-m-d');
                    }
                    if ($subtaskEndDate->gt($newEndDate)) {
                        $subtaskData['end_date'] = $newEndDate->format('Y-m-d');
                    }
                    if ($subtaskEndDate->lt($subtaskStartDate)) {
                        $subtaskData['end_date'] = $subtaskData['start_date'];
                    }

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
            } else {
                // Jika tidak ada subtask dalam request, sesuaikan semua subtask yang ada
                foreach ($task->subTasks as $subtask) {
                    $subtaskStartDate = $subtask->start_date;
                    $subtaskEndDate = $subtask->end_date;

                    $updated = false;
                    if ($subtaskStartDate->lt($newStartDate)) {
                        $subtask->start_date = $newStartDate;
                        $updated = true;
                    }
                    if ($subtaskEndDate->gt($newEndDate)) {
                        $subtask->end_date = $newEndDate;
                        $updated = true;
                    }
                    if ($subtask->end_date->lt($subtask->start_date)) {
                        $subtask->end_date = $subtask->start_date;
                        $updated = true;
                    }
                    if ($updated) {
                        $subtask->save();
                    }
                }
            }

            return redirect()->route('tasks.index')->with('success', 'Tugas berhasil diperbarui! Tanggal subtask telah disesuaikan sesuai rentang tugas utama.');
        });
    }

    private function submitRevisionForReview(Request $request, Task $task, array $validated)
    {
        return DB::transaction(function () use ($request, $task, $validated) {
            $newStartDate = Carbon::parse($validated['start_date']);
            $newEndDate = Carbon::parse($validated['end_date']);

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
                'is_all_day' => isset($validated['full_day']) ? $validated['full_day'] : false,
            ];

            // Data asli subtasks
            $originalSubtasks = [];
            $proposedSubtasks = [];
            $deletedSubtasks = [];

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

            // Proses subtask yang diusulkan
            if ($request->has('subtasks')) {
                foreach ($validated['subtasks'] as $tempId => $subtaskData) {
                    $subtaskStartDate = Carbon::parse($subtaskData['start_date']);
                    $subtaskEndDate = Carbon::parse($subtaskData['end_date']);

                    // Sesuaikan tanggal subtask jika di luar rentang tugas utama
                    if ($subtaskStartDate->lt($newStartDate)) {
                        $subtaskData['start_date'] = $newStartDate->format('Y-m-d');
                    }
                    if ($subtaskEndDate->gt($newEndDate)) {
                        $subtaskData['end_date'] = $newEndDate->format('Y-m-d');
                    }
                    if ($subtaskEndDate->lt($subtaskStartDate)) {
                        $subtaskData['end_date'] = $subtaskData['start_date'];
                    }

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

            // Proses subtask yang dihapus
            if ($request->has('deleted_subtasks') && $request->deleted_subtasks) {
                $deletedIds = array_filter(explode(',', $request->deleted_subtasks));
                foreach ($deletedIds as $deletedId) {
                    if (isset($originalSubtasks[$deletedId])) {
                        $deletedSubtasks[$deletedId] = $originalSubtasks[$deletedId];
                    }
                }
            }

            // Jika tidak ada subtask dalam request, sesuaikan tanggal subtask yang ada
            if (!$request->has('subtasks')) {
                foreach ($originalSubtasks as $subtaskId => $subtaskData) {
                    $subtaskStartDate = Carbon::parse($subtaskData['start_date']);
                    $subtaskEndDate = Carbon::parse($subtaskData['end_date']);

                    if ($subtaskStartDate->lt($newStartDate)) {
                        $subtaskData['start_date'] = $newStartDate->format('Y-m-d');
                    }
                    if ($subtaskEndDate->gt($newEndDate)) {
                        $subtaskData['end_date'] = $newEndDate->format('Y-m-d');
                    }
                    if (Carbon::parse($subtaskData['end_date'])->lt(Carbon::parse($subtaskData['start_date']))) {
                        $subtaskData['end_date'] = $subtaskData['start_date'];
                    }
                    $originalSubtasks[$subtaskId] = $subtaskData;
                }
            }

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

            $revision = TaskRevision::create([
                'task_id' => $task->id,
                'collaborator_id' => Auth::id(),
                'revision_type' => 'update_task_with_subtasks',
                'original_data' => $originalData,
                'proposed_data' => $proposedData,
                'status' => 'pending'
            ]);

            return redirect()->route('tasks.index')->with('success', 'Perubahan Anda telah diajukan dan menunggu persetujuan pemilik tugas. Tanggal subtask telah disesuaikan sesuai rentang tugas utama.');
        });
    }

    public function destroy(Task $task)
    {
        try {
            if (!$task->canEdit(Auth::id())) {
                return redirect()->route('tasks.index')
                    ->with('error', 'Anda tidak memiliki izin untuk menghapus tugas ini.');
            }

            DB::beginTransaction();

            $task->subTasks()->delete();
            $task->collaborators()->delete();
            $task->revisions()->delete();
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

    private function validateSubtaskDatesRecursive($subtasks, $task, $parentSubtask = null, $mainTaskStartDate = null, $mainTaskEndDate = null)
    {
        foreach ($subtasks as $tempId => $subtaskData) {
            $subtaskStartDate = Carbon::parse($subtaskData['start_date']);
            $subtaskEndDate = Carbon::parse($subtaskData['end_date']);
            $parentStartDate = $parentSubtask ? Carbon::parse($parentSubtask->start_date) : ($mainTaskStartDate ? Carbon::parse($mainTaskStartDate) : Carbon::parse($task->start_date));
            $parentEndDate = $parentSubtask ? Carbon::parse($parentSubtask->end_date) : ($mainTaskEndDate ? Carbon::parse($mainTaskEndDate) : Carbon::parse($task->end_date));

            if ($subtaskStartDate->lt($parentStartDate)) {
                throw ValidationException::withMessages([
                    "subtasks.$tempId.start_date" => "Tanggal mulai subtask '{$subtaskData['title']}' harus pada atau setelah tanggal mulai parent ({$parentStartDate->format('d/m/Y')}).",
                ]);
            }

            if ($subtaskEndDate->gt($parentEndDate)) {
                throw ValidationException::withMessages([
                    "subtasks.$tempId.end_date" => "Tanggal selesai subtask '{$subtaskData['title']}' harus pada atau sebelum tanggal selesai parent ({$parentEndDate->format('d/m/Y')}).",
                ]);
            }

            if ($subtaskEndDate->lt($subtaskStartDate)) {
                throw ValidationException::withMessages([
                    "subtasks.$tempId.end_date" => "Tanggal selesai subtask '{$subtaskData['title']}' tidak boleh sebelum tanggal mulai subtask.",
                ]);
            }

            if (isset($subtaskData['subtasks'])) {
                $subtask = isset($subtaskData['id']) && $subtaskData['id'] ? SubTask::find($subtaskData['id']) : null;
                if (!$subtask) {
                    $subtask = new SubTask([
                        'start_date' => $subtaskStartDate,
                        'end_date' => $subtaskEndDate,
                    ]);
                }
                $this->validateSubtaskDatesRecursive($subtaskData['subtasks'], $task, $subtask, $mainTaskStartDate, $mainTaskEndDate);
            }
        }
    }

    public function toggle(Request $request, Task $task)
    {
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

        return DB::transaction(function () use ($task, $isCompleted) {
            $task->completed = $isCompleted;
            $task->save();

            $task->subTasks()->update(['completed' => $isCompleted]);

            $leafSubTasks = $task->subTasks->filter(function ($st) use ($task) {
                return $task->subTasks->where('parent_id', $st->id)->count() == 0;
            });

            $subtaskCompleted = $isCompleted ? $leafSubTasks->count() : 0;
            $subtaskTotal = $leafSubTasks->count();

            $progressPercentage = $subtaskTotal > 0 ? round(($subtaskCompleted / $subtaskTotal) * 100) : ($isCompleted ? 100 : 0);

            $totalTasks = Task::count();
            $completedTasks = Task::where('completed', true)->count();
            $overallProgress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

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

    public function toggleAll(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);

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

        return DB::transaction(function () use ($task, $isCompleted) {
            $task->subTasks()->update(['completed' => $isCompleted]);

            $task->completed = $isCompleted;
            $task->save();

            $leafSubTasks = $task->subTasks->filter(function ($st) use ($task) {
                return $task->subTasks->where('parent_id', $st->id)->count() == 0;
            });

            $subtaskCompleted = $isCompleted ? $leafSubTasks->count() : 0;
            $subtaskTotal = $leafSubTasks->count();

            $progressPercentage = $subtaskTotal > 0 ? round(($subtaskCompleted / $subtaskTotal) * 100) : 0;

            $totalTasks = Task::count();
            $completedTasks = Task::where('completed', true)->count();
            $overallProgress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

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

    public function toggleSubtask(Request $request, SubTask $subtask)
    {
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
            $subtask->completed = $isCompleted;
            $subtask->save();

            $task = $subtask->task;

            $leafSubTasks = $task->subTasks->filter(function ($st) use ($task) {
                return $task->subTasks->where('parent_id', $st->id)->count() == 0;
            });

            $subtaskCompleted = $leafSubTasks->where('completed', true)->count();
            $subtaskTotal = $leafSubTasks->count();

            $task->completed = ($subtaskTotal > 0 && $subtaskCompleted === $subtaskTotal);
            $task->save();

            $progressPercentage = $subtaskTotal > 0 ? round(($subtaskCompleted / $subtaskTotal) * 100) : ($task->completed ? 100 : 0);

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
