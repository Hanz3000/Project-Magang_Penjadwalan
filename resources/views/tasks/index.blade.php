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
                        <h1 class="text-3xl font-bold text-gray-800">Task Manager</h1>
                    </div>
                    <p class="text-gray-500">Organize your work efficiently</p>
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
                                        Month
                                    </button>
                                    <button class="px-3 py-1 text-sm rounded-md transition-all duration-200 fc-timeGridWeek-button text-gray-600 hover:text-gray-800 font-medium" id="week-view">
                                        Week
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
                                My Tasks
                            </h2>
                            <div class="flex gap-2">
                                <button class="px-3 py-1 text-sm bg-gray-100 rounded-lg">All</button>
                                <button class="px-3 py-1 text-sm bg-blue-100 text-blue-600 rounded-lg">Active</button>
                                <button class="px-3 py-1 text-sm bg-gray-100 rounded-lg">Completed</button>
                            </div>
                        </div>

                        <div class="space-y-3" id="task-list-container"> {{-- Tambahkan ID untuk update mudah --}}
                            @foreach($tasks as $task)
                            @php
                            $durationDays = $task->start_date->diffInDays($task->end_date) + 1;
                            $subtaskCompleted = $task->subTasks->where('completed', true)->count();
                            $subtaskTotal = $task->subTasks->count();
                            $progressPercentage = $subtaskTotal > 0 ? round(($subtaskCompleted / $subtaskTotal) * 100) : ($task->completed ? 100 : 0);
                            @endphp
                            <div class="border border-gray-200 rounded-lg p-4 transition-all duration-200 hover:border-blue-200 hover:shadow-xs {{ $task->completed ? 'bg-gray-50' : 'bg-white' }}" id="task-item-{{ $task->id }}"> {{-- Tambahkan ID --}}
                                <div class="flex items-start gap-3">
                                    <input type="checkbox"
                                        class="task-checkbox"
                                        data-task-id="{{ $task->id }}"
                                        {{ $task->completed ? 'checked' : '' }}>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="font-medium text-gray-800 {{ $task->completed ? 'line-through text-gray-400' : '' }} task-title"> {{-- Tambahkan class --}}
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
                                        <div class="mt-3 ml-7 pl-3 border-l-2 border-gray-200 task-subtasks-container"> {{-- Tambahkan class --}}
                                            <div class="flex justify-between items-center mb-2">
                                                <div class="text-xs text-gray-500 subtask-progress-text"> {{-- Tambahkan class --}}
                                                    Subtasks ({{ $subtaskCompleted }}/{{ $subtaskTotal }})
                                                </div>
                                                <div class="w-20 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                                    <div class="h-full bg-blue-500 subtask-progress-bar" style="width: {{ $progressPercentage }}%"></div> {{-- Tambahkan class --}}
                                                </div>
                                            </div>
                                            <ul class="space-y-2">
                                                @foreach($task->subTasks as $subTask)
                                                <li class="flex items-center gap-2 subtask-item" id="subtask-item-{{ $subTask->id }}"> {{-- Tambahkan ID dan class --}}
                                                    <form action="{{ route('subtasks.toggle', $subTask->id) }}" method="POST" class="subtask-toggle-form">
    @csrf
    @method('PATCH')
    <input type="checkbox"
        class="subtask-checkbox"
        data-sub-task-id="{{ $subTask->id }}"
        data-task-id="{{ $task->id }}"
        {{ $subTask->completed ? 'checked' : '' }}>
</form>

                                                    <span class="text-sm {{ $subTask->completed ? 'line-through text-gray-400' : 'text-gray-600' }} subtask-text"> {{-- Tambahkan class --}}
                                                        {{ $subTask->title }}
                                                    </span>
                                                </li>
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
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Overview</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-blue-50 p-3 rounded-lg">
                                <div class="text-blue-600 text-sm mb-1">Total Tasks</div>
                                <div class="text-2xl font-bold text-gray-800">{{ $totalTasks }}</div>
                            </div>
                            <div class="bg-green-50 p-3 rounded-lg">
                                <div class="text-green-600 text-sm mb-1">Completed</div>
                                <div class="text-2xl font-bold text-gray-800">{{ $tasks->where('completed', true)->count() }}</div>
                            </div>
                            <div class="bg-purple-50 p-3 rounded-lg">
                                <div class="text-purple-600 text-sm mb-1">Progress</div>
                                <div class="text-2xl font-bold text-gray-800">
                                    {{ $totalTasks > 0 ? round(($tasks->where('completed', true)->count() / $totalTasks) * 100) : 0 }}%
                                </div>
                            </div>
                            <div class="bg-yellow-50 p-3 rounded-lg">
                                <div class="text-yellow-600 text-sm mb-1">Overdue</div>
                                <div class="text-2xl font-bold text-gray-800">0</div> {{-- You'll need to calculate this dynamically --}}
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Priority</h2>
                        <div class="space-y-3">
                            @foreach(['urgent' => 'High', 'high' => 'Important', 'medium' => 'Medium', 'low' => 'Low'] as $key => $label)
                            <div>
                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                    <span>{{ $label }}</span>
                                    <span>{{ $priorityCounts[$key] ?? 0 }} tasks</span>
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
                            <h2 class="text-lg font-semibold text-gray-800">Categories</h2>
                            <button class="text-blue-600 text-sm font-medium">View All</button>
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

    @push('styles')
    {{-- FullCalendar CSS --}}
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js'></script>

<script>
    // Variabel global untuk menyimpan data task saat ini
    let currentTaskData = null;
    let calendar = null;

   // ✅ Fungsi untuk memperbarui tampilan subtask di daftar utama
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
}

// ✅ Fungsi untuk memperbarui progress bar dan persentase task
function updateTaskProgress(taskId, progressPercentage, subtasksCompleted, subtasksTotal, mainTaskCompleted) {
    const taskItem = document.getElementById(`task-item-${taskId}`);
    if (!taskItem) return;

    const progressText = taskItem.querySelector('.task-progress-percentage');
    if (progressText) {
        progressText.textContent = `${progressPercentage}%`;
    }

    const progressBar = taskItem.querySelector('.subtask-progress-bar');
    if (progressBar) {
        progressBar.style.width = `${progressPercentage}%`;
    }

    const subtaskProgressText = taskItem.querySelector('.subtask-progress-text');
    if (subtaskProgressText) {
        subtaskProgressText.textContent = `Subtasks (${subtasksCompleted}/${subtasksTotal})`;
    }

    const mainCheckbox = taskItem.querySelector('.task-checkbox');
    const taskTitle = taskItem.querySelector('.task-title');

    if (mainCheckbox) {
        mainCheckbox.checked = mainTaskCompleted;
    }

    if (taskTitle) {
        taskTitle.classList.toggle('line-through', mainTaskCompleted);
        taskTitle.classList.toggle('text-gray-400', mainTaskCompleted);
        taskTitle.classList.toggle('text-gray-800', !mainTaskCompleted);
    }

    taskItem.classList.toggle('bg-gray-50', mainTaskCompleted);
    taskItem.classList.toggle('bg-white', !mainTaskCompleted);
}

// ✅ Fungsi untuk memperbarui calendar events
function updateCalendarEvent(taskId, progressPercentage, mainTaskCompleted) {
    if (typeof calendar !== 'undefined') {
        const event = calendar.getEventById(taskId);
        if (event) {
            event.setExtendedProp('progress', progressPercentage);
            event.setExtendedProp('mainTaskCompleted', mainTaskCompleted);

            // Tidak wajib refetch kalau pakai extendedProp, tapi kalau style warna diubah boleh pakai ini:
            calendar.refetchEvents();
        }
    }
}


    function openTaskModal(taskId, content) {
        document.getElementById('taskModalContent').innerHTML = content;
        document.getElementById('taskModal').classList.remove('hidden');
        document.getElementById('taskModal').classList.add('flex');
        document.body.style.overflow = 'hidden';

        // Setup event handlers untuk subtask di modal
        setupSubtaskHandlers();
    }

    function closeTaskModal() {
        document.getElementById('taskModal').classList.add('hidden');
        document.getElementById('taskModal').classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    function setupSubtaskHandlers() {
        document.querySelectorAll('.subtask-checkbox-modal').forEach(checkbox => {
            // Hapus event listener lama untuk mencegah duplikasi
            checkbox.removeEventListener('change', handleSubtaskChange);
            // Tambahkan event listener baru
            checkbox.addEventListener('change', handleSubtaskChange);
        });
    }

    // Fungsi utama untuk handle perubahan subtask di modal
    function handleSubtaskChange(e) {
        const subtaskId = this.dataset.subTaskId;
        const taskId = this.dataset.taskId;
        const isCompleted = this.checked;
        const subtaskItem = this.closest('.subtask-item-modal');
        const subtaskText = subtaskItem.querySelector('.subtask-text-modal');

        // Simpan state original untuk rollback jika gagal
        const originalCheckedState = !isCompleted;
        const originalTextContent = subtaskText.textContent;

        // Update UI modal secara optimistic (langsung update sebelum response server)
        if (isCompleted) {
            subtaskText.classList.add('line-through', 'text-gray-400');
            subtaskText.classList.remove('text-gray-600');
        } else {
            subtaskText.classList.remove('line-through', 'text-gray-400');
            subtaskText.classList.add('text-gray-600');
        }

        // Kirim request ke server
        fetch(`/subtasks/${subtaskId}/toggle`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                completed: isCompleted
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // ✅ UPDATE BAGIAN 1: Update progress di modal
                const modalProgressBar = document.querySelector('.subtask-progress-bar-modal');
                const modalProgressText = document.querySelector('.subtask-progress-text-modal');
                
                if (modalProgressBar) {
                    modalProgressBar.style.width = `${data.progress}%`;
                }
                if (modalProgressText) {
                    modalProgressText.textContent = `Subtasks (${data.subtasksCompleted}/${data.subtasksTotal})`;
                }

                // ✅ UPDATE BAGIAN 2: Update subtask di daftar utama (TANPA REFRESH)
                updateMainTaskListSubtask(taskId, subtaskId, data.completed);

                // ✅ UPDATE BAGIAN 3: Update progress di daftar utama (TANPA REFRESH)
                updateTaskProgress(taskId, data.progress, data.subtasksCompleted, data.subtasksTotal, data.mainTaskCompleted);

                // ✅ UPDATE BAGIAN 4: Update calendar events (TANPA REFRESH)
                updateCalendarEvent(taskId, data.progress, data.mainTaskCompleted);

                // ✅ UPDATE BAGIAN 5: Update currentTaskData untuk konsistensi
                if (currentTaskData && currentTaskData.subtasks) {
                    const subtaskIndex = currentTaskData.subtasks.findIndex(sub => sub.id == subtaskId);
                    if (subtaskIndex !== -1) {
                        currentTaskData.subtasks[subtaskIndex].completed = data.completed;
                    }
                    currentTaskData.progress = data.progress;
                    currentTaskData.subtasksCompleted = data.subtasksCompleted;
                    currentTaskData.mainTaskCompleted = data.mainTaskCompleted;
                }

                // Show success notification
                showNotification('Subtask berhasil diperbarui', 'success');
                
            } else {
                throw new Error(data.message || 'Terjadi kesalahan pada server');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Rollback UI jika gagal
            this.checked = originalCheckedState;
            subtaskText.textContent = originalTextContent;
            
            if (originalCheckedState) {
                subtaskText.classList.add('line-through', 'text-gray-400');
                subtaskText.classList.remove('text-gray-600');
            } else {
                subtaskText.classList.remove('line-through', 'text-gray-400');
                subtaskText.classList.add('text-gray-600');
            }
            
            showNotification('Gagal memperbarui subtask: ' + error.message, 'error');
        });
    }

    // Fungsi untuk sinkronisasi checkbox di daftar utama (jika diperlukan)
    function syncMainTaskListCheckboxes() {
        document.querySelectorAll('.subtask-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function(e) {
                const subtaskId = this.dataset.subTaskId;
                const taskId = this.dataset.taskId;
                const isCompleted = this.checked;
                
                // Update UI main list
                const subtaskText = this.closest('form').nextElementSibling;
                if (subtaskText) {
                    if (isCompleted) {
                        subtaskText.classList.add('line-through', 'text-gray-400');
                        subtaskText.classList.remove('text-gray-600');
                    } else {
                        subtaskText.classList.remove('line-through', 'text-gray-400');
                        subtaskText.classList.add('text-gray-600');
                    }
                }

                // Send request to server
                fetch(`/subtasks/${subtaskId}/toggle`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        completed: isCompleted
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Update progress in main list
                        updateTaskProgress(taskId, data.progress, data.subtasksCompleted, data.subtasksTotal, data.mainTaskCompleted);
                        
                        // Update calendar
                        updateCalendarEvent(taskId, data.progress, data.mainTaskCompleted);
                        
                        // Jika modal terbuka dan menampilkan task yang sama, update juga modal
                        if (!document.getElementById('taskModal').classList.contains('hidden')) {
                            const modalTaskId = document.getElementById('taskModalContent').dataset.taskId;
                            if (modalTaskId === taskId) {
                                const modalCheckbox = document.querySelector(`[data-sub-task-id="${subtaskId}"].subtask-checkbox-modal`);
                                if (modalCheckbox) {
                                    modalCheckbox.checked = this.checked;
                                    
                                    // Update text styling di modal
                                    const modalText = modalCheckbox.closest('.subtask-item-modal').querySelector('.subtask-text-modal');
                                    if (modalText) {
                                        if (this.checked) {
                                            modalText.classList.add('line-through', 'text-gray-400');
                                            modalText.classList.remove('text-gray-600');
                                        } else {
                                            modalText.classList.remove('line-through', 'text-gray-400');
                                            modalText.classList.add('text-gray-600');
                                        }
                                    }
                                }
                                
                                // Update modal progress
                                const modalProgressBar = document.querySelector('.subtask-progress-bar-modal');
                                const modalProgressText = document.querySelector('.subtask-progress-text-modal');
                                if (modalProgressBar) {
                                    modalProgressBar.style.width = `${data.progress}%`;
                                }
                                if (modalProgressText) {
                                    modalProgressText.textContent = `Subtasks (${data.subtasksCompleted}/${data.subtasksTotal})`;
                                }
                            }
                        }
                        
                        showNotification('Subtask berhasil diperbarui', 'success');
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan pada server');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // Rollback UI
                    this.checked = !isCompleted;
                    if (!isCompleted) {
                        subtaskText.classList.add('line-through', 'text-gray-400');
                        subtaskText.classList.remove('text-gray-600');
                    } else {
                        subtaskText.classList.remove('line-through', 'text-gray-400');
                        subtaskText.classList.add('text-gray-600');
                    }
                    
                    showNotification('Gagal memperbarui subtask: ' + error.message, 'error');
                });
            });
        });
    }

    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transition-all duration-300 ${
            type === 'success'
                ? 'bg-green-500 text-white'
                : 'bg-red-500 text-white'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    // Tutup modal ketika mengklik di luar area modal
    document.getElementById('taskModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeTaskModal();
        }
    });

    // Tutup modal dengan tombol Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('taskModal').classList.contains('hidden')) {
            closeTaskModal();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
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
                        color: '@if($task->priority == "urgent") #ef4444 @elseif($task->priority == "high") #f97316 @elseif($task->priority == "medium") #eab308 @else #22c55e @endif',
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
                info.el.classList.add('rounded-md', 'shadow-sm', 'text-xs', 'font-medium', 'p-1', 'cursor-pointer', 'relative');
                info.el.style.backgroundColor = info.event.backgroundColor;
                info.el.style.borderColor = info.event.backgroundColor;
                info.el.style.color = 'white';

                const dot = document.createElement('span');
                dot.className = 'priority-dot';
                dot.style.backgroundColor = info.event.backgroundColor;
                dot.style.width = '6px';
                dot.style.height = '6px';
                dot.style.borderRadius = '50%';
                dot.style.display = 'inline-block';
                dot.style.marginRight = '4px';
                dot.style.verticalAlign = 'middle';
                info.el.prepend(dot);

                if (info.event.extendedProps.hasSubtasks) {
                    const progress = info.event.extendedProps.progress;
                    const progressBar = document.createElement('div');
                    progressBar.className = 'absolute bottom-0 left-0 h-1 rounded-b-md';
                    progressBar.style.backgroundColor = 'rgba(255,255,255,0.7)';
                    progressBar.style.width = progress + '%';
                    info.el.appendChild(progressBar);
                }
            },
            eventClick: function(info) {
                info.jsEvent.preventDefault();

                const event = info.event;
                const taskId = event.id;
                
                // 🔥 PENTING: Simpan data task saat ini ke variabel global
                currentTaskData = {
                    id: taskId,
                    title: event.title,
                    progress: event.extendedProps.progress,
                    subtasks: event.extendedProps.subtasks,
                    subtasksCompleted: event.extendedProps.subtasksCompleted,
                    subtasksTotal: event.extendedProps.subtasksTotal,
                    mainTaskCompleted: event.extendedProps.mainTaskCompleted
                };

                document.getElementById('taskModalContent').dataset.taskId = taskId;
                document.getElementById('taskModalContent').dataset.taskData = JSON.stringify(currentTaskData);

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

                const durationInDays = event.start && event.end ?
                    Math.ceil((event.end.getTime() - event.start.getTime()) / (1000 * 60 * 60 * 24)) : 0;
                const durationText = durationInDays > 0 ? durationInDays + ' hari' : '';

                const priorityLabels = {
                    'urgent': 'Urgent',
                    'high': 'High',
                    'medium': 'Medium',
                    'low': 'Low'
                };
                const priorityLabel = priorityLabels[event.extendedProps.priority] || 'N/A';

                const priorityColors = {
                    'urgent': 'bg-red-100 text-red-800',
                    'high': 'bg-orange-100 text-orange-800',
                    'medium': 'bg-yellow-100 text-yellow-800',
                    'low': 'bg-green-100 text-green-800'
                };
                const priorityClass = priorityColors[event.extendedProps.priority] || 'bg-gray-100 text-gray-800';

                let subtasksHtml = '';
                if (event.extendedProps.hasSubtasks) {
                    subtasksHtml = `
                        <div class="mt-4 border-t border-gray-200 pt-4">
                            <div class="flex justify-between items-center mb-2">
                                <div class="text-xs text-gray-500 subtask-progress-text-modal">
                                    Subtasks (${event.extendedProps.subtasksCompleted}/${event.extendedProps.subtasksTotal})
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

                const modalContent = `
                    <h4 class="text-xl font-bold text-gray-900 mb-2">${event.title}</h4>
                    <p class="text-gray-600 mb-4">${event.extendedProps.description || 'Tidak ada deskripsi.'}</p>
                    <div class="space-y-2">
                        <p><strong>Kategori:</strong> <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">${event.extendedProps.category}</span></p>
                        <p><strong>Prioritas:</strong> <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${priorityClass}">${priorityLabel}</span></p>
                        <p><strong>Durasi:</strong> ${startDate} - ${formattedEndDate} (${durationText})</p>
                        <p><strong>Progress:</strong> <span class="text-blue-600 subtask-percentage">${event.extendedProps.progress}%</span></p>
                    </div>
                    ${subtasksHtml}
                    <div class="mt-6 flex justify-end gap-3">
                        <a href="/tasks/${taskId}/edit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Edit
                        </a>
                        <form action="/tasks/${taskId}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Hapus
                            </button>
                        </form>
                    </div>
                `;
                openTaskModal(taskId, modalContent);
            }
        });

        calendar.render();

        // Kalender Navigasi Kustom
        document.getElementById('prev-month').addEventListener('click', function() {
            calendar.prev();
            updateCalendarTitle();
        });

        document.getElementById('next-month').addEventListener('click', function() {
            calendar.next();
            updateCalendarTitle();
        });

        document.getElementById('month-view').addEventListener('click', function() {
            calendar.changeView('dayGridMonth');
            updateCalendarTitle();
            // Update button styles
            this.classList.add('bg-white', 'text-blue-600', 'shadow-sm');
            document.getElementById('week-view').classList.remove('bg-white', 'text-blue-600', 'shadow-sm');
            document.getElementById('week-view').classList.add('text-gray-600', 'hover:text-gray-800');
        });

        document.getElementById('week-view').addEventListener('click', function() {
            calendar.changeView('timeGridWeek');
            updateCalendarTitle();
            // Update button styles
            this.classList.add('bg-white', 'text-blue-600', 'shadow-sm');
            document.getElementById('month-view').classList.remove('bg-white', 'text-blue-600', 'shadow-sm');
            document.getElementById('month-view').classList.add('text-gray-600', 'hover:text-gray-800');
        });

        function updateCalendarTitle() {
            document.getElementById('calendar-title').textContent = calendar.view.title;
        }

        // Panggil saat DOMContentLoaded untuk inisialisasi judul kalender
        updateCalendarTitle();

        // Handle main task toggling (outside modal)
        document.querySelectorAll('.task-toggle-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                const taskId = this.querySelector('.task-checkbox').dataset.taskId;
                const isCompleted = this.querySelector('.task-checkbox').checked;

                fetch(this.action, {
                        method: 'POST', // or 'PATCH' as defined in your route
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            completed: isCompleted
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update UI for the main task
                            const taskItem = document.getElementById(`task-item-${taskId}`);
                            const taskTitle = taskItem.querySelector('.task-title');
                            if (data.completed) {
                                taskTitle.classList.add('line-through', 'text-gray-400');
                                taskItem.classList.add('bg-gray-50');
                                taskItem.classList.remove('bg-white');
                            } else {
                                taskTitle.classList.remove('line-through', 'text-gray-400');
                                taskItem.classList.remove('bg-gray-50');
                                taskItem.classList.add('bg-white');
                            }

                            // Also update subtasks' UI if the main task is completed/uncompleted
                            taskItem.querySelectorAll('.subtask-checkbox').forEach(subCheckbox => {
                                subCheckbox.checked = data.completed;
                                const subText = subCheckbox.closest('form').nextElementSibling;
                                if (data.completed) {
                                    subText.classList.add('line-through', 'text-gray-400');
                                    subText.classList.remove('text-gray-600');
                                } else {
                                    subText.classList.remove('line-through', 'text-gray-400');
                                    subText.classList.add('text-gray-600');
                                }
                            });

                            // Update progress bar and text for subtasks
                            const mainSubtaskProgressBar = taskItem.querySelector('.subtask-progress-bar');
                            const mainSubtaskProgressText = taskItem.querySelector('.subtask-progress-text');
                            const taskProgressPercentage = taskItem.querySelector('.task-progress-percentage');
                            if (mainSubtaskProgressBar && mainSubtaskProgressText && taskProgressPercentage) {
                                mainSubtaskProgressBar.style.width = `${data.progress}%`;
                                mainSubtaskProgressText.textContent = `Subtasks (${data.subtasksCompleted}/${data.subtasksTotal})`;
                                taskProgressPercentage.textContent = `${data.progress}%`;
                            }

                           showNotification(data.message || 'Terjadi kesalahan', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Rollback checkbox state
                        this.querySelector('.task-checkbox').checked = !isCompleted;
                        showNotification('Gagal memperbarui tugas: ' + error.message, 'error');
                    });
            });
        });

        // Initialize subtask handlers for main task list
        syncMainTaskListCheckboxes();

        // Handle task filtering
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const filter = this.dataset.filter;
                
                // Update active button
                document.querySelectorAll('.filter-btn').forEach(b => {
                    b.classList.remove('bg-blue-100', 'text-blue-800');
                    b.classList.add('text-gray-600');
                });
                this.classList.add('bg-blue-100', 'text-blue-800');
                this.classList.remove('text-gray-600');

                // Filter tasks
                document.querySelectorAll('.task-item').forEach(item => {
                    const taskCompleted = item.querySelector('.task-checkbox').checked;
                    const taskPriority = item.dataset.priority;
                    const taskCategory = item.dataset.category;

                    let shouldShow = true;

                    switch(filter) {
                        case 'completed':
                            shouldShow = taskCompleted;
                            break;
                        case 'pending':
                            shouldShow = !taskCompleted;
                            break;
                        case 'urgent':
                            shouldShow = taskPriority === 'urgent';
                            break;
                        case 'high':
                            shouldShow = taskPriority === 'high';
                            break;
                        case 'medium':
                            shouldShow = taskPriority === 'medium';
                            break;
                        case 'low':
                            shouldShow = taskPriority === 'low';
                            break;
                        default:
                            shouldShow = true;
                    }

                    if (shouldShow) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });

        // Handle search functionality
        const searchInput = document.getElementById('task-search');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                
                document.querySelectorAll('.task-item').forEach(item => {
                    const taskTitle = item.querySelector('.task-title').textContent.toLowerCase();
                    const taskDescription = item.querySelector('.task-description')?.textContent.toLowerCase() || '';
                    
                    if (taskTitle.includes(searchTerm) || taskDescription.includes(searchTerm)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }

        // Handle sorting functionality
        const sortSelect = document.getElementById('task-sort');
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                const sortBy = this.value;
                const taskContainer = document.querySelector('.task-list-container');
                const tasks = Array.from(taskContainer.querySelectorAll('.task-item'));

                tasks.sort((a, b) => {
                    switch(sortBy) {
                        case 'priority':
                            const priorityOrder = { 'urgent': 0, 'high': 1, 'medium': 2, 'low': 3 };
                            return priorityOrder[a.dataset.priority] - priorityOrder[b.dataset.priority];
                        
                        case 'due_date':
                            const dateA = new Date(a.dataset.dueDate);
                            const dateB = new Date(b.dataset.dueDate);
                            return dateA - dateB;
                        
                        case 'created_at':
                            const createdA = new Date(a.dataset.createdAt);
                            const createdB = new Date(b.dataset.createdAt);
                            return createdB - createdA;
                        
                        case 'progress':
                            const progressA = parseInt(a.dataset.progress) || 0;
                            const progressB = parseInt(b.dataset.progress) || 0;
                            return progressB - progressA;
                        
                        default:
                            return 0;
                    }
                });

                // Re-append sorted tasks
                tasks.forEach(task => taskContainer.appendChild(task));
            });
        }

        // Handle bulk actions
        const selectAllCheckbox = document.getElementById('select-all-tasks');
        const bulkActionBtn = document.getElementById('bulk-action-btn');
        const bulkActionSelect = document.getElementById('bulk-action-select');

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const taskCheckboxes = document.querySelectorAll('.task-bulk-checkbox');
                taskCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkActionVisibility();
            });
        }

        // Handle individual task selection for bulk actions
        document.querySelectorAll('.task-bulk-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateBulkActionVisibility();
                
                // Update select all checkbox state
                const allCheckboxes = document.querySelectorAll('.task-bulk-checkbox');
                const checkedCheckboxes = document.querySelectorAll('.task-bulk-checkbox:checked');
                
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = allCheckboxes.length === checkedCheckboxes.length;
                    selectAllCheckbox.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length < allCheckboxes.length;
                }
            });
        });

        function updateBulkActionVisibility() {
            const checkedCheckboxes = document.querySelectorAll('.task-bulk-checkbox:checked');
            if (bulkActionBtn) {
                bulkActionBtn.style.display = checkedCheckboxes.length > 0 ? 'block' : 'none';
            }
        }

        // Handle bulk action execution
        if (bulkActionBtn) {
            bulkActionBtn.addEventListener('click', function() {
                const selectedTasks = Array.from(document.querySelectorAll('.task-bulk-checkbox:checked'))
                    .map(checkbox => checkbox.dataset.taskId);
                const action = bulkActionSelect.value;

                if (selectedTasks.length === 0) {
                    showNotification('Pilih minimal satu tugas', 'error');
                    return;
                }

                let confirmMessage = '';
                switch(action) {
                    case 'delete':
                        confirmMessage = `Apakah Anda yakin ingin menghapus ${selectedTasks.length} tugas?`;
                        break;
                    case 'complete':
                        confirmMessage = `Apakah Anda yakin ingin menandai ${selectedTasks.length} tugas sebagai selesai?`;
                        break;
                    case 'incomplete':
                        confirmMessage = `Apakah Anda yakin ingin menandai ${selectedTasks.length} tugas sebagai belum selesai?`;
                        break;
                    default:
                        showNotification('Pilih aksi yang valid', 'error');
                        return;
                }

                if (confirm(confirmMessage)) {
                    fetch('/tasks/bulk-action', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            action: action,
                            task_ids: selectedTasks
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification(data.message, 'success');
                            // Refresh the page or update UI accordingly
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            showNotification(data.message || 'Terjadi kesalahan', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Gagal melakukan aksi: ' + error.message, 'error');
                    });
                }
            });
        }

        // Handle drag and drop for task reordering
        let draggedElement = null;

        document.querySelectorAll('.task-item').forEach(item => {
            item.setAttribute('draggable', true);
            
            item.addEventListener('dragstart', function(e) {
                draggedElement = this;
                this.style.opacity = '0.5';
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/html', this.innerHTML);
            });

            item.addEventListener('dragend', function() {
                this.style.opacity = '';
                draggedElement = null;
            });

            item.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
                this.classList.add('drag-over');
            });

            item.addEventListener('dragleave', function() {
                this.classList.remove('drag-over');
            });

            item.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('drag-over');
                
                if (draggedElement !== this) {
                    const container = this.parentNode;
                    const draggedIndex = Array.from(container.children).indexOf(draggedElement);
                    const targetIndex = Array.from(container.children).indexOf(this);
                    
                    if (draggedIndex < targetIndex) {
                        container.insertBefore(draggedElement, this.nextSibling);
                    } else {
                        container.insertBefore(draggedElement, this);
                    }
                    
                    // Update task order in backend
                    updateTaskOrder();
                }
            });
        });

        function updateTaskOrder() {
            const taskIds = Array.from(document.querySelectorAll('.task-item'))
                .map(item => item.dataset.taskId);
            
            fetch('/tasks/reorder', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    task_ids: taskIds
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Urutan tugas berhasil diperbarui', 'success');
                } else {
                    showNotification('Gagal memperbarui urutan tugas', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Gagal memperbarui urutan tugas: ' + error.message, 'error');
            });
        }

        // Auto-save functionality for task editing
        let autoSaveTimeout;
        document.querySelectorAll('.auto-save-input').forEach(input => {
            input.addEventListener('input', function() {
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(() => {
                    autoSaveTask(this);
                }, 1000); // Auto-save after 1 second of inactivity
            });
        });

        function autoSaveTask(input) {
            const taskId = input.dataset.taskId;
            const field = input.dataset.field;
            const value = input.value;

            fetch(`/tasks/${taskId}/auto-save`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    field: field,
                    value: value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show subtle save indicator
                    showSaveIndicator(input, 'saved');
                } else {
                    showSaveIndicator(input, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showSaveIndicator(input, 'error');
            });
        }

        function showSaveIndicator(input, status) {
            const indicator = input.nextElementSibling;
            if (indicator && indicator.classList.contains('save-indicator')) {
                indicator.textContent = status === 'saved' ? '✓ Tersimpan' : '✗ Gagal';
                indicator.className = `save-indicator text-xs ${status === 'saved' ? 'text-green-600' : 'text-red-600'}`;
                
                setTimeout(() => {
                    indicator.textContent = '';
                }, 2000);
            }
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + N: New task
            if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
                e.preventDefault();
                window.location.href = '/tasks/create';
            }
            
            // Ctrl/Cmd + F: Focus search
            if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                e.preventDefault();
                const searchInput = document.getElementById('task-search');
                if (searchInput) {
                    searchInput.focus();
                }
            }
            
            // Ctrl/Cmd + A: Select all tasks
            if ((e.ctrlKey || e.metaKey) && e.key === 'a' && e.target.tagName !== 'INPUT') {
                e.preventDefault();
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = !selectAllCheckbox.checked;
                    selectAllCheckbox.dispatchEvent(new Event('change'));
                }
            }
        });

        // Initialize tooltips
        document.querySelectorAll('[data-tooltip]').forEach(element => {
            element.addEventListener('mouseenter', function() {
                const tooltip = document.createElement('div');
                tooltip.className = 'absolute z-50 px-2 py-1 text-sm text-white bg-gray-800 rounded shadow-lg';
                tooltip.textContent = this.dataset.tooltip;
                tooltip.style.top = (this.offsetTop - 30) + 'px';
                tooltip.style.left = this.offsetLeft + 'px';
                document.body.appendChild(tooltip);
                this.tooltipElement = tooltip;
            });
            
            element.addEventListener('mouseleave', function() {
                if (this.tooltipElement) {
                    document.body.removeChild(this.tooltipElement);
                    this.tooltipElement = null;
                }
            });
        });

        // Initialize with current date highlighted in calendar
        const today = new Date();
        calendar.gotoDate(today);
    });
    
</script>
@endpush