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

            <!-- Success Messages -->
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

            <!-- Collaboration Status Alert for Collaborators -->
            @if($task->user_id !== Auth::id())
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                <strong>Mode Kolaborator:</strong> Perubahan yang Anda buat akan dikirim untuk review oleh pemilik task (<strong>{{ $task->user->name }}</strong>) sebelum diterapkan.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Form Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <form action="{{ route('tasks.update', $task->id) }}" method="POST" id="task-form">
                    @csrf
                    @method('PUT')

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
                                            <input id="start_date" name="start_date" type="text" required
                                                value="{{ old('start_date', $task->start_date ? $task->start_date->format('Y-m-d') : '') }}"
                                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 flatpickr-input"
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
                                                value="{{ old('start_time', $task->start_time ? $task->start_time->format('H:i') : '00:00') }}"
                                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 flatpickr-input"
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
                                </div>

                                <!-- End Date & Time -->
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal Selesai <span class="text-red-500">*</span>
                                    </label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="relative">
                                            <input id="end_date" name="end_date" type="text" required
                                                value="{{ old('end_date', $task->end_date ? $task->end_date->format('Y-m-d') : '') }}"
                                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 flatpickr-input"
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
                                                value="{{ old('end_time', $task->end_time ? $task->end_time->format('H:i') : '23:59') }}"
                                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 flatpickr-input"
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
                                </div>
                            </div>
                        </div>

                        <!-- Full Day Checkbox -->
                        <div class="mb-8">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" 
                                       name="full_day" 
                                       value="1" 
                                       {{ old('full_day', $task->is_all_day) ? 'checked' : '' }}
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
                                    class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 resize-none">{{ old('description', $task->description) }}</textarea>
                                <div class="absolute top-3 left-3 pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h7"></path>
                                    </svg>
                                </div>
                            </div>
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
                                        <button type="button" id="add-root-subtask"
                                            class="inline-flex items-center px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition-colors duration-200 text-sm font-medium shadow-sm">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Tambah Subtask
                                        </button>
                                    </div>
                                </div>
                                <div id="subtasks-container" class="relative p-6 space-y-3 min-h-[120px] bg-gray-50 overflow-x-auto">
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

                        <!-- Hidden inputs -->
                        <input type="hidden" name="deleted_subtasks" id="deleted_subtasks" value="">
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
                            Update Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Notification Container -->
    <div id="notification-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
@endsection

@push('scripts')
<!-- Tambahkan Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let currentUser = @json(Auth::user());
    let taskData = @json($task);
    let isOwner = taskData.user_id === currentUser.id;
    let deletedSubtasks = [];

    // Fungsi notifikasi (tanpa emoji, gunakan SVG)
    function showNotification(message, type = 'info', duration = 3000) {
    const container = document.getElementById('notification-container') || createNotificationContainer();
    const notification = document.createElement('div');
    
    // Gunakan SVG asli alih-alih emoji
    let svgIcon = '';
    if (type === 'success') {
        svgIcon = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`;
    } else if (type === 'error') {
        svgIcon = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>`;
    } else if (type === 'warning') {
        svgIcon = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>`;
    } else {
        svgIcon = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;
    }

    notification.className = `
        transform translate-x-full opacity-0 transition-all duration-300 ease-out
        px-6 py-4 rounded-lg shadow-lg text-white font-medium text-sm
        flex items-center gap-3 max-w-sm
        ${type === 'success' ? 'bg-green-600' : 
          type === 'error' ? 'bg-red-600' : 
          type === 'warning' ? 'bg-yellow-600' : 'bg-blue-600'}
    `;
    notification.innerHTML = `
        ${svgIcon}
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
    window.addSubtask = function(parentId) {
        console.log('addSubtask dipanggil dengan parentId:', parentId);

        const subtasksContainer = document.querySelector('.subtasks-scroll-container');
        const noSubtasksMessage = document.getElementById('no-subtasks');
        if (noSubtasksMessage) noSubtasksMessage.style.display = 'none';

        const subtaskId = 'new-subtask-' + Date.now() + '-' + Math.floor(Math.random() * 1000);
        let level = 0;

        if (parentId) {
            const parentItem = document.querySelector(`.subtask-item[data-id="${parentId}"]`);
            console.log('Parent Item:', parentItem);
            if (!parentItem) {
                showNotification('Gagal: Parent tidak ditemukan', 'error');
                return;
            }
            level = parseInt(parentItem.dataset.level || 0) + 1;
            if (level >= 6) {
                showNotification('Maksimal level subtask adalah 6', 'warning');
                return;
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
                    <button type="button" class="add-child-btn p-2 text-indigo-600 hover:text-indigo-800 transition-colors" 
                            data-parent-id="${subtaskId}" title="Tambah Child">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </button>
                    ` : ''}
                    <button type="button" onclick="removeSubtask('${subtaskId}', false)"
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
            if (!parentItem) {
                showNotification('Parent tidak ditemukan', 'error');
                return;
            }
            let childrenContainer = parentItem.querySelector(`.subtask-children[data-parent="${parentId}"]`);
            if (!childrenContainer) {
                childrenContainer = document.createElement('div');
                childrenContainer.className = 'subtask-children mt-2';
                childrenContainer.dataset.parent = parentId;
                parentItem.appendChild(childrenContainer);
            }
            childrenContainer.appendChild(subtaskElement);
        } else {
            subtasksContainer.appendChild(subtaskElement);
        }

        // Inisialisasi setelah elemen benar-benar di DOM
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
    };

    window.removeSubtask = function(subtaskId, isExisting = false) {
        const childSubtasks = document.querySelectorAll(`input[name$="[parent_id]"][value="${subtaskId}"]`);
        childSubtasks.forEach(childInput => {
            const childItem = childInput.closest('.subtask-item');
            const childId = childItem?.dataset.id;
            const childIsExisting = childItem?.dataset.existing === 'true';
            if (childId) removeSubtask(childId, childIsExisting);
        });

        const subtaskElement = document.querySelector(`.subtask-item[data-id="${subtaskId}"]`);
        if (subtaskElement) subtaskElement.remove();

        const subtasksContainer = document.querySelector('.subtasks-scroll-container');
        if (subtasksContainer && subtasksContainer.querySelectorAll('.subtask-item').length === 0) {
            const noSubtasksMessage = document.getElementById('no-subtasks');
            if (noSubtasksMessage) noSubtasksMessage.style.display = 'block';
        }

        if (isExisting && !subtaskId.startsWith('new-subtask-')) {
            const deletedInput = document.getElementById('deleted_subtasks');
            const deletedIds = deletedInput.value ? deletedInput.value.split(',') : [];
            if (!deletedIds.includes(subtaskId)) {
                deletedIds.push(subtaskId);
                deletedInput.value = deletedIds.join(',');
            }
        }
        showNotification('Subtask dihapus', 'info');
    };

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

    // Event delegation untuk "Tambah Child"
    document.getElementById('subtasks-container').addEventListener('click', function(e) {
        const btn = e.target.closest('.add-child-btn');
        if (btn) {
            const parentId = btn.dataset.parentId;
            addSubtask(parentId);
        }
    });

    // Tombol "Tambah Subtask" di root
    document.getElementById('add-root-subtask').addEventListener('click', function() {
        addSubtask(null);
    });

    // Inisialisasi Flatpickr untuk subtask yang sudah ada
    document.querySelectorAll('.subtask-item').forEach(item => {
        initFlatpickrForSubtask(item);
    });

    // Form submission
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

        if (!isOwner) {
            showNotification('📬 Mengirim usulan perubahan untuk review...', 'info');
        } else {
            showNotification('💾 Menyimpan perubahan...', 'info');
        }
    });

    if (!isOwner) {
        showNotification('🧑‍🤝‍🧑 Mode kolaborator: Perubahan akan dikirim untuk review', 'info', 4000);
    }
});
</script>
@endpush