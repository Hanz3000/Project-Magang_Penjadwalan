@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-4">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-blue-100 rounded-xl">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-800">Management Tugas</h1>
                </div>
               <p class="text-gray-500">Kelola tugas dan progres dengan lebih teratur</p>
            </div>
            <a href="{{ route('tasks.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-medium shadow-sm transition-all duration-200 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Task
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Kalender Tugas
                        </h2>
                        <div class="flex items-center gap-2">
                            <button id="prev-month" class="px-3 py-1 text-sm rounded-md transition-all duration-200 text-gray-600 hover:text-gray-800 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <span id="calendar-title" class="text-base font-semibold text-gray-800"></span>
                            <button id="next-month" class="px-3 py-1 text-sm rounded-md transition-all duration-200 text-gray-600 hover:text-gray-800 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>

                            <div class="flex bg-gray-100 p-1 rounded-lg ml-4">
                                <button class="px-3 py-1 text-sm rounded-md transition-all duration-200 fc-dayGridMonth-button bg-white text-blue-600 shadow-sm font-medium" id="month-view">
                                    Bulan
                                </button>
                                <button class="px-3 py-1 text-sm rounded-md transition-all duration-200 fc-timeGridWeek-button text-gray-600 hover:text-gray-800 font-medium" id="week-view">
                                    Minggu
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="calendar" style="height: 600px;" class="rounded-lg overflow-hidden border border-gray-200"></div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 8l2 2 4-4"></path>
                            </svg>
                         Tugas Saya
                        </h2>
                        <div class="flex gap-2">
                            <button class="px-3 py-1 text-sm bg-gray-100 rounded-lg">Semua</button>
                            <button class="px-3 py-1 text-sm bg-blue-100 text-blue-600 rounded-lg">Aktif</button>
                            <button class="px-3 py-1 text-sm bg-gray-100 rounded-lg">Selesai</button>
                        </div>
                    </div>

                    <div class="space-y-3" id="task-list-container">
 @php
    function renderSubtasks($subtasks, $parentId = null) {
        $html = '';
        foreach ($subtasks->where('parent_id', $parentId) as $subTask) {
            $checked = $subTask->completed ? 'checked' : '';
            $lineClass = $subTask->completed ? 'line-through text-gray-400' : 'text-gray-600';

            $html .= '<li class="ml-4 subtask-item flex items-start gap-2">';
            $html .= '<form action="' . route('subtasks.toggle', $subTask->id) . '" method="POST" class="subtask-toggle-form">';
            $html .= csrf_field() . method_field('PATCH');
            $html .= '<input type="checkbox" class="subtask-checkbox" data-sub-task-id="' . $subTask->id . '" ' . $checked . '>';
            $html .= '</form>';
            $html .= '<span class="text-sm ' . $lineClass . ' subtask-text">' . e($subTask->title) . '</span>';

            if ($subtasks->where('parent_id', $subTask->id)->count() > 0) {
                $html .= '<ul class="ml-6 space-y-2">';
                $html .= renderSubtasks($subtasks, $subTask->id);
                $html .= '</ul>';
            }

            $html .= '</li>';
        }
        return $html;
    }
    @endphp

                        @foreach($tasks as $task)
                        @php
                        $durationDays = $task->start_date->diffInDays($task->end_date) + 1;
                        $subtaskCompleted = $task->subTasks->where('completed', true)->count();
                        $subtaskTotal = $task->subTasks->count();
                        $progressPercentage = $subtaskTotal > 0 ? round(($subtaskCompleted / $subtaskTotal) * 100) : ($task->completed ? 100 : 0);
                        @endphp
                        <div class="border border-gray-200 rounded-lg p-4 transition-all duration-200 hover:border-blue-200 hover:shadow-xs {{ $task->completed ? 'bg-gray-50' : 'bg-white' }}" id="task-item-{{ $task->id }}">
                            <div class="flex items-start gap-3">
                                <form action="{{ route('tasks.toggle', $task->id) }}" method="POST" class="task-toggle-form">
                                    @csrf
                                    @method('PATCH')
                                    <input type="checkbox"
                                        class="task-checkbox"
                                        data-task-id="{{ $task->id }}"
                                        {{ $task->completed ? 'checked' : '' }}>
                                </form>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-medium text-gray-800 {{ $task->completed ? 'line-through text-gray-400' : '' }} task-title">
                                                {{ $task->title }}
                                            </h3>
                                            <div class="flex items-center gap-2 text-sm text-gray-500 mt-1">
                                                <span>{{ $task->start_date->format('M d') }} - {{ $task->end_date->format('M d') }}</span>
                                                <span class="text-xs text-gray-400">•</span>
                                                <span>{{ $durationDays }} {{ $durationDays > 1 ? 'days' : 'day' }}</span>
                                                @if($subtaskTotal > 0)
                                                <span class="text-xs text-gray-400">•</span>
                                                <span class="text-blue-600 task-progress-percentage">{{ $progressPercentage }}%</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex gap-1.5">
                                            <a href="{{ route('tasks.edit', $task->id) }}"
                                                class="text-gray-500 hover:text-blue-600 p-1.5 rounded-lg hover:bg-blue-50 transition-colors duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-gray-500 hover:text-red-600 p-1.5 rounded-lg hover:bg-red-50 transition-colors duration-200"
                                                    onclick="return confirm('Delete this task?')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    @if($task->subTasks->count() > 0)
                                    <div class="mt-3 ml-7 pl-3 border-l-2 border-gray-200 task-subtasks-container">
                                        <div class="flex justify-between items-center mb-2">
                                            <div class="text-xs text-gray-500 subtask-progress-text">
                                                Subtugas ({{ $subtaskCompleted }}/{{ $subtaskTotal }})
                                            </div>
                                            <div class="w-20 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-blue-500 subtask-progress-bar" style="width: {{ $progressPercentage }}%"></div>
                                            </div>
                                        </div>
                                     <ul class="space-y-2 pl-4" id="task-tree">
    @foreach($task->subTasks->whereNull('parent_id') as $subTask)
        @include('partials.subtask-item', [
            'subTask' => $subTask,
            'allSubTasks' => $task->subTasks,
            'level' => 0
        ])
    @endforeach
</ul>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="space-y-5">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 p-3 rounded-lg">
                            <div class="text-blue-600 text-sm mb-1">Total Tugas</div>
                            <div class="text-2xl font-bold text-gray-800">{{ $totalTasks }}</div>
                        </div>
                        <div class="bg-green-50 p-3 rounded-lg">
                            <div class="text-green-600 text-sm mb-1">Selesai</div>
                            <div class="text-2xl font-bold text-gray-800">{{ $tasks->where('completed', true)->count() }}</div>
                        </div>
                        <div class="bg-purple-50 p-3 rounded-lg">
                            <div class="text-purple-600 text-sm mb-1">Progress</div>
                            <div class="text-2xl font-bold text-gray-800">
                                {{ $totalTasks > 0 ? round(($tasks->where('completed', true)->count() / $totalTasks) * 100) : 0 }}%
                            </div>
                        </div>
                        <div class="bg-yellow-50 p-3 rounded-lg">
                            <div class="text-yellow-600 text-sm mb-1">Terlambat</div>
                            <div class="text-2xl font-bold text-gray-800">1922</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Prioritas</h2>
                    <div class="space-y-3">
                        @foreach(['urgent' => 'Sangat mendesak', 'high' => 'Tinggi', 'medium' => 'Sedang', 'low' => 'Rendah'] as $key => $label)
                        <div>
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span>{{ $label }}</span>
                                <span>{{ $priorityCounts[$key] ?? 0 }} Tugas</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                <div class="bg-@if($key == 'urgent') red-500 @elseif($key == 'high') orange-500 @elseif($key == 'medium') yellow-500 @else green-500 @endif h-1.5 rounded-full"
                                    style="width: {{ $totalTasks > 0 ? (($priorityCounts[$key] ?? 0) / $totalTasks) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">Kategori</h2>
                        <button class="text-blue-600 text-sm font-medium">Lihat semua</button>
                    </div>
                    <div class="space-y-3">
                        @foreach($categories as $category)
                        <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-700">{{ $category->name }}</span>
                            </div>
                            <span class="text-sm text-gray-500">{{ $category->tasks->count() }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="taskModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50 backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-2xl max-w-md w-full mx-4 max-h-[90vh] overflow-hidden border border-gray-200 transform transition-all duration-300 scale-95 hover:scale-100">
        <!-- Header dengan gradient -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-4 rounded-t-xl">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-white">Detail Tugas</h3>
                <button onclick="closeTaskModal()" class="text-white hover:text-blue-100 p-1 rounded-full hover:bg-blue-700 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Konten dengan padding dan scroll halus -->
        <div id="taskModalContent" class="p-6 space-y-4 text-gray-700 overflow-y-auto max-h-[calc(90vh-120px)] scrollbar-thin scrollbar-thumb-blue-200 scrollbar-track-transparent">
            <!-- Konten akan diisi secara dinamis -->
        </div>
        
        <!-- Footer dengan aksi -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 rounded-b-xl flex justify-end gap-3">
            
        </div>
    </div>
</div>

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<style>
    .child-items.collapsed {
    display: none;
}

.toggle-icon.collapsed svg {
    transform: rotate(-90deg);
}

.toggle-icon svg {
    transition: transform 0.2s ease;
}
</style>
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js'></script>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.3/echo.min.js"></script>

<script>
    let currentTaskData = null;
    let calendar = null;

    // Function to sync checkbox states between modal and main list
    function syncCheckboxStates(taskId) {
        const mainCheckboxes = document.querySelectorAll(`#task-item-${taskId} .subtask-checkbox`);
        const modalCheckboxes = document.querySelectorAll('.subtask-checkbox-modal');
        
        mainCheckboxes.forEach(mainCheckbox => {
            const subtaskId = mainCheckbox.dataset.subTaskId;
            const modalCheckbox = Array.from(modalCheckboxes).find(cb => cb.dataset.subTaskId === subtaskId);
            
            if (modalCheckbox) {
                modalCheckbox.checked = mainCheckbox.checked;
                const modalText = modalCheckbox.closest('.subtask-item-modal')?.querySelector('.subtask-text-modal');
                if (modalText) {
                    modalText.classList.toggle('line-through', mainCheckbox.checked);
                    modalText.classList.toggle('text-gray-400', mainCheckbox.checked);
                    modalText.classList.toggle('text-gray-600', !mainCheckbox.checked);
                }
            }
        });
    }

    // Function to update progress in modal
    function updateModalProgress(taskId, progressPercentage, subtasksCompleted, subtasksTotal) {
        const modal = document.getElementById('taskModal');
        if (!modal.classList.contains('hidden')) {
            const modalTaskId = document.getElementById('taskModalContent').dataset.taskId;
            if (modalTaskId === taskId) {
                const modalProgressBar = document.querySelector('.subtask-progress-bar-modal');
                const modalProgressText = document.querySelector('.subtask-progress-text-modal');
                const modalProgressPercentage = document.querySelector('.subtask-percentage');
                
                if (modalProgressBar) modalProgressBar.style.width = `${progressPercentage}%`;
                if (modalProgressText) modalProgressText.textContent = `Subtasks (${subtasksCompleted}/${subtasksTotal})`;
                if (modalProgressPercentage) modalProgressPercentage.textContent = `${progressPercentage}%`;
            }
        }
    }

    // Function to update subtask in main list
    function updateMainTaskListSubtask(taskId, subtaskId, isCompleted) {
        const taskItem = document.getElementById(`task-item-${taskId}`);
        if (!taskItem) return;

        const subtaskCheckbox = taskItem.querySelector(`[data-sub-task-id="${subtaskId}"]`);
        if (subtaskCheckbox) {
            subtaskCheckbox.checked = isCompleted;
            const subtaskText = subtaskCheckbox.closest('form').nextElementSibling;
            if (subtaskText) {
                subtaskText.classList.toggle('line-through', isCompleted);
                subtaskText.classList.toggle('text-gray-400', isCompleted);
                subtaskText.classList.toggle('text-gray-600', !isCompleted);
            }
        }

        const subtaskCheckboxes = taskItem.querySelectorAll('.subtask-checkbox');
        const subtaskTotal = subtaskCheckboxes.length;
        const subtaskCompleted = Array.from(subtaskCheckboxes).filter(cb => cb.checked).length;
        const progressPercentage = subtaskTotal > 0 ? Math.round((subtaskCompleted / subtaskTotal) * 100) : (subtaskCompleted > 0 ? 100 : 0);
        updateTaskProgress(taskId, progressPercentage, subtaskCompleted, subtaskTotal, subtaskCompleted === subtaskTotal);
    }

    // Function to update task progress
    function updateTaskProgress(taskId, progressPercentage, subtasksCompleted, subtasksTotal, mainTaskCompleted) {
        const taskItem = document.getElementById(`task-item-${taskId}`);
        if (!taskItem) return;

        // Update progress elements
        const progressText = taskItem.querySelector('.task-progress-percentage');
        if (progressText) progressText.textContent = `${progressPercentage}%`;

        const progressBar = taskItem.querySelector('.subtask-progress-bar');
        if (progressBar) progressBar.style.width = `${progressPercentage}%`;

        const subtaskProgressText = taskItem.querySelector('.subtask-progress-text');
        if (subtaskProgressText) subtaskProgressText.textContent = `Subtasks (${subtasksCompleted}/${subtasksTotal})`;

        // Update main task elements
        const mainCheckbox = taskItem.querySelector('.task-checkbox');
        const taskTitle = taskItem.querySelector('.task-title');

        if (mainCheckbox) mainCheckbox.checked = mainTaskCompleted;
        if (taskTitle) {
            taskTitle.classList.toggle('line-through', mainTaskCompleted);
            taskTitle.classList.toggle('text-gray-400', mainTaskCompleted);
            taskTitle.classList.toggle('text-gray-800', !mainTaskCompleted);
        }

        // Update background
        taskItem.classList.toggle('bg-gray-50', mainTaskCompleted);
        taskItem.classList.toggle('bg-white', !mainTaskCompleted);

        // Update calendar event
        updateCalendarEvent(taskId, progressPercentage, mainTaskCompleted);
    }

    // Function to update calendar event
    // Fungsi yang diperbarui untuk mengupdate event di kalender
function updateCalendarEvent(taskId, progressPercentage, mainTaskCompleted) {
    if (!calendar) return;

    const taskItem = document.getElementById(`task-item-${taskId}`);
    if (!taskItem) return;

    const title = taskItem.querySelector('.task-title')?.textContent || '';
    const dateText = taskItem.querySelector('.task-title')?.nextElementSibling?.querySelector('span')?.textContent || '';
    const [startDateStr, endDateStr] = dateText.split(' - ');

    try {
        const startDate = startDateStr ? new Date(startDateStr + ' 00:00:00').toISOString().split('T')[0] : '';
        const endDate = endDateStr ? new Date(endDateStr + ' 23:59:59').toISOString().split('T')[0] : '';

        let event = calendar.getEventById(taskId);
        if (event) {
            // Simpan priority yang ada atau default ke 'medium'
            const priority = event.extendedProps.priority || 'medium';
            
            // Update semua properti event
            event.setProp('title', title);
            if (startDate) event.setProp('start', startDate);
            if (endDate) event.setProp('end', endDate);
            event.setExtendedProp('progress', progressPercentage);
            event.setExtendedProp('mainTaskCompleted', mainTaskCompleted);
            
            // Update warna berdasarkan status completed
            const bgColor = getEventColor(priority, mainTaskCompleted);
            event.setProp('backgroundColor', bgColor);
            event.setProp('borderColor', bgColor);
            
            // Render ulang event
            event.setProp('display', 'none'); // Force refresh
            event.setProp('display', 'auto');
            
            // Update progress bar jika ada subtask
            if (event.extendedProps.hasSubtasks) {
                const eventEl = event.el;
                if (eventEl) {
                    // Hapus progress bar lama jika ada
                    const oldProgressBar = eventEl.querySelector('.progress-bar');
                    if (oldProgressBar) oldProgressBar.remove();
                    
                    // Buat progress bar baru
                    const progressBar = document.createElement('div');
                    progressBar.className = 'progress-bar absolute bottom-0 left-0 h-1 rounded-b-md';
                    progressBar.style.backgroundColor = mainTaskCompleted ? 'rgba(156, 163, 175, 0.7)' : 'rgba(255,255,255,0.7)';
                    progressBar.style.width = progressPercentage + '%';
                    eventEl.appendChild(progressBar);
                }
            }
            
            calendar.render();
        } else if (startDate && endDate) {
            calendar.addEvent({
                id: taskId,
                title: title,
                start: startDate,
                end: endDate,
                backgroundColor: getEventColor('medium', mainTaskCompleted),
                borderColor: getEventColor('medium', mainTaskCompleted),
                extendedProps: {
                    progress: progressPercentage,
                    mainTaskCompleted: mainTaskCompleted,
                    priority: 'medium',
                    hasSubtasks: document.querySelector(`#task-item-${taskId} .subtask-checkbox`).length > 0
                }
            });
            calendar.render();
        }
    } catch (e) {
        console.error('Error updating calendar event:', e);
    }
}

// Fungsi yang diperbarui untuk menangani perubahan subtask
function handleMainListSubtaskChange(e) {
    const checkbox = e.target;
    const subtaskId = checkbox.dataset.subTaskId;
    const taskId = checkbox.dataset.taskId;
    const isCompleted = checkbox.checked;
    const subtaskText = checkbox.closest('form')?.nextElementSibling;

    // Update UI segera
    if (subtaskText) {
        subtaskText.classList.toggle('line-through', isCompleted);
        subtaskText.classList.toggle('text-gray-400', isCompleted);
        subtaskText.classList.toggle('text-gray-600', !isCompleted);
    }

    fetch(`/subtasks/${subtaskId}/toggle`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ completed: isCompleted })
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            const { subtasksTotal, subtasksCompleted } = data;
            const progressPercentage = subtasksTotal > 0 ? Math.round((subtasksCompleted / subtasksTotal) * 100) : 0;
            const mainTaskCompleted = subtasksCompleted === subtasksTotal;

            // Update semua tampilan
            syncCheckboxStates(taskId);
            updateModalProgress(taskId, progressPercentage, subtasksCompleted, subtasksTotal);
            updateTaskProgress(taskId, progressPercentage, subtasksCompleted, subtasksTotal, mainTaskCompleted);
            
            // Force update calendar event
            updateCalendarEvent(taskId, progressPercentage, mainTaskCompleted);
            
            showNotification('Tugas berhasil diperbarui', 'success');
        } else {
            throw new Error(data.message || 'Failed to update subtask');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Kembalikan UI ke state sebelumnya jika error
        checkbox.checked = !isCompleted;
        if (subtaskText) {
            subtaskText.classList.toggle('line-through', !isCompleted);
            subtaskText.classList.toggle('text-gray-400', !isCompleted);
            subtaskText.classList.toggle('text-gray-600', isCompleted);
        }
        showNotification('Failed to update subtask: ' + error.message, 'error');
    });
}

    // Function to get event color based on priority and completion status
    function getEventColor(priority, isCompleted) {
        // Gray for completed tasks regardless of priority
        if (isCompleted) return '#d1d5db';
        
        // Color based on priority for incomplete tasks
        return priority === 'urgent' ? '#ef4444' :
               priority === 'high' ? '#f97316' :
               priority === 'medium' ? '#eab308' : '#22c55e';
    }

    // Function to open task modal
    function openTaskModal(taskId, content) {
        document.getElementById('taskModalContent').innerHTML = content;
        document.getElementById('taskModalContent').dataset.taskId = taskId;
        document.getElementById('taskModal').classList.remove('hidden');
        document.getElementById('taskModal').classList.add('flex');
        document.body.style.overflow = 'hidden';
        
        syncCheckboxStates(taskId);
        setupSubtaskHandlers();
    }

    // Function to close task modal
    function closeTaskModal() {
        document.getElementById('taskModal').classList.add('hidden');
        document.getElementById('taskModal').classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    // Function to setup subtask handlers in modal
    function setupSubtaskHandlers() {
        document.querySelectorAll('.subtask-checkbox-modal').forEach(checkbox => {
            checkbox.removeEventListener('change', handleModalSubtaskChange);
            checkbox.addEventListener('change', handleModalSubtaskChange);
        });
    }

    // Function to handle subtask change from modal
    function handleModalSubtaskChange(e) {
        const checkbox = e.target;
        const subtaskId = checkbox.dataset.subTaskId;
        const taskId = checkbox.dataset.taskId;
        const isCompleted = checkbox.checked;
        const subtaskItem = checkbox.closest('.subtask-item-modal');
        const subtaskText = subtaskItem?.querySelector('.subtask-text-modal');

        // Immediate UI update
        if (subtaskText) {
            subtaskText.classList.toggle('line-through', isCompleted);
            subtaskText.classList.toggle('text-gray-400', isCompleted);
            subtaskText.classList.toggle('text-gray-600', !isCompleted);
        }

        // API call to update subtask
        fetch(`/subtasks/${subtaskId}/toggle`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ completed: isCompleted })
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const { subtasksTotal, subtasksCompleted } = data;
                const progressPercentage = subtasksTotal > 0 ? Math.round((subtasksCompleted / subtasksTotal) * 100) : 0;
                const mainTaskCompleted = subtasksCompleted === subtasksTotal;

                updateModalProgress(taskId, progressPercentage, subtasksCompleted, subtasksTotal);
                updateMainTaskListSubtask(taskId, subtaskId, isCompleted);
                updateTaskProgress(taskId, progressPercentage, subtasksCompleted, subtasksTotal, mainTaskCompleted);

                showNotification('Tugas berhasil diperbarui', 'success');
            } else {
                throw new Error(data.message || 'Failed to update subtask');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Revert UI on error
            checkbox.checked = !isCompleted;
            if (subtaskText) {
                subtaskText.classList.toggle('line-through', !isCompleted);
                subtaskText.classList.toggle('text-gray-400', !isCompleted);
                subtaskText.classList.toggle('text-gray-600', isCompleted);
            }
            showNotification('Failed to update subtask: ' + error.message, 'error');
        });
    }

    // Function to handle subtask change from main list
    function handleMainListSubtaskChange(e) {
        const checkbox = e.target;
        const subtaskId = checkbox.dataset.subTaskId;
        const taskId = checkbox.dataset.taskId;
        const isCompleted = checkbox.checked;
        const subtaskText = checkbox.closest('form')?.nextElementSibling;

        // Immediate UI update
        if (subtaskText) {
            subtaskText.classList.toggle('line-through', isCompleted);
            subtaskText.classList.toggle('text-gray-400', isCompleted);
            subtaskText.classList.toggle('text-gray-600', !isCompleted);
        }

        // API call to update subtask
        fetch(`/subtasks/${subtaskId}/toggle`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ completed: isCompleted })
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const { subtasksTotal, subtasksCompleted } = data;
                const progressPercentage = subtasksTotal > 0 ? Math.round((subtasksCompleted / subtasksTotal) * 100) : 0;
                const mainTaskCompleted = subtasksCompleted === subtasksTotal;

                syncCheckboxStates(taskId);
                updateModalProgress(taskId, progressPercentage, subtasksCompleted, subtasksTotal);
                updateTaskProgress(taskId, progressPercentage, subtasksCompleted, subtasksTotal, mainTaskCompleted);

                showNotification('Tugas berhasil diperbarui', 'success');
            } else {
                throw new Error(data.message || 'Failed to update subtask');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Revert UI on error
            checkbox.checked = !isCompleted;
            if (subtaskText) {
                subtaskText.classList.toggle('line-through', !isCompleted);
                subtaskText.classList.toggle('text-gray-400', !isCompleted);
                subtaskText.classList.toggle('text-gray-600', isCompleted);
            }
            showNotification('Failed to update subtask: ' + error.message, 'error');
        });
    }

    // Function to sync main list checkboxes
    function syncMainTaskListCheckboxes() {
        document.querySelectorAll('.subtask-checkbox').forEach(checkbox => {
            checkbox.removeEventListener('change', handleMainListSubtaskChange);
            checkbox.addEventListener('change', handleMainListSubtaskChange);
        });
    }

    // Function to show notification
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transition-all duration-300 ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Setup modal event listeners
        document.getElementById('taskModal').addEventListener('click', function(e) {
            if (e.target === this) closeTaskModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('taskModal').classList.contains('hidden')) {
                closeTaskModal();
            }
        });

        // Initialize calendar
        try {
            var calendarEl = document.getElementById('calendar');
            if (!calendarEl) {
                console.error('Calendar element not found');
                return;
            }

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: false,
                locale: 'id',
                height: '100%',
                contentHeight: 'auto',
                events: [
                    @foreach($tasks as $task)
                        @php
                            $subtaskCompleted = $task->subTasks->where('completed', true)->count();
                            $subtaskTotal = $task->subTasks->count();
                            $progressPercentage = $subtaskTotal > 0 ? round(($subtaskCompleted / $subtaskTotal) * 100) : ($task->completed ? 100 : 0);
                        @endphp
                        {
                            id: '{{ $task->id }}',
                            title: '{{ addslashes($task->title) }}',
                            start: '{{ $task->start_date->format("Y-m-d") }}',
                            end: '{{ $task->end_date->copy()->addDay()->format("Y-m-d") }}',
                            backgroundColor: getEventColor('{{ $task->priority }}', {{ $task->completed ? 'true' : 'false' }}),
                            borderColor: getEventColor('{{ $task->priority }}', {{ $task->completed ? 'true' : 'false' }}),
                            extendedProps: {
                                description: '{{ addslashes($task->description ?? "") }}',
                                priority: '{{ $task->priority }}',
                                progress: {{ $progressPercentage }},
                                category: '{{ addslashes(optional($task->category)->name) }}',
                                hasSubtasks: {{ $task->subTasks->count() > 0 ? 'true' : 'false' }},
                                subtasksCompleted: {{ $subtaskCompleted }},
                                subtasksTotal: {{ $subtaskTotal }},
                                mainTaskCompleted: {{ $task->completed ? 'true' : 'false' }},
                                subtasks: [
                                    @foreach($task->subTasks as $subTask)
                                        {
                                            id: '{{ $subTask->id }}',
                                            title: '{{ addslashes($subTask->title) }}',
                                            completed: {{ $subTask->completed ? 'true' : 'false' }}
                                        }@if (!$loop->last),@endif
                                    @endforeach
                                ]
                            }
                        }@if (!$loop->last),@endif
                    @endforeach
                ],
                eventDidMount: function(info) {
                    const isCompleted = info.event.extendedProps.mainTaskCompleted;
                    
                    info.el.classList.add('rounded-md', 'shadow-sm', 'text-xs', 'font-medium', 'p-1', 'cursor-pointer', 'relative');
                    
                    // Set colors based on completion status
                    if (isCompleted) {
                        info.el.style.backgroundColor = '#d1d5db';
                        info.el.style.borderColor = '#d1d5db';
                        info.el.style.color = '#6b7280';
                    } else {
                        info.el.style.backgroundColor = info.event.backgroundColor;
                        info.el.style.borderColor = info.event.borderColor;
                        info.el.style.color = 'white';
                    }

                    // Add priority dot
                    const dot = document.createElement('span');
                    dot.className = 'priority-dot';
                    dot.style.backgroundColor = isCompleted ? '#9ca3af' : info.event.backgroundColor;
                    dot.style.width = '6px';
                    dot.style.height = '6px';
                    dot.style.borderRadius = '50%';
                    dot.style.display = 'inline-block';
                    dot.style.marginRight = '4px';
                    dot.style.verticalAlign = 'middle';
                    info.el.prepend(dot);

                    // Add progress bar if task has subtasks
                    if (info.event.extendedProps.hasSubtasks) {
                        const progress = info.event.extendedProps.progress;
                        const progressBar = document.createElement('div');
                        progressBar.className = 'absolute bottom-0 left-0 h-1 rounded-b-md';
                        progressBar.style.backgroundColor = isCompleted ? 'rgba(156, 163, 175, 0.7)' : 'rgba(255,255,255,0.7)';
                        progressBar.style.width = progress + '%';
                        info.el.appendChild(progressBar);
                    }
                },
                eventClick: function(info) {
                    info.jsEvent.preventDefault();

                    const event = info.event;
                    const taskId = event.id;
                    
                    currentTaskData = {
                        id: taskId,
                        title: event.title,
                        progress: event.extendedProps.progress,
                        subtasks: event.extendedProps.subtasks || [],
                        subtasksCompleted: event.extendedProps.subtasksCompleted || 0,
                        subtasksTotal: event.extendedProps.subtasksTotal || 0,
                        mainTaskCompleted: event.extendedProps.mainTaskCompleted || false
                    };

                    document.getElementById('taskModalContent').dataset.taskId = taskId;
                    document.getElementById('taskModalContent').dataset.taskData = JSON.stringify(currentTaskData);

                    // Format dates
                    const startDate = event.start ? event.start.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    }) : '';

                    const endDate = event.end ? new Date(event.end) : null;
                    if (endDate) endDate.setDate(endDate.getDate() - 1);
                    const formattedEndDate = endDate ? endDate.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    }) : '';

                    // Calculate duration
                    const durationInDays = event.start && event.end ?
                        Math.ceil((event.end.getTime() - event.start.getTime()) / (1000 * 60 * 60 * 24)) : 0;
                    const durationText = durationInDays > 0 ? durationInDays + ' hari' : '';

                    // Priority labels and colors
                    const priorityLabels = {
                        'urgent': 'Sangat Mendesak',
                        'high': 'Tinggi',
                        'medium': 'Sedang',
                        'low': 'Rendah'
                    };
                    const priorityLabel = priorityLabels[event.extendedProps.priority] || 'N/A';

                    const priorityColors = {
                        'urgent': 'bg-red-100 text-red-800',
                        'high': 'bg-orange-100 text-orange-800',
                        'medium': 'bg-yellow-100 text-yellow-800',
                        'low': 'bg-green-100 text-green-800'
                    };
                    const priorityClass = priorityColors[event.extendedProps.priority] || 'bg-gray-100 text-gray-800';

                    // Build subtasks HTML if exists
                    let subtasksHtml = '';
                    if (event.extendedProps.hasSubtasks) {
                        subtasksHtml = `
                            <div class="mt-4 border-t border-gray-200 pt-4">
                                <div class="flex justify-between items-center mb-2">
                                    <div class="text-xs text-gray-500 subtask-progress-text-modal">
                                        Sub tugas (${event.extendedProps.subtasksCompleted}/${event.extendedProps.subtasksTotal})
                                    </div>
                                    <div class="w-20 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-blue-500 subtask-progress-bar-modal" style="width: ${event.extendedProps.progress}%"></div>
                                    </div>
                                </div>
                                <ul class="space-y-2">
                                    ${event.extendedProps.subtasks.map(subtask => `
                                        <li class="flex items-center gap-2 subtask-item-modal">
                                            <form class="subtask-toggle-form">
                                                @csrf
                                                @method('PATCH')
                                                <input type="checkbox"
                                                    class="rounded h-3.5 w-3.5 text-blue-600 focus:ring-blue-500 border-gray-300 subtask-checkbox-modal"
                                                    data-sub-task-id="${subtask.id}"
                                                    data-task-id="${taskId}"
                                                    ${subtask.completed ? 'checked' : ''}>
                                            </form>
                                            <span class="text-sm ${subtask.completed ? 'line-through text-gray-400' : 'text-gray-600'} subtask-text-modal">
                                                ${subtask.title}
                                            </span>
                                        </li>
                                    `).join('')}
                                </ul>
                            </div>
                        `;
                    }

                    // Build modal content
                    const modalContent = `
                        <h4 class="text-xl font-bold text-gray-900 mb-2">${event.title}</h4>
                        <p class="text-gray-600 mb-4">${event.extendedProps.description || 'No description.'}</p>
                        <div class="space-y-2">
                            <p><strong>Kategori:</strong> <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">${event.extendedProps.category || 'N/A'}</span></p>
                            <p><strong>Prioritas:</strong> <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${priorityClass}">${priorityLabel}</span></p>
                            <p><strong>Durasi:</strong> ${startDate} - ${formattedEndDate} (${durationText})</p>
                            <p><strong>Progress:</strong> <span class="text-blue-600 subtask-percentage">${event.extendedProps.progress}%</span></p>
                        </div>
                        ${subtasksHtml}
                        <div class="mt-6 flex justify-end gap-3">
                            <a href="/tasks/${taskId}/edit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Edit
                            </a>
                            <form action="/tasks/${taskId}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Delete
                                </button>
                            </form>
                        </div>
                    `;
                    openTaskModal(taskId, modalContent);
                }
            });

            calendar.render();

            // Setup calendar navigation
            document.getElementById('prev-month')?.addEventListener('click', function() {
                calendar.prev();
                updateCalendarTitle();
            });

            document.getElementById('next-month')?.addEventListener('click', function() {
                calendar.next();
                updateCalendarTitle();
            });

            document.getElementById('month-view')?.addEventListener('click', function() {
                calendar.changeView('dayGridMonth');
                updateCalendarTitle();
                this.classList.add('bg-white', 'text-blue-600', 'shadow-sm');
                document.getElementById('week-view').classList.remove('bg-white', 'text-blue-600', 'shadow-sm');
                document.getElementById('week-view').classList.add('text-gray-600', 'hover:text-gray-800');
            });

            document.getElementById('week-view')?.addEventListener('click', function() {
                calendar.changeView('timeGridWeek');
                updateCalendarTitle();
                this.classList.add('bg-white', 'text-blue-600', 'shadow-sm');
                document.getElementById('month-view').classList.remove('bg-white', 'text-blue-600', 'shadow-sm');
                document.getElementById('month-view').classList.add('text-gray-600', 'hover:text-gray-800');
            });

            function updateCalendarTitle() {
                const titleElement = document.getElementById('calendar-title');
                if (titleElement && calendar) {
                    titleElement.textContent = calendar.view.title;
                }
            }
            updateCalendarTitle();

        } catch (e) {
            console.error('Error initializing calendar:', e);
        }

        // Setup task toggle forms
        document.querySelectorAll('.task-toggle-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const taskId = this.querySelector('.task-checkbox').dataset.taskId;
                const isCompleted = this.querySelector('.task-checkbox').checked;
                const taskItem = document.getElementById(`task-item-${taskId}`);
                if (!taskItem) return;

                // Immediate UI updates
                const taskTitle = taskItem.querySelector('.task-title');
                const subtaskCheckboxes = taskItem.querySelectorAll('.subtask-checkbox');

                if (taskTitle) {
                    taskTitle.classList.toggle('line-through', isCompleted);
                    taskTitle.classList.toggle('text-gray-400', isCompleted);
                    taskTitle.classList.toggle('text-gray-800', !isCompleted);
                }
                taskItem.classList.toggle('bg-gray-50', isCompleted);
                taskItem.classList.toggle('bg-white', !isCompleted);

                subtaskCheckboxes.forEach(checkbox => {
                    checkbox.checked = isCompleted;
                    const subtaskText = checkbox.closest('form').nextElementSibling;
                    if (subtaskText) {
                        subtaskText.classList.toggle('line-through', isCompleted);
                        subtaskText.classList.toggle('text-gray-400', isCompleted);
                        subtaskText.classList.toggle('text-gray-600', !isCompleted);
                    }
                });

                // API call to update task
                fetch(this.action, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ completed: isCompleted })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const subtaskTotal = data.subtasksTotal;
                        const subtaskCompleted = data.subtasksCompleted;
                        const progressPercentage = subtaskTotal > 0 ? Math.round((subtaskCompleted / subtaskTotal) * 100) : (isCompleted ? 100 : 0);
                        const mainTaskCompleted = isCompleted;

                        // Update all subtasks
                        subtaskCheckboxes.forEach(checkbox => {
                            const subtaskId = checkbox.dataset.subTaskId;
                            fetch(`/subtasks/${subtaskId}/toggle`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({ completed: isCompleted })
                            }).catch(e => console.error('Error updating subtask:', e));
                        });

                        // Update modal if open
                        syncCheckboxStates(taskId);
                        updateModalProgress(taskId, progressPercentage, subtaskCompleted, subtaskTotal);

                        // Update task progress and calendar
                        updateTaskProgress(taskId, progressPercentage, subtaskCompleted, subtaskTotal, mainTaskCompleted);
                        showNotification(data.message || 'Task updated successfully', 'success');
                    } else {
                        throw new Error(data.message || 'Failed to update task');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Revert UI on error
                    this.querySelector('.task-checkbox').checked = !isCompleted;
                    if (taskTitle) {
                        taskTitle.classList.toggle('line-through', !isCompleted);
                        taskTitle.classList.toggle('text-gray-400', !isCompleted);
                        taskTitle.classList.toggle('text-gray-800', isCompleted);
                    }
                    taskItem.classList.toggle('bg-gray-50', !isCompleted);
                    taskItem.classList.toggle('bg-white', isCompleted);

                    subtaskCheckboxes.forEach(checkbox => {
                        checkbox.checked = !isCompleted;
                        const subtaskText = checkbox.closest('form').nextElementSibling;
                        if (subtaskText) {
                            subtaskText.classList.toggle('line-through', !isCompleted);
                            subtaskText.classList.toggle('text-gray-400', !isCompleted);
                            subtaskText.classList.toggle('text-gray-600', isCompleted);
                        }
                    });

                    showNotification('Failed to update task: ' + error.message, 'error');
                });
            });
        });

        // Setup subtask checkboxes
        syncMainTaskListCheckboxes();

        // Initialize Pusher (if needed)
        try {
            window.Pusher = new Pusher('your-pusher-key', {
                cluster: 'your-pusher-cluster',
                encrypted: true
            });
            console.log('Pusher initialized');

            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: 'your-pusher-key',
                cluster: 'your-pusher-cluster',
                encrypted: true
            });
            console.log('Echo initialized');

            window.Echo.connector.pusher.connection.bind('connected', () => {
                console.log('WebSocket connected');
            });
            window.Echo.connector.pusher.connection.bind('disconnected', () => {
                console.log('WebSocket disconnected');
            });

            window.Echo.channel('task-updates')
                .listen('TaskUpdated', (data) => {
                    console.log('Task updated event received:', data);
                    const task = data.task;

                    // Update task list
                    updateTaskProgress(task.id, task.progress, task.subtasksCompleted, task.subtasksTotal, task.mainTaskCompleted);
                    task.subtasks.forEach(subtask => {
                        updateMainTaskListSubtask(task.id, subtask.id, subtask.completed);
                    });

                    // Update calendar event
                    let event = calendar.getEventById(task.id);
                    if (event) {
                        event.setProp('title', task.title);
                        event.setProp('start', task.start_date);
                        event.setProp('end', task.end_date);
                        event.setProp('backgroundColor', getEventColor(task.priority, task.mainTaskCompleted));
                        event.setProp('borderColor', getEventColor(task.priority, task.mainTaskCompleted));
                        event.setExtendedProp('progress', task.progress);
                        event.setExtendedProp('mainTaskCompleted', task.mainTaskCompleted);
                        event.setExtendedProp('subtasksCompleted', task.subtasksCompleted);
                        event.setExtendedProp('subtasksTotal', task.subtasksTotal);
                        event.setExtendedProp('subtasks', task.subtasks);
                    } else {
                        calendar.addEvent({
                            id: task.id,
                            title: task.title,
                            start: task.start_date,
                            end: task.end_date,
                            backgroundColor: getEventColor(task.priority, task.mainTaskCompleted),
                            borderColor: getEventColor(task.priority, task.mainTaskCompleted),
                            extendedProps: {
                                description: task.description || '',
                                priority: task.priority,
                                progress: task.progress,
                                category: task.category || '',
                                hasSubtasks: task.subtasks.length > 0,
                                subtasksCompleted: task.subtasksCompleted,
                                subtasksTotal: task.subtasksTotal,
                                mainTaskCompleted: task.mainTaskCompleted,
                                subtasks: task.subtasks
                            }
                        });
                    }
                    calendar.render();

                    // Update modal if open
                    if (!document.getElementById('taskModal').classList.contains('hidden')) {
                        const modalTaskId = document.getElementById('taskModalContent').dataset.taskId;
                        if (modalTaskId === task.id) {
                            const modalProgressBar = document.querySelector('.subtask-progress-bar-modal');
                            const modalProgressText = document.querySelector('.subtask-progress-text-modal');
                            if (modalProgressBar) modalProgressBar.style.width = `${task.progress}%`;
                            if (modalProgressText) modalProgressText.textContent = `Subtasks (${task.subtasksCompleted}/${task.subtasksTotal})`;
                            
                            // Sync checkbox states
                            syncCheckboxStates(task.id);
                        }
                    }
                });
        } catch (e) {
            console.error('Error initializing Pusher:', e);
        }

         document.querySelectorAll('.child-items').forEach(item => {
        item.classList.add('collapsed');
    });

    // Toggle button functionality
    document.querySelectorAll('.toggle-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const subtaskId = this.getAttribute('data-subtask-id');
            const childItems = document.getElementById(`child-items-${subtaskId}`);
            const icon = this.querySelector('.toggle-icon');
            
            childItems.classList.toggle('collapsed');
            icon.classList.toggle('collapsed');
        });
    });
    });
</script>
@endpush
@endsection