@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <h1 class="text-2xl font-bold mb-6">Edit Task</h1>

    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                Judul Task
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="title" name="title" type="text"
                value="{{ old('title', $task->title) }}" placeholder="Masukkan judul task" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                Deskripsi
            </label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline resize-none"
                id="description" name="description" rows="3"
                placeholder="Masukkan deskripsi task">{{ old('description', $task->description) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="due_date">
                Tanggal Jatuh Tempo
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="due_date" name="due_date" type="date"
                value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="priority">
                Prioritas
            </label>
            <div class="relative">
                <select class="block appearance-none w-full bg-white border border-gray-300 text-gray-700 py-2 px-3 pr-8 rounded leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500"
                    id="priority" name="priority">
                    <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Rendah</option>
                    <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Sedang</option>
                    <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>Tinggi</option>
                    <option value="urgent" {{ old('priority', $task->priority) == 'urgent' ? 'selected' : '' }}>Sangat Mendesak</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                    </svg>
                </div>
            </div>
        </div>

<!-- Subtasks section -->
<div class="mb-6" id="subtasks-container">
    <label class="block text-gray-700 text-sm font-bold mb-2">
        Sub Tasks
    </label>
    <div class="space-y-2" id="subtasks-list">
        @foreach($task->subTasks->sortBy('order') as $index => $subtask)
        <div class="flex items-center space-x-2 subtask-item group pl-6" data-subtask-id="{{ $subtask->id }}">
            <span class="text-gray-500 text-sm">{{ $index + 1 }}.</span>
            <input type="text" name="subtasks[{{ $subtask->id }}]"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                value="{{ $subtask->title }}" placeholder="Sub task">
            <input type="hidden" name="subtask_ids[]" value="{{ $subtask->id }}">
            <button type="button" class="remove-subtask text-red-500 hover:text-red-700 opacity-0 group-hover:opacity-100 transition-opacity">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        @endforeach
        
        <!-- Empty subtask template for new additions -->
        <div class="flex items-center space-x-2 subtask-item hidden pl-6" id="subtask-template">
            <span class="text-gray-500 text-sm"></span>
            <input type="text" name="subtasks[]"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                placeholder="Sub task">
            <button type="button" class="remove-subtask text-red-500 hover:text-red-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
    <button type="button" id="add-subtask" class="mt-2 text-blue-500 hover:text-blue-700 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        Tambah Sub Task
    </button>
</div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Update Task
            </button>
            <a href="{{ route('tasks.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800">
                Kembali ke Daftar
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const subtasksContainer = document.getElementById('subtasks-list');
        const addSubtaskBtn = document.getElementById('add-subtask');
        const subtaskTemplate = document.getElementById('subtask-template');

        // Add new subtask
        addSubtaskBtn.addEventListener('click', function() {
            const newSubtask = subtaskTemplate.cloneNode(true);
            newSubtask.classList.remove('hidden');
            
            // Update the numbering
            const subtaskItems = subtasksContainer.querySelectorAll('.subtask-item:not(#subtask-template)');
            const newNumber = subtaskItems.length + 1;
            newSubtask.querySelector('span').textContent = newNumber + '.';
            
            // Clear the value
            newSubtask.querySelector('input[type="text"]').value = '';
            
            subtasksContainer.appendChild(newSubtask);
        });

        // Remove subtask
        subtasksContainer.addEventListener('click', function(e) {
            if (e.target.closest('.remove-subtask')) {
                const subtaskItem = e.target.closest('.subtask-item');
                const subtaskItems = subtasksContainer.querySelectorAll('.subtask-item:not(#subtask-template)');
                
                if (subtaskItems.length > 1 || !subtaskItem.dataset.subtaskId) {
                    subtaskItem.remove();
                    updateSubtaskNumbers();
                } else {
                    alert('Task harus memiliki minimal satu subtask.');
                }
            }
        });

        // Update subtask numbers
        function updateSubtaskNumbers() {
            const subtaskItems = subtasksContainer.querySelectorAll('.subtask-item:not(#subtask-template)');
            subtaskItems.forEach((item, index) => {
                item.querySelector('span').textContent = (index + 1) + '.';
            });
        }
    });
</script>
@endpush
