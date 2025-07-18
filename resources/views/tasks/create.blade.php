@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-12">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header Card -->
        <div class="mb-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Tambah Task Baru</h1>
            <p class="text-gray-600">Buat dan kelola pekerjaan Anda dengan mudah</p>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <form action="{{ route('tasks.store') }}" method="POST" id="task-form">
                @csrf
                
                <!-- Form Content -->
                <div class="p-8">
                    <!-- Basic Information Section -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                            Informasi Dasar
                        </h2>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Title Input -->
                            <div class="lg:col-span-2">
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Judul Tugas <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input id="title" name="title" type="text" placeholder="Masukkan judul tugas" required
                                        class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Category Selection -->
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kategori <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="relative flex items-center gap-2">
                                        <div class="flex-1 relative">
                                            <div class="custom-select border border-gray-300 rounded-xl focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent transition-all duration-200 hover:border-gray-400">
                                                <input type="text" id="category-search" placeholder="Cari atau pilih kategori..." 
                                                    class="w-full px-4 py-3 pl-12 border-0 rounded-t-xl focus:ring-0 focus:border-0"
                                                    style="outline: none;">
                                                <div id="category-options" class="max-h-40 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100 hidden">
                                                    @foreach($categories as $category)
                                                        <div class="category-option px-4 py-2 cursor-pointer hover:bg-gray-100" data-id="{{ $category->id }}">
                                                            {{ $category->name }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <input type="hidden" name="category_id" id="category_id" required>
                                            </div>
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                </svg>
                                            </div>
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center dropdown-toggle">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <button type="button" onclick="openCategoryModal()" 
                                            class="px-4 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors duration-200"
                                            title="Kelola Kategori">
                                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Priority Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Prioritas <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="relative priority-option">
                                        <input type="radio" name="priority" value="urgent" required class="sr-only peer">
                                        <div class="px-4 py-3 bg-red-50 border border-red-200 rounded-xl cursor-pointer transition-all duration-200 peer-checked:ring-2 peer-checked:ring-red-500 peer-checked:border-red-500 peer-checked:bg-red-100 hover:border-red-300 hover:bg-red-100">
                                            <div class="flex items-center justify-center">
                                                <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                                <span class="text-sm font-medium text-red-700">Urgent</span>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="relative priority-option">
                                        <input type="radio" name="priority" value="high" required class="sr-only peer">
                                        <div class="px-4 py-3 bg-yellow-50 border border-yellow-200 rounded-xl cursor-pointer transition-all duration-200 peer-checked:ring-2 peer-checked:ring-yellow-500 peer-checked:border-yellow-500 peer-checked:bg-yellow-100 hover:border-yellow-300 hover:bg-yellow-100">
                                            <div class="flex items-center justify-center">
                                                <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                                                <span class="text-sm font-medium text-yellow-700">Tinggi</span>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="relative priority-option">
                                        <input type="radio" name="priority" value="medium" required class="sr-only peer">
                                        <div class="px-4 py-3 bg-blue-50 border border-blue-200 rounded-xl cursor-pointer transition-all duration-200 peer-checked:ring-2 peer-checked:ring-blue-500 peer-checked:border-blue-500 peer-checked:bg-blue-100 hover:border-blue-300 hover:bg-blue-100">
                                            <div class="flex items-center justify-center">
                                                <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                                                <span class="text-sm font-medium text-blue-700">Sedang</span>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="relative priority-option">
                                        <input type="radio" name="priority" value="low" required class="sr-only peer">
                                        <div class="px-4 py-3 bg-green-50 border border-green-200 rounded-xl cursor-pointer transition-all duration-200 peer-checked:ring-2 peer-checked:ring-green-500 peer-checked:border-green-500 peer-checked:bg-green-100 hover:border-green-300 hover:bg-green-100">
                                            <div class="flex items-center justify-center">
                                                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                                <span class="text-sm font-medium text-green-700">Rendah</span>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Section -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                            Timeline
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Start Date & Time -->
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Mulai <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="relative">
                                        <input id="start_date" name="start_date" type="date" required
                                            class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <input id="start_time" name="start_time" type="time" 
                                            class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- End Date & Time -->
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Selesai <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="relative">
                                        <input id="end_date" name="end_date" type="date" required
                                            class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <input id="end_time" name="end_time" type="time" 
                                            class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Section -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                            <div class="w-2 h-2 bg-purple-500 rounded-full mr-3"></div>
                            Deskripsi
                        </h2>
                        
                        <div class="relative">
                            <textarea id="description" name="description" rows="4" placeholder="Masukkan deskripsi detail tugas Anda..."
                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 resize-none"></textarea>
                            <div class="absolute top-3 left-3 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Subtasks Section -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                            <div class="w-2 h-2 bg-indigo-500 rounded-full mr-3"></div>
                            Subtask Bertingkat
                        </h2>
                        
                        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                            <div class="bg-gradient-to-r from-indigo-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-indigo-700">Daftar Subtask</span>
                                    </div>
                                    <button type="button" onclick="addSubtask(null)"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition-colors duration-200 text-sm font-medium shadow-sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Tambah Subtask
                                    </button>
                                </div>
                            </div>
                            <div id="subtasks-container" class="p-6 space-y-3 min-h-[120px] bg-gray-50">
                                <div class="text-center text-gray-500 text-sm py-8" id="no-subtasks">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="font-medium text-gray-400">Belum ada subtask</span>
                                        <span class="text-gray-400 text-xs mt-1">Klik tombol "Tambah Subtask" untuk mulai menambahkan</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-gray-50 px-8 py-6 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <a href="{{ route('tasks.index') }}"
                        class="inline-flex items-center px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Daftar
                    </a>
                    
                    <button type="submit"
                        class="inline-flex items-center px-8 py-3 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Tugas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Category Management Modal -->
<div id="category-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            Kelola Kategori
                        </h3>
                        
                        <!-- Add New Category Form -->
                        <div class="mb-6">
                            <div class="flex gap-2">
                                <input type="text" id="new-category-name" placeholder="Nama kategori baru" 
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <button type="button" onclick="addCategory()"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    Tambah
                                </button>
                            </div>
                        </div>
                        
                        <!-- Categories Table -->
                        <div class="overflow-y-auto max-h-96">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nama Kategori
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="categories-table-body" class="bg-white divide-y divide-gray-200">
                                    @foreach($categories as $category)
                                    <tr data-id="{{ $category->id }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $category->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button type="button" onclick="editCategory(this)" 
                                                class="text-blue-600 hover:text-blue-900 mr-3">
                                                Edit
                                            </button>
                                            <button type="button" onclick="deleteCategory({{ $category->id }})" 
                                                class="text-red-600 hover:text-red-900">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeCategoryModal()"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .scrollbar-thin {
        scrollbar-width: thin;
    }
    .scrollbar-thumb-gray-300::-webkit-scrollbar-thumb {
        background-color: #d1d5db;
        border-radius: 6px;
    }
    .scrollbar-track-gray-100::-webkit-scrollbar-track {
        background-color: #f3f4f6;
    }
    .custom-select {
        position: relative;
        width: 100%;
        border-radius: 0.75rem;
    }
    .custom-select input {
        border-bottom: none !important;
        border-radius: 0.75rem 0.75rem 0 0;
    }
    #category-options {
        max-height: 10rem; /* Approximately 5 options visible */
        border-top: none;
        border-radius: 0 0 0.75rem 0.75rem;
        background-color: white;
        position: absolute;
        width: 100%;
        z-index: 10;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        display: none;
    }
    .category-option {
        border-bottom: 1px solid #e5e7eb;
    }
    .category-option:hover {
        background-color: #f3f4f6;
    }
    .category-option:last-child {
        border-bottom: none;
    }
    .dropdown-toggle {
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script>
let subtaskIdCounter = 0;

// Category Management Functions
function openCategoryModal() {
    document.getElementById('category-modal').classList.remove('hidden');
}

function closeCategoryModal() {
    document.getElementById('category-modal').classList.add('hidden');
}

function addCategory() {
    const nameInput = document.getElementById('new-category-name');
    const name = nameInput.value.trim();
    
    if (!name) {
        alert('Nama kategori tidak boleh kosong');
        return;
    }
    
    const tempId = Date.now();
    
    const tableBody = document.getElementById('categories-table-body');
    const newRow = document.createElement('tr');
    newRow.dataset.id = tempId;
    newRow.innerHTML = `
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">${name}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <button type="button" onclick="editCategory(this)" 
                class="text-blue-600 hover:text-blue-900 mr-3">
                Edit
            </button>
            <button type="button" onclick="deleteCategory(${tempId})" 
                class="text-red-600 hover:text-red-900">
                Hapus
            </button>
        </td>
    `;
    tableBody.appendChild(newRow);
    
    const optionsContainer = document.getElementById('category-options');
    const newOption = document.createElement('div');
    newOption.className = 'category-option';
    newOption.dataset.id = tempId;
    newOption.textContent = name;
    newOption.addEventListener('click', selectCategory);
    optionsContainer.appendChild(newOption);
    
    nameInput.value = '';
}

function editCategory(button) {
    const row = button.closest('tr');
    const id = row.dataset.id;
    const nameCell = row.querySelector('td:first-child div');
    const currentName = nameCell.textContent;
    
    const newName = prompt('Edit nama kategori:', currentName);
    if (newName && newName.trim() !== '' && newName !== currentName) {
        nameCell.textContent = newName.trim();
        
        const options = document.querySelectorAll('#category-options .category-option');
        options.forEach(option => {
            if (option.dataset.id === id) {
                option.textContent = newName.trim();
            }
        });
    }
}

function deleteCategory(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus kategori ini?')) {
        return;
    }
    
    const row = document.querySelector(`#categories-table-body tr[data-id="${id}"]`);
    if (row) row.remove();
    
    const option = document.querySelector(`#category-options .category-option[data-id="${id}"]`);
    if (option) option.remove();
}

function selectCategory(event) {
    const option = event.target;
    const selectedId = option.dataset.id;
    const selectedText = option.textContent;
    const searchInput = document.getElementById('category-search');
    const hiddenInput = document.getElementById('category_id');
    const optionsContainer = document.getElementById('category-options');

    hiddenInput.value = selectedId;
    searchInput.value = selectedText;
    optionsContainer.style.display = 'none';
}

// Subtask Management Functions
function getIndentLevel(element) {
    let level = 0;
    let current = element;
    while (current && current.classList.contains('subtask-item')) {
        level++;
        current = current.parentElement.closest('.subtask-item');
    }
    return level;
}

function addSubtask(parentElement = null) {
    const noSubtasksMsg = document.getElementById('no-subtasks');
    if (noSubtasksMsg) noSubtasksMsg.style.display = 'none';

    const parentId = parentElement?.dataset.id || null;
    const subtaskWrapper = document.createElement('div');
    const currentId = ++subtaskIdCounter;
    subtaskWrapper.dataset.id = currentId;
    subtaskWrapper.className = 'subtask-item';

    const indentLevel = getIndentLevel(parentElement);
    const marginLeft = indentLevel * 20;

    subtaskWrapper.innerHTML = `
    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm" style="margin-left: ${marginLeft}px;">
        <div class="flex items-center gap-3">
            <div class="flex-1">
                <input type="text" name="subtasks[${currentId}][title]" placeholder="Masukkan nama subtask"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" required>
                <input type="hidden" name="subtasks[${currentId}][parent_id]" value="${parentId ?? ''}">
                <label class="flex items-center mt-2 text-sm gap-2">
                    <input type="checkbox" name="subtasks[${currentId}][is_group]" value="1"
                        class="form-checkbox rounded text-blue-600">
                    Subtask ini grup?
                </label>
            </div>
            <div class="flex gap-2">
                <button type="button" 
                    class="px-3 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors duration-200 text-xs font-medium"
                    onclick="addSubtask(this.closest('.subtask-item'))"
                    title="Tambah Sub-subtask">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </button>
                <button type="button" 
                    class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors duration-200 text-xs font-medium"
                    onclick="removeSubtask(this.closest('.subtask-item'))"
                    title="Hapus Subtask">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div class="mt-3 space-y-3 child-container"></div>
    </div>
`;

    const container = parentElement?.querySelector('.child-container') || document.getElementById('subtasks-container');
    container.appendChild(subtaskWrapper);
}

function removeSubtask(element) {
    element.remove();
    const container = document.getElementById('subtasks-container');
    const subtasks = container.querySelectorAll('.subtask-item');
    if (subtasks.length === 0) {
        const noSubtasksMsg = document.getElementById('no-subtasks');
        if (noSubtasksMsg) noSubtasksMsg.style.display = 'block';
    }
}

document.getElementById('task-form').addEventListener('submit', function(e) {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const startTime = document.getElementById('start_time').value;
    const endTime = document.getElementById('end_time').value;
    
    if (startDate && endDate) {
        const startDateTime = new Date(`${startDate}T${startTime || '00:00'}`);
        const endDateTime = new Date(`${endDate}T${endTime || '23:59'}`);
        if (startDateTime > endDateTime) {
            e.preventDefault();
            alert('Waktu mulai tidak boleh lebih besar dari waktu selesai');
            return false;
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('category-search');
    const optionsContainer = document.getElementById('category-options');
    const hiddenInput = document.getElementById('category_id');
    const options = document.querySelectorAll('.category-option');
    const dropdownToggle = document.querySelector('.dropdown-toggle');

    // Toggle dropdown with arrow button
    dropdownToggle.addEventListener('click', function(e) {
        e.preventDefault();
        const isOpen = optionsContainer.style.display === 'block';
        optionsContainer.style.display = isOpen ? 'none' : 'block';
        if (!isOpen) {
            options.forEach(option => option.style.display = 'block'); // Show all options when opening
            searchInput.focus();
        }
    });

    // Open dropdown on input click
    searchInput.addEventListener('click', function() {
        optionsContainer.style.display = 'block';
        options.forEach(option => option.style.display = 'block'); // Show all options initially
        searchInput.focus();
    });

    // Filter options on input
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        let hasVisibleOptions = false;
        options.forEach(option => {
            const text = option.textContent.toLowerCase();
            option.style.display = text.includes(searchTerm) ? 'block' : 'none';
            if (text.includes(searchTerm)) hasVisibleOptions = true;
        });
        optionsContainer.style.display = 'block'; // Keep dropdown open during search
    });

    // Select option on click
    options.forEach(option => {
        option.addEventListener('click', function(e) {
            selectCategory({ target: this });
        });
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.custom-select')) {
            optionsContainer.style.display = 'none';
        }
    });

    // Set default time
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const currentTime = `${hours}:${minutes}`;
    
    if (!document.getElementById('start_time').value) {
        document.getElementById('start_time').value = currentTime;
    }
    if (!document.getElementById('end_time').value) {
        const endTime = new Date(now.getTime() + 60 * 60 * 1000);
        const endHours = String(endTime.getHours()).padStart(2, '0');
        const endMinutes = String(endTime.getMinutes()).padStart(2, '0');
        document.getElementById('end_time').value = `${endHours}:${endMinutes}`;
    }

    // Priority selection
    const priorityOptions = document.querySelectorAll('.priority-option input[type="radio"]');
    priorityOptions.forEach(option => {
        option.addEventListener('change', function() {
            priorityOptions.forEach(opt => {
                const parent = opt.closest('.priority-option');
                const div = parent.querySelector('div');
                div.classList.remove('ring-2', 'ring-red-500', 'ring-yellow-500', 'ring-blue-500', 'ring-green-500');
                div.classList.remove('border-red-500', 'border-yellow-500', 'border-blue-500', 'border-green-500');
                div.classList.remove('bg-red-100', 'bg-yellow-100', 'bg-blue-100', 'bg-green-100');
            });
            if (this.checked) {
                const parent = this.closest('.priority-option');
                const div = parent.querySelector('div');
                const value = this.value;
                switch(value) {
                    case 'urgent': div.classList.add('ring-2', 'ring-red-500', 'border-red-500', 'bg-red-100'); break;
                    case 'high': div.classList.add('ring-2', 'ring-yellow-500', 'border-yellow-500', 'bg-yellow-100'); break;
                    case 'medium': div.classList.add('ring-2', 'ring-blue-500', 'border-blue-500', 'bg-blue-100'); break;
                    case 'low': div.classList.add('ring-2', 'ring-green-500', 'border-green-500', 'bg-green-100'); break;
                }
            }
        });
    });
});
</script>
@endpush
@endsection