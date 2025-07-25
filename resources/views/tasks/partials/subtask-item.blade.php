@props(['subtask', 'subtasksByParent', 'level', 'task'])

@php
    // Initialize defaults
    $defaultDate = now();
    $task_start_date = isset($task) && $task->start_date ? \Carbon\Carbon::parse($task->start_date) : $defaultDate;
    $task_end_date = isset($task) && $task->end_date ? \Carbon\Carbon::parse($task->end_date) : $defaultDate->copy()->addDay();

    // Handle subtask data
    if (!$subtask) {
        $subtask_title = old('subtasks.new.title', '');
        $subtask_start_date = $task_start_date;
        $subtask_end_date = $task_end_date;
        $subtask_id = 'new-' . uniqid();
        $parentId = null;
    } else {
        $subtask_title = old('subtasks.' . $subtask->id . '.title', $subtask->title ?? '');
        $subtask_start_date = $subtask->start_date ? \Carbon\Carbon::parse($subtask->start_date) : $task_start_date;
        $subtask_end_date = $subtask->end_date ? \Carbon\Carbon::parse($subtask->end_date) : $task_end_date;
        $subtask_id = $subtask->id;
        $parentId = $subtask->parent_id ?? null;
    }

    // Determine parent date limits
    $parentStartDateForLimits = $task_start_date;
    $parentEndDateForLimits = $task_end_date;

    if ($parentId && isset($subtasksByParent) && $subtasksByParent instanceof \Illuminate\Support\Collection) {
        $parentSubtask = $subtasksByParent->flatten(1)->firstWhere('id', $parentId);
        if ($parentSubtask) {
            $parentStartDateForLimits = $parentSubtask->start_date ? \Carbon\Carbon::parse($parentSubtask->start_date) : $parentStartDateForLimits;
            $parentEndDateForLimits = $parentSubtask->end_date ? \Carbon\Carbon::parse($parentSubtask->end_date) : $parentEndDateForLimits;
        }
    }

    // Format dates
    $parentStartDateLimitString = $parentStartDateForLimits->format('Y-m-d');
    $parentEndDateLimitString = $parentEndDateForLimits->format('Y-m-d');
    $subtaskStartDateValueString = $subtask_start_date->format('Y-m-d');
    $subtaskEndDateValueString = $subtask_end_date->format('Y-m-d');
    $displaySubtaskStart = $subtask_start_date->format('d/m/Y');
    $displaySubtaskEnd = $subtask_end_date->format('d/m/Y');

    // Get children
    $children = isset($subtasksByParent) && $subtasksByParent instanceof \Illuminate\Support\Collection
        ? $subtasksByParent->get($subtask_id, collect())
        : collect();

    $level = is_numeric($level) ? (int)$level : 0;
@endphp

<div class="subtask-item bg-white rounded-lg border border-gray-200 p-4 mb-3 shadow-sm relative"
     data-id="{{ $subtask_id }}"
     data-level="{{ $level }}"
     data-existing="{{ $subtask ? 'true' : 'false' }}"
     style="margin-left: {{ $level * 16 }}px; {{ $level > 0 ? 'border-left: 2px solid #6366F1; padding-left: 14px;' : '' }}">
    <div class="flex flex-col md:flex-row md:items-center gap-4">
        <div class="flex-1">
            <div class="flex items-center gap-2">
                <input type="text" name="subtasks[{{ $subtask_id }}][title]" value="{{ $subtask_title }}" placeholder="Judul subtask" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-3">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Tanggal Mulai</label>
                    <div class="relative">
                        <input type="date" name="subtasks[{{ $subtask_id }}][start_date]"
                               min="{{ $parentStartDateLimitString }}" max="{{ $parentEndDateLimitString }}"
                               value="{{ old('subtasks.' . $subtask_id . '.start_date', $subtaskStartDateValueString) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent start-date-input">
                    </div>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Tanggal Selesai</label>
                    <div class="relative">
                        <input type="date" name="subtasks[{{ $subtask_id }}][end_date]"
                               min="{{ $parentStartDateLimitString }}" max="{{ $parentEndDateLimitString }}"
                               value="{{ old('subtasks.' . $subtask_id . '.end_date', $subtaskEndDateValueString) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent end-date-input">
                    </div>
                </div>
            </div>
            <div class="subtask-date mt-2">
                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-xs">{{ $displaySubtaskStart }} - {{ $displaySubtaskEnd }}</span>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <input type="hidden" name="subtasks[{{ $subtask_id }}][parent_id]" value="{{ $parentId ?? '' }}">
            @if($level < 5)
            <button type="button" onclick="addSubtask('{{ $subtask_id }}')"
                class="p-2 text-indigo-600 hover:text-indigo-800 transition-colors" title="Tambah Child">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </button>
            @endif
            <button type="button" onclick="removeSubtask('{{ $subtask_id }}', {{ $subtask ? 'true' : 'false' }})"
                class="p-2 text-red-600 hover:text-red-800 transition-colors" title="Hapus">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 21m5-14v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
    </div>

    @if($children->count() > 0)
        @foreach($children as $childSubtask)
            @if($childSubtask)
                @include('tasks.partials.subtask-item', [
                    'subtask' => $childSubtask,
                    'subtasksByParent' => $subtasksByParent,
                    'level' => $level + 1,
                    'task' => $task
                ])
            @endif
        @endforeach
    @endif
</div>