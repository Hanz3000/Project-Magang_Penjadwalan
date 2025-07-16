@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
                <h1 class="text-2xl font-semibold text-white">Tambah Task Baru</h1>
                <p class="text-blue-100 mt-1">Buat task baru untuk mengelola pekerjaan Anda</p>
            </div>

            <div class="px-8 py-6">
                <form action="{{ route('tasks.store') }}" method="POST" id="task-form" class="space-y-6">
                    @csrf

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Tugas <span class="text-red-500">*</span></label>
                        <input id="title" name="title" type="text" placeholder="Masukkan judul tugas" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea id="description" name="description" rows="4" placeholder="Masukkan deskripsi tugas"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                        <select id="category_id" name="category_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                            <input id="start_date" name="start_date" type="date" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai <span class="text-red-500">*</span></label>
                            <input id="end_date" name="end_date" type="date" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Subtask Bertingkat</label>
                        <div class="border border-gray-300 rounded-lg">
                            <div class="bg-gray-100 px-4 py-2 flex justify-between items-center">
                                <span class="text-sm font-semibold text-gray-700">Daftar Subtask</span>
                                <button type="button" onclick="addSubtask(null)"
                                    class="px-3 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 text-xs">Tambah Subtask</button>
                            </div>
                            <div id="subtasks-container" class="px-4 py-3 space-y-3"></div>
                        </div>

                         <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @php
            $priorities = [
                ['value' => 'urgent', 'label' => 'Sangat Mendesak', 'color' => 'red'],
                ['value' => 'high', 'label' => 'Tinggi', 'color' => 'yellow'],
                ['value' => 'medium', 'label' => 'Sedang', 'color' => 'blue'],
                ['value' => 'low', 'label' => 'Rendah', 'color' => 'green'],
            ];
        @endphp

        @foreach($priorities as $p)
        <label class="flex items-center gap-2">
            <input type="radio" name="priority" value="{{ $p['value'] }}" required>
            <span class="text-sm text-gray-700">{{ $p['label'] }}</span>
        </label>
        @endforeach
    </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Tugas
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
                <div class="flex justify-start">
                    <a href="{{ route('tasks.index') }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let subtaskIdCounter = 0;

function addSubtask(parentElement = null) {
    const parentId = parentElement?.dataset.id || null;
    const subtaskWrapper = document.createElement('div');
    const currentId = ++subtaskIdCounter;
    subtaskWrapper.dataset.id = currentId;
    subtaskWrapper.className = 'subtask-item space-y-2';

    subtaskWrapper.innerHTML = `
        <div class="flex items-center gap-3">
            <input type="text" name="subtasks[${currentId}][title]" placeholder="Subtask"
                class="flex-1 px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500" required>
            <input type="hidden" name="subtasks[${currentId}][parent_id]" value="${parentId ?? ''}">
            <button type="button" class="px-3 py-2 bg-green-500 text-white rounded hover:bg-green-600"
                onclick="addSubtask(this.closest('.subtask-item'))">Tambah Anak</button>
            <button type="button" class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600"
                onclick="this.closest('.subtask-item').remove()">Hapus</button>
        </div>
        <div class="ml-6 space-y-3 child-container"></div>
    `;

    const container = parentElement?.querySelector('.child-container') || document.getElementById('subtasks-container');
    container.appendChild(subtaskWrapper);
}
</script>
@endpush
@endsection
