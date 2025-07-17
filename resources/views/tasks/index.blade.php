@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header section remains unchanged -->
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
                <!-- Calendar section remains unchanged -->
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

                <!-- Task list section with modified tree view -->
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
                        function renderSubtasks($subtasks, $parentId = null, $task = null) {
                            $html = '';

                            foreach ($subtasks->where('parent_id', $parentId) as $subTask) {
                                $isParent = $subtasks->where('parent_id', $subTask->id)->count() > 0;
                                
                                if ($isParent) {
                                    // Parent subtask with toggle button and title
                                    $html .= '<div class="subtask-parent" data-subtask-id="' . $subTask->id . '">';
                                    $html .= '<div class="flex items-center gap-2">';
                                    $html .= '<button class="subtask-parent-toggle-btn text-gray-400 hover:text-gray-600 transition-colors duration-200" 
                                                data-subtask-id="' . $subTask->id . '" 
                                                data-expanded="true">
                                                <svg class="w-4 h-4 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>';
                                    $html .= '<span class="text-sm font-semibold text-gray-700 cursor-pointer subtask-parent-title" data-subtask-id="' . $subTask->id . '">' . e($subTask->title) . '</span>';
                                    $html .= '</div>';
                                    
                                    // Container for children with vertical layout
                                    $html .= '<div class="subtask-children pl-6 mt-2" id="subtask-children-' . $subTask->id . '">';
                                    $html .= renderSubtasks($subtasks, $subTask->id, $task);
                                    $html .= '</div>';
                                    $html .= '</div>';
                                } else {
                                    // Leaf subtask with checkbox
                                    $checked = $subTask->completed ? 'checked' : '';
                                    $lineClass = $subTask->completed ? 'line-through text-gray-400' : 'text-gray-600';

                                    $html .= '<div class="subtask-item flex items-center gap-2 py-1" data-subtask-id="' . $subTask->id . '">';
                                    $html .= '<form action="' . route('subtasks.toggle', $subTask->id) . '" method="POST" class="subtask-toggle-form">';
                                    $html .= csrf_field() . method_field('PATCH');
                                    $html .= '<input type="checkbox"
                                        class="subtask-checkbox"
                                        data-sub-task-id="' . $subTask->id . '"
                                        data-task-id="' . $task->id . '"
                                        data-is-leaf="true"
                                        data-parent-id="' . $subTask->parent_id . '" ' . ($subTask->completed ? ' checked' : '' ) . '>';
                                    $html .= '</form>';
                                    $html .= '<span class="text-sm ' . $lineClass . ' subtask-text">' . e($subTask->title) . '</span>';
                                    $html .= '</div>';
                                }
                            }

                            return $html;
                        }
                        @endphp

                        @foreach($tasks as $task)
                        @php
                        $leafSubTasks = $task->subTasks->filter(function($subTask) use ($task) {
                        return !$task->subTasks->where('parent_id', $subTask->id)->count();
                        });
                        $subtaskCompleted = $leafSubTasks->where('completed', true)->count();
                        $subtaskTotal = $leafSubTasks->count();
                        $progressPercentage = $subtaskTotal > 0
                        ? round(($subtaskCompleted / $subtaskTotal) * 100)
                        : ($task->completed ? 100 : 0);
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
                                        <div class="flex items-center gap-2 flex-1">
                                            @if($task->subTasks->count() > 0)
                                            <!-- Toggle button untuk subtasks -->
                                            <button class="subtask-toggle-btn text-gray-400 hover:text-gray-600 transition-colors duration-200" 
                                                    data-task-id="{{ $task->id }}" 
                                                    data-expanded="true">
                                                <svg class="w-4 h-4 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                            @endif
                                            <div>
                                                <h3 class="font-medium text-gray-800 {{ $task->completed ? 'line-through text-gray-400' : '' }} task-title cursor-pointer" data-task-id="{{ $task->id }}">
                                                    {{ $task->title }}
                                                </h3>
                                                <div class="flex items-center gap-2 text-sm text-gray-500 mt-1">
                                                    <span>{{ $task->start_date->format('M d') }} - {{ $task->end_date->format('M d') }}</span>
                                                    <span class="text-xs text-gray-400">•</span>
                                                    <span>
                                                        <span>
                                                            {{ $task->durationDays }} {{ $task->durationDays > 1 ? 'days' : 'day' }}
                                                        </span>

                                                        @if($subtaskTotal > 0)
                                                        <span class="text-xs text-gray-400">•</span>
                                                        <span class="text-blue-600 task-progress-percentage">{{ $progressPercentage }}%</span>
                                                        @endif
                                                </div>
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
                                    <div class="mt-3 ml-7 pl-3 border-l-2 border-gray-200 task-subtasks-container" id="subtasks-container-{{ $task->id }}">
                                        <div class="flex justify-between items-center mb-2">
                                            <div class="text-xs text-gray-500 subtask-progress-text">
                                                Subtugas ({{ $subtaskCompleted }}/{{ $subtaskTotal }})
                                            </div>
                                            <div class="w-20 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-blue-500 subtask-progress-bar" style="width: {{ $progressPercentage }}%"></div>
                                            </div>
                                        </div>
                                        <div class="space-y-2 vertical-tree" id="task-tree-{{ $task->id }}">
                                            {!! renderSubtasks($task->subTasks, null, $task) !!}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right sidebar remains unchanged -->
            <div class="space-y-5">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 p-3 rounded-lg">
                            <div class="text-blue-600 text-sm mb-1">Total Tugas</div>
                            <div class="text-2xl font-bold text-gray-800" id="total-tasks-count">{{ $totalTasks }}</div>
                        </div>
                        <div class="bg-green-50 p-3 rounded-lg">
                            <div class="text-green-600 text-sm mb-1">Selesai</div>
                            <div class="text-2xl font-bold text-gray-800" id="completed-tasks-count">{{ $tasks->where('completed', true)->count() }}</div>
                        </div>
                        <div class="bg-purple-50 p-3 rounded-lg">
                            <div class="text-purple-600 text-sm mb-1">Progress</div>
                            <div class="text-2xl font-bold text-gray-800" id="overall-progress-percentage">
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

<!-- Modal and loading indicator remain unchanged -->
<div id="taskModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 mx-4 max-h-[80vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6 sticky top-0 bg-white">
            <h3 class="text-lg font-semibold text-gray-800">Detail Tugas</h3>
            <button onclick="closeTaskModal()" class="text-gray-500 hover:text-gray-700 p-1 rounded-full hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div id="taskModalContent" class="space-y-4 text-sm text-gray-700">
        </div>
    </div>
</div>

<div id="loading-indicator" class="fixed top-4 right-4 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg hidden">
    <div class="flex items-center gap-2">
        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>Memperbarui...</span>
    </div>
</div>

@endsection

@push('scripts')
<!-- FullCalendar CSS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js'></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize calendar
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            headerToolbar: false,
            events: [
                @foreach($tasks as $task)
                {
                    id: '{{ $task->id }}',
                    title: '{{ $task->title }}',
                    start: '{{ $task->start_date->format('Y-m-d') }}',
                    end: '{{ $task->end_date->addDay()->format('Y-m-d') }}', // add 1 day to make it inclusive
                    extendedProps: {
                        description: '{{ $task->description }}',
                        priority: '{{ $task->priority }}',
                        completed: {{ $task->completed ? 'true' : 'false' }},
                        url: '{{ route('tasks.edit', $task->id) }}'
                    },
                    backgroundColor: getPriorityColor('{{ $task->priority }}'),
                    borderColor: getPriorityColor('{{ $task->priority }}'),
                    textColor: '#fff',
                    @if($task->completed)
                        className: 'line-through opacity-80'
                    @endif
                },
                @endforeach
            ],
            eventClick: function(info) {
                info.jsEvent.preventDefault();
                
                const task = info.event;
                const modalContent = document.getElementById('taskModalContent');
                
                let priorityText = '';
                switch(task.extendedProps.priority) {
                    case 'urgent':
                        priorityText = 'Sangat Mendesak';
                        break;
                    case 'high':
                        priorityText = 'Tinggi';
                        break;
                    case 'medium':
                        priorityText = 'Sedang';
                        break;
                    case 'low':
                        priorityText = 'Rendah';
                        break;
                }
                
                modalContent.innerHTML = `
                    <div>
                        <h4 class="font-semibold text-lg mb-2">${task.title}</h4>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="px-2 py-1 text-xs rounded-full ${getPriorityBadgeClass(task.extendedProps.priority)}">
                                ${priorityText}
                            </span>
                            ${task.extendedProps.completed ? '<span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Selesai</span>' : ''}
                        </div>
                    </div>
                    
                    <div class="space-y-1">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Mulai:</span>
                            <span class="font-medium">${formatDate(task.start)}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Selesai:</span>
                            <span class="font-medium">${formatDate(task.end)}</span>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h5 class="font-medium text-gray-700 mb-1">Deskripsi:</h5>
                        <p class="text-gray-600">${task.extendedProps.description || 'Tidak ada deskripsi'}</p>
                    </div>
                    
                    <div class="flex gap-2 mt-6 pt-4 border-t border-gray-200">
                        <a href="${task.extendedProps.url}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg transition-colors duration-200">
                            Edit
                        </a>
                        <button onclick="closeTaskModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded-lg transition-colors duration-200">
                            Tutup
                        </button>
                    </div>
                `;
                
                document.getElementById('taskModal').classList.remove('hidden');
            }
        });
        
        calendar.render();
        
        // Set initial calendar title
        updateCalendarTitle(calendar);
        
        // Navigation buttons
        document.getElementById('prev-month').addEventListener('click', function() {
            calendar.prev();
            updateCalendarTitle(calendar);
        });
        
        document.getElementById('next-month').addEventListener('click', function() {
            calendar.next();
            updateCalendarTitle(calendar);
        });
        
        // View switching
        document.getElementById('month-view').addEventListener('click', function() {
            calendar.changeView('dayGridMonth');
            updateCalendarTitle(calendar);
            this.classList.add('bg-white', 'text-blue-600', 'shadow-sm');
            document.getElementById('week-view').classList.remove('bg-white', 'text-blue-600', 'shadow-sm');
        });
        
        document.getElementById('week-view').addEventListener('click', function() {
            calendar.changeView('timeGridWeek');
            updateCalendarTitle(calendar);
            this.classList.add('bg-white', 'text-blue-600', 'shadow-sm');
            document.getElementById('month-view').classList.remove('bg-white', 'text-blue-600', 'shadow-sm');
        });
        
        // AJAX Task checkbox handling
        document.querySelectorAll('.task-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                handleTaskToggle(this);
            });
        });
        
        // AJAX Subtask checkbox handling
        document.querySelectorAll('.subtask-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                handleSubtaskToggle(this);
            });
        });

        // Subtask toggle functionality
        document.querySelectorAll('.subtask-toggle-btn').forEach(button => {
            button.addEventListener('click', function() {
                toggleSubtasks(this);
            });
        });

        // Subtask parent toggle functionality
        document.querySelectorAll('.subtask-parent-toggle-btn').forEach(button => {
            button.addEventListener('click', function() {
                toggleSubtaskParent(this);
            });
        });

        // Task title click to toggle subtasks
        document.querySelectorAll('.task-title').forEach(title => {
            title.addEventListener('click', function() {
                const taskId = this.getAttribute('data-task-id');
                const toggleBtn = document.querySelector(`[data-task-id="${taskId}"].subtask-toggle-btn`);
                if (toggleBtn) {
                    toggleSubtasks(toggleBtn);
                }
            });
        });

        // Subtask parent title click to toggle children
        document.querySelectorAll('.subtask-parent-title').forEach(title => {
            title.addEventListener('click', function() {
                const subtaskId = this.getAttribute('data-subtask-id');
                const toggleBtn = document.querySelector(`[data-subtask-id="${subtaskId}"].subtask-parent-toggle-btn`);
                if (toggleBtn) {
                    toggleSubtaskParent(toggleBtn);
                }
            });
        });
    });

    // Function to toggle subtask parent visibility
    function toggleSubtaskParent(button) {
        const subtaskId = button.getAttribute('data-subtask-id');
        const childrenContainer = document.getElementById(`subtask-children-${subtaskId}`);
        const icon = button.querySelector('svg');
        const isExpanded = button.getAttribute('data-expanded') === 'true';

        if (isExpanded) {
            // Collapse children
            childrenContainer.style.maxHeight = childrenContainer.scrollHeight + 'px';
            childrenContainer.offsetHeight; // Force reflow
            childrenContainer.style.maxHeight = '0';
            childrenContainer.style.opacity = '0';
            childrenContainer.style.paddingTop = '0';
            childrenContainer.style.paddingBottom = '0';
            childrenContainer.style.marginTop = '0';
            
            // Rotate icon
            icon.style.transform = 'rotate(-90deg)';
            
            button.setAttribute('data-expanded', 'false');
            
            // Hide completely after animation
            setTimeout(() => {
                if (button.getAttribute('data-expanded') === 'false') {
                    childrenContainer.style.display = 'none';
                }
            }, 200);
        } else {
            // Expand children
            childrenContainer.style.display = 'block';
            childrenContainer.style.maxHeight = '0';
            childrenContainer.style.opacity = '0';
            childrenContainer.style.paddingTop = '0';
            childrenContainer.style.paddingBottom = '0';
            childrenContainer.style.marginTop = '0';
            
            // Force reflow
            childrenContainer.offsetHeight;
            
            // Animate to full height
            childrenContainer.style.maxHeight = childrenContainer.scrollHeight + 'px';
            childrenContainer.style.opacity = '1';
            childrenContainer.style.paddingTop = '';
            childrenContainer.style.paddingBottom = '';
            childrenContainer.style.marginTop = '';
            
            // Rotate icon
            icon.style.transform = 'rotate(0deg)';
            
            button.setAttribute('data-expanded', 'true');
            
            // Remove max-height after animation
            setTimeout(() => {
                if (button.getAttribute('data-expanded') === 'true') {
                    childrenContainer.style.maxHeight = '';
                }
            }, 200);
        }
    }

    // Function to toggle subtasks visibility
    function toggleSubtasks(button) {
        const taskId = button.getAttribute('data-task-id');
        const subtasksContainer = document.getElementById(`subtasks-container-${taskId}`);
        const icon = button.querySelector('svg');
        const isExpanded = button.getAttribute('data-expanded') === 'true';

        if (isExpanded) {
            // Collapse subtasks
            subtasksContainer.style.maxHeight = subtasksContainer.scrollHeight + 'px';
            subtasksContainer.offsetHeight; // Force reflow
            subtasksContainer.style.maxHeight = '0';
            subtasksContainer.style.opacity = '0';
            subtasksContainer.style.paddingTop = '0';
            subtasksContainer.style.paddingBottom = '0';
            subtasksContainer.style.marginTop = '0';
            
            // Rotate icon
            icon.style.transform = 'rotate(-90deg)';
            
            button.setAttribute('data-expanded', 'false');
            
            // Hide completely after animation
            setTimeout(() => {
                if (button.getAttribute('data-expanded') === 'false') {
                    subtasksContainer.style.display = 'none';
                }
            }, 200);
        } else {
            // Expand subtasks
            subtasksContainer.style.display = 'block';
            subtasksContainer.style.maxHeight = '0';
            subtasksContainer.style.opacity = '0';
            subtasksContainer.style.paddingTop = '0';
            subtasksContainer.style.paddingBottom = '0';
            subtasksContainer.style.marginTop = '0';
            
            // Force reflow
            subtasksContainer.offsetHeight;
            
            // Animate to full height
            subtasksContainer.style.maxHeight = subtasksContainer.scrollHeight + 'px';
            subtasksContainer.style.opacity = '1';
            subtasksContainer.style.paddingTop = '';
            subtasksContainer.style.paddingBottom = '';
            subtasksContainer.style.marginTop = '';
            
            // Rotate icon
            icon.style.transform = 'rotate(0deg)';
            
            button.setAttribute('data-expanded', 'true');
            
            // Remove max-height after animation
            setTimeout(() => {
                if (button.getAttribute('data-expanded') === 'true') {
                    subtasksContainer.style.maxHeight = '';
                }
            }, 200);
        }
    }
    
    // Function to handle task toggle with AJAX - Enhanced to check/uncheck all subtasks
    function handleTaskToggle(checkbox) {
        const taskId = checkbox.getAttribute('data-task-id');
        const form = checkbox.closest('form');
        const url = form.getAttribute('action');
        const isCompleted = checkbox.checked;
        
        // Show loading indicator
        showLoadingIndicator();
        
        // Get CSRF token
        const token = form.querySelector('input[name="_token"]').value;
        
        // Send AJAX request
        fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                completed: isCompleted
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update UI elements
                updateTaskUI(taskId, data);
                updateSummaryUI(data);
                updateCalendarEvent(taskId, data.task.completed);
                
                // Update all subtasks to match the main task status
                updateAllSubtasksStatus(taskId, isCompleted);
                
                // Show success message (optional)
                showNotification('Tugas berhasil diperbarui!', 'success');
            } else {
                // Revert checkbox state on error
                checkbox.checked = !isCompleted;
                showNotification('Gagal memperbarui tugas!', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Revert checkbox state on error
            checkbox.checked = !isCompleted;
            showNotification('Terjadi kesalahan!', 'error');
        })
        .finally(() => {
            hideLoadingIndicator();
        });
    }

    // Function to update all subtasks status when main task is toggled
    function updateAllSubtasksStatus(taskId, isCompleted) {
        const taskContainer = document.getElementById(`task-item-${taskId}`);
        const subtaskCheckboxes = taskContainer.querySelectorAll('.subtask-checkbox');
        
        subtaskCheckboxes.forEach(subtaskCheckbox => {
            const subtaskId = subtaskCheckbox.getAttribute('data-sub-task-id');
            const subtaskForm = subtaskCheckbox.closest('form');
            const subtaskUrl = subtaskForm.getAttribute('action');
            const token = subtaskForm.querySelector('input[name="_token"]').value;
            
            // Update checkbox visually first
            subtaskCheckbox.checked = isCompleted;
            
            // Update subtask text styling
            const subtaskText = subtaskCheckbox.parentElement.nextElementSibling;
            if (isCompleted) {
                subtaskText.classList.add('line-through', 'text-gray-400');
                subtaskText.classList.remove('text-gray-600');
            } else {
                subtaskText.classList.remove('line-through', 'text-gray-400');
                subtaskText.classList.add('text-gray-600');
            }
            
            // Send AJAX request to update subtask in backend
            fetch(subtaskUrl, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    completed: isCompleted
                })
            })
            .catch(error => {
                console.error('Error updating subtask:', error);
            });
        });
    }
    
    // Function to handle subtask toggle with AJAX
    function handleSubtaskToggle(checkbox) {
        const subtaskId = checkbox.getAttribute('data-sub-task-id');
        const taskId = checkbox.getAttribute('data-task-id');
        const form = checkbox.closest('form');
        const url = form.getAttribute('action');
        const isCompleted = checkbox.checked;
        
        // Show loading indicator
        showLoadingIndicator();
        
        // Get CSRF token
        const token = form.querySelector('input[name="_token"]').value;
        
        // Send AJAX request
        fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                completed: isCompleted
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update subtask UI
                updateSubtaskUI(subtaskId, data);
                // Update parent task progress
                updateTaskProgressUI(taskId, data);
                updateSummaryUI(data);
                
                // Show success message (optional)
                showNotification('Subtugas berhasil diperbarui!', 'success');
            } else {
                // Revert checkbox state on error
                checkbox.checked = !isCompleted;
                showNotification('Gagal memperbarui subtugas!', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Revert checkbox state on error
            checkbox.checked = !isCompleted;
            showNotification('Terjadi kesalahan!', 'error');
        })
        .finally(() => {
            hideLoadingIndicator();
        });
    }
    
    // Function to update task UI elements
    function updateTaskUI(taskId, data) {
        const taskItem = document.getElementById(`task-item-${taskId}`);
        const taskTitle = taskItem.querySelector('.task-title');
        const taskCheckbox = taskItem.querySelector('.task-checkbox');
        
        // Update checkbox state
        taskCheckbox.checked = data.task.completed;
        
        // Update task title styling
        if (data.task.completed) {
            taskTitle.classList.add('line-through', 'text-gray-400');
            taskItem.classList.add('bg-gray-50');
            taskItem.classList.remove('bg-white');
        } else {
            taskTitle.classList.remove('line-through', 'text-gray-400');
            taskItem.classList.remove('bg-gray-50');
            taskItem.classList.add('bg-white');
        }
        
        // Update progress if subtasks exist
        if (data.progressPercentage !== undefined) {
            updateTaskProgressUI(taskId, data);
        }
    }
    
    // Function to update subtask UI elements
    function updateSubtaskUI(subtaskId, data) {
        const subtaskCheckbox = document.querySelector(`[data-sub-task-id="${subtaskId}"]`);
        const subtaskText = subtaskCheckbox.parentElement.nextElementSibling;
        
        // Update checkbox state
        subtaskCheckbox.checked = data.subtask.completed;
        
        // Update subtask text styling
        if (data.subtask.completed) {
            subtaskText.classList.add('line-through', 'text-gray-400');
            subtaskText.classList.remove('text-gray-600');
        } else {
            subtaskText.classList.remove('line-through', 'text-gray-400');
            subtaskText.classList.add('text-gray-600');
        }
    }
    
    // Function to update task progress UI
    function updateTaskProgressUI(taskId, data) {
        const taskItem = document.getElementById(`task-item-${taskId}`);
        const progressPercentage = taskItem.querySelector('.task-progress-percentage');
        const subtaskProgressText = taskItem.querySelector('.subtask-progress-text');
        const subtaskProgressBar = taskItem.querySelector('.subtask-progress-bar');
        
        if (progressPercentage) {
            progressPercentage.textContent = `${data.progressPercentage}%`;
        }
        
        if (subtaskProgressText) {
            subtaskProgressText.textContent = `Subtugas (${data.subtaskCompleted}/${data.subtaskTotal})`;
        }
        
        if (subtaskProgressBar) {
            subtaskProgressBar.style.width = `${data.progressPercentage}%`;
        }
    }
    
    // Function to update summary UI
    function updateSummaryUI(data) {
        if (data.totalTasks !== undefined) {
            document.getElementById('total-tasks-count').textContent = data.totalTasks;
        }
        
        if (data.completedTasks !== undefined) {
            document.getElementById('completed-tasks-count').textContent = data.completedTasks;
        }
        
        if (data.overallProgress !== undefined) {
            document.getElementById('overall-progress-percentage').textContent = `${data.overallProgress}%`;
        }
    }
    
    // Function to update calendar event
    function updateCalendarEvent(taskId, completed) {
        // This would require access to the calendar instance
        // You might need to store the calendar instance globally or implement this differently
        // For now, we'll skip this or implement a simple refresh
    }
    
    // Utility functions
    function showLoadingIndicator() {
        document.getElementById('loading-indicator').classList.remove('hidden');
    }
    
    function hideLoadingIndicator() {
        document.getElementById('loading-indicator').classList.add('hidden');
    }
    
    function showNotification(message, type = 'success') {
        // Create a simple notification
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-4 py-2 rounded-lg shadow-lg z-50 ${
            type === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Remove notification after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
    
    function updateCalendarTitle(calendar) {
        const view = calendar.view;
        let title = '';
        
        if (view.type === 'dayGridMonth') {
            title = view.currentStart.toLocaleDateString('id-ID', { 
                month: 'long', 
                year: 'numeric' 
            });
        } else if (view.type === 'timeGridWeek') {
            const start = view.currentStart;
            const end = new Date(view.currentEnd);
            end.setDate(end.getDate() - 1); // Adjust end date
            
            const startMonth = start.toLocaleDateString('id-ID', { month: 'short' });
            const endMonth = end.toLocaleDateString('id-ID', { month: 'short' });
            
            if (startMonth === endMonth) {
                title = `${start.getDate()} - ${end.getDate()} ${startMonth} ${start.getFullYear()}`;
            } else {
                title = `${start.getDate()} ${startMonth} - ${end.getDate()} ${endMonth} ${start.getFullYear()}`;
            }
        }
        
        document.getElementById('calendar-title').textContent = title;
    }
    
    function getPriorityColor(priority) {
        switch(priority) {
            case 'urgent': return '#ef4444'; // red-500
            case 'high': return '#f97316'; // orange-500
            case 'medium': return '#eab308'; // yellow-500
            case 'low': return '#22c55e'; // green-500
            default: return '#3b82f6'; // blue-500
        }
    }
    
    function getPriorityBadgeClass(priority) {
        switch(priority) {
            case 'urgent': return 'bg-red-100 text-red-800';
            case 'high': return 'bg-orange-100 text-orange-800';
            case 'medium': return 'bg-yellow-100 text-yellow-800';
            case 'low': return 'bg-green-100 text-green-800';
            default: return 'bg-blue-100 text-blue-800';
        }
    }
    
    function formatDate(date) {
        return new Date(date).toLocaleDateString('id-ID', { 
            weekday: 'long', 
            day: 'numeric', 
            month: 'long', 
            year: 'numeric' 
        });
    }
    
    function closeTaskModal() {
        document.getElementById('taskModal').classList.add('hidden');
    }
</script>

<style>
    /* CSS for vertical tree structure */
    .vertical-tree {
        display: flex;
        flex-direction: column;
    }
    
    .subtask-parent {
        margin-bottom: 0.5rem;
    }
    
    .subtask-children {
        transition: all 0.2s ease-in-out;
        overflow: hidden;
        padding-left: 1.5rem;
    }
    
    .subtask-item {
        margin-bottom: 0.25rem;
    }
    
    .subtask-toggle-btn svg,
    .subtask-parent-toggle-btn svg {
        transition: transform 0.2s ease-in-out;
    }
    
    .task-subtasks-container {
        transition: all 0.2s ease-in-out;
        overflow: hidden;
    }
    
    .task-title,
    .subtask-parent-title {
        cursor: pointer;
    }
</style>

@endpush

