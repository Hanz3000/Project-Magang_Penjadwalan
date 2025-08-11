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
        // 1. Buat query tanpa get() dulu
        $tasksQuery = Task::with([
            'user',
            'category',
            'subTasks' => function ($query) {
                $query->orderBy('parent_id')->orderBy('created_at');
            },
            'collaborators' => function ($query) {
                $query->where('status', 'approved')->with('user');
            },
            'revisions' => function($query) {
                // Load semua revisi yang pending untuk task ini
                $query->where('status', 'pending');
            }
        ])
        ->where(function ($query) {
            $query->where('user_id', Auth::id())
                  ->orWhereHas('collaborators', function ($q) {
                      $q->where('user_id', Auth::id())->where('status', 'approved');
                  });
        })
        ->orderBy('start_date', 'asc');

        // 2. Ambil sekali
        $tasksCollection = $tasksQuery->get();

        // 3. Mapping data dengan revision_status dan preview system
        $taskData = $tasksCollection->map(function ($task) {
            $durationDays = 0;
            if ($task->start_date && $task->end_date) {
                $start = \Carbon\Carbon::parse($task->start_date);
                $end = \Carbon\Carbon::parse($task->end_date);
                $durationDays = $end->diffInDays($start) + 1;
            }

            $isOwner = $task->user_id === Auth::id();
            $isCollaborator = $task->collaborators->where('user_id', Auth::id())->where('status', 'approved')->isNotEmpty();
            
            // Ambil revisi pending untuk task ini
            $pendingRevisions = $task->revisions->where('status', 'pending');
            
            // Untuk collaborator: tampilkan preview perubahan mereka
            // Untuk owner: tampilkan data asli + indikator ada pending revisions
            $displaySubTasks = $this->getDisplaySubTasks($task, $isOwner, $isCollaborator, $pendingRevisions);
            
            $leafSubTasks = $displaySubTasks->filter(function ($st) use ($displaySubTasks) {
                return $displaySubTasks->where('parent_id', $st['id'])->count() == 0;
            });
            $subtaskCompleted = $leafSubTasks->where('completed', true)->count();
            $subtaskTotal = $leafSubTasks->count();
            $calendarProgress = $subtaskTotal > 0 ? round(($subtaskCompleted / $subtaskTotal) * 100) : ($task->completed ? 100 : 0);

            // Untuk task level, cek apakah ada revisi pending
            $taskRevisionStatus = null;
            $taskDisplayData = $task->toArray();
            
            if (!$isOwner && $isCollaborator) {
                // Collaborator melihat preview perubahan mereka
                $userPendingRevision = $pendingRevisions
                    ->where('collaborator_id', Auth::id())
                    ->where('revision_type', 'update_task_with_subtasks')
                    ->sortByDesc('created_at')
                    ->first();
                    
                if ($userPendingRevision && isset($userPendingRevision->proposed_data['task'])) {
                    $taskRevisionStatus = 'pending';
                    // Override data dengan proposed data untuk preview
                    $proposedTaskData = $userPendingRevision->proposed_data['task'];
                    foreach ($proposedTaskData as $field => $value) {
                        if (isset($taskDisplayData[$field])) {
                            $taskDisplayData[$field] = $value;
                        }
                    }
                }
            } else if ($isOwner) {
                // Owner melihat status pending dari collaborator
                $hasTaskPendingRevisions = $pendingRevisions->whereIn('revision_type', [
                    'update_task_with_subtasks', 'update'
                ])->isNotEmpty();
                
                if ($hasTaskPendingRevisions) {
                    $taskRevisionStatus = 'has_pending';
                }
            }

            return [
                'id' => $task->id,
                'title' => $taskDisplayData['title'] ?? $task->title,
                'description' => $taskDisplayData['description'] ?? $task->description,
                'priority' => $taskDisplayData['priority'] ?? $task->priority,
                'start_date' => isset($taskDisplayData['start_date']) ? 
                    \Carbon\Carbon::parse($taskDisplayData['start_date'])->format('Y-m-d') : 
                    $task->start_date?->format('Y-m-d'),
                'end_date' => isset($taskDisplayData['end_date']) ? 
                    \Carbon\Carbon::parse($taskDisplayData['end_date'])->format('Y-m-d') : 
                    $task->end_date?->format('Y-m-d'),
                'start_date_formatted' => isset($taskDisplayData['start_date']) ? 
                    \Carbon\Carbon::parse($taskDisplayData['start_date'])->format('M d') : 
                    $task->start_date?->format('M d'),
                'end_date_formatted' => isset($taskDisplayData['end_date']) ? 
                    \Carbon\Carbon::parse($taskDisplayData['end_date'])->format('M d') : 
                    $task->end_date?->format('M d'),
                'start_time' => $taskDisplayData['start_time'] ?? ($task->start_time ? $task->start_time->format('H:i') : null),
                'end_time' => $taskDisplayData['end_time'] ?? ($task->end_time ? $task->end_time->format('H:i') : null),
                'is_all_day' => $taskDisplayData['is_all_day'] ?? $task->is_all_day,
                'completed' => $task->completed, // Status completed tidak bisa diubah via revisi
                'durationDays' => $durationDays,
                'calendarProgress' => $calendarProgress,
                'category_id' => $taskDisplayData['category_id'] ?? $task->category_id,
                'category_name' => $task->category?->name ?? null,
                'category_color' => $task->category?->color ?? '#6B7280',
                'is_owner' => $isOwner,
                'owner_name' => $task->user?->name ?? 'Unknown',
                'revision_status' => $taskRevisionStatus, // Status revisi untuk task
                'sub_tasks' => $displaySubTasks->values()->toArray(),
                'collaborators' => $task->collaborators->map(function ($collab) {
                    return [
                        'id' => $collab->id,
                        'user_id' => $collab->user?->id,
                        'name' => $collab->user?->name ?? 'Deleted User',
                        'email' => $collab->user?->email ?? '',
                        'can_edit' => $collab->can_edit,
                        'status' => $collab->status
                    ];
                })->values()->toArray()
            ];
        });

        $tasks = $taskData; // gunakan $taskData sebagai $tasks

        // Calendar Events
        $calendarEvents = $tasksCollection->map(function ($task) {
            if (!$task->start_date || !$task->end_date) return null;

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

        // Pending Revisions
        $pendingRevisions = TaskRevision::with(['task', 'collaborator'])
            ->whereHas('task', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->orWhere('collaborator_id', Auth::id())
            ->where('status', 'pending')
            ->get();

        // Categories
        $categories = Category::withCount(['tasks' => function ($query) {
            $query->where('user_id', Auth::id())
                  ->orWhereHas('collaborators', function ($q) {
                      $q->where('user_id', Auth::id())->where('status', 'approved');
                  });
        }])->get();

        // Stats
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
            'taskData',
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
            'line' => $e->getLine(),
        ], 500);
    }
}

    /**
     * Get display subtasks based on user role and pending revisions
     */
    private function getDisplaySubTasks($task, $isOwner, $isCollaborator, $pendingRevisions)
    {
        $originalSubTasks = collect($task->subTasks)->map(function($st) {
            return [
                'id' => $st->id,
                'title' => $st->title,
                'completed' => $st->completed,
                'parent_id' => $st->parent_id,
                'is_group' => $st->is_group,
                'start_date' => $st->start_date?->format('Y-m-d'),
                'end_date' => $st->end_date?->format('Y-m-d'),
                'revision_status' => null,
                'is_preview' => false
            ];
        });
        
        if (!$isOwner && $isCollaborator) {
            // Collaborator: tampilkan preview perubahan mereka
            return $this->getCollaboratorPreviewSubTasks($originalSubTasks, $pendingRevisions);
        } else if ($isOwner) {
            // Owner: tampilkan data asli dengan indikator pending
            return $this->getOwnerViewSubTasks($originalSubTasks, $pendingRevisions);
        }
        
        return $originalSubTasks;
    }
    
    /**
     * Get collaborator preview subtasks (with their pending changes)
     */
    private function getCollaboratorPreviewSubTasks($originalSubTasks, $pendingRevisions)
    {
        $userPendingRevision = $pendingRevisions
            ->where('collaborator_id', Auth::id())
            ->whereIn('revision_type', ['update_task_with_subtasks', 'create_subtask', 'create_multiple_subtasks'])
            ->sortByDesc('created_at')
            ->first();
            
        if (!$userPendingRevision) {
            return $originalSubTasks;
        }
        
        $displaySubTasks = $originalSubTasks->keyBy('id');
        
        // Handle different revision types
        switch ($userPendingRevision->revision_type) {
            case 'update_task_with_subtasks':
                $this->applyTaskWithSubtasksPreview($displaySubTasks, $userPendingRevision);
                break;
                
            case 'create_subtask':
                $this->applyCreateSubtaskPreview($displaySubTasks, $userPendingRevision);
                break;
                
            case 'create_multiple_subtasks':
                $this->applyCreateMultipleSubtasksPreview($displaySubTasks, $userPendingRevision);
                break;
        }
        
        return $displaySubTasks->values();
    }
    
    /**
     * Get owner view subtasks (with pending indicators)
     */
    private function getOwnerViewSubTasks($originalSubTasks, $pendingRevisions)
    {
        $displaySubTasks = $originalSubTasks->keyBy('id');
        
        foreach ($pendingRevisions as $revision) {
            switch ($revision->revision_type) {
                case 'update_task_with_subtasks':
                    $this->applyOwnerPendingIndicators($displaySubTasks, $revision);
                    break;
                    
                case 'create_subtask':
                case 'create_multiple_subtasks':
                    // Owner akan melihat indikator ada subtask baru yang pending
                    // Bisa ditambahkan logic untuk menampilkan preview subtask baru
                    break;
            }
        }
        
        return $displaySubTasks->values();
    }
    
    /**
     * Apply task with subtasks preview for collaborator
     */
    private function applyTaskWithSubtasksPreview($displaySubTasks, $revision)
    {
        $proposedData = $revision->proposed_data;
        
        // Handle updated/new subtasks
        if (isset($proposedData['subtasks'])) {
            foreach ($proposedData['subtasks'] as $tempId => $subtaskData) {
                if (isset($subtaskData['id']) && $displaySubTasks->has($subtaskData['id'])) {
                    // Update existing subtask with preview
                    $existing = $displaySubTasks->get($subtaskData['id']);
                    $existing['title'] = $subtaskData['title'];
                    $existing['start_date'] = $subtaskData['start_date'];
                    $existing['end_date'] = $subtaskData['end_date'];
                    $existing['is_group'] = $subtaskData['is_group'] ?? false;
                    $existing['revision_status'] = 'pending';
                    $existing['is_preview'] = true;
                    $displaySubTasks->put($subtaskData['id'], $existing);
                } else if ($subtaskData['is_new'] ?? false) {
                    // Add new subtask preview
                    $newSubtask = [
                        'id' => 'temp_' . $tempId, // Temporary ID for new items
                        'title' => $subtaskData['title'],
                        'completed' => false,
                        'parent_id' => $subtaskData['parent_id'],
                        'is_group' => $subtaskData['is_group'] ?? false,
                        'start_date' => $subtaskData['start_date'],
                        'end_date' => $subtaskData['end_date'],
                        'revision_status' => 'pending',
                        'is_preview' => true
                    ];
                    $displaySubTasks->put('temp_' . $tempId, $newSubtask);
                }
            }
        }
        
        // Handle deleted subtasks (mark as deleted preview)
        if (isset($proposedData['deleted_subtasks'])) {
            foreach ($proposedData['deleted_subtasks'] as $subtaskId => $subtaskData) {
                if ($displaySubTasks->has($subtaskId)) {
                    $existing = $displaySubTasks->get($subtaskId);
                    $existing['revision_status'] = 'pending_delete';
                    $existing['is_preview'] = true;
                    $displaySubTasks->put($subtaskId, $existing);
                }
            }
        }
    }
    
    /**
     * Apply create subtask preview for collaborator
     */
    private function applyCreateSubtaskPreview($displaySubTasks, $revision)
    {
        $subtaskData = $revision->proposed_data['subtask_data'] ?? [];
        
        $newSubtask = [
            'id' => 'temp_new_' . $revision->id,
            'title' => $subtaskData['title'] ?? 'New Subtask',
            'completed' => false,
            'parent_id' => $subtaskData['parent_id'] ?? null,
            'is_group' => $subtaskData['is_group'] ?? false,
            'start_date' => $subtaskData['start_date'] ?? null,
            'end_date' => $subtaskData['end_date'] ?? null,
            'revision_status' => 'pending',
            'is_preview' => true
        ];
        
        $displaySubTasks->put('temp_new_' . $revision->id, $newSubtask);
    }
    
    /**
     * Apply create multiple subtasks preview for collaborator
     */
    private function applyCreateMultipleSubtasksPreview($displaySubTasks, $revision)
    {
        $subtasksData = $revision->proposed_data['subtasks_data'] ?? [];
        
        foreach ($subtasksData as $index => $subtaskData) {
            $newSubtask = [
                'id' => 'temp_multi_' . $revision->id . '_' . $index,
                'title' => $subtaskData['title'] ?? 'New Subtask',
                'completed' => false,
                'parent_id' => $subtaskData['parent_id'] ?? null,
                'is_group' => $subtaskData['is_group'] ?? false,
                'start_date' => $subtaskData['start_date'] ?? null,
                'end_date' => $subtaskData['end_date'] ?? null,
                'revision_status' => 'pending',
                'is_preview' => true
            ];
            
            $displaySubTasks->put('temp_multi_' . $revision->id . '_' . $index, $newSubtask);
        }
    }
    
    /**
     * Apply pending indicators for owner view
     */
    private function applyOwnerPendingIndicators($displaySubTasks, $revision)
    {
        $proposedData = $revision->proposed_data;
        
        // Mark subtasks that have pending changes
        if (isset($proposedData['subtasks'])) {
            foreach ($proposedData['subtasks'] as $subtaskData) {
                if (isset($subtaskData['id']) && $displaySubTasks->has($subtaskData['id'])) {
                    $existing = $displaySubTasks->get($subtaskData['id']);
                    $existing['revision_status'] = 'has_pending';
                    $displaySubTasks->put($subtaskData['id'], $existing);
                }
            }
        }
        
        // Mark subtasks that will be deleted
        if (isset($proposedData['deleted_subtasks'])) {
            foreach ($proposedData['deleted_subtasks'] as $subtaskId => $subtaskData) {
                if ($displaySubTasks->has($subtaskId)) {
                    $existing = $displaySubTasks->get($subtaskId);
                    $existing['revision_status'] = 'pending_delete';
                    $displaySubTasks->put($subtaskId, $existing);
                }
            }
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

    public function reviewRevision(Request $request, $revisionId)
    {
        $revision = \App\Models\TaskRevision::with(['task'])->findOrFail($revisionId);

        // Hanya owner yang boleh approve/reject
        if ($revision->task->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Anda tidak berhak melakukan aksi ini.'], 403);
        }

        $action = $request->input('action');
        if ($action === 'selective_approve') {
            $approvedFields = $request->input('approved_fields', []);
            if (empty($approvedFields)) {
                return response()->json(['success' => false, 'message' => 'Tidak ada field yang disetujui.']);
            }

            $proposedTask = $revision->proposed_data['task'] ?? [];
            $task = $revision->task;

            // Update task utama
            foreach ($approvedFields as $field) {
                if (array_key_exists($field, $proposedTask)) {
                    $task->$field = $proposedTask[$field];
                }
            }
            $task->save();

            // Update subtask jika ada
            $proposedSubtasks = $revision->proposed_data['subtasks'] ?? [];
            foreach ($proposedSubtasks as $subId => $proposedSubtask) {
                // Cek apakah ada field subtask yang di-approve, misal: title:subtaskId
                foreach ($approvedFields as $field) {
                    if (strpos($field, 'subtask:') === 0) {
                        [$_, $fieldName, $id] = explode(':', $field);
                        if ($id == $subId && isset($proposedSubtask[$fieldName])) {
                            $subtask = \App\Models\SubTask::find($subId);
                            if ($subtask) {
                                $subtask->$fieldName = $proposedSubtask[$fieldName];
                                $subtask->save();
                            }
                        }
                    }
                }
            }

            $revision->status = 'approved';
            $revision->save();

            return response()->json(['success' => true, 'message' => 'Perubahan terpilih berhasil diterapkan']);
        } elseif ($action === 'reject') {
            $revision->status = 'rejected';
            $revision->save();
            return response()->json(['success' => true, 'message' => 'Revisi ditolak']);
        }

        return response()->json(['success' => false, 'message' => 'Aksi tidak dikenali.']);
    }
}