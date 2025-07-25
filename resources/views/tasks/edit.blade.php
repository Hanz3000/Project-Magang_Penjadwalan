@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-12">
        <div class="max-w-4xl mx-auto px-6">
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
                                            <input id="start_date" name="start_date" type="date" required
                                                value="{{ old('start_date', $task->start_date ? $task->start_date->format('Y-m-d') : '') }}"
                                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 @error('start_date') border-red-500 @endif">
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
                                            <input id="start_time" name="start_time" type="text"
                                                value="{{ old('start_time', $task->start_time ? $task->start_time->format('H:i') : '00:00') }}"
                                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 time-picker-input @error('start_time') border-red-500 @endif">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
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
                                            <input id="end_date" name="end_date" type="date" required
                                                value="{{ old('end_date', $task->end_date ? $task->end_date->format('Y-m-d') : '') }}"
                                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 @error('end_date') border-red-500 @endif">
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
                                                value="{{ old('end_time', $task->end_time ? $task->end_time->format('H:i') : '23:59') }}"
                                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 time-picker-input @error('end_time') border-red-500 @endif">
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
                    <div class="mb-8 px-8">
                        <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                            <div class="w-2 h-2 bg-purple-500 rounded-full mr-3"></div>
                            Deskripsi
                        </h2>

                        <div class="relative">
                            <textarea id="description" name="description" rows="4" placeholder="Masukkan deskripsi detail tugas Anda..."
                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 resize-none @error('description') border-red-500 @endif">{{ old('description', $task->description) }}</textarea>
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

                    <!-- Inside the edit.blade.php form -->
                    <!-- Inside the edit.blade.php form -->
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
                                <!-- Scroll Indicator -->
                                <div id="scroll-indicator" class="absolute top-2 right-2 text-xs text-gray-400 bg-white px-2 py-1 rounded shadow-sm hidden z-10">
                                    ← Scroll untuk melihat lebih banyak
                                </div>
                                <!-- Subtasks Scroll Container -->
                                <div class="subtasks-scroll-container min-w-full">
                                    @if($task->subTasks->count() > 0)
    @php
        $subtasksByParent = $task->subTasks->groupBy('parent_id');
        $rootSubtasks = $subtasksByParent->get(null, collect());
    @endphp
    @foreach($rootSubtasks as $subtask)
        @include('tasks.partials.subtask-item', [
            'subtask' => $subtask,
            'subtasksByParent' => $subtasksByParent,
            'level' => 0,
            'task' => $task
        ])
    @endforeach
@else
    <div class="text-center text-gray-500 text-sm py-8" id="no-subtasks">
        <div class="flex flex-col items-center">
            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-2a4 4 0 00-4-4H5a4 4 0 000 8h1a4 4 0 004-4zm0 0h6m0 0v2a4 4 0 004 4h1a4 4 0 000-8h-1a4 4 0 00-4 4v2" />
            </svg>
            <span class="text-gray-400 text-xs mt-1">Klik tombol "Tambah Subtask" untuk mulai menambahkan</span>
        </div>
    </div>
@endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Hidden input for deleted subtasks -->
                    <input type="hidden" name="deleted_subtasks" id="deleted_subtasks" value="">
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
@endsection

@push('styles')
    <style>
        .scrollbar-thin {
            scrollbar-width: thin;
            scrollbar-color: #3B82F6 #E5E7EB;
        }
        .subtask-date {
    font-size: 0.7rem;
    color: #6b7280;
    margin-top: 2px;
    display: flex;
    gap: 4px;
    align-items: center;
}

.subtask-date svg {
    width: 10px;
    height: 10px;
    flex-shrink: 0;
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
document.addEventListener('DOMContentLoaded', function () {
    let subtaskIdCounter = {{ $task->subTasks ? $task->subTasks->count() : 0 }};
    let currentTimeInput = null;
    let deletedSubtasks = [];

    // --- Time Picker Functions (No changes needed) ---
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
                document.querySelectorAll('#hour-list .time-option').forEach(opt => opt.classList.remove('selected'));
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
                document.querySelectorAll('#minute-list .time-option').forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
            });
            minuteList.appendChild(minuteDiv);
        }
        const selectedHourElement = hourList.querySelector(`.time-option[data-value="${selectedHour}"]`);
        const selectedMinuteElement = minuteList.querySelector(`.time-option[data-value="${selectedMinute}"]`);
        if (selectedHourElement) {
            hourList.scrollTop = selectedHourElement.offsetTop - hourList.offsetHeight / 2 + selectedHourElement.offsetHeight / 2;
        }
        if (selectedMinuteElement) {
            minuteList.scrollTop = selectedMinuteElement.offsetTop - minuteList.offsetHeight / 2 + selectedMinuteElement.offsetHeight / 2;
        }
    }
    function setTimeFromPicker() {
        if (!currentTimeInput) return;
        const selectedHour = document.querySelector('#hour-list .time-option.selected')?.dataset.value || '0';
        const selectedMinute = document.querySelector('#minute-list .time-option.selected')?.dataset.value || '0';
        currentTimeInput.value = `${selectedHour.toString().padStart(2, '0')}:${selectedMinute.toString().padStart(2, '0')}`;
        closeTimePicker();
    }

    // --- Full Day Toggle & Date Validation (No changes needed) ---
    function toggleTimeInputs(isFullDay) {
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        if (isFullDay) {
            if (!startTimeInput.dataset.originalTime) {
                startTimeInput.dataset.originalTime = startTimeInput.value;
            }
            if (!endTimeInput.dataset.originalTime) {
                endTimeInput.dataset.originalTime = endTimeInput.value;
            }
            startTimeInput.value = '00:00';
            endTimeInput.value = '23:59';
            startTimeInput.disabled = true;
            endTimeInput.disabled = true;
        } else {
            if (startTimeInput.dataset.originalTime) {
                startTimeInput.value = startTimeInput.dataset.originalTime;
            }
            if (endTimeInput.dataset.originalTime) {
                endTimeInput.value = endTimeInput.dataset.originalTime;
            }
            startTimeInput.disabled = false;
            endTimeInput.disabled = false;
        }
    }
    function validateDates() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        if (!startDateInput.value || !endDateInput.value) return;
        const startDate = new Date(`${startDateInput.value}T${startTimeInput.value || '00:00'}`);
        const endDate = new Date(`${endDateInput.value}T${endTimeInput.value || '23:59'}`);
        if (endDate < startDate) {
            alert('Tanggal selesai tidak boleh sebelum tanggal mulai');
            endDateInput.value = startDateInput.value;
            if (startDateInput.value === endDateInput.value) {
                const startTime = startTimeInput.value || '00:00';
                const [hours, minutes] = startTime.split(':').map(Number);
                let endHours = hours;
                let endMinutes = minutes + 30;
                if (endMinutes >= 60) {
                    endHours += 1;
                    endMinutes -= 60;
                }
                endTimeInput.value = `${endHours.toString().padStart(2, '0')}:${endMinutes.toString().padStart(2, '0')}`;
            }
        }
        // Update subtask limits after main task date changes
        setSubtaskDateLimits();
    }

    // --- Subtask Scroll Indicator (No changes needed) ---
    function setupSubtaskScrollIndicator() {
        const container = document.getElementById('subtasks-container');
        const indicator = document.getElementById('scroll-indicator');
        if (!container || !indicator) return;
        container.addEventListener('scroll', function() {
            if (this.scrollLeft > 0) {
                indicator.classList.remove('hidden');
            } else {
                indicator.classList.add('hidden');
            }
        });
    }

    // --- Subtask Functions (Corrected & Updated) ---

    // Helper to format date for display (YYYY-MM-DD to DD/MM/YYYY)
    function formatDateDisplay(dateString) {
        if (!dateString) return '';
        const parts = dateString.split('-');
        if (parts.length === 3) {
            return `${parts[2]}/${parts[1]}/${parts[0]}`; // DD/MM/YYYY
        }
        return dateString; // Fallback if format is unexpected
    }

    // Helper to get parent dates (returns YYYY-MM-DD format for inputs)
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

    // Function to set date limits for all subtasks based on their parents
    function setSubtaskDateLimits() {
        document.querySelectorAll('.subtask-item').forEach(item => {
            const parentIdInput = item.querySelector('input[name$="[parent_id]"]');
            const parentId = parentIdInput ? parentIdInput.value : null;
            const { parentStartDate, parentEndDate } = getParentDates(parentId);
            const startInput = item.querySelector('input[name$="[start_date]"]');
            const endInput = item.querySelector('input[name$="[end_date]"]');
            const dateDisplaySpan = item.querySelector('.subtask-date span'); // Get the display span

            if (startInput) {
                startInput.min = parentStartDate;
                startInput.max = parentEndDate;
                // Ensure current value is within bounds
                if (startInput.value && startInput.value < parentStartDate) startInput.value = parentStartDate;
                if (startInput.value && startInput.value > parentEndDate) startInput.value = parentEndDate;
            }
            if (endInput) {
                endInput.min = parentStartDate;
                endInput.max = parentEndDate;
                if (endInput.value && endInput.value < parentStartDate) endInput.value = parentStartDate;
                if (endInput.value && endInput.value > parentEndDate) endInput.value = parentEndDate;
            }

            // Update the displayed date range
            if (dateDisplaySpan) {
                 const displayStart = formatDateDisplay(startInput?.value || parentStartDate);
                 const displayEnd = formatDateDisplay(endInput?.value || parentEndDate);
                 dateDisplaySpan.textContent = `${displayStart} - ${displayEnd}`;
            }
        });
    }

    // Function to update date limits for children when a parent's dates change
    function updateChildSubtaskLimits(parentSubtaskId) {
        const parentItem = document.querySelector(`.subtask-item[data-id="${parentSubtaskId}"]`);
        if (!parentItem) return;
        const parentStartInput = parentItem.querySelector('input[name$="[start_date]"]');
        const parentEndInput = parentItem.querySelector('input[name$="[end_date]"]');
        const parentStartDate = parentStartInput ? parentStartInput.value : '';
        const parentEndDate = parentEndInput ? parentEndInput.value : '';
        if (!parentStartDate || !parentEndDate) return; // Skip if parent dates are not set

        // Find direct children
        const childSubtasks = document.querySelectorAll(`input[name$="[parent_id]"][value="${parentSubtaskId}"]`);
        childSubtasks.forEach(childInput => {
            const childItem = childInput.closest('.subtask-item');
            if (!childItem) return;
            const childStartInput = childItem.querySelector('input[name$="[start_date]"]');
            const childEndInput = childItem.querySelector('input[name$="[end_date]"]');
            if (childStartInput) {
                childStartInput.min = parentStartDate;
                childStartInput.max = parentEndDate;
                if (childStartInput.value < parentStartDate) childStartInput.value = parentStartDate;
                if (childStartInput.value > parentEndDate) childStartInput.value = parentEndDate;
            }
            if (childEndInput) {
                childEndInput.min = parentStartDate;
                childEndInput.max = parentEndDate;
                if (childEndInput.value < parentStartDate) childEndInput.value = parentStartDate;
                if (childEndInput.value > parentEndDate) childEndInput.value = parentEndDate;
            }
            // Update the displayed date range for the child
            const childDateDisplaySpan = childItem.querySelector('.subtask-date span');
            if (childDateDisplaySpan) {
                 const displayStart = formatDateDisplay(childStartInput?.value || parentStartDate);
                 const displayEnd = formatDateDisplay(childEndInput?.value || parentEndDate);
                 childDateDisplaySpan.textContent = `${displayStart} - ${displayEnd}`;
            }
            // Recursively update grandchildren
            const childId = childItem.dataset.id;
            if (childId) {
                updateChildSubtaskLimits(childId);
            }
        });
    }

    // Function to add a new subtask
    function addSubtask(parentId) {
        const subtasksContainer = document.querySelector('.subtasks-scroll-container');
        const noSubtasksMessage = document.getElementById('no-subtasks');
        if (noSubtasksMessage) {
            noSubtasksMessage.style.display = 'none';
        }
        const subtaskId = 'new-subtask-' + Date.now(); // Unique ID for new subtasks
        let level = 0;
        if (parentId) {
            const parentItem = document.querySelector(`.subtask-item[data-id="${parentId}"]`);
            if (parentItem) {
                level = parseInt(parentItem.dataset.level || 0) + 1;
                if (level >= 6) {
                    alert('Maksimal level subtask adalah 6');
                    return;
                }
            }
        }
        const { parentStartDate, parentEndDate } = getParentDates(parentId);
        const displayParentStart = formatDateDisplay(parentStartDate);
        const displayParentEnd = formatDateDisplay(parentEndDate);

        const subtaskElement = document.createElement('div');
        subtaskElement.className = `subtask-item bg-white rounded-lg border border-gray-200 p-4 mb-3 shadow-sm relative`;
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
                                <input type="date" name="subtasks[${subtaskId}][start_date]"
                                    min="${parentStartDate}" max="${parentEndDate}"
                                    value="${parentStartDate}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent start-date-input">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Tanggal Selesai</label>
                            <div class="relative">
                                <input type="date" name="subtasks[${subtaskId}][end_date]"
                                    min="${parentStartDate}" max="${parentEndDate}"
                                    value="${parentEndDate}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent end-date-input">
                            </div>
                        </div>
                    </div>
                    <div class="subtask-date mt-2">
                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-xs">${displayParentStart} - ${displayParentEnd}</span> {{-- Display formatted dates --}}
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <input type="hidden" name="subtasks[${subtaskId}][parent_id]" value="${parentId || ''}">
                    <button type="button" onclick="addSubtask('${subtaskId}')"
                        class="p-2 text-indigo-600 hover:text-indigo-800 transition-colors" title="Tambah Child">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </button>
                    <button type="button" onclick="removeSubtask('${subtaskId}', false)"
                        class="p-2 text-red-600 hover:text-red-800 transition-colors" title="Hapus">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;
        subtasksContainer.appendChild(subtaskElement);

        // Add event listeners for date changes on the new subtask
        const startDateInput = subtaskElement.querySelector('.start-date-input');
        const endDateInput = subtaskElement.querySelector('.end-date-input');
        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value;
            if (endDateInput.value < this.value) {
                endDateInput.value = this.value;
            }
            // Update displayed date for this subtask
            const dateDisplaySpan = subtaskElement.querySelector('.subtask-date span');
            if(dateDisplaySpan) {
                const displayStart = formatDateDisplay(this.value);
                const displayEnd = formatDateDisplay(endDateInput.value);
                dateDisplaySpan.textContent = `${displayStart} - ${displayEnd}`;
            }
            updateChildSubtaskLimits(subtaskId); // Update children limits
        });
        endDateInput.addEventListener('change', function() {
             // Update displayed date for this subtask
            const dateDisplaySpan = subtaskElement.querySelector('.subtask-date span');
            if(dateDisplaySpan) {
                const displayStart = formatDateDisplay(startDateInput.value);
                const displayEnd = formatDateDisplay(this.value);
                dateDisplaySpan.textContent = `${displayStart} - ${displayEnd}`;
            }
             updateChildSubtaskLimits(subtaskId); // Update children limits
        });
        checkScrollIndicator();
    }

    // Function to remove a subtask (including children)
    function removeSubtask(subtaskId, isExisting = false) {
        // First, recursively remove all child subtasks
        const childSubtasks = document.querySelectorAll(`input[name$="[parent_id]"][value="${subtaskId}"]`);
        childSubtasks.forEach(childInput => {
            const childId = childInput.closest('.subtask-item')?.dataset.id;
            if (childId) {
                 removeSubtask(childId, document.querySelector(`.subtask-item[data-id="${childId}"]`)?.dataset.existing === 'true'); // Pass correct isExisting flag
            }
        });
        // Then remove the subtask itself
        const subtaskElement = document.querySelector(`.subtask-item[data-id="${subtaskId}"]`);
        if (subtaskElement) {
            subtaskElement.remove();
        }
        // Show "no subtasks" message if container is empty
        const subtasksContainer = document.querySelector('.subtasks-scroll-container');
        if (subtasksContainer && subtasksContainer.querySelectorAll('.subtask-item').length === 0) {
            const noSubtasksMessage = document.getElementById('no-subtasks');
            if (noSubtasksMessage) {
                noSubtasksMessage.style.display = 'block';
            }
        }
        checkScrollIndicator();
        // If this is an existing subtask (not new), add to deleted list
        if (isExisting && !subtaskId.startsWith('new-subtask-')) {
            const deletedInput = document.getElementById('deleted_subtasks');
            const deletedIds = deletedInput.value ? deletedInput.value.split(',') : [];
            if (!deletedIds.includes(subtaskId)) {
                deletedIds.push(subtaskId);
                deletedInput.value = deletedIds.join(',');
            }
        }
    }
    function checkScrollIndicator() {
        const container = document.getElementById('subtasks-container');
        const scrollContainer = document.querySelector('.subtasks-scroll-container');
        const indicator = document.getElementById('scroll-indicator');
        if (container && scrollContainer && indicator) {
             if (scrollContainer.scrollWidth > container.clientWidth) {
                indicator.classList.remove('hidden');
            } else {
                indicator.classList.add('hidden');
            }
        }
    }

    // --- Initialize Event Listeners (Corrected) ---
    document.addEventListener('DOMContentLoaded', function() {
        // Time picker event listeners
        document.querySelectorAll('.time-picker-input').forEach(input => {
            input.addEventListener('focus', function() {
                openTimePicker(this);
            });
        });
        document.getElementById('close-time-picker').addEventListener('click', closeTimePicker);
        document.getElementById('cancel-time').addEventListener('click', closeTimePicker);
        document.getElementById('ok-time').addEventListener('click', setTimeFromPicker);

        // Full day toggle
        const fullDayToggle = document.getElementById('full_day_toggle');
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        startTimeInput.dataset.originalTime = startTimeInput.value;
        endTimeInput.dataset.originalTime = endTimeInput.value;
        if (startTimeInput.value === '00:00' && endTimeInput.value === '23:59') {
            fullDayToggle.checked = true;
            toggleTimeInputs(true);
        }
        fullDayToggle.addEventListener('change', function() {
            toggleTimeInputs(this.checked);
        });

        // Date change validation
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        [startDateInput, endDateInput].forEach(input => {
            input.addEventListener('change', validateDates);
        });

        // Initialize subtask scroll indicator
        setupSubtaskScrollIndicator();

        // Set initial date limits for subtasks
        setSubtaskDateLimits();

        // Add event listeners for existing subtask date changes
        document.querySelectorAll('.subtask-item .start-date-input').forEach(input => {
             input.addEventListener('change', function() {
                 const subtaskItem = this.closest('.subtask-item');
                 const subtaskId = subtaskItem.dataset.id;
                 const endDateInput = subtaskItem.querySelector('.end-date-input');
                 endDateInput.min = this.value;
                 if(endDateInput.value < this.value) endDateInput.value = this.value;

                 // Update displayed date for this subtask
                 const dateDisplaySpan = subtaskItem.querySelector('.subtask-date span');
                 if(dateDisplaySpan) {
                     const displayStart = formatDateDisplay(this.value);
                     const displayEnd = formatDateDisplay(endDateInput.value);
                     dateDisplaySpan.textContent = `${displayStart} - ${displayEnd}`;
                 }

                 updateChildSubtaskLimits(subtaskId);
             });
        });
        document.querySelectorAll('.subtask-item .end-date-input').forEach(input => {
             input.addEventListener('change', function() {
                 const subtaskItem = this.closest('.subtask-item');
                 const subtaskId = subtaskItem.dataset.id;

                 // Update displayed date for this subtask
                 const startDateInput = subtaskItem.querySelector('.start-date-input');
                 const dateDisplaySpan = subtaskItem.querySelector('.subtask-date span');
                 if(dateDisplaySpan) {
                     const displayStart = formatDateDisplay(startDateInput.value);
                     const displayEnd = formatDateDisplay(this.value);
                     dateDisplaySpan.textContent = `${displayStart} - ${displayEnd}`;
                 }

                 updateChildSubtaskLimits(subtaskId);
             });
        });
    });

    // --- Form submission handling (No changes needed) ---
    document.getElementById('task-form').addEventListener('submit', function(e) {
        validateDates(); // Validate main task dates
        // Check if end date/time is before start date/time
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        if (startDateInput.value && endDateInput.value) {
            const startDate = new Date(`${startDateInput.value}T${startTimeInput.value || '00:00'}`);
            const endDate = new Date(`${endDateInput.value}T${endTimeInput.value || '23:59'}`);
            if (endDate < startDate) {
                e.preventDefault();
                alert('Tanggal selesai tidak boleh sebelum tanggal mulai');
                return false;
            }
        }
        return true;
    });

    // Make functions globally accessible
    window.addSubtask = addSubtask;
    window.removeSubtask = removeSubtask;
    window.toggleSubtaskCollapse = function(){}; // Placeholder if needed elsewhere
});
</script>
@endpush