@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
                <h1 class="text-2xl font-semibold text-white">Tambah Task Baru</h1>
                <p class="text-blue-100 mt-1">Buat task baru untuk mengelola pekerjaan Anda</p>
            </div>

            <!-- Form Content -->
            <div class="px-8 py-6">
                <form action="{{ route('tasks.store') }}" method="POST" id="task-form" class="space-y-6">
                    @csrf

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Tugas <span class="text-red-500">*</span></label>
                        <input id="title" name="title" type="text" placeholder="Masukkan judul tugas" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea id="description" name="description" rows="4" placeholder="Masukkan deskripsi tugas"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"></textarea>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                        <select id="category_id" name="category_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Prioritas <span class="text-red-500">*</span></label>
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
                            <label class="relative cursor-pointer priority-option" data-priority="{{ $p['value'] }}">
                                <input type="radio" class="sr-only" name="priority" value="{{ $p['value'] }}" required>
                                <div class="flex flex-col items-center justify-center p-4 rounded-lg border-2 border-gray-200 transition-all duration-200 priority-container">
                                    <div class="w-4 h-4 rounded-full border-2 border-gray-300 mb-2 priority-circle"></div>
                                    <span class="text-sm font-medium text-gray-700 text-center">{{ $p['label'] }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Date Range -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                            <input id="start_date" name="start_date" type="date" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai <span class="text-red-500">*</span></label>
                            <input id="end_date" name="end_date" type="date" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                    </div>

                    <!-- Subtasks -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Subtask</label>
                        <div class="border border-gray-300 rounded-lg">
                            <div class="bg-gray-100 px-4 py-2 rounded-t-lg flex justify-between items-center">
                                <span class="text-sm font-semibold text-gray-700">Daftar Subtask</span>
                                <!-- Tombol Tambah Subtask Baru -->
                                <button type="button" onclick="addRootSubtask()"
                                    class="px-3 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors text-xs">Tambah Subtask Baru</button>
                            </div>
                            <div id="subtasks-container" class="divide-y divide-gray-200">
                                <div class="subtask-item px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <input type="text" name="subtasks[]" placeholder="Masukkan subtask"
                                            class="flex-1 px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <button type="button" onclick="addSubtask(this)"
                                            class="px-3 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition-colors">Tambah Anak</button>
                                        <button type="button" onclick="removeSubtask(this)"
                                            class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition-colors">Hapus</button>
                                    </div>
                                    <div class="ml-6 space-y-3"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-4">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Tugas
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
                <div class="flex justify-start">
                    <a href="{{ route('tasks.index') }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
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
document.addEventListener('DOMContentLoaded', function() {
    // Tombol prioritas
    document.querySelectorAll('.priority-option').forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();
            const radioInput = this.querySelector('input[type="radio"]');
            const priority = this.getAttribute('data-priority');
            if(radioInput) {
                radioInput.checked = true;
                document.querySelectorAll('.priority-option').forEach(opt => {
                    const container = opt.querySelector('.priority-container');
                    const circle = opt.querySelector('.priority-circle');
                    container.className = 'flex flex-col items-center justify-center p-4 rounded-lg border-2 border-gray-200 transition-all duration-200 priority-container';
                    circle.className = 'w-4 h-4 rounded-full border-2 border-gray-300 mb-2 priority-circle';
                });
                const selectedContainer = this.querySelector('.priority-container');
                const selectedCircle = this.querySelector('.priority-circle');
                switch(priority) {
                    case 'urgent':
                        selectedContainer.classList.add('border-red-500', 'bg-red-50');
                        selectedCircle.classList.add('border-red-500', 'bg-red-500');
                        break;
                    case 'high':
                        selectedContainer.classList.add('border-yellow-500', 'bg-yellow-50');
                        selectedCircle.classList.add('border-yellow-500', 'bg-yellow-500');
                        break;
                    case 'medium':
                        selectedContainer.classList.add('border-blue-500', 'bg-blue-50');
                        selectedCircle.classList.add('border-blue-500', 'bg-blue-500');
                        break;
                    case 'low':
                        selectedContainer.classList.add('border-green-500', 'bg-green-50');
                        selectedCircle.classList.add('border-green-500', 'bg-green-500');
                        break;
                }
            }
        });
    });

    const today = new Date().toISOString().split('T')[0];
    document.getElementById('start_date').value = today;
    document.getElementById('end_date').value = today;
});

// Function untuk menambah subtask baru di root
function addRootSubtask() {
    const container = document.getElementById('subtasks-container');
    const div = document.createElement('div');
    div.className = 'subtask-item px-4 py-3';
    div.innerHTML = `
        <div class="flex items-center gap-3">
            <input type="text" name="subtasks[]" placeholder="Masukkan subtask"
                class="flex-1 px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            <button type="button" onclick="addSubtask(this)"
                class="px-3 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition-colors">Tambah Anak</button>
            <button type="button" onclick="removeSubtask(this)"
                class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition-colors">Hapus</button>
        </div>
        <div class="ml-6 space-y-3"></div>
    `;
    container.appendChild(div);
}

// Function add nested subtask
function addSubtask(button) {
    const parent = button.closest('.subtask-item');
    const container = parent.querySelector('div.ml-6');
    const div = document.createElement('div');
    div.className = 'subtask-item';
    div.innerHTML = `
        <div class="flex items-center gap-3">
            <input type="text" name="subtasks[]" placeholder="Masukkan subtask anak"
                class="flex-1 px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            <button type="button" onclick="addSubtask(this)"
                class="px-3 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition-colors">Tambah Anak</button>
            <button type="button" onclick="removeSubtask(this)"
                class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition-colors">Hapus</button>
        </div>
        <div class="ml-6 space-y-3"></div>
    `;
    container.appendChild(div);
}

// Function remove subtask
function removeSubtask(button) {
    button.closest('.subtask-item').remove();
}
</script>
@endpush
@endsection
