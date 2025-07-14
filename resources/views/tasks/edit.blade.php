@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <h1 class="text-2xl font-bold mb-6">Edit Task</h1>

    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Form fields sama dengan create.blade.php -->
        <!-- Copy semua field dari create.blade.php -->
        <!-- Tapi tambahkan value untuk masing-masing field -->

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                Judul Task
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="title" name="title" type="text"
                value="{{ old('title', $task->title) }}" placeholder="Masukkan judul task" required>
        </div>

        <!-- Tambahkan semua field lainnya dengan value -->

        <!-- Untuk subtasks -->
        <div class="mb-6" id="subtasks-container">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Sub Tasks
            </label>
            <div class="space-y-2" id="subtasks-list">
                @foreach($task->subTasks as $subtask)
                <div class="flex items-center space-x-2 subtask-item">
                    <input type="text" name="subtasks[]"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        value="{{ $subtask->title }}" placeholder="Sub task">
                    <button type="button" class="remove-subtask text-red-500 hover:text-red-700">
                        <!-- Icon trash -->
                    </button>
                </div>
                @endforeach
                <!-- Tambahkan satu empty subtask jika tidak ada -->
                @if($task->subTasks->isEmpty())
                <div class="flex items-center space-x-2 subtask-item">
                    <input type="text" name="subtasks[]"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        placeholder="Sub task">
                    <button type="button" class="remove-subtask text-red-500 hover:text-red-700">
                        <!-- Icon trash -->
                    </button>
                </div>
                @endif
            </div>
            <button type="button" id="add-subtask" class="mt-2 text-blue-500 hover:text-blue-700 flex items-center">
                <!-- Icon plus -->
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

<!-- Copy juga script JavaScript dari create.blade.php -->
@push('scripts')
<script>
    // Sama seperti di create.blade.php
</script>
@endpush
@endsection