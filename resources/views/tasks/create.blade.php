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
                <p class="text-gray-600">Buat tugas baru dengan cepat dan terorganisir</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Terdapat beberapa kesalahan:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Form Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <form action="{{ route('tasks.store') }}" method="POST" id="task-form">
                    @csrf

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
                                        <input id="title" name="title" type="text" value="{{ old('title') }}"
                                            placeholder="Masukkan judul tugas" required
                                            class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 @error('title') border-red-500 @endif">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                    @error('title')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @endif
                                </div>

                                <!-- Category Selection -->
                                @if (isset($categories) && $categories->count() > 0)
                                    <div>
                                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                            Kategori
                                        </label>
                                        <div class="relative flex items-center gap-2">
                                            <div class="flex-1 relative">
                                                <select id="category_id" name="category_id"
                                                    class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 appearance-none @error('category_id') border-red-500 @endif">
                                                    <option value="">Pilih Kategori</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}"
                                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <button type="button" onclick="openCategoryModal()"
                                                class="p-3 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200"
                                                title="Kelola Kategori">
                                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        @error('category_id')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @endif
                                    </div>
                                @endif

                                <!-- Priority Selection -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        Prioritas <span class="text-red-500">*</span>
                                    </label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <label class="relative priority-option">
                                            <input type="radio" name="priority" value="urgent" required
                                                class="sr-only peer" {{ old('priority') == 'urgent' ? 'checked' : '' }}>
                                            <div
                                                class="px-4 py-3 bg-red-50 border border-red-200 rounded-xl cursor-pointer transition-all duration-200 peer-checked:ring-2 peer-checked:ring-red-500 peer-checked:border-red-500 peer-checked:bg-red-100 hover:border-red-300 hover:bg-red-100">
                                                <div class="flex items-center justify-center">
                                                    <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                                    <span class="text-sm font-medium text-red-700">Urgent</span>
                                                </div>
                                            </div>
                                        </label>
                                        <label class="relative priority-option">
                                            <input type="radio" name="priority" value="high" required
                                                class="sr-only peer" {{ old('priority') == 'high' ? 'checked' : '' }}>
                                            <div
                                                class="px-4 py-3 bg-yellow-50 border border-yellow-200 rounded-xl cursor-pointer transition-all duration-200 peer-checked:ring-2 peer-checked:ring-yellow-500 peer-checked:border-yellow-500 peer-checked:bg-yellow-100 hover:border-yellow-300 hover:bg-yellow-100">
                                                <div class="flex items-center justify-center">
                                                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                                                    <span class="text-sm font-medium text-yellow-700">Tinggi</span>
                                                </div>
                                            </div>
                                        </label>
                                        <label class="relative priority-option">
                                            <input type="radio" name="priority" value="medium" required
                                                class="sr-only peer" {{ old('priority') == 'medium' ? 'checked' : '' }}>
                                            <div
                                                class="px-4 py-3 bg-blue-50 border border-blue-200 rounded-xl cursor-pointer transition-all duration-200 peer-checked:ring-2 peer-checked:ring-blue-500 peer-checked:border-blue-500 peer-checked:bg-blue-100 hover:border-blue-300 hover:bg-blue-100">
                                                <div class="flex items-center justify-center">
                                                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                                                    <span class="text-sm font-medium text-blue-700">Sedang</span>
                                                </div>
                                            </div>
                                        </label>
                                        <label class="relative priority-option">
                                            <input type="radio" name="priority" value="low" required
                                                class="sr-only peer" {{ old('priority') == 'low' ? 'checked' : '' }}>
                                            <div
                                                class="px-4 py-3 bg-green-50 border border-green-200 rounded-xl cursor-pointer transition-all duration-200 peer-checked:ring-2 peer-checked:ring-green-500 peer-checked:border-green-500 peer-checked:bg-green-100 hover:border-green-300 hover:bg-green-100">
                                                <div class="flex items-center justify-center">
                                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                                    <span class="text-sm font-medium text-green-700">Rendah</span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    @error('priority')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @endif
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
                                            <input id="start_date" name="start_date" type="text" required
                                                value="{{ old('start_date') }}"
                                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 flatpickr-input @error('start_date') border-red-500 @endif"
                                                placeholder="Pilih tanggal">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="relative">
                                            <input id="start_time" name="start_time" type="text"
                                                value="{{ old('start_time', '00:00') }}"
                                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 flatpickr-input @error('start_time') border-red-500 @endif"
                                                placeholder="Pilih waktu">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    @error('start_date')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @endif
                                    @error('start_time')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @endif
                                </div>

                                <!-- End Date & Time -->
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal Selesai <span class="text-red-500">*</span>
                                    </label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="relative">
                                            <input id="end_date" name="end_date" type="text" required
                                                value="{{ old('end_date') }}"
                                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 flatpickr-input @error('end_date') border-red-500 @endif"
                                                placeholder="Pilih tanggal">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="relative">
                                            <input id="end_time" name="end_time" type="text"
                                                value="{{ old('end_time', '23:59') }}"
                                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 flatpickr-input @error('end_time') border-red-500 @endif"
                                                placeholder="Pilih waktu">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    @error('end_date')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @endif
                                    @error('end_time')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Full Day Checkbox -->
                        <div class="mb-8">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" 
                                       name="full_day" 
                                       value="1" 
                                       {{ old('full_day') ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                       id="full_day_toggle">
                                <span class="text-sm text-gray-700">Sehari penuh</span>
                            </label>
                        </div>

                        <!-- Description Section -->
                        <div class="mb-8">
                            <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                                <div class="w-2 h-2 bg-purple-500 rounded-full mr-3"></div>
                                Deskripsi
                            </h2>
                            <div class="relative">
                                <textarea id="description" name="description" rows="4" placeholder="Masukkan deskripsi detail tugas Anda..."
                                    class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 resize-none @error('description') border-red-500 @endif">{{ old('description') }}</textarea>
                                <div class="absolute top-3 left-3 pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h7"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @endif
                        </div>

                        <!-- Subtask Section -->
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
                                            <span class="text-sm font-medium text-indigo-700">Daftar Subtask (Maksimal 6 level)</span>
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
                                <div id="subtasks-container" class="relative p-6 space-y-3 min-h-[120px] bg-gray-50 overflow-x-auto">
                                    <div class="subtasks-scroll-container min-w-full" id="subtasks-list">
                                        <div class="text-center text-gray-500 text-sm py-8" id="no-subtasks">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 17v-2a4 4 0 00-4-4H5a4 4 0 000 8h1a4 4 0 004-4zm0 0h6m0 0v2a4 4 0 004 4h1a4 4 0 000-8h-1a4 4 0 00-4 4v2" />
                                                </svg>
                                                <span class="text-gray-400 text-xs mt-1">Klik tombol "Tambah Subtask" untuk mulai menambahkan</span>
                                            </div>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Daftar
                        </a>
                        <button type="submit" id="submit-btn"
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

    <!-- Notification Container -->
    <div id="notification-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <!-- Category Management Modal -->
    <div id="category-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Kelola Kategori</h3>
                            <div class="flex gap-2 mb-4">
                                <input type="text" id="new-category-name" placeholder="Nama kategori baru"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <button onclick="addCategory()"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    Tambah
                                </button>
                            </div>
                            <!-- Categories Table -->
                            <div class="overflow-y-auto max-h-96">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="categories-table-body" class="bg-white divide-y divide-gray-200">
                                        @foreach($categories as $category)
                                            <tr data-id="{{ $category->id }}">
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $category->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <button onclick="deleteCategory('{{ $category->id }}')"
                                                        class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
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
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Selesai
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<!-- Tambahkan Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Fungsi notifikasi
    function showNotification(message, type = 'info', duration = 3000) {
        const container = document.getElementById('notification-container') || createNotificationContainer();
        const notification = document.createElement('div');
        notification.className = `
            transform translate-x-full opacity-0 transition-all duration-300 ease-out
            px-6 py-4 rounded-lg shadow-lg text-white font-medium text-sm
            flex items-center gap-3 max-w-sm
            ${type === 'success' ? 'bg-green-600' : 
              type === 'error' ? 'bg-red-600' : 
              type === 'warning' ? 'bg-yellow-600' : 'bg-blue-600'}
        `;
        const icon = type === 'success' ? '✅' : 
                     type === 'error' ? '❌' : 
                     type === 'warning' ? '⚠️' : 'ℹ️';
        notification.innerHTML = `
            <span class="text-lg">${icon}</span>
            <span class="flex-1">${message}</span>
            <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;
        container.appendChild(notification);
        setTimeout(() => notification.classList.remove('translate-x-full', 'opacity-0'), 10);
        setTimeout(() => {
            notification.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => notification.remove(), 300);
        }, duration);
    }

    function createNotificationContainer() {
        const container = document.createElement('div');
        container.id = 'notification-container';
        container.className = 'fixed top-4 right-4 z-50 space-y-2';
        document.body.appendChild(container);
        return container;
    }

    // Full Day Toggle
    const fullDayToggle = document.getElementById('full_day_toggle');
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');

    function toggleTimeInputs(isFullDay) {
        if (isFullDay) {
            startTimeInput.value = '00:00';
            endTimeInput.value = '23:59';
            startTimeInput.disabled = true;
            endTimeInput.disabled = true;
        } else {
            startTimeInput.disabled = false;
            endTimeInput.disabled = false;
        }
    }

    if (fullDayToggle) {
        fullDayToggle.addEventListener('change', function() {
            toggleTimeInputs(this.checked);
        });
        toggleTimeInputs(fullDayToggle.checked);
    }

    // Inisialisasi Flatpickr
    flatpickr("#start_date", {
        dateFormat: "Y-m-d",
        onChange: function(selectedDates) {
            document.getElementById('start_time')._flatpickr.open();
        }
    });

    flatpickr("#start_time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });

    flatpickr("#end_date", {
        dateFormat: "Y-m-d",
        onChange: function(selectedDates) {
            document.getElementById('end_time')._flatpickr.open();
        }
    });

    flatpickr("#end_time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });

    // Fungsi untuk inisialisasi Flatpickr pada subtask
    function initFlatpickrForSubtask(subtaskElement) {
        const startDateInput = subtaskElement.querySelector('input[name*="[start_date]"]');
        const endDateInput = subtaskElement.querySelector('input[name*="[end_date]"]');

        if (startDateInput && !startDateInput._flatpickr) {
            flatpickr(startDateInput, {
                dateFormat: "Y-m-d"
            });
        }
        if (endDateInput && !endDateInput._flatpickr) {
            flatpickr(endDateInput, {
                dateFormat: "Y-m-d"
            });
        }
    }

    // Subtask Management
    let deletedSubtasks = [];

    function formatDateDisplay(dateString) {
        if (!dateString) return '';
        const parts = dateString.split('-');
        if (parts.length === 3) return `${parts[2]}/${parts[1]}/${parts[0]}`;
        return dateString;
    }

    function getParentDates(parentId) {
        let parentStartDate = document.getElementById('start_date').value;
        let parentEndDate = document.getElementById('end_date').value;
        if (parentId) {
            const parentItem = document.querySelector(`.subtask-item[data-id="${parentId}"]`);
            if (parentItem) {
                const parentStartInput = parentItem.querySelector('input[name$="[start_date]"]');
                const parentEndInput = parentItem.querySelector('input[name$="[end_date]"]');
                if (parentStartInput && parentStartInput.value) parentStartDate = parentStartInput.value;
                if (parentEndInput && parentEndInput.value) parentEndDate = parentEndInput.value;
            }
        }
        return { parentStartDate, parentEndDate };
    }

    function addSubtask(parentId) {
        const subtasksContainer = document.getElementById('subtasks-list');
        const noSubtasksMessage = document.getElementById('no-subtasks');
        if (noSubtasksMessage) noSubtasksMessage.style.display = 'none';

        const subtaskId = 'new-subtask-' + Date.now();
        let level = 0;
        if (parentId) {
            const parentItem = document.querySelector(`.subtask-item[data-id="${parentId}"]`);
            if (parentItem) {
                level = parseInt(parentItem.dataset.level || 0) + 1;
                if (level >= 6) {
                    showNotification('Maksimal level subtask adalah 6', 'warning');
                    return;
                }
            }
        }

        const { parentStartDate, parentEndDate } = getParentDates(parentId);
        const displayParentStart = formatDateDisplay(parentStartDate);
        const displayParentEnd = formatDateDisplay(parentEndDate);

        const subtaskElement = document.createElement('div');
        subtaskElement.className = 'subtask-item bg-white rounded-lg border border-gray-200 p-4 mb-3 shadow-sm relative';
        subtaskElement.dataset.id = subtaskId;
        subtaskElement.dataset.level = level;
        subtaskElement.style.marginLeft = `${level * 16}px`;
        if (level > 0) {
            subtaskElement.style.borderLeft = '2px solid #6366F1';
            subtaskElement.style.paddingLeft = '14px';
        }

        subtaskElement.innerHTML = `
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <input type="text" name="subtasks[${subtaskId}][title]" placeholder="Judul subtask" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-3">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Tanggal Mulai</label>
                            <div class="relative">
                                <input type="text" name="subtasks[${subtaskId}][start_date]"
                                    value="${parentStartDate}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm flatpickr-input start-date-input"
                                    placeholder="Pilih tanggal">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Tanggal Selesai</label>
                            <div class="relative">
                                <input type="text" name="subtasks[${subtaskId}][end_date]"
                                    value="${parentEndDate}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm flatpickr-input end-date-input"
                                    placeholder="Pilih tanggal">
                            </div>
                        </div>
                    </div>
                    <div class="subtask-date mt-2">
                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-xs">${displayParentStart} - ${displayParentEnd}</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <input type="hidden" name="subtasks[${subtaskId}][parent_id]" value="${parentId || ''}">
                    ${level < 5 ? `
                    <button type="button" onclick="addSubtask('${subtaskId}')"
                        class="p-2 text-indigo-600 hover:text-indigo-800 transition-colors" title="Tambah Child">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </button>
                    ` : ''}
                    <button type="button" onclick="removeSubtask('${subtaskId}')"
                        class="p-2 text-red-600 hover:text-red-800 transition-colors" title="Hapus">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="subtask-children mt-2" data-parent="${subtaskId}"></div>
        `;

        if (parentId) {
            const parentItem = document.querySelector(`.subtask-item[data-id="${parentId}"]`);
            let childrenContainer = parentItem?.querySelector(`.subtask-children[data-parent="${parentId}"]`);
            if (!childrenContainer) {
                childrenContainer = document.createElement('div');
                childrenContainer.className = 'subtask-children mt-2';
                childrenContainer.dataset.parent = parentId;
                parentItem?.appendChild(childrenContainer);
            }
            childrenContainer.appendChild(subtaskElement);
        } else {
            subtasksContainer.appendChild(subtaskElement);
        }

        // Inisialisasi Flatpickr
        initFlatpickrForSubtask(subtaskElement);

        // Event listener
        const startDateInput = subtaskElement.querySelector('.start-date-input');
        const endDateInput = subtaskElement.querySelector('.end-date-input');
        startDateInput.addEventListener('change', function () {
            endDateInput.min = this.value;
            if (endDateInput.value && endDateInput.value < this.value) {
                endDateInput.value = this.value;
            }
            const dateDisplaySpan = subtaskElement.querySelector('.subtask-date span');
            if (dateDisplaySpan) {
                const displayStart = formatDateDisplay(this.value);
                const displayEnd = formatDateDisplay(endDateInput.value);
                dateDisplaySpan.textContent = `${displayStart} - ${displayEnd}`;
            }
            updateChildSubtaskLimits(subtaskId);
        });
        endDateInput.addEventListener('change', function () {
            const dateDisplaySpan = subtaskElement.querySelector('.subtask-date span');
            if (dateDisplaySpan) {
                const displayStart = formatDateDisplay(startDateInput.value);
                const displayEnd = formatDateDisplay(this.value);
                dateDisplaySpan.textContent = `${displayStart} - ${displayEnd}`;
            }
            updateChildSubtaskLimits(subtaskId);
        });

        showNotification('Subtask baru ditambahkan', 'success');
    }

    function removeSubtask(subtaskId) {
        const childSubtasks = document.querySelectorAll(`input[name$="[parent_id]"][value="${subtaskId}"]`);
        childSubtasks.forEach(childInput => {
            const childItem = childInput.closest('.subtask-item');
            const childId = childItem?.dataset.id;
            if (childId) removeSubtask(childId);
        });

        const subtaskElement = document.querySelector(`.subtask-item[data-id="${subtaskId}"]`);
        if (subtaskElement) subtaskElement.remove();

        const subtasksContainer = document.getElementById('subtasks-list');
        if (subtasksContainer && subtasksContainer.querySelectorAll('.subtask-item').length === 0) {
            const noSubtasksMessage = document.getElementById('no-subtasks');
            if (noSubtasksMessage) noSubtasksMessage.style.display = 'block';
        }

        showNotification('Subtask dihapus', 'info');
    }

    function updateChildSubtaskLimits(parentSubtaskId) {
        const parentItem = document.querySelector(`.subtask-item[data-id="${parentSubtaskId}"]`);
        if (!parentItem) return;
        const parentStartInput = parentItem.querySelector('input[name$="[start_date]"]');
        const parentEndInput = parentItem.querySelector('input[name$="[end_date]"]');
        const parentStartDate = parentStartInput?.value || '';
        const parentEndDate = parentEndInput?.value || '';
        if (!parentStartDate || !parentEndDate) return;

        const childSubtasks = document.querySelectorAll(`input[name$="[parent_id]"][value="${parentSubtaskId}"]`);
        childSubtasks.forEach(childInput => {
            const childItem = childInput.closest('.subtask-item');
            if (!childItem) return;
            const childStartInput = childItem.querySelector('input[name$="[start_date]"]');
            const childEndInput = childItem.querySelector('input[name$="[end_date]"]');
            if (childStartInput) {
                childStartInput._flatpickr.set('minDate', parentStartDate);
                childStartInput._flatpickr.set('maxDate', parentEndDate);
                if (childStartInput.value < parentStartDate) childStartInput.value = parentStartDate;
                if (childStartInput.value > parentEndDate) childStartInput.value = parentEndDate;
            }
            if (childEndInput) {
                childEndInput._flatpickr.set('minDate', parentStartDate);
                childEndInput._flatpickr.set('maxDate', parentEndDate);
                if (childEndInput.value < parentStartDate) childEndInput.value = parentStartDate;
                if (childEndInput.value > parentEndDate) childEndInput.value = parentEndDate;
            }
            const childDateDisplaySpan = childItem.querySelector('.subtask-date span');
            if (childDateDisplaySpan) {
                const displayStart = formatDateDisplay(childStartInput?.value || parentStartDate);
                const displayEnd = formatDateDisplay(childEndInput?.value || parentEndDate);
                childDateDisplaySpan.textContent = `${displayStart} - ${displayEnd}`;
            }
            const childId = childItem.dataset.id;
            if (childId) updateChildSubtaskLimits(childId);
        });
    }

    // Expose to global
    window.addSubtask = addSubtask;
    window.removeSubtask = removeSubtask;

    // Initialize existing subtask pickers
    document.querySelectorAll('.subtask-item').forEach(item => {
        initFlatpickrForSubtask(item);
    });

    // Form validation
    document.getElementById('task-form').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submit-btn');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...';

        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');

        if (startDateInput.value && endDateInput.value) {
            const startDate = new Date(`${startDateInput.value}T${startTimeInput.value || '00:00'}`);
            const endDate = new Date(`${endDateInput.value}T${endTimeInput.value || '23:59'}`);
            if (endDate < startDate) {
                e.preventDefault();
                showNotification('Tanggal selesai tidak boleh sebelum tanggal mulai', 'error');
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
                return;
            }
        }

        showNotification('💾 Menyimpan tugas baru...', 'info');
    });

    // Category Management
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
            showNotification('Nama kategori tidak boleh kosong', 'error');
            return;
        }

        fetch('{{ route("categories.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name: name })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tbody = document.getElementById('categories-table-body');
                const row = document.createElement('tr');
                row.dataset.id = data.category.id;
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">${data.category.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <button onclick="deleteCategory('${data.category.id}')" class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                    </td>
                `;
                tbody.appendChild(row);
                nameInput.value = '';
                showNotification('Kategori berhasil ditambahkan', 'success');
            }
        })
        .catch(() => {
            showNotification('Gagal menambahkan kategori', 'error');
        });
    }

    function deleteCategory(id) {
        if (!confirm('Yakin ingin menghapus kategori ini?')) return;

        fetch(`{{ url('categories') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`tr[data-id="${id}"]`)?.remove();
                showNotification('Kategori berhasil dihapus', 'success');
            }
        })
        .catch(() => {
            showNotification('Gagal menghapus kategori', 'error');
        });
    }

    window.openCategoryModal = openCategoryModal;
    window.closeCategoryModal = closeCategoryModal;
    window.addCategory = addCategory;
    window.deleteCategory = deleteCategory;
});
</script>
@endpush