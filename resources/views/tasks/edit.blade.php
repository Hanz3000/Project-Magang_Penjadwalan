@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-12">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Task</h1>
            <p class="text-gray-600">Perbarui detail tugas Anda di bawah ini</p>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-8 space-y-6">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="title">
                            Judul Task <span class="text-red-500">*</span>
                        </label>
                        <input id="title" name="title" type="text" value="{{ old('title', $task->title) }}" placeholder="Masukkan judul task" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="description">Deskripsi</label>
                        <textarea id="description" name="description" rows="3" placeholder="Masukkan deskripsi task"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $task->description) }}</textarea>
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="due_date">Tanggal Jatuh Tempo</label>
                        <input id="due_date" name="due_date" type="date" value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="priority">Prioritas</label>
                        <select id="priority" name="priority" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Rendah</option>
                            <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Sedang</option>
                            <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>Tinggi</option>
                            <option value="urgent" {{ old('priority', $task->priority) == 'urgent' ? 'selected' : '' }}>Sangat Mendesak</option>
                        </select>
                    </div>

                    <!-- Subtasks Tree -->
                    <div id="subtasks-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sub Tasks</label>
                        <div class="space-y-1" id="subtasks-list">
                            @php
                                function renderSubtasks($subtasks, $parent_id = null, $level = 0) {
                                    foreach ($subtasks->where('parent_id', $parent_id) as $subtask) {
                                        echo '<div class="flex items-center mb-2 bg-gray-50 rounded px-3 py-2" style="margin-left: '.($level * 20).'px;">';

                                        if($level == 0){
                                            // Parent: hanya label
                                            echo '<span class="text-gray-800 font-semibold">'.$subtask->title.'</span>';
                                            echo '<input type="hidden" name="subtasks['.$subtask->id.'][title]" value="'.$subtask->title.'">';
                                        } else {
                                            // Child: checkbox, edit, delete
                                            echo '<input type="checkbox" id="subtask-'.$subtask->id.'" name="subtasks['.$subtask->id.'][completed]" '.($subtask->completed ? 'checked' : '').' class="h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-3">';
                                            echo '<input type="text" name="subtasks['.$subtask->id.'][title]" value="'.$subtask->title.'" class="border border-gray-300 rounded px-2 py-1 w-full mr-3">';
                                            echo '<button type="button" onclick="deleteSubtask(this)" class="text-red-500 hover:text-red-700 transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                  </button>';
                                        }

                                        echo '</div>';

                                        // Recursive render
                                        renderSubtasks($subtasks, $subtask->id, $level + 1);
                                    }
                                }
                                renderSubtasks($task->subTasks);
                            @endphp
                        </div>

                        <!-- Add New Subtask -->
                        <div class="mt-4 flex items-center gap-3">
                            <input type="text" id="new_subtask_input" name="new_subtask" placeholder="Tambah sub task baru"
                                class="border border-gray-300 rounded px-3 py-2 w-full">
                            <button type="button" onclick="addNewSubtask()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">Tambah</button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-gray-50 px-8 py-6 border-t border-gray-200 flex justify-between items-center">
                        <a href="{{ route('tasks.index') }}"
                            class="inline-flex items-center px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Daftar
                        </a>

                        <button type="submit"
                            class="inline-flex items-center px-8 py-3 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg hover:from-blue-700 hover:to-blue-800 shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Task
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<script>
function deleteSubtask(button) {
    button.parentElement.remove();
}

function addNewSubtask() {
    const input = document.getElementById('new_subtask_input');
    const value = input.value.trim();
    if (value !== '') {
        const container = document.getElementById('subtasks-list');
        const div = document.createElement('div');
        div.className = 'flex items-center mb-2 bg-gray-50 rounded px-3 py-2';
        div.innerHTML = `
            <input type="checkbox" name="subtasks_new[][completed]" class="h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-3">
            <input type="text" name="subtasks_new[][title]" value="${value}" class="border border-gray-300 rounded px-2 py-1 w-full mr-3">
            <button type="button" onclick="deleteSubtask(this)" class="text-red-500 hover:text-red-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        `;
        container.appendChild(div);
        input.value = '';
    }
}
</script>
