@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 p-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header section with modern design -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">Management Tugas</h1>
                </div>
                <p class="text-gray-600">Kelola tugas dan progres dengan lebih teratur dan efisien</p>
            </div>
            <a href="{{ route('tasks.create') }}"
                class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-xl font-medium shadow-lg hover:shadow-xl transition-all duration-300 flex items-center gap-2 transform hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tugas Baru
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <!-- Calendar section with modern styling -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Kalender Tugas
                        </h2>
                        <div class="flex items-center gap-3">
                            <button id="prev-month" class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <span id="calendar-title" class="text-lg font-semibold text-gray-800 min-w-[200px] text-center"></span>
                            <button id="next-month" class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>

                            <div class="flex bg-gray-100 p-1 rounded-xl ml-4">
                                <button class="px-4 py-2 text-sm rounded-lg transition-all duration-200 fc-dayGridMonth-button bg-white text-blue-600 shadow-sm font-medium" id="month-view">
                                    Bulan
                                </button>
                                <button class="px-4 py-2 text-sm rounded-lg transition-all duration-200 fc-timeGridWeek-button text-gray-600 hover:text-gray-800 font-medium" id="week-view">
                                    Minggu
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="calendar" style="height: 600px;" class="rounded-xl overflow-hidden border border-gray-200 shadow-inner"></div>
                </div>

                <!-- Task list section with enhanced design -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 8l2 2 4-4"></path>
                            </svg>
                            Tugas Saya
                        </h2>
                        <div class="flex gap-2">
                            <button class="px-4 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded-xl transition-all duration-200">Semua</button>
                            <button class="px-4 py-2 text-sm bg-blue-100 text-blue-600 hover:bg-blue-200 rounded-xl transition-all duration-200">Aktif</button>
                            <button class="px-4 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded-xl transition-all duration-200">Selesai</button>
                        </div>
                    </div>

                    <div class="space-y-4" id="task-list-container">
                        @php
                        function renderSubtasks($subtasks, $parentId = null, $task = null) {
                            $html = '';

                            foreach ($subtasks->where('parent_id', $parentId) as $subTask) {
                                $isParent = $subtasks->where('parent_id', $subTask->id)->count() > 0;
                                
                                if ($isParent) {
                                    // Parent subtask with toggle button and title
                                    $html .= '<div class="subtask-parent" data-subtask-id="' . $subTask->id . '">';
                                    $html .= '<div class="flex items-center gap-3 py-2">';
                                    $html .= '<button class="subtask-parent-toggle-btn text-gray-400 hover:text-blue-600 transition-all duration-200 p-1 rounded-lg hover:bg-blue-50" 
                                                data-subtask-id="' . $subTask->id . '" 
                                                data-expanded="true">
                                                <svg class="w-4 h-4 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>';
                                    $html .= '<span class="text-sm font-semibold text-gray-700 cursor-pointer subtask-parent-title hover:text-blue-600 transition-colors duration-200" data-subtask-id="' . $subTask->id . '">' . e($subTask->title) . '</span>';
                                    $html .= '</div>';
                                    
                                    // Container for children with vertical layout
                                    $html .= '<div class="subtask-children pl-8 mt-2 border-l-2 border-blue-100" id="subtask-children-' . $subTask->id . '">';
                                    $html .= renderSubtasks($subtasks, $subTask->id, $task);
                                    $html .= '</div>';
                                    $html .= '</div>';
                                } else {
                                    // Leaf subtask with checkbox
                                    $checked = $subTask->completed ? 'checked' : '';
                                    $lineClass = $subTask->completed ? 'line-through text-gray-400' : 'text-gray-700';

                                    $html .= '<div class="subtask-item flex items-center gap-3 py-2 px-3 rounded-lg hover:bg-blue-50 transition-all duration-200" data-subtask-id="' . $subTask->id . '">';
                                    $html .= '<form action="' . route('subtasks.toggle', $subTask->id) . '" method="POST" class="subtask-toggle-form">';
                                    $html .= csrf_field() . method_field('PATCH');
                                    $html .= '<input type="checkbox"
                                        class="subtask-checkbox w-4 h-4 text-blue-600 rounded focus:ring-blue-500 focus:ring-2"
                                        data-sub-task-id="' . $subTask->id . '"
                                        data-task-id="' . $task->id . '"
                                        data-is-leaf="true"
                                        data-parent-id="' . $subTask->parent_id . '" ' . ($subTask->completed ? ' checked' : '' ) . '>';
                                    $html .= '</form>';
                                    $html .= '<span class="text-sm ' . $lineClass . ' subtask-text flex-1">' . e($subTask->title) . '</span>';
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

                        <div class="border border-gray-200 rounded-xl p-5 transition-all duration-300 hover:border-blue-300 hover:shadow-lg {{ $task->completed ? 'bg-gray-50/50' : 'bg-white' }} backdrop-blur-sm" id="task-item-{{ $task->id }}">
                            <div class="flex items-start gap-4">
                                <form action="{{ route('tasks.toggle', $task->id) }}" method="POST" class="task-toggle-form">
                                    @csrf
                                    @method('PATCH')
                                    <input type="checkbox"
                                        class="task-checkbox w-5 h-5 text-blue-600 rounded focus:ring-blue-500 focus:ring-2 mt-1"
                                        data-task-id="{{ $task->id }}"
                                        {{ $task->completed ? 'checked' : '' }}>
                                </form>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-center gap-3 flex-1">
                                            @if($task->subTasks->count() > 0)
                                            <!-- Toggle button untuk subtasks -->
                                            <button class="subtask-toggle-btn text-gray-400 hover:text-blue-600 transition-all duration-200 p-1 rounded-lg hover:bg-blue-50" 
                                                    data-task-id="{{ $task->id }}" 
                                                    data-expanded="true">
                                                <svg class="w-5 h-5 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                            @endif
                                            <div class="flex-1">
                                                <h3 class="font-semibold text-gray-800 {{ $task->completed ? 'line-through text-gray-400' : '' }} task-title cursor-pointer hover:text-blue-600 transition-colors duration-200" data-task-id="{{ $task->id }}" onclick="openTaskModal({{ $task->id }})">
                                                    {{ $task->title }}
                                                </h3>
                                                <div class="flex items-center gap-3 text-sm text-gray-500 mt-2">
                                                    <div class="flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                        <span>{{ $task->start_date->format('M d') }} - {{ $task->end_date->format('M d') }}</span>
                                                    </div>
                                                    <span class="text-xs text-gray-300">•</span>
                                                    <div class="flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        <span>{{ $task->durationDays }} {{ $task->durationDays > 1 ? 'hari' : 'hari' }}</span>
                                                    </div>
                                                    @if($subtaskTotal > 0)
                                                    <span class="text-xs text-gray-300">•</span>
                                                    <div class="flex items-center gap-1">
                                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                                        </svg>
                                                        <span class="text-blue-600 font-medium task-progress-percentage">{{ $progressPercentage }}%</span>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex gap-1">
                                            <button onclick="openTaskModal({{ $task->id }})"
                                                class="text-gray-400 hover:text-blue-600 p-2 rounded-lg hover:bg-blue-50 transition-all duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                            <a href="{{ route('tasks.edit', $task->id) }}"
                                                class="text-gray-400 hover:text-blue-600 p-2 rounded-lg hover:bg-blue-50 transition-all duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-gray-400 hover:text-red-600 p-2 rounded-lg hover:bg-red-50 transition-all duration-200"
                                                    onclick="return confirm('Hapus tugas ini?')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    @if($task->subTasks->count() > 0)
                                    <div class="mt-4 ml-8 pl-4 border-l-2 border-blue-100 task-subtasks-container" id="subtasks-container-{{ $task->id }}">
                                        <div class="flex justify-between items-center mb-3">
                                            <div class="text-xs text-gray-500 subtask-progress-text font-medium">
                                                Subtugas ({{ $subtaskCompleted }}/{{ $subtaskTotal }})
                                            </div>
                                            <div class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600 subtask-progress-bar transition-all duration-500" style="width: {{ $progressPercentage }}%"></div>
                                            </div>
                                        </div>
                                        <div class="space-y-1 vertical-tree" id="task-tree-{{ $task->id }}">
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

            <!-- Right sidebar with enhanced design -->
            <div class="space-y-6">
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-6">Ringkasan</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200">
                            <div class="text-blue-600 text-sm mb-1 font-medium">Total Tugas</div>
                            <div class="text-2xl font-bold text-gray-800" id="total-tasks-count">{{ $totalTasks }}</div>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-xl border border-green-200">
                            <div class="text-green-600 text-sm mb-1 font-medium">Selesai</div>
                            <div class="text-2xl font-bold text-gray-800" id="completed-tasks-count">{{ $tasks->where('completed', true)->count() }}</div>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-xl border border-purple-200">
                            <div class="text-purple-600 text-sm mb-1 font-medium">Progress</div>
                            <div class="text-2xl font-bold text-gray-800" id="overall-progress-percentage">
                                {{ $totalTasks > 0 ? round(($tasks->where('completed', true)->count() / $totalTasks) * 100) : 0 }}%
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-4 rounded-xl border border-yellow-200">
                            <div class="text-yellow-600 text-sm mb-1 font-medium">Terlambat</div>
                            <div class="text-2xl font-bold text-gray-800">{{ $tasks->where('end_date', '<', now())->where('completed', false)->count() }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-6">Prioritas</h2>
                    <div class="space-y-4">
                        @foreach(['urgent' => 'Sangat mendesak', 'high' => 'Tinggi', 'medium' => 'Sedang', 'low' => 'Rendah'] as $key => $label)
                        <div>
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span class="font-medium">{{ $label }}</span>
                                <span>{{ $priorityCounts[$key] ?? 0 }} Tugas</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r @if($key == 'urgent') from-red-500 to-red-600 @elseif($key == 'high') from-orange-500 to-orange-600 @elseif($key == 'medium') from-yellow-500 to-yellow-600 @else from-green-500 to-green-600 @endif h-2 rounded-full transition-all duration-500"
                                    style="width: {{ $totalTasks > 0 ? (($priorityCounts[$key] ?? 0) / $totalTasks) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-lg font-semibold text-gray-800">Kategori</h2>
                        <button class="text-blue-600 text-sm font-medium hover:text-blue-700 transition-colors duration-200">Lihat semua</button>
                    </div>
                    <div class="space-y-3">
                        @foreach($categories as $category)
                        <div class="flex items-center justify-between p-3 hover:bg-blue-50 rounded-xl transition-all duration-200 cursor-pointer">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-700">{{ $category->name }}</span>
                            </div>
                            <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded-lg">{{ $category->tasks->count() }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Interactive Modal - Centered and Modern -->
<div id="taskModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex justify-center items-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden">
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h3 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                Detail Tugas
            </h3>
            <button onclick="closeTaskModal()" class="text-gray-500 hover:text-gray-700 p-2 rounded-xl hover:bg-white/50 transition-all duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Modal Content -->
        <div class="overflow-y-auto max-h-[calc(90vh-140px)]">
            <div id="taskModalContent" class="p-6 space-y-6 text-sm text-gray-700">
                <!-- Content will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Loading Indicator - Centered -->
<div id="loading-indicator" class="fixed inset-0 bg-black/20 backdrop-blur-sm hidden flex justify-center items-center z-40">
    <div class="bg-white rounded-2xl shadow-2xl px-8 py-6 flex items-center gap-4">
        <svg class="animate-spin h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="text-gray-700 font-medium">Memperbarui...</span>
    </div>
</div>

<!-- Success/Error Notification Container -->
<div id="notification-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

@endsection

@push('scripts')
<!-- FullCalendar CSS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js'></script>

<script>
    // Store tasks data for modal access
    let tasksData = @json($tasks);
    let calendar;

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize calendar
        var calendarEl = document.getElementById('calendar');
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            headerToolbar: false,
            height: 600,
            events: [
                @foreach($tasks as $task)
                {
                    id: '{{ $task->id }}',
                    title: '{{ $task->title }}',
                    start: '{{ $task->start_date->format('Y-m-d') }}',
                    end: '{{ $task->end_date->addDay()->format('Y-m-d') }}',
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
                        className: 'opacity-60'
                    @endif
                },
                @endforeach
            ],
            eventClick: function(info) {
                info.jsEvent.preventDefault();
                openTaskModal(info.event.id);
            },
            eventDidMount: function(info) {
                // Add hover effects
                info.el.style.cursor = 'pointer';
                info.el.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.02)';
                    this.style.transition = 'transform 0.2s ease';
                });
                info.el.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
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
        
        // Initialize event listeners
        initializeEventListeners();
    });

    // Initialize all event listeners
    function initializeEventListeners() {
        // Task checkbox handling
        document.querySelectorAll('.task-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                handleTaskToggle(this);
            });
        });
        
        // Subtask checkbox handling
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
            title.addEventListener('click', function(e) {
                e.stopPropagation();
                const taskId = this.getAttribute('data-task-id');
                openTaskModal(taskId);
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

        // Close modal when clicking outside
        document.getElementById('taskModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeTaskModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeTaskModal();
            }
        });
    }

    // Enhanced function to open task modal
    function openTaskModal(taskId) {
        const task = tasksData.find(t => t.id == taskId);
        if (!task) return;

        const modalContent = document.getElementById('taskModalContent');
        
        let priorityText = '';
        let priorityClass = '';
        switch(task.priority) {
            case 'urgent':
                priorityText = 'Sangat Mendesak';
                priorityClass = 'bg-gradient-to-r from-red-100 to-red-200 text-red-800 border border-red-300';
                break;
            case 'high':
                priorityText = 'Tinggi';
                priorityClass = 'bg-gradient-to-r from-orange-100 to-orange-200 text-orange-800 border border-orange-300';
                break;
            case 'medium':
                priorityText = 'Sedang';
                priorityClass = 'bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800 border border-yellow-300';
                break;
            case 'low':
                priorityText = 'Rendah';
                priorityClass = 'bg-gradient-to-r from-green-100 to-green-200 text-green-800 border border-green-300';
                break;
        }

        // Calculate progress
        const leafSubTasks = task.sub_tasks ? task.sub_tasks.filter(st => 
            !task.sub_tasks.some(parent => parent.parent_id === st.id)
        ) : [];
        const subtaskCompleted = leafSubTasks.filter(st => st.completed).length;
        const subtaskTotal = leafSubTasks.length;
        const progressPercentage = subtaskTotal > 0 ? Math.round((subtaskCompleted / subtaskTotal) * 100) : (task.completed ? 100 : 0);

        // Generate subtasks HTML
        let subtasksHtml = '';
        if (task.sub_tasks && task.sub_tasks.length > 0) {
            subtasksHtml = `
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 border border-blue-200">
                    <div class="flex justify-between items-center mb-4">
                        <h5 class="font-semibold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 8l2 2 4-4"></path>
                            </svg>
                            Subtugas (${subtaskCompleted}/${subtaskTotal})
                        </h5>
                        <div class="flex items-center gap-3">
                            <div class="w-32 h-3 bg-white rounded-full overflow-hidden shadow-inner">
                                <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600 transition-all duration-500" style="width: ${progressPercentage}%"></div>
                            </div>
                            <span class="text-sm font-semibold text-blue-600">${progressPercentage}%</span>
                        </div>
                    </div>
                    <div class="space-y-2 max-h-60 overflow-y-auto">
                        ${renderModalSubtasks(task.sub_tasks, null, task)}
                    </div>
                </div>
            `;
        }

        modalContent.innerHTML = `
            <div class="flex items-start gap-4 mb-6">
                <form action="/tasks/${task.id}/toggle" method="POST" class="task-toggle-form-modal">
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                    <input type="hidden" name="_method" value="PATCH">
                    <input type="checkbox"
                        class="task-checkbox-modal w-6 h-6 text-blue-600 rounded-lg focus:ring-blue-500 focus:ring-2"
                        data-task-id="${task.id}"
                        ${task.completed ? 'checked' : ''}>
                </form>
                <div class="flex-1">
                    <h4 class="font-bold text-xl mb-3 ${task.completed ? 'line-through text-gray-400' : 'text-gray-800'}" id="modal-task-title-${task.id}">
                        ${task.title}
                    </h4>
                    <div class="flex items-center gap-3 mb-4 flex-wrap">
                        <span class="px-3 py-1 text-sm rounded-xl font-medium ${priorityClass}">
                            ${priorityText}
                        </span>
                        ${task.completed ? '<span class="px-3 py-1 text-sm bg-gradient-to-r from-green-100 to-green-200 text-green-800 rounded-xl font-medium border border-green-300">Selesai</span>' : ''}
                        ${subtaskTotal > 0 ? `<span class="px-3 py-1 text-sm bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 rounded-xl font-medium border border-blue-300">${progressPercentage}% Progress</span>` : ''}
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-600">Tanggal Mulai</span>
                    </div>
                    <span class="font-semibold text-gray-800">${formatDateString(task.start_date)}</span>
                </div>
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-600">Tanggal Selesai</span>
                    </div>
                    <span class="font-semibold text-gray-800">${formatDateString(task.end_date)}</span>
                </div>
            </div>
            
            <div class="mb-6">
                <h5 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Deskripsi
                </h5>
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200">
                    <p class="text-gray-700 leading-relaxed">${task.description || 'Tidak ada deskripsi tersedia'}</p>
                </div>
            </div>

            ${subtasksHtml}
            
            <div class="flex gap-3 pt-6 border-t border-gray-200">
                <a href="/tasks/${task.id}/edit" class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-center py-3 px-6 rounded-xl font-medium transition-all duration-300 transform hover:scale-105 shadow-lg">
                    Edit Tugas
                </a>
                <button onclick="closeTaskModal()" class="flex-1 bg-gradient-to-r from-gray-200 to-gray-300 hover:from-gray-300 hover:to-gray-400 text-gray-800 py-3 px-6 rounded-xl font-medium transition-all duration-300 transform hover:scale-105">
                    Tutup
                </button>
            </div>
        `;

        // Add event listeners for modal checkboxes
        const modalTaskCheckbox = modalContent.querySelector('.task-checkbox-modal');
        if (modalTaskCheckbox) {
            modalTaskCheckbox.addEventListener('change', function() {
                handleModalTaskToggle(this);
            });
        }

        const modalSubtaskCheckboxes = modalContent.querySelectorAll('.subtask-checkbox-modal');
        modalSubtaskCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                handleModalSubtaskToggle(this);
            });
        });
        
        // Show modal with animation
        const modal = document.getElementById('taskModal');
        modal.classList.remove('hidden');
        modal.style.opacity = '0';
        modal.style.transform = 'scale(0.95)';
        
        setTimeout(() => {
            modal.style.opacity = '1';
            modal.style.transform = 'scale(1)';
            modal.style.transition = 'all 0.3s ease-out';
        }, 10);
    }

    // Enhanced function to render subtasks in modal
    function renderModalSubtasks(subtasks, parentId = null, task) {
        let html = '';
        
        const filteredSubtasks = subtasks.filter(st => st.parent_id === parentId);
        
        filteredSubtasks.forEach(subTask => {
            const isParent = subtasks.some(st => st.parent_id === subTask.id);
            
            if (isParent) {
                // Parent subtask
                html += `
                    <div class="subtask-parent-modal bg-white rounded-lg p-3 border border-gray-200" data-subtask-id="${subTask.id}">
                        <div class="flex items-center gap-3 py-1">
                            <button class="subtask-parent-toggle-btn-modal text-gray-400 hover:text-blue-600 transition-all duration-200 p-1 rounded-lg hover:bg-blue-50" 
                                    data-subtask-id="${subTask.id}" 
                                    data-expanded="true">
                                <svg class="w-4 h-4 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <span class="text-sm font-semibold text-gray-700">${subTask.title}</span>
                        </div>
                        <div class="subtask-children-modal pl-8 mt-2 border-l-2 border-blue-100" id="modal-subtask-children-${subTask.id}">
                            ${renderModalSubtasks(subtasks, subTask.id, task)}
                        </div>
                    </div>
                `;
            } else {
                // Leaf subtask with checkbox
                const lineClass = subTask.completed ? 'line-through text-gray-400' : 'text-gray-700';
                html += `
                    <div class="subtask-item-modal flex items-center gap-3 py-2 px-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 transition-all duration-200" data-subtask-id="${subTask.id}">
                        <form action="/subtasks/${subTask.id}/toggle" method="POST" class="subtask-toggle-form-modal">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                            <input type="hidden" name="_method" value="PATCH">
                            <input type="checkbox"
                                class="subtask-checkbox-modal w-4 h-4 text-blue-600 rounded focus:ring-blue-500 focus:ring-2"
                                data-sub-task-id="${subTask.id}"
                                data-task-id="${task.id}"
                                ${subTask.completed ? 'checked' : ''}>
                        </form>
                        <span class="text-sm ${lineClass} subtask-text-modal flex-1">${subTask.title}</span>
                    </div>
                `;
            }
        });

        return html;
    }

    // Enhanced task toggle handler
    function handleTaskToggle(checkbox) {
        const taskId = checkbox.getAttribute('data-task-id');
        const form = checkbox.closest('form');
        const url = form.getAttribute('action');
        const isCompleted = checkbox.checked;
        
        showLoadingIndicator();
        
        const token = form.querySelector('input[name="_token"]').value;
        
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
                updateTaskUI(taskId, data);
                updateSummaryUI(data);
                updateCalendarEvent(taskId, data.task.completed);
                updateAllSubtasksUI(taskId, isCompleted);
                
                // Update modal if open
                updateModalTaskUI(taskId, data);
                updateModalSubtasksUI(taskId, isCompleted);
                
                updateTasksData(taskId, data);
                
                showNotification('Tugas dan semua subtugas berhasil diperbarui!', 'success');
            } else {
                checkbox.checked = !isCompleted;
                showNotification('Gagal memperbarui tugas!', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            checkbox.checked = !isCompleted;
            showNotification('Terjadi kesalahan!', 'error');
        })
        .finally(() => {
            hideLoadingIndicator();
        });
    }

    // Enhanced subtask toggle handler
    function handleSubtaskToggle(checkbox) {
        const subtaskId = checkbox.getAttribute('data-sub-task-id');
        const taskId = checkbox.getAttribute('data-task-id');
        const form = checkbox.closest('form');
        const url = form.getAttribute('action');
        const isCompleted = checkbox.checked;
        
        showLoadingIndicator();
        
        const token = form.querySelector('input[name="_token"]').value;
        
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
                updateSubtaskUI(subtaskId, data);
                updateTaskProgressUI(taskId, data);
                updateMainTaskUI(taskId, data);
                updateSummaryUI(data);
                
                // Update modal if open
                updateModalSubtaskUI(subtaskId, data);
                updateModalTaskUI(taskId, data);
                
                updateTasksData(taskId, data);
                
                showNotification('Subtugas berhasil diperbarui!', 'success');
            } else {
                checkbox.checked = !isCompleted;
                showNotification('Gagal memperbarui subtugas!', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            checkbox.checked = !isCompleted;
            showNotification('Terjadi kesalahan!', 'error');
        })
        .finally(() => {
            hideLoadingIndicator();
        });
    }

    // Modal task toggle handler
    function handleModalTaskToggle(checkbox) {
        const taskId = checkbox.getAttribute('data-task-id');
        const form = checkbox.closest('form');
        const url = form.getAttribute('action');
        const isCompleted = checkbox.checked;
        
        showLoadingIndicator();
        
        const token = form.querySelector('input[name="_token"]').value;
        
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
                updateTaskUI(taskId, data);
                updateSummaryUI(data);
                updateAllSubtasksUI(taskId, isCompleted);
                
                updateModalTaskUI(taskId, data);
                updateModalSubtasksUI(taskId, isCompleted);
                
                updateTasksData(taskId, data);
                
                showNotification('Tugas dan semua subtugas berhasil diperbarui!', 'success');
            } else {
                checkbox.checked = !isCompleted;
                showNotification('Gagal memperbarui tugas!', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            checkbox.checked = !isCompleted;
            showNotification('Terjadi kesalahan!', 'error');
        })
        .finally(() => {
            hideLoadingIndicator();
        });
    }

    // Modal subtask toggle handler
    function handleModalSubtaskToggle(checkbox) {
        const subtaskId = checkbox.getAttribute('data-sub-task-id');
        const taskId = checkbox.getAttribute('data-task-id');
        const form = checkbox.closest('form');
        const url = form.getAttribute('action');
        const isCompleted = checkbox.checked;
        
        showLoadingIndicator();
        
        const token = form.querySelector('input[name="_token"]').value;
        
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
                updateSubtaskUI(subtaskId, data);
                updateTaskProgressUI(taskId, data);
                updateMainTaskUI(taskId, data);
                updateSummaryUI(data);
                
                updateModalSubtaskUI(subtaskId, data);
                updateModalTaskUI(taskId, data);
                
                updateTasksData(taskId, data);
                
                showNotification('Subtugas berhasil diperbarui!', 'success');
            } else {
                checkbox.checked = !isCompleted;
                showNotification('Gagal memperbarui subtugas!', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            checkbox.checked = !isCompleted;
            showNotification('Terjadi kesalahan!', 'error');
        })
        .finally(() => {
            hideLoadingIndicator();
        });
    }

    // Enhanced toggle functions with smooth animations
    function toggleSubtasks(button) {
        const taskId = button.getAttribute('data-task-id');
        const subtasksContainer = document.getElementById(`subtasks-container-${taskId}`);
        const icon = button.querySelector('svg');
        const isExpanded = button.getAttribute('data-expanded') === 'true';

        if (isExpanded) {
            // Collapse
            subtasksContainer.style.maxHeight = subtasksContainer.scrollHeight + 'px';
            subtasksContainer.offsetHeight;
            subtasksContainer.style.maxHeight = '0';
            subtasksContainer.style.opacity = '0';
            subtasksContainer.style.transform = 'translateY(-10px)';
            
            icon.style.transform = 'rotate(-90deg)';
            button.setAttribute('data-expanded', 'false');
            
            setTimeout(() => {
                if (button.getAttribute('data-expanded') === 'false') {
                    subtasksContainer.style.display = 'none';
                }
            }, 300);
        } else {
            // Expand
            subtasksContainer.style.display = 'block';
            subtasksContainer.style.maxHeight = '0';
            subtasksContainer.style.opacity = '0';
            subtasksContainer.style.transform = 'translateY(-10px)';
            
            subtasksContainer.offsetHeight;
            
            subtasksContainer.style.maxHeight = subtasksContainer.scrollHeight + 'px';
            subtasksContainer.style.opacity = '1';
            subtasksContainer.style.transform = 'translateY(0)';
            
            icon.style.transform = 'rotate(0deg)';
            button.setAttribute('data-expanded', 'true');
            
            setTimeout(() => {
                if (button.getAttribute('data-expanded') === 'true') {
                    subtasksContainer.style.maxHeight = '';
                }
            }, 300);
        }
    }

    function toggleSubtaskParent(button) {
        const subtaskId = button.getAttribute('data-subtask-id');
        const childrenContainer = document.getElementById(`subtask-children-${subtaskId}`);
        const icon = button.querySelector('svg');
        const isExpanded = button.getAttribute('data-expanded') === 'true';

        if (isExpanded) {
            // Collapse
            childrenContainer.style.maxHeight = childrenContainer.scrollHeight + 'px';
            childrenContainer.offsetHeight;
            childrenContainer.style.maxHeight = '0';
            childrenContainer.style.opacity = '0';
            childrenContainer.style.transform = 'translateY(-10px)';
            
            icon.style.transform = 'rotate(-90deg)';
            button.setAttribute('data-expanded', 'false');
            
            setTimeout(() => {
                if (button.getAttribute('data-expanded') === 'false') {
                    childrenContainer.style.display = 'none';
                }
            }, 300);
        } else {
            // Expand
            childrenContainer.style.display = 'block';
            childrenContainer.style.maxHeight = '0';
            childrenContainer.style.opacity = '0';
            childrenContainer.style.transform = 'translateY(-10px)';
            
            childrenContainer.offsetHeight;
            
            childrenContainer.style.maxHeight = childrenContainer.scrollHeight + 'px';
            childrenContainer.style.opacity = '1';
            childrenContainer.style.transform = 'translateY(0)';
            
            icon.style.transform = 'rotate(0deg)';
            button.setAttribute('data-expanded', 'true');
            
            setTimeout(() => {
                if (button.getAttribute('data-expanded') === 'true') {
                    childrenContainer.style.maxHeight = '';
                }
            }, 300);
        }
    }

    // UI Update functions
    function updateTaskUI(taskId, data) {
        const taskItem = document.getElementById(`task-item-${taskId}`);
        const taskTitle = taskItem.querySelector('.task-title');
        const taskCheckbox = taskItem.querySelector('.task-checkbox');
        
        taskCheckbox.checked = data.task.completed;
        
        if (data.task.completed) {
            taskTitle.classList.add('line-through', 'text-gray-400');
            taskItem.classList.add('bg-gray-50/50');
            taskItem.classList.remove('bg-white');
        } else {
            taskTitle.classList.remove('line-through', 'text-gray-400');
            taskItem.classList.remove('bg-gray-50/50');
            taskItem.classList.add('bg-white');
        }
        
        if (data.progressPercentage !== undefined) {
            updateTaskProgressUI(taskId, data);
        }
    }

    function updateSubtaskUI(subtaskId, data) {
        const subtaskCheckbox = document.querySelector(`[data-sub-task-id="${subtaskId}"]`);
        const subtaskText = subtaskCheckbox.parentElement.nextElementSibling;
        
        subtaskCheckbox.checked = data.subtask.completed;
        
        if (data.subtask.completed) {
            subtaskText.classList.add('line-through', 'text-gray-400');
            subtaskText.classList.remove('text-gray-700');
        } else {
            subtaskText.classList.remove('line-through', 'text-gray-400');
            subtaskText.classList.add('text-gray-700');
        }
    }

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

    function updateMainTaskUI(taskId, data) {
        const taskItem = document.getElementById(`task-item-${taskId}`);
        const taskTitle = taskItem.querySelector('.task-title');
        const taskCheckbox = taskItem.querySelector('.task-checkbox');
        
        taskCheckbox.checked = data.task.completed;
        
        if (data.task.completed) {
            taskTitle.classList.add('line-through', 'text-gray-400');
            taskItem.classList.add('bg-gray-50/50');
            taskItem.classList.remove('bg-white');
        } else {
            taskTitle.classList.remove('line-through', 'text-gray-400');
            taskItem.classList.remove('bg-gray-50/50');
            taskItem.classList.add('bg-white');
        }
    }

    function updateAllSubtasksUI(taskId, isCompleted) {
        const taskContainer = document.getElementById(`task-item-${taskId}`);
        const subtaskCheckboxes = taskContainer.querySelectorAll('.subtask-checkbox');
        
        subtaskCheckboxes.forEach(subtaskCheckbox => {
            subtaskCheckbox.checked = isCompleted;
            
            const subtaskText = subtaskCheckbox.parentElement.nextElementSibling;
            if (isCompleted) {
                subtaskText.classList.add('line-through', 'text-gray-400');
                subtaskText.classList.remove('text-gray-700');
            } else {
                subtaskText.classList.remove('line-through', 'text-gray-400');
                subtaskText.classList.add('text-gray-700');
            }
        });
    }

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

    // Modal UI update functions
    function updateModalTaskUI(taskId, data) {
        const modalTaskTitle = document.getElementById(`modal-task-title-${taskId}`);
        const modalTaskCheckbox = document.querySelector(`.task-checkbox-modal[data-task-id="${taskId}"]`);
        
        if (modalTaskCheckbox) {
            modalTaskCheckbox.checked = data.task.completed;
        }
        
        if (modalTaskTitle) {
            if (data.task.completed) {
                modalTaskTitle.classList.add('line-through', 'text-gray-400');
            } else {
                modalTaskTitle.classList.remove('line-through', 'text-gray-400');
            }
        }
    }

    function updateModalSubtasksUI(taskId, isCompleted) {
        const modalSubtaskCheckboxes = document.querySelectorAll(`.subtask-checkbox-modal[data-task-id="${taskId}"]`);
        
        modalSubtaskCheckboxes.forEach(subtaskCheckbox => {
            subtaskCheckbox.checked = isCompleted;
            
            const subtaskText = subtaskCheckbox.parentElement.nextElementSibling;
            if (isCompleted) {
                subtaskText.classList.add('line-through', 'text-gray-400');
                subtaskText.classList.remove('text-gray-700');
            } else {
                subtaskText.classList.remove('line-through', 'text-gray-400');
                subtaskText.classList.add('text-gray-700');
            }
        });
    }

    function updateModalSubtaskUI(subtaskId, data) {
        const modalSubtaskCheckbox = document.querySelector(`.subtask-checkbox-modal[data-sub-task-id="${subtaskId}"]`);
        const modalSubtaskText = modalSubtaskCheckbox ? modalSubtaskCheckbox.parentElement.nextElementSibling : null;
        
        if (modalSubtaskCheckbox) {
            modalSubtaskCheckbox.checked = data.subtask.completed;
        }
        
        if (modalSubtaskText) {
            if (data.subtask.completed) {
                modalSubtaskText.classList.add('line-through', 'text-gray-400');
                modalSubtaskText.classList.remove('text-gray-700');
            } else {
                modalSubtaskText.classList.remove('line-through', 'text-gray-400');
                modalSubtaskText.classList.add('text-gray-700');
            }
        }
    }

    function updateTasksData(taskId, data) {
        const taskIndex = tasksData.findIndex(t => t.id == taskId);
        if (taskIndex !== -1) {
            tasksData[taskIndex].completed = data.task.completed;
            
            if (data.subtask && tasksData[taskIndex].sub_tasks) {
                const subtaskIndex = tasksData[taskIndex].sub_tasks.findIndex(st => st.id == data.subtask.id);
                if (subtaskIndex !== -1) {
                    tasksData[taskIndex].sub_tasks[subtaskIndex].completed = data.subtask.completed;
                }
            }
        }
    }

    function updateCalendarEvent(taskId, completed) {
        if (calendar) {
            const event = calendar.getEventById(taskId);
            if (event) {
                if (completed) {
                    event.setProp('className', 'opacity-60');
                } else {
                    event.setProp('className', '');
                }
            }
        }
    }

    // Utility functions
    function showLoadingIndicator() {
        document.getElementById('loading-indicator').classList.remove('hidden');
    }
    
    function hideLoadingIndicator() {
        document.getElementById('loading-indicator').classList.add('hidden');
    }
    
    function showNotification(message, type = 'success') {
        const container = document.getElementById('notification-container');
        const notification = document.createElement('div');
        
        const bgColor = type === 'success' ? 'from-green-500 to-green-600' : 'from-red-500 to-red-600';
        const icon = type === 'success' ? 
            '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>' :
            '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
        
        notification.className = `bg-gradient-to-r ${bgColor} text-white px-6 py-4 rounded-xl shadow-2xl transform transition-all duration-300 flex items-center gap-3 max-w-sm`;
        notification.innerHTML = `
            ${icon}
            <span class="font-medium">${message}</span>
        `;
        
        // Add animation
        notification.style.transform = 'translateX(100%)';
        notification.style.opacity = '0';
        
        container.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
            notification.style.opacity = '1';
        }, 10);
        
        // Remove after 4 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 4000);
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
            end.setDate(end.getDate() - 1);
            
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
            case 'urgent': return '#ef4444';
            case 'high': return '#f97316';
            case 'medium': return '#eab308';
            case 'low': return '#22c55e';
            default: return '#3b82f6';
        }
    }
    
    function formatDateString(dateString) {
        return new Date(dateString).toLocaleDateString('id-ID', { 
            day: 'numeric', 
            month: 'long', 
            year: 'numeric' 
        });
    }
    
    function closeTaskModal() {
        const modal = document.getElementById('taskModal');
        modal.style.opacity = '0';
        modal.style.transform = 'scale(0.95)';
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.style.opacity = '';
            modal.style.transform = '';
            modal.style.transition = '';
        }, 300);
    }
</script>

<style>
    /* Enhanced CSS with modern animations and effects */
    .vertical-tree {
        display: flex;
        flex-direction: column;
    }
    
    .subtask-parent {
        margin-bottom: 0.75rem;
    }
    
    .subtask-children {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        padding-left: 2rem;
    }
    
    .subtask-item {
        margin-bottom: 0.5rem;
        transition: all 0.2s ease-in-out;
    }
    
    .subtask-item:hover {
        transform: translateX(4px);
    }
    
    .subtask-toggle-btn svg,
    .subtask-parent-toggle-btn svg {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .task-subtasks-container {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }
    
    .task-title,
    .subtask-parent-title {
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }

    /* Modal specific styles */
    .subtask-parent-modal {
        margin-bottom: 0.75rem;
    }
    
    .subtask-children-modal {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        padding-left: 2rem;
    }
    
    .subtask-item-modal {
        margin-bottom: 0.5rem;
        transition: all 0.2s ease-in-out;
    }

    .subtask-item-modal:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    /* Enhanced progress bar animation */
    .subtask-progress-bar {
        transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Backdrop blur support */
    .backdrop-blur-sm {
        backdrop-filter: blur(4px);
    }

    /* Custom scrollbar */
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }

    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Enhanced hover effects */
    .hover\:scale-105:hover {
        transform: scale(1.05);
    }

    /* Gradient text support */
    .bg-clip-text {
        -webkit-background-clip: text;
        background-clip: text;
    }

    /* Enhanced shadow effects */
    .shadow-2xl {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    /* Animation keyframes */
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    /* Focus styles */
    .focus\:ring-2:focus {
        outline: 2px solid transparent;
        outline-offset: 2px;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
    }
</style>

@endpush

