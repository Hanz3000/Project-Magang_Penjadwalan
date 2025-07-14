@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <h1 class="text-2xl font-bold mb-6">Tambah Task Baru</h1>

    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                Judul Task
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="title" name="title" type="text" placeholder="Masukkan judul task" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                Deskripsi
            </label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="description" name="description" rows="3" placeholder="Masukkan deskripsi task"></textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="category_id">
                Kategori
            </label>
            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="category_id" name="category_id" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Prioritas
            </label>
            <div class="flex items-center space-x-4">
                <label class="inline-flex items-center">
                    <input type="radio" class="form-radio text-red-600" name="priority" value="urgent" required>
                    <span class="ml-2">Urgent</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" class="form-radio text-orange-500" name="priority" value="high">
                    <span class="ml-2">High</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" class="form-radio text-yellow-500" name="priority" value="medium">
                    <span class="ml-2">Medium</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" class="form-radio text-green-500" name="priority" value="low">
                    <span class="ml-2">Low</span>
                </label>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="start_date">
                    Tanggal Mulai
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="start_date" name="start_date" type="date" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="end_date">
                    Tanggal Selesai
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="end_date" name="end_date" type="date" required>
            </div>
        </div>

        <div class="mb-6" id="subtasks-container">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Sub Tasks
            </label>
            <div class="space-y-2" id="subtasks-list">
                <div class="flex items-center space-x-2 subtask-item">
                    <input type="text" name="subtasks[]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Sub task">
                    <button type="button" class="remove-subtask text-red-500 hover:text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
                Simpan Task
            </button>
            <a href="{{ route('tasks.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800">
                Kembali ke Daftar
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add subtask
        document.getElementById('add-subtask').addEventListener('click', function() {
            const subtaskItem = document.createElement('div');
            subtaskItem.className = 'flex items-center space-x-2 subtask-item mt-2';
            subtaskItem.innerHTML = `
            <input type="text" name="subtasks[]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Sub task">
            <button type="button" class="remove-subtask text-red-500 hover:text-red-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        `;
            document.getElementById('subtasks-list').appendChild(subtaskItem);
        });

        // Remove subtask
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-subtask') || e.target.closest('.remove-subtask')) {
                const subtaskItem = e.target.closest('.subtask-item');
                if (subtaskItem && document.querySelectorAll('.subtask-item').length > 1) {
                    subtaskItem.remove();
                }
            }
        });

        // Set default dates
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('start_date').value = today;
        document.getElementById('end_date').value = today;
    });
</script>
@endpush
@endsection