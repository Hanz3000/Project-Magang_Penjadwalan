@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-12">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Header Card -->
            <div class="mb-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Task</h1>
                <p class="text-gray-600">Perbarui detail tugas Anda dengan mudah</p>
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

            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Form Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <form action="{{ route('tasks.update', $task->id) }}" method="POST" id="task-form">
                    @csrf
                    @method('PUT')

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
                                        <input id="title" name="title" type="text"
                                            value="{{ old('title', $task->title) }}" placeholder="Masukkan judul tugas"
                                            required
                                            class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 @error('title') border-red-500 @enderror">
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
                                    @enderror
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
                                                    class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 appearance-none @error('category_id') border-red-500 @enderror">
                                                    <option value="">Pilih Kategori</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}"
                                                            {{ old('category_id', $task->category_id) == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div
                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        @error('category_id')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
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
                                                class="sr-only peer"
                                                {{ old('priority', $task->priority) == 'urgent' ? 'checked' : '' }}>
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
                                                class="sr-only peer"
                                                {{ old('priority', $task->priority) == 'high' ? 'checked' : '' }}>
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
                                                class="sr-only peer"
                                                {{ old('priority', $task->priority) == 'medium' ? 'checked' : '' }}>
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
                                                class="sr-only peer"
                                                {{ old('priority', $task->priority) == 'low' ? 'checked' : '' }}>
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
                                    @enderror
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
                                                value="{{ old('start_date', $task->start_date ? $task->start_date->format('Y-m-d') : '') }}"
                                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 @error('start_date') border-red-500 @enderror">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="relative">
                                            <input id="start_time" name="start_time" type="text" readonly
                                                value="{{ old('start_time', $task->start_time ? $task->start_time->format('H:i') : '00:00') }}"
                                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 time-picker-input @error('start_time') border-red-500 @enderror">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    @error('start_date')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                    @error('start_time')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- End Date & Time -->
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Selesai <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="relative">
                                        <input id="end_date" name="end_date" type="date" required
                                            value="{{ old('end_date', $task->end_date ? $task->end_date->format('Y-m-d') : '') }}"
                                            class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 @error('end_date') border-red-500 @enderror">
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
                                        <input id="end_time" name="end_time" type="text" readonly
                                            value="{{ old('end_time', $task->end_time ? $task->end_time->format('H:i') : '23:59') }}"
                                            class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 time-picker-input @error('end_time') border-red-500 @enderror">
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
                                @enderror
                                @error('end_time')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <!-- Full Day Button -->
                        <div class="mt-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" id="full_day_toggle" name="full_day" value="1"
    class="form-checkbox h-5 w-5 text-blue-600" {{ $task->full_day ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Sehari Penuh</span>
                            </label>
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
                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 resize-none @error('description') border-red-500 @enderror">{{ old('description', $task->description) }}</textarea>
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
                        @enderror
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
                                        <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                            </path>
                                        </svg>
                                        <span class="text-sm font-medium text-indigo-700">Daftar Subtask (Maksimal 6
                                            level)</span>
                                    </div>
                                    <button type="button" id="add-subtask-button" onclick="addSubtask(null)"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition-colors duration-200 text-sm font-medium shadow-sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Tambah Subtask
                                    </button>
                                </div>
                            </div>
                            <div id="subtasks-container"
                                class="relative p-6 space-y-3 min-h-[120px] bg-gray-50 overflow-x-auto">
                                <!-- Scroll Indicator -->
                                <div id="scroll-indicator"
                                    class="absolute top-2 right-2 text-xs text-gray-400 bg-white px-2 py-1 rounded shadow-sm hidden z-10">
                                    ← Scroll untuk melihat lebih banyak
                                </div>

                                <!-- Subtasks Scroll Container -->
                                <div class="subtasks-scroll-container min-w-full">
                                    @if ($task->subTasks && $task->subTasks->count() > 0)
                                        @foreach ($task->subTasks->where('parent_id', null) as $index => $subtask)
                                            <div class="subtask-item" data-id="{{ $subtask->id }}">
                                                <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm"
                                                    style="margin-left: 0px;">
                                                    <div class="flex items-center gap-3">
                                                        <div class="flex-1">
                                                            <input type="hidden"
                                                                name="subtasks[{{ $index }}][id]"
                                                                value="{{ $subtask->id }}">
                                                            <input type="text"
                                                                name="subtasks[{{ $index }}][title]"
                                                                value="{{ $subtask->title }}"
                                                                placeholder="Masukkan nama subtask"
                                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                required>
                                                            <input type="hidden"
                                                                name="subtasks[{{ $index }}][parent_id]"
                                                                value="{{ $subtask->parent_id ?? '' }}">
                                                        </div>
                                                        <div class="flex gap-2">
                                                            <button type="button"
                                                                class="px-3 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors duration-200 text-xs font-medium"
                                                                onclick="addSubtask(this.closest('.subtask-item'))"
                                                                title="Tambah Sub-subtask">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                                                                    </path>
                                                                </svg>
                                                            </button>
                                                            <button type="button"
                                                                class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors duration-200 text-xs font-medium"
                                                                onclick="removeSubtask(this.closest('.subtask-item'))"
                                                                title="Hapus Subtask">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3 space-y-3 child-container">
                                                        @foreach ($task->subTasks->where('parent_id', $subtask->id) as $childIndex => $childSubtask)
                                                            <div class="subtask-item" data-id="{{ $childSubtask->id }}">
                                                                <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm"
                                                                    style="margin-left: 20px;">
                                                                    <div class="flex items-center gap-3">
                                                                        <div class="flex-1">
                                                                            <input type="hidden"
                                                                                name="subtasks[{{ $index }}_{{ $childIndex }}][id]"
                                                                                value="{{ $childSubtask->id }}">
                                                                            <input type="text"
                                                                                name="subtasks[{{ $index }}_{{ $childIndex }}][title]"
                                                                                value="{{ $childSubtask->title }}"
                                                                                placeholder="Masukkan nama subtask"
                                                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                                required>
                                                                            <input type="hidden"
                                                                                name="subtasks[{{ $index }}_{{ $childIndex }}][parent_id]"
                                                                                value="{{ $childSubtask->parent_id ?? '' }}">
                                                                        </div>
                                                                        <div class="flex gap-2">
                                                                            <button type="button"
                                                                                class="px-3 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors duration-200 text-xs font-medium"
                                                                                onclick="addSubtask(this.closest('.subtask-item'))"
                                                                                title="Tambah Sub-subtask">
                                                                                <svg class="w-3 h-3" fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                                                                                    </path>
                                                                                </svg>
                                                                            </button>
                                                                            <button type="button"
                                                                                class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors duration-200 text-xs font-medium"
                                                                                onclick="removeSubtask(this.closest('.subtask-item'))"
                                                                                title="Hapus Subtask">
                                                                                <svg class="w-3 h-3" fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                                                </svg>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mt-3 space-y-3 child-container">
                                                                        @foreach ($task->subTasks->where('parent_id', $childSubtask->id) as $grandChildIndex => $grandChildSubtask)
                                                                            <div class="subtask-item"
                                                                                data-id="{{ $grandChildSubtask->id }}">
                                                                                <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm"
                                                                                    style="margin-left: 40px;">
                                                                                    <div class="flex items-center gap-3">
                                                                                        <div class="flex-1">
                                                                                            <input type="hidden"
                                                                                                name="subtasks[{{ $index }}_{{ $childIndex }}_{{ $grandChildIndex }}][id]"
                                                                                                value="{{ $grandChildSubtask->id }}">
                                                                                            <input type="text"
                                                                                                name="subtasks[{{ $index }}_{{ $childIndex }}_{{ $grandChildIndex }}][title]"
                                                                                                value="{{ $grandChildSubtask->title }}"
                                                                                                placeholder="Masukkan nama subtask"
                                                                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                                                required>
                                                                                            <input type="hidden"
                                                                                                name="subtasks[{{ $index }}_{{ $childIndex }}_{{ $grandChildIndex }}][parent_id]"
                                                                                                value="{{ $grandChildSubtask->parent_id ?? '' }}">
                                                                                        </div>
                                                                                        <div class="flex gap-2">
                                                                                            <button type="button"
                                                                                                class="px-3 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors duration-200 text-xs font-medium"
                                                                                                onclick="addSubtask(this.closest('.subtask-item'))"
                                                                                                title="Tambah Sub-subtask">
                                                                                                <svg class="w-3 h-3"
                                                                                                    fill="none"
                                                                                                    stroke="currentColor"
                                                                                                    viewBox="0 0 24 24">
                                                                                                    <path
                                                                                                        stroke-linecap="round"
                                                                                                        stroke-linejoin="round"
                                                                                                        stroke-width="2"
                                                                                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                                                                                                    </path>
                                                                                                </svg>
                                                                                            </button>
                                                                                            <button type="button"
                                                                                                class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors duration-200 text-xs font-medium"
                                                                                                onclick="removeSubtask(this.closest('.subtask-item'))"
                                                                                                title="Hapus Subtask">
                                                                                                <svg class="w-3 h-3"
                                                                                                    fill="none"
                                                                                                    stroke="currentColor"
                                                                                                    viewBox="0 0 24 24">
                                                                                                    <path
                                                                                                        stroke-linecap="round"
                                                                                                        stroke-linejoin="round"
                                                                                                        stroke-width="2"
                                                                                                        d="M6 18L18 6M6 6l12 12">
                                                                                                    </path>
                                                                                                </svg>
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div
                                                                                        class="mt-3 space-y-3 child-container">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-center text-gray-500 text-sm py-8" id="no-subtasks">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                                <span class="font-medium text-gray-400">Belum ada subtask</span>
                                                <span class="text-gray-400 text-xs mt-1">Klik tombol "Tambah Subtask" untuk
                                                    mulai menambahkan</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <!-- Hidden input for deleted subtasks -->
                            <input type="hidden" name="deleted_subtasks" id="deleted_subtasks" value="">
                        </div>
                    </div>
            </div>

            <!-- Action Buttons -->
            <div
                class="bg-gray-50 px-8 py-6 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                <a href="{{ route('tasks.index') }}"
                    class="inline-flex items-center px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Daftar
                </a>

                <button type="submit"
                    class="inline-flex items-center px-8 py-3 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Update Task
                </button>
            </div>
            </form>
        </div>
    </div>
    </div>

    @push('styles')
        <style>
            .scrollbar-thin {
                scrollbar-width: thin;
                scrollbar-color: #3B82F6 #E5E7EB;
            }

            .scrollbar-thin::-webkit-scrollbar {
                width: 6px;
            }

            .scrollbar-thin::-webkit-scrollbar-track {
                background: #E5E7EB;
                border-radius: 3px;
            }

            .scrollbar-thin::-webkit-scrollbar-thumb {
                background: #3B82F6;
                border-radius: 3px;
            }

            .scrollbar-thin::-webkit-scrollbar-thumb:hover {
                background: #2563EB;
            }

            .time-option {
                padding: 8px;
                text-align: center;
                cursor: pointer;
                transition: background-color 0.2s;
            }

            .time-option:hover {
                background-color: #EFF6FF;
            }

            .time-option.selected {
                background-color: #DBEAFE;
                font-weight: 600;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            let subtaskIdCounter = {{ $task->subTasks ? $task->subTasks->count() : 0 }};
            let currentTimeInput = null;
            let deletedSubtasks = [];

            // Time Picker Functions
            function openTimePicker(inputElement) {
                currentTimeInput = inputElement;
                const currentValue = inputElement.value || '00:00';
                const [hours, minutes] = currentValue.split(':').map(Number);

                populateTimeLists(hours, minutes);
                document.getElementById('time-picker-modal').classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeTimePicker() {
                document.getElementById('time-picker-modal').classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                currentTimeInput = null;
            }

            function populateTimeLists(selectedHour, selectedMinute) {
                const hourList = document.getElementById('hour-list');
                const minuteList = document.getElementById('minute-list');

                hourList.innerHTML = '';
                for (let i = 0; i <= 23; i++) {
                    const hourDiv = document.createElement('div');
                    hourDiv.className = `time-option ${i === selectedHour ? 'selected' : ''}`;
                    hourDiv.textContent = i.toString().padStart(2, '0');
                    hourDiv.dataset.value = i;
                    hourDiv.addEventListener('click', function() {
                        document.querySelectorAll('#hour-list .time-option').forEach(opt => opt.classList.remove(
                            'selected'));
                        this.classList.add('selected');
                    });
                    hourList.appendChild(hourDiv);
                }

                minuteList.innerHTML = '';
                for (let i = 0; i <= 59; i += 1) {
                    const minuteDiv = document.createElement('div');
                    minuteDiv.className = `time-option ${i === selectedMinute ? 'selected' : ''}`;
                    minuteDiv.textContent = i.toString().padStart(2, '0');
                    minuteDiv.dataset.value = i;
                    minuteDiv.addEventListener('click', function() {
                        document.querySelectorAll('#minute-list .time-option').forEach(opt => opt.classList.remove(
                            'selected'));
                        this.classList.add('selected');
                    });
                    minuteList.appendChild(minuteDiv);
                }

                const selectedHourElement = hourList.querySelector(`.time-option[data-value="${selectedHour}"]`);
                const selectedMinuteElement = minuteList.querySelector(`.time-option[data-value="${selectedMinute}"]`);

                if (selectedHourElement) {
                    hourList.scrollTop = selectedHourElement.offsetTop - hourList.offsetHeight / 2 + selectedHourElement
                        .offsetHeight / 2;
                }
                if (selectedMinuteElement) {
                    minuteList.scrollTop = selectedMinuteElement.offsetTop - minuteList.offsetHeight / 2 + selectedMinuteElement
                        .offsetHeight / 2;
                }
            }

            function setTimeFromPicker() {
                if (!currentTimeInput) return;

                const selectedHour = document.querySelector('#hour-list .time-option.selected')?.dataset.value || '0';
                const selectedMinute = document.querySelector('#minute-list .time-option.selected')?.dataset.value || '0';

                currentTimeInput.value =
                    `${selectedHour.toString().padStart(2, '0')}:${selectedMinute.toString().padStart(2, '0')}`;
                closeTimePicker();
            }

            // Initialize Time Picker and Full Day Toggle
            document.addEventListener('DOMContentLoaded', function() {
                const startTimeInput = document.getElementById('start_time');
                const endTimeInput = document.getElementById('end_time');
                const fullDayToggle = document.getElementById('full_day_toggle');

                // Store original time values for restoration
                startTimeInput.dataset.originalTime = startTimeInput.value;
                endTimeInput.dataset.originalTime = endTimeInput.value;

                // Auto-check full day if times are default full day times
                if (startTimeInput.value === '00:00' && endTimeInput.value === '23:59') {
                    fullDayToggle.checked = true;
                    startTimeInput.disabled = true;
                    endTimeInput.disabled = true;
                }

                // Function to setup time picker event listeners
                function setupTimePickerListeners() {
                    // Remove existing listeners first to avoid duplicates
                    startTimeInput.removeEventListener('click', timePickerHandler);
                    endTimeInput.removeEventListener('click', timePickerHandler);

                    // Add new listeners
                    startTimeInput.addEventListener('click', timePickerHandler);
                    endTimeInput.addEventListener('click', timePickerHandler);
                }

                // Handler function for time picker
                function timePickerHandler() {
                    openTimePicker(this);
                }

                // Full day toggle change handler
                fullDayToggle.addEventListener('change', function() {
                    if (this.checked) {
                        // Save current time before overriding
                        startTimeInput.dataset.originalTime = startTimeInput.value;
                        endTimeInput.dataset.originalTime = endTimeInput.value;

                        startTimeInput.value = '00:00';
                        endTimeInput.value = '23:59';
                        startTimeInput.disabled = true;
                        endTimeInput.disabled = true;
                    } else {
                        // Restore time and make editable
                        startTimeInput.disabled = false;
                        endTimeInput.disabled = false;
                        startTimeInput.value = startTimeInput.dataset.originalTime || '00:00';
                        endTimeInput.value = endTimeInput.dataset.originalTime || '23:59';

                        // Setup time picker listeners when full day is unchecked
                        setupTimePickerListeners();
                    }
                });

                // Initialize time picker click handlers if not full day
                if (!fullDayToggle.checked) {
                    setupTimePickerListeners();
                }

                // Time picker modal event listeners
                document.getElementById('close-time-picker').addEventListener('click', closeTimePicker);
                document.getElementById('cancel-time').addEventListener('click', closeTimePicker);
                document.getElementById('ok-time').addEventListener('click', setTimeFromPicker);
            });

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
                const indentLevel = getIndentLevel(parentElement);

                if (indentLevel >= 6) {
                    alert('Maksimal 6 level subtask telah tercapai');
                    return;
                }

                const subtaskWrapper = document.createElement('div');
                const currentId = ++subtaskIdCounter;
                subtaskWrapper.dataset.id = currentId;
                subtaskWrapper.className = 'subtask-item';

                const marginLeft = indentLevel * 20;

                subtaskWrapper.innerHTML = `
                <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm" style="margin-left: ${marginLeft}px;">
                    <div class="flex items-center gap-3">
                        <div class="flex-1">
                            <input type="text" name="new_subtasks[${currentId}][title]" placeholder="Masukkan nama subtask"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" required>
                            <input type="hidden" name="new_subtasks[${currentId}][parent_id]" value="${parentId ?? ''}">
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

                const container = parentElement?.querySelector('.child-container') || document.querySelector(
                    '#subtasks-container .subtasks-scroll-container');
                if (container.firstChild && container.firstChild.id === 'no-subtasks') {
                    container.insertBefore(subtaskWrapper, container.firstChild.nextSibling);
                } else {
                    container.insertBefore(subtaskWrapper, container.firstChild);
                }
            }

            function removeSubtask(element) {
                if (confirm('Apakah Anda yakin ingin menghapus subtask ini beserta semua subtask di bawahnya?')) {
                    const subtaskId = element.dataset.id;
                    if (subtaskId) {
                        deletedSubtasks.push(subtaskId);
                        document.getElementById('deleted_subtasks').value = deletedSubtasks.join(',');
                    }
                    element.remove();

                    const container = document.getElementById('subtasks-container');
                    const subtasks = container.querySelectorAll('.subtask-item');
                    if (subtasks.length === 0) {
                        const noSubtasksMsg = document.getElementById('no-subtasks');
                        if (noSubtasksMsg) noSubtasksMsg.style.display = 'block';
                    }
                }
            }

            // Form validation with time consideration
            document.getElementById('task-form').addEventListener('submit', function(e) {
                const title = document.getElementById('title').value.trim();
                const priority = document.querySelector('input[name="priority"]:checked');
                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;
                const startTime = document.getElementById('start_time').value;
                const endTime = document.getElementById('end_time').value;

                if (!title) {
                    e.preventDefault();
                    alert('Judul task wajib diisi!');
                    document.getElementById('title').focus();
                    return false;
                }

                if (!priority) {
                    e.preventDefault();
                    alert('Prioritas wajib dipilih!');
                    return false;
                }

                if (startDate && endDate) {
                    const startDateTime = new Date(`${startDate}T${startTime || '00:00'}`);
                    const endDateTime = new Date(`${endDate}T${endTime || '23:59'}`);

                    if (startDateTime > endDateTime) {
                        e.preventDefault();
                        alert('Waktu mulai tidak boleh lebih besar dari waktu selesai');
                        return false;
                    }
                }

                const newSubtaskInputs = document.querySelectorAll('input[name*="new_subtasks"][name*="[title]"]');
                for (let input of newSubtaskInputs) {
                    if (input.value.trim() === '') {
                        e.preventDefault();
                        alert('Semua subtask baru harus memiliki judul!');
                        input.focus();
                        return false;
                    }
                }
            });

            // Priority selection functionality
            const priorityOptions = document.querySelectorAll('.priority-option input[type="radio"]');
            priorityOptions.forEach(option => {
                if (option.checked) {
                    const parent = option.closest('.priority-option');
                    const div = parent.querySelector('div');
                    const value = option.value;

                    switch (value) {
                        case 'urgent':
                            div.classList.add('ring-2', 'ring-red-500', 'border-red-500', 'bg-red-100');
                            break;
                        case 'high':
                            div.classList.add('ring-2', 'ring-yellow-500', 'border-yellow-500', 'bg-yellow-100');
                            break;
                        case 'medium':
                            div.classList.add('ring-2', 'ring-blue-500', 'border-blue-500', 'bg-blue-100');
                            break;
                        case 'low':
                            div.classList.add('ring-2', 'ring-green-500', 'border-green-500', 'bg-green-100');
                            break;
                    }
                }

                option.addEventListener('change', function() {
                    priorityOptions.forEach(opt => {
                        const parent = opt.closest('.priority-option');
                        const div = parent.querySelector('div');
                        div.classList.remove('ring-2', 'ring-red-500', 'ring-yellow-500',
                            'ring-blue-500', 'ring-green-500');
                        div.classList.remove('border-red-500', 'border-yellow-500', 'border-blue-500',
                            'border-green-500');
                        div.classList.remove('bg-red-100', 'bg-yellow-100', 'bg-blue-100',
                            'bg-green-100');
                    });

                    if (this.checked) {
                        const parent = this.closest('.priority-option');
                        const div = parent.querySelector('div');
                        const value = this.value;

                        switch (value) {
                            case 'urgent':
                                div.classList.add('ring-2', 'ring-red-500', 'border-red-500', 'bg-red-100');
                                break;
                            case 'high':
                                div.classList.add('ring-2', 'ring-yellow-500', 'border-yellow-500',
                                    'bg-yellow-100');
                                break;
                            case 'medium':
                                div.classList.add('ring-2', 'ring-blue-500', 'border-blue-500', 'bg-blue-100');
                                break;
                            case 'low':
                                div.classList.add('ring-2', 'ring-green-500', 'border-green-500',
                                    'bg-green-100');
                                break;
                        }
                    }
                });
            });
        </script>

        <!-- Time Picker Modal -->
        <div id="time-picker-modal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">
                    <div class="p-6 bg-gradient-to-b from-blue-50 to-white">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-semibold text-gray-900">Pilih Waktu</h3>
                            <button id="close-time-picker"
                                class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="flex justify-center items-center gap-4 mb-6">
                            <div class="relative flex flex-col items-center">
                                <div
                                    class="w-20 h-40 overflow-y-auto border border-gray-300 rounded-lg bg-white shadow-sm scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100">
                                    <div id="hour-list" class="flex flex-col items-center py-2">
                                        <!-- Hours will be populated by JavaScript -->
                                    </div>
                                </div>
                                <span
                                    class="absolute top-1/2 left-1/2 transform -translate-y-1/2 -translate-x-full text-gray-500 text-xl mr-2">:</span>
                            </div>
                            <div class="relative flex flex-col items-center">
                                <div
                                    class="w-20 h-40 overflow-y-auto border border-gray-300 rounded-lg bg-white shadow-sm scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100">
                                    <div id="minute-list" class="flex flex-col items-center py-2">
                                        <!-- Minutes will be populated by JavaScript -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-4">
                            <button id="cancel-time"
                                class="px-4 py-2 text-gray-700 font-medium hover:bg-gray-100 rounded-lg transition-colors duration-200">Batal</button>
                            <button id="ok-time"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">OK</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endpush
@endsection
