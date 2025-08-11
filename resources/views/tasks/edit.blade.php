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
            @if ($task->user_id !== Auth::id())
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                <strong>Mode Kolaborator:</strong> Perubahan yang Anda buat akan dikirim untuk review oleh
                                pemilik task (<strong>{{ $task->user->name }}</strong>) sebelum diterapkan.
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
                                    <div id="priority-container" class="grid grid-cols-2 gap-3">
                                        <!-- Urgent Priority -->
                                        <label class="relative priority-option group">
                                            <input type="radio" name="priority" value="urgent" required
                                                class="sr-only peer"
                                                {{ old('priority', $task->priority) == 'urgent' ? 'checked' : '' }}>
                                            <div
                                                class="px-4 py-3 bg-red-50 border border-red-200 rounded-xl cursor-pointer transition-all duration-500 ease-in-out peer-checked:ring-2 peer-checked:ring-red-400 peer-checked:ring-opacity-50 peer-checked:border-red-400 peer-checked:bg-red-100 hover:border-red-300 hover:bg-red-75 hover:shadow-sm peer-checked:shadow-md transform-gpu">
                                                <div class="flex items-center justify-center">
                                                    <div
                                                        class="w-3 h-3 bg-red-500 rounded-full mr-2 transition-all duration-400 ease-in-out peer-checked:scale-110 peer-checked:shadow-sm">
                                                    </div>
                                                    <span
                                                        class="text-sm font-medium text-red-700 transition-colors duration-300 ease-in-out peer-checked:text-red-800">Urgent</span>
                                                </div>
                                                <!-- Priority Level Dots -->
                                                <div class="flex justify-center gap-1 mt-2">
                                                    <div
                                                        class="w-1.5 h-1.5 bg-red-500 rounded-full transition-all duration-300 ease-in-out">
                                                    </div>
                                                    <div
                                                        class="w-1.5 h-1.5 bg-red-500 rounded-full transition-all duration-300 ease-in-out delay-75">
                                                    </div>
                                                    <div
                                                        class="w-1.5 h-1.5 bg-red-500 rounded-full transition-all duration-300 ease-in-out delay-150">
                                                    </div>
                                                    <div
                                                        class="w-1.5 h-1.5 bg-red-500 rounded-full transition-all duration-300 ease-in-out delay-200">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Check Icon with Smooth Animation -->
                                            <div
                                                class="priority-check-icon absolute -top-2 -right-2 w-6 h-6 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center shadow-lg opacity-0 scale-0 peer-checked:opacity-100 peer-checked:scale-100 transition-all duration-400 ease-out delay-100">
                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        </label>

                                        <!-- High Priority -->
                                        <label class="relative priority-option group">
                                            <input type="radio" name="priority" value="high" required
                                                class="sr-only peer"
                                                {{ old('priority', $task->priority) == 'high' ? 'checked' : '' }}>
                                            <div
                                                class="px-4 py-3 bg-yellow-50 border border-yellow-200 rounded-xl cursor-pointer transition-all duration-500 ease-in-out peer-checked:ring-2 peer-checked:ring-yellow-400 peer-checked:ring-opacity-50 peer-checked:border-yellow-400 peer-checked:bg-yellow-100 hover:border-yellow-300 hover:bg-yellow-75 hover:shadow-sm peer-checked:shadow-md transform-gpu">
                                                <div class="flex items-center justify-center">
                                                    <div
                                                        class="w-3 h-3 bg-yellow-500 rounded-full mr-2 transition-all duration-400 ease-in-out peer-checked:scale-110 peer-checked:shadow-sm">
                                                    </div>
                                                    <span
                                                        class="text-sm font-medium text-yellow-700 transition-colors duration-300 ease-in-out peer-checked:text-yellow-800">Tinggi</span>
                                                </div>
                                                <!-- Priority Level Dots -->
                                                <div class="flex justify-center gap-1 mt-2">
                                                    <div
                                                        class="w-1.5 h-1.5 bg-yellow-500 rounded-full transition-all duration-300 ease-in-out">
                                                    </div>
                                                    <div
                                                        class="w-1.5 h-1.5 bg-yellow-500 rounded-full transition-all duration-300 ease-in-out delay-75">
                                                    </div>
                                                    <div
                                                        class="w-1.5 h-1.5 bg-yellow-500 rounded-full transition-all duration-300 ease-in-out delay-150">
                                                    </div>
                                                    <div
                                                        class="w-1.5 h-1.5 bg-gray-300 rounded-full transition-all duration-300 ease-in-out delay-200">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Check Icon with Smooth Animation -->
                                            <div
                                                class="priority-check-icon absolute -top-2 -right-2 w-6 h-6 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center shadow-lg opacity-0 scale-0 peer-checked:opacity-100 peer-checked:scale-100 transition-all duration-400 ease-out delay-100">
                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        </label>

                                        <!-- Medium Priority -->
                                        <label class="relative priority-option group">
                                            <input type="radio" name="priority" value="medium" required
                                                class="sr-only peer"
                                                {{ old('priority', $task->priority) == 'medium' ? 'checked' : '' }}>
                                            <div
                                                class="px-4 py-3 bg-blue-50 border border-blue-200 rounded-xl cursor-pointer transition-all duration-500 ease-in-out peer-checked:ring-2 peer-checked:ring-blue-400 peer-checked:ring-opacity-50 peer-checked:border-blue-400 peer-checked:bg-blue-100 hover:border-blue-300 hover:bg-blue-75 hover:shadow-sm peer-checked:shadow-md transform-gpu">
                                                <div class="flex items-center justify-center">
                                                    <div
                                                        class="w-3 h-3 bg-blue-500 rounded-full mr-2 transition-all duration-400 ease-in-out peer-checked:scale-110 peer-checked:shadow-sm">
                                                    </div>
                                                    <span
                                                        class="text-sm font-medium text-blue-700 transition-colors duration-300 ease-in-out peer-checked:text-blue-800">Sedang</span>
                                                </div>
                                                <!-- Priority Level Dots -->
                                                <div class="flex justify-center gap-1 mt-2">
                                                    <div
                                                        class="w-1.5 h-1.5 bg-blue-500 rounded-full transition-all duration-300 ease-in-out">
                                                    </div>
                                                    <div
                                                        class="w-1.5 h-1.5 bg-blue-500 rounded-full transition-all duration-300 ease-in-out delay-75">
                                                    </div>
                                                    <div
                                                        class="w-1.5 h-1.5 bg-gray-300 rounded-full transition-all duration-300 ease-in-out delay-150">
                                                    </div>
                                                    <div
                                                        class="w-1.5 h-1.5 bg-gray-300 rounded-full transition-all duration-300 ease-in-out delay-200">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Check Icon with Smooth Animation -->
                                            <div
                                                class="priority-check-icon absolute -top-2 -right-2 w-6 h-6 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center shadow-lg opacity-0 scale-0 peer-checked:opacity-100 peer-checked:scale-100 transition-all duration-400 ease-out delay-100">
                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        </label>

                                        <!-- Low Priority -->
                                        <label class="relative priority-option group">
                                            <input type="radio" name="priority" value="low" required
                                                class="sr-only peer"
                                                {{ old('priority', $task->priority) == 'low' ? 'checked' : '' }}>
                                            <div
                                                class="px-4 py-3 bg-green-50 border border-green-200 rounded-xl cursor-pointer transition-all duration-500 ease-in-out peer-checked:ring-2 peer-checked:ring-green-400 peer-checked:ring-opacity-50 peer-checked:border-green-400 peer-checked:bg-green-100 hover:border-green-300 hover:bg-green-75 hover:shadow-sm peer-checked:shadow-md transform-gpu">
                                                <div class="flex items-center justify-center">
                                                    <div
                                                        class="w-3 h-3 bg-green-500 rounded-full mr-2 transition-all duration-400 ease-in-out peer-checked:scale-110 peer-checked:shadow-sm">
                                                    </div>
                                                    <span
                                                        class="text-sm font-medium text-green-700 transition-colors duration-300 ease-in-out peer-checked:text-green-800">Rendah</span>
                                                </div>
                                                <!-- Priority Level Dots -->
                                                <div class="flex justify-center gap-1 mt-2">
                                                    <div
                                                        class="w-1.5 h-1.5 bg-green-500 rounded-full transition-all duration-300 ease-in-out">
                                                    </div>
                                                    <div
                                                        class="w-1.5 h-1.5 bg-gray-300 rounded-full transition-all duration-300 ease-in-out delay-75">
                                                    </div>
                                                    <div
                                                        class="w-1.5 h-1.5 bg-gray-300 rounded-full transition-all duration-300 ease-in-out delay-150">
                                                    </div>
                                                    <div
                                                        class="w-1.5 h-1.5 bg-gray-300 rounded-full transition-all duration-300 ease-in-out delay-200">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Check Icon with Smooth Animation -->
                                            <div
                                                class="priority-check-icon absolute -top-2 -right-2 w-6 h-6 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center shadow-lg opacity-0 scale-0 peer-checked:opacity-100 peer-checked:scale-100 transition-all duration-400 ease-out delay-100">
                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        </label>
                                    </div>
                                    @error('priority')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @endif
                                </div>

                                <!-- Custom CSS for Priority -->
                                <style>
                                    .priority-option:hover .priority-check-icon {
                                        opacity: 0.3;
                                        scale: 0.8;
                                    }

                                    .priority-option .peer:checked ~ div {
                                        animation: gentle-pulse 0.6s ease-out;
                                    }

                                    @keyframes gentle-pulse {
                                        0% {
                                            transform: scale(1);
                                        }

                                        50% {
                                            transform: scale(1.01);
                                        }

                                        100% {
                                            transform: scale(1);
                                        }
                                    }

                                    .hover\:bg-red-75:hover {
                                        background-color: rgb(254 242 242);
                                    }

                                    .hover\:bg-yellow-75:hover {
                                        background-color: rgb(255 251 235);
                                    }

                                    .hover\:bg-blue-75:hover {
                                        background-color: rgb(239 246 255);
                                    }

                                    .hover\:bg-green-75:hover {
                                        background-color: rgb(240 253 244);
                                    }
                                </style>
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
                                                value="{{ old('start_date', $task->start_date ? $task->start_date->format('Y-m-d') : now()->format('Y-m-d')) }}"
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
                                                class="time-picker-input w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 @error('start_time') border-red-500 @endif">
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
                                </div>

                                <!-- End Date & Time -->
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal Selesai <span class="text-red-500">*</span>
                                    </label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="relative">
                                            <input id="end_date" name="end_date" type="date" required
                                                value="{{ old('end_date', $task->end_date ? $task->end_date->format('Y-m-d') : now()->format('Y-m-d')) }}"
                                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 @error('end_date') border-red-500 @endif">
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
                                            <input id="end_time" name="end_time" type="text"
                                                value="{{ old('end_time', $task->end_time ? $task->end_time->format('H:i') : '23:59') }}"
                                                class="time-picker-input w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 @error('end_time') border-red-500 @endif">
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
                                </div>
                            </div>

                            <!-- Full Day Button -->
                            <div class="mt-4">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="full_day_toggle" name="full_day" value="1"
                                        class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500"
                                        {{ old('full_day', $task->is_all_day) ? 'checked' : '' }}>
                                    <span class="ml-2 text-gray-700 text-sm">Sehari Penuh</span>
                                </label>
                                @error('full_day')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Description Section -->
                        <div class="mb-8">
                            <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                                <div class="w-2 h-2 bg-purple-500 rounded-full mr-3"></div>
                                Deskripsi
                            </h2>

                            <div class="relative">
                                <textarea id="description" name="description" rows="4"
                                    placeholder="Masukkan deskripsi detail tugas Anda..."
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
                                        <button type="button" onclick="addSubtask(null)"
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
                                        @if ($task->subTasks->count() > 0)
                                            @php
                                                $subtasksByParent = $task->subTasks->groupBy('parent_id');
                                                $rootSubtasks = $subtasksByParent->get(null, collect());
                                            @endphp
                                            @foreach ($rootSubtasks as $subtask)
                                                @include('tasks.partials.subtask-item', [
                                                    'subtask' => $subtask,
                                                    'subtasksByParent' => $subtasksByParent,
                                                    'level' => 0,
                                                    'task' => $task,
                                                ])
                                            @endforeach
                                        @else
                                            <div class="text-center text-gray-500 text-sm py-8" id="no-subtasks">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 17v-2a4 4 0 00-4-4H5a4 4 0 000 8h1a4 4 0 004-4zm0 0h6m0 0v2a4 4 0 004 4h1a4 4 0 000-8h-1a4 4 0 00-4 4v2" />
                                                    </svg>
                                                    <span class="text-gray-400 text-xs mt-1">Klik tombol "Tambah Subtask"
                                                        untuk mulai menambahkan</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- Hidden input for deleted subtasks -->
                            <input type="hidden"
                                            name="deleted_subtasks" id="deleted_subtasks" value="">
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Update Task
                                    </button>
                                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Time Picker Modal -->
    <div id="time-picker-modal"
        class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4 overflow-y-auto">
        <div
            class="bg-white rounded-xl shadow-2xl w-full max-w-sm sm:max-w-md max-h-[90vh] overflow-y-auto scrollbar-custom">
            <div class="p-6 bg-gradient-to-b from-blue-50 to-white">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Pilih Waktu</h3>
                    <button id="close-time-picker" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <div class="flex justify-center items-center gap-8 mb-6">
                    <!-- Hour List -->
                    <div class="relative flex flex-col items-center">
                        <label class="text-xs font-medium text-gray-600 mb-2">Jam</label>
                        <div
                            class="w-28 h-64 overflow-y-auto border border-gray-300 rounded-lg bg-white shadow-sm scrollbar-custom">
                            <div id="hour-list" class="flex flex-col items-center py-4"></div>
                        </div>
                    </div>
                    <!-- Minute List -->
                    <div class="relative flex flex-col items-center">
                        <label class="text-xs font-medium text-gray-600 mb-2">Menit</label>
                        <div
                            class="w-28 h-64 overflow-y-auto border border-gray-300 rounded-lg bg-white shadow-sm scrollbar-custom">
                            <div id="minute-list" class="flex flex-col items-center py-4"></div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <button id="cancel-time"
                        class="px-4 py-2 text-gray-700 font-medium bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">Batal</button>
                    <button id="ok-time"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">OK</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Scrollbar Styles */
        .scrollbar-custom {
            scrollbar-width: thin;
            scrollbar-color: #3B82F6 #E5E7EB;
            -webkit-overflow-scrolling: touch;
            /* Dukungan scroll sentuh untuk mobile */
        }

        .scrollbar-custom::-webkit-scrollbar {
            width: 10px;
            /* Lebar scrollbar lebih besar untuk visibilitas */
        }

        .scrollbar-custom::-webkit-scrollbar-track {
            background: #F1F5F9;
            /* Warna track terang */
            border-radius: 8px;
            margin: 8px 0;
            /* Margin untuk estetika */
        }

        .scrollbar-custom::-webkit-scrollbar-thumb {
            background: #3B82F6;
            /* Warna thumb biru cerah */
            border-radius: 8px;
            border: 2px solid #F1F5F9;
            /* Border untuk kontras */
        }

        .scrollbar-custom::-webkit-scrollbar-thumb:hover {
            background: #2563EB;
            /* Warna lebih gelap saat hover */
        }

        /* Time Picker Styles */
        .time-option {
            padding: 12px 16px;
            /* Padding lebih besar untuk kemudahan sentuh */
            text-align: center;
            cursor: pointer;
            font-size: 1.1rem;
            /* Ukuran font lebih besar */
            transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out, transform 0.1s ease-in-out;
            width: 100%;
            /* Memastikan opsi memenuhi lebar container */
        }

        .time-option:hover {
            background-color: #EFF6FF;
            transform: scale(1.03);
            /* Efek zoom kecil saat hover */
        }

        .time-option.selected {
            background-color: #DBEAFE;
            color: #1E40AF;
            font-weight: 600;
            transform: scale(1.03);
            /* Efek zoom untuk opsi terpilih */
        }

        .time-option:focus {
            outline: 2px solid #3B82F6;
            /* Fokus jelas untuk keyboard */
            outline-offset: 2px;
        }

        /* Smooth Scroll Behavior for Time Lists */
        #hour-list,
        #minute-list {
            scroll-behavior: smooth;
            /* Scroll mulus */
            scroll-snap-type: y mandatory;
            /* Snap ke opsi untuk pengalaman lebih baik */
        }

        .time-option {
            scroll-snap-align: center;
            /* Opsi snap ke tengah saat scroll */
        }

        /* Modal Transition */
        #time-picker-modal {
            transition: opacity 0.2s ease-in-out, transform 0.2s ease-in-out;
        }

        /* Disabled Input Styling */
        .time-picker-input:disabled {
            background-color: #F3F4F6;
            cursor: not-allowed;
            opacity: 0.7;
        }

        /* Touch Support for Mobile */
        @media (hover: none) {
            .time-option:active {
                background-color: #DBEAFE;
                transform: scale(1.03);
            }
        }

        /* Ensure modal content is scrollable */
        #time-picker-modal>div {
            -webkit-overflow-scrolling: touch;
            /* Scroll sentuh untuk modal */
        }

        /* Allow background scrolling (optional, uncomment if needed) */
        /*
                                                body.modal-open {
                                                    overflow: auto !important;
                                                }
                                                */
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let subtaskIdCounter = {{ $task->subTasks ? $task->subTasks->count() : 0 }};
            let currentTimeInput = null;
            let deletedSubtasks = [];

            // --- Priority Selection Enhancement ---
            function setupPrioritySelection() {
                const priorityOptions = document.querySelectorAll('.priority-option');
                const selectedPriority = "{{ old('priority', $task->priority) }}";
                if (selectedPriority) {
                    const selectedOption = document.querySelector(
                        `input[name="priority"][value="${selectedPriority}"]`);
                    if (selectedOption) {
                        selectedOption.checked = true;
                        highlightSelectedPriority(selectedOption);
                    }
                }

                priorityOptions.forEach(option => {
                    const radioInput = option.querySelector('input[type="radio"]');
                    option.addEventListener('click', function() {
                        priorityOptions.forEach(opt => {
                            opt.classList.remove('ring-2', 'ring-offset-2');
                            const icon = opt.querySelector('.priority-check-icon');
                            if (icon) icon.classList.add('hidden');
                        });
                        highlightSelectedPriority(radioInput);
                        option.classList.add('transform', 'scale-95');
                        setTimeout(() => option.classList.remove('transform', 'scale-95'), 150);
                    });

                    radioInput.addEventListener('change', function() {
                        if (this.checked) highlightSelectedPriority(this);
                    });
                });
            }

            function highlightSelectedPriority(radioInput) {
                if (!radioInput) return;
                const parentOption = radioInput.closest('.priority-option');
                if (!parentOption) return;
                parentOption.classList.add('ring-2', 'ring-offset-2');
                const icon = parentOption.querySelector('.priority-check-icon');
                if (icon) icon.classList.remove('hidden');
                const priority = radioInput.value;
                let ringColor = 'ring-blue-500';
                if (priority === 'urgent') ringColor = 'ring-red-500';
                if (priority === 'high') ringColor = 'ring-yellow-500';
                if (priority === 'medium') ringColor = 'ring-blue-500';
                if (priority === 'low') ringColor = 'ring-green-500';
                parentOption.classList.add(ringColor);
            }

            // --- Time Picker Functions ---
            function openTimePicker(inputElement) {
                if (inputElement.disabled) return;
                currentTimeInput = inputElement;
                const currentValue = inputElement.value || '00:00';
                const [hours, minutes] = currentValue.split(':').map(Number);
                populateTimeLists(hours, minutes);

                const modal = document.getElementById('time-picker-modal');
                modal.classList.remove('hidden');
                modal.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    modal.classList.remove('opacity-0', 'scale-95');
                    modal.classList.add('opacity-100', 'scale-100');
                }, 50);

                const hourList = document.getElementById('hour-list');
                if (hourList) {
                    const selectedHourElement = hourList.querySelector('.time-option.selected');
                    if (selectedHourElement) {
                        selectedHourElement.focus();
                        scrollToOption(hourList, selectedHourElement);
                    }
                }
            }

            function closeTimePicker() {
                const modal = document.getElementById('time-picker-modal');
                modal.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('opacity-0', 'scale-95', 'opacity-100', 'scale-100');
                    currentTimeInput = null;
                }, 200);
            }

            // Ganti fungsi populateTimeLists dengan yang ini:
            function populateTimeLists(selectedHour, selectedMinute) {
                const hourList = document.getElementById('hour-list');
                const minuteList = document.getElementById('minute-list');
                hourList.innerHTML = '';
                minuteList.innerHTML = '';

                // Buat opsi jam (00-23)
                for (let i = 0; i <= 23; i++) {
                    const hourDiv = document.createElement('div');
                    hourDiv.className =
                        `time-option px-4 py-2 text-center cursor-pointer hover:bg-blue-50 ${
                i === selectedHour ? 'bg-blue-100 font-medium text-blue-700 selected' : ''
            }`;
                    hourDiv.textContent = i.toString().padStart(2, '0');
                    hourDiv.dataset.value = i;
                    hourDiv.addEventListener('click', function() {
                        document.querySelectorAll('#hour-list .time-option').forEach(opt =>
                            opt.classList.remove('bg-blue-100', 'font-medium', 'text-blue-700',
                                'selected')
                        );
                        this.classList.add('bg-blue-100', 'font-medium', 'text-blue-700', 'selected');
                    });
                    hourList.appendChild(hourDiv);
                }

                // BUAT OPSI MENIT (00-59) - PERUBAHAN UTAMA DI SINI
                for (let i = 0; i <= 59; i++) { // Ubah dari i += 5 menjadi i++
                    const minuteDiv = document.createElement('div');
                    minuteDiv.className =
                        `time-option px-4 py-2 text-center cursor-pointer hover:bg-blue-50 ${
                i === selectedMinute ? 'bg-blue-100 font-medium text-blue-700 selected' : ''
            }`;
                    minuteDiv.textContent = i.toString().padStart(2, '0');
                    minuteDiv.dataset.value = i;
                    minuteDiv.addEventListener('click', function() {
                        document.querySelectorAll('#minute-list .time-option').forEach(opt =>
                            opt.classList.remove('bg-blue-100', 'font-medium', 'text-blue-700',
                                'selected')
                        );
                        this.classList.add('bg-blue-100', 'font-medium', 'text-blue-700', 'selected');
                    });
                    minuteList.appendChild(minuteDiv);
                }

                // Scroll ke jam dan menit yang dipilih
                const selectedHourElement = hourList.querySelector(`.time-option[data-value="${selectedHour}"]`);
                const selectedMinuteElement = minuteList.querySelector(
                    `.time-option[data-value="${selectedMinute}"]`);

                if (selectedHourElement) {
                    selectedHourElement.scrollIntoView({
                        block: 'center'
                    });
                }
                if (selectedMinuteElement) {
                    selectedMinuteElement.scrollIntoView({
                        block: 'center'
                    });
                }
            }

            function scrollToOption(list, option) {
                if (!list || !option) return;
                const listHeight = list.offsetHeight;
                const optionHeight = option.offsetHeight;
                const optionTop = option.offsetTop;
                list.scrollTo({
                    top: optionTop - listHeight / 2 + optionHeight / 2,
                    behavior: 'smooth'
                });
            }

            function setTimeFromPicker() {
                if (!currentTimeInput) return;
                const selectedHour = document.querySelector('#hour-list .time-option.selected')?.dataset.value ||
                    '00';
                const selectedMinute = document.querySelector('#minute-list .time-option.selected')?.dataset
                    .value || '00';
                currentTimeInput.value = `${selectedHour.padStart(2, '0')}:${selectedMinute.padStart(2, '0')}`;
                closeTimePicker();
                validateDates();
            }

            // --- Full Day Toggle & Date Validation ---
            function toggleTimeInputs(isFullDay) {
                const startTimeInput = document.getElementById('start_time');
                const endTimeInput = document.getElementById('end_time');

                if (isFullDay) {
                    if (!startTimeInput.dataset.originalTime) {
                        startTimeInput.dataset.originalTime = startTimeInput.value || '00:00';
                    }
                    if (!endTimeInput.dataset.originalTime) {
                        endTimeInput.dataset.originalTime = endTimeInput.value || '23:59';
                    }
                    startTimeInput.value = '00:00';
                    endTimeInput.value = '23:59';
                    startTimeInput.disabled = true;
                    endTimeInput.disabled = true;
                } else {
                    startTimeInput.value = startTimeInput.dataset.originalTime || '00:00';
                    endTimeInput.value = endTimeInput.dataset.originalTime || '23:59';
                    startTimeInput.disabled = false;
                    endTimeInput.disabled = false;
                }
                validateDates();
            }

            function validateDates() {
                const startDateInput = document.getElementById('start_date');
                const endDateInput = document.getElementById('end_date');
                const startTimeInput = document.getElementById('start_time');
                const endTimeInput = document.getElementById('end_time');
                const fullDayToggle = document.getElementById('full_day_toggle');

                if (!startDateInput.value || !endDateInput.value) return;

                const startDateTime = new Date(`${startDateInput.value}T${startTimeInput.value || '00:00'}`);
                const endDateTime = new Date(`${endDateInput.value}T${endTimeInput.value || '23:59'}`);

                if (endDateTime < startDateTime && !fullDayToggle.checked) {
                    showAlert('Tanggal/waktu selesai tidak boleh sebelum tanggal/waktu mulai', 'error');
                    endDateInput.value = startDateInput.value;
                    if (startDateInput.value === endDateInput.value) {
                        const [hours, minutes] = (startTimeInput.value || '00:00').split(':').map(Number);
                        let endHours = hours;
                        let endMinutes = minutes + 30;
                        if (endMinutes >= 60) {
                            endHours += 1;
                            endMinutes -= 60;
                        }
                        if (endHours >= 24) {
                            endHours = 23;
                            endMinutes = 59;
                        }
                        endTimeInput.value =
                            `${endHours.toString().padStart(2, '0')}:${endMinutes.toString().padStart(2, '0')}`;
                    }
                }
                updateAllSubtaskDates();
            }

            function updateAllSubtaskDates() {
                const startDateInput = document.getElementById('start_date');
                const endDateInput = document.getElementById('end_date');
                const newStartDate = startDateInput.value;
                const newEndDate = endDateInput.value;
                let adjusted = false;

                document.querySelectorAll('.subtask-item').forEach(item => {
                    const parentIdInput = item.querySelector('input[name$="[parent_id]"]');
                    const parentId = parentIdInput ? parentIdInput.value : null;
                    const {
                        parentStartDate,
                        parentEndDate
                    } = getParentDates(parentId);

                    const startInput = item.querySelector('input[name$="[start_date]"]');
                    const endInput = item.querySelector('input[name$="[end_date]"]');
                    const titleInput = item.querySelector('input[name$="[title]"]');
                    const dateDisplaySpan = item.querySelector('.subtask-date span');

                    if (startInput && endInput) {
                        startInput.min = parentStartDate;
                        startInput.max = parentEndDate;
                        endInput.min = parentStartDate;
                        endInput.max = parentEndDate;

                        // Sesuaikan tanggal subtask jika di luar rentang
                        if (startInput.value < parentStartDate) {
                            startInput.value = parentStartDate;
                            adjusted = true;
                        }
                        if (startInput.value > parentEndDate) {
                            startInput.value = parentEndDate;
                            adjusted = true;
                        }
                        if (endInput.value < parentStartDate) {
                            endInput.value = parentStartDate;
                            adjusted = true;
                        }
                        if (endInput.value > parentEndDate) {
                            endInput.value = parentEndDate;
                            adjusted = true;
                        }
                        if (endInput.value < startInput.value) {
                            endInput.value = startInput.value;
                            adjusted = true;
                        }

                        if (dateDisplaySpan) {
                            const displayStart = formatDateDisplay(startInput.value);
                            const displayEnd = formatDateDisplay(endInput.value);
                            dateDisplaySpan.textContent = `${displayStart} - ${displayEnd}`;
                        }

                        const subtaskId = item.dataset.id;
                        updateChildSubtaskLimits(subtaskId);
                    }
                });

                if (adjusted) {
                    showAlert('Tanggal subtask telah disesuaikan agar sesuai dengan rentang tanggal tugas utama.',
                        'info');
                }
            }

            // --- Subtask Scroll Indicator ---
            function setupSubtaskScrollIndicator() {
                const container = document.getElementById('subtasks-container');
                const indicator = document.getElementById('scroll-indicator');
                if (!container || !indicator) return;

                container.addEventListener('scroll', function() {
                    indicator.classList.toggle('hidden', this.scrollLeft <= 0);
                });
            }

            // --- Subtask Functions ---
            function formatDateDisplay(dateString) {
                if (!dateString) return '';
                const parts = dateString.split('-');
                if (parts.length === 3) {
                    return `${parts[2]}/${parts[1]}/${parts[0]}`;
                }
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
                return {
                    parentStartDate,
                    parentEndDate
                };
            }

            function setSubtaskDateLimits() {
                document.querySelectorAll('.subtask-item').forEach(item => {
                    const parentIdInput = item.querySelector('input[name$="[parent_id]"]');
                    const parentId = parentIdInput ? parentIdInput.value : null;
                    const {
                        parentStartDate,
                        parentEndDate
                    } = getParentDates(parentId);

                    const startInput = item.querySelector('input[name$="[start_date]"]');
                    const endInput = item.querySelector('input[name$="[end_date]"]');
                    const dateDisplaySpan = item.querySelector('.subtask-date span');

                    if (startInput) {
                        startInput.min = parentStartDate;
                        startInput.max = parentEndDate;
                    }
                    if (endInput) {
                        endInput.min = parentStartDate;
                        endInput.max = parentEndDate;
                    }
                    if (dateDisplaySpan && startInput && endInput) {
                        const displayStart = formatDateDisplay(startInput.value || parentStartDate);
                        const displayEnd = formatDateDisplay(endInput.value || parentEndDate);
                        dateDisplaySpan.textContent = `${displayStart} - ${displayEnd}`;
                    }
                });
            }

            function updateChildSubtaskLimits(parentSubtaskId) {
                const parentItem = document.querySelector(`.subtask-item[data-id="${parentSubtaskId}"]`);
                if (!parentItem) return;

                const parentStartInput = parentItem.querySelector('input[name$="[start_date]"]');
                const parentEndInput = parentItem.querySelector('input[name$="[end_date]"]');
                const parentTitle = parentItem.querySelector('input[name$="[title]"]').value;
                const parentStartDate = parentStartInput ? parentStartInput.value : '';
                const parentEndDate = parentEndInput ? parentEndInput.value : '';

                if (!parentStartDate || !parentEndDate) return;

                let adjusted = false;

                document.querySelectorAll(`input[name$="[parent_id]"][value="${parentSubtaskId}"]`).forEach(
                    childInput => {
                        const childItem = childInput.closest('.subtask-item');
                        if (!childItem) return;

                        const childStartInput = childItem.querySelector('input[name$="[start_date]"]');
                        const childEndInput = childItem.querySelector('input[name$="[end_date]"]');
                        const childTitle = childItem.querySelector('input[name$="[title]"]').value;

                        if (childStartInput) {
                            childStartInput.min = parentStartDate;
                            if (childStartInput.value < parentStartDate) {
                                childStartInput.value = parentStartDate;
                                adjusted = true;
                            }
                            if (childStartInput.value > parentEndDate) {
                                childStartInput.value = parentEndDate;
                                adjusted = true;
                            }
                        }

                        if (childEndInput) {
                            childEndInput.min = parentStartDate;
                            childEndInput.max = parentEndDate;
                            if (childEndInput.value < parentStartDate) {
                                childEndInput.value = parentStartDate;
                                adjusted = true;
                            }
                            if (childEndInput.value > parentEndDate) {
                                childEndInput.value = parentEndDate;
                                adjusted = true;
                            }
                            if (childEndInput.value < childStartInput.value) {
                                childEndInput.value = childStartInput.value;
                                adjusted = true;
                            }
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

                if (adjusted) {
                    showAlert(
                        `Tanggal subtask anak dari '${parentTitle}' telah disesuaikan agar sesuai dengan rentang tanggal subtask induk.`,
                        'info'
                    );
                }
            }

            function addSubtask(parentId) {
                const subtasksContainer = document.querySelector('.subtasks-scroll-container');
                const noSubtasksMessage = document.getElementById('no-subtasks');
                if (noSubtasksMessage) {
                    noSubtasksMessage.style.display = 'none';
                }

                const subtaskId = 'new-subtask-' + Date.now();
                let level = 0;

                if (parentId) {
                    const parentItem = document.querySelector(`.subtask-item[data-id="${parentId}"]`);
                    if (parentItem) {
                        level = parseInt(parentItem.dataset.level || 0) + 1;
                        if (level >= 6) {
                            showAlert('Maksimal level subtask adalah 6', 'warning');
                            return;
                        }
                    }
                }

                const {
                    parentStartDate,
                    parentEndDate
                } = getParentDates(parentId);
                const displayParentStart = formatDateDisplay(parentStartDate);
                const displayParentEnd = formatDateDisplay(parentEndDate);

                const subtaskElement = document.createElement('div');
                subtaskElement.className =
                    `subtask-item bg-white rounded-lg border border-gray-200 p-4 mb-3 shadow-sm relative transition-all duration-200 hover:shadow-md`;
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
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
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
                            <div class="subtask-date mt-2 flex items-center text-xs text-gray-500">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>${displayParentStart} - ${displayParentEnd}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="hidden" name="subtasks[${subtaskId}][parent_id]" value="${parentId || ''}">
                            <button type="button" onclick="addSubtask('${subtaskId}')"
                                class="p-2 text-indigo-600 hover:text-indigo-800 transition-colors duration-200 rounded-full hover:bg-indigo-50"
                                title="Tambah Child">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </button>
                            <button type="button" onclick="removeSubtask('${subtaskId}', false)"
                                class="p-2 text-red-600 hover:text-red-800 transition-colors duration-200 rounded-full hover:bg-red-50"
                                title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                `;

                // ...existing code...
                if (parentId) {
                    // Cari semua subtask dengan parentId yang sama
                    const siblings = Array.from(subtasksContainer.querySelectorAll('.subtask-item'))
                        .filter(item => item.querySelector(`input[name$="[parent_id]"]`).value === parentId);

                    if (siblings.length > 0) {
                        // Sisipkan setelah sibling terakhir
                        siblings[siblings.length - 1].after(subtaskElement);
                    } else {
                        // Sisipkan setelah parent
                        const parentItem = subtasksContainer.querySelector(`.subtask-item[data-id="${parentId}"]`);
                        if (parentItem) {
                            parentItem.after(subtaskElement);
                        } else {
                            subtasksContainer.appendChild(subtaskElement);
                        }
                    }
                } else {
                    // Jika root subtask, cari root terakhir
                    const rootSubtasks = Array.from(subtasksContainer.querySelectorAll('.subtask-item'))
                        .filter(item => !item.querySelector(`input[name$="[parent_id]"]`).value);
                    if (rootSubtasks.length > 0) {
                        rootSubtasks[rootSubtasks.length - 1].after(subtaskElement);
                    } else {
                        subtasksContainer.appendChild(subtaskElement);
                    }
                }
                // ...existing code...

                const startDateInput = subtaskElement.querySelector('.start-date-input');
                const endDateInput = subtaskElement.querySelector('.end-date-input');

                startDateInput.addEventListener('change', function() {
                    endDateInput.min = this.value;
                    if (endDateInput.value < this.value) endDateInput.value = this.value;

                    const dateDisplaySpan = subtaskElement.querySelector('.subtask-date span');
                    if (dateDisplaySpan) {
                        dateDisplaySpan.textContent =
                            `${formatDateDisplay(this.value)} - ${formatDateDisplay(endDateInput.value)}`;
                    }

                    updateChildSubtaskLimits(subtaskId);
                });

                endDateInput.addEventListener('change', function() {
                    const dateDisplaySpan = subtaskElement.querySelector('.subtask-date span');
                    if (dateDisplaySpan) {
                        dateDisplaySpan.textContent =
                            `${formatDateDisplay(startDateInput.value)} - ${formatDateDisplay(this.value)}`;
                    }

                    updateChildSubtaskLimits(subtaskId);
                });

                checkScrollIndicator();
            }

            function removeSubtask(subtaskId, isExisting = false) {
                document.querySelectorAll(`input[name$="[parent_id]"][value="${subtaskId}"]`).forEach(
                    childInput => {
                        const childId = childInput.closest('.subtask-item')?.dataset.id;
                        if (childId) {
                            removeSubtask(childId, document.querySelector(`.subtask-item[data-id="${childId}"]`)
                                ?.dataset.existing === 'true');
                        }
                    });

                const subtaskElement = document.querySelector(`.subtask-item[data-id="${subtaskId}"]`);
                if (subtaskElement) {
                    subtaskElement.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => subtaskElement.remove(), 200);
                }

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

                checkScrollIndicator();
            }

            function checkScrollIndicator() {
                const container = document.getElementById('subtasks-container');
                const scrollContainer = document.querySelector('.subtasks-scroll-container');
                const indicator = document.getElementById('scroll-indicator');
                if (container && scrollContainer && indicator) {
                    indicator.classList.toggle('hidden', scrollContainer.scrollWidth <= container.clientWidth);
                }
            }

            function showAlert(message, type = 'info') {
                const alertDiv = document.createElement('div');
                const colors = {
                    error: 'bg-red-100 border-red-400 text-red-700',
                    success: 'bg-green-100 border-green-400 text-green-700',
                    warning: 'bg-yellow-100 border-yellow-400 text-yellow-700',
                    info: 'bg-blue-100 border-blue-400 text-blue-700'
                };

                alertDiv.className =
                    `fixed top-4 right-4 border-l-4 ${colors[type]} px-4 py-3 rounded shadow-lg z-50 transition-all duration-300 transform translate-x-0 opacity-100`;
                alertDiv.innerHTML = `
                    <div class="flex items-center">
                        <span class="mr-2">${message}</span>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-auto">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                `;

                document.body.appendChild(alertDiv);

                setTimeout(() => {
                    alertDiv.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => alertDiv.remove(), 300);
                }, 3000);
            }

            function validateSubtaskDatesBeforeSubmit() {
                let isValid = true;
                document.querySelectorAll('.subtask-item').forEach(item => {
                    const parentIdInput = item.querySelector('input[name$="[parent_id]"]');
                    const parentId = parentIdInput ? parentIdInput.value : null;
                    const {
                        parentStartDate,
                        parentEndDate
                    } = getParentDates(parentId);

                    const startInput = item.querySelector('input[name$="[start_date]"]');
                    const endInput = item.querySelector('input[name$="[end_date]"]');
                    const titleInput = item.querySelector('input[name$="[title]"]');
                    const title = titleInput ? titleInput.value : 'Subtask';

                    if (startInput && endInput) {
                        if (startInput.value < parentStartDate) {
                            showAlert(
                                `Tanggal mulai subtask '${title}' harus pada atau setelah ${formatDateDisplay(parentStartDate)}.`,
                                'error'
                            );
                            isValid = false;
                        }
                        if (startInput.value > parentEndDate) {
                            showAlert(
                                `Tanggal mulai subtask '${title}' harus pada atau sebelum ${formatDateDisplay(parentEndDate)}.`,
                                'error'
                            );
                            isValid = false;
                        }
                        if (endInput.value < parentStartDate) {
                            showAlert(
                                `Tanggal selesai subtask '${title}' harus pada atau setelah ${formatDateDisplay(parentStartDate)}.`,
                                'error'
                            );
                            isValid = false;
                        }
                        if (endInput.value > parentEndDate) {
                            showAlert(
                                `Tanggal selesai subtask '${title}' harus pada atau sebelum ${formatDateDisplay(parentEndDate)}.`,
                                'error'
                            );
                            isValid = false;
                        }
                        if (endInput.value < startInput.value) {
                            showAlert(
                                `Tanggal selesai subtask '${title}' tidak boleh sebelum tanggal mulai.`,
                                'error'
                            );
                            isValid = false;
                        }
                    }
                });
                return isValid;
            }

            function initializeEventListeners() {
                document.querySelectorAll('.time-picker-input').forEach(input => {
                    input.addEventListener('click', function() {
                        if (!this.disabled) {
                            openTimePicker(this);
                        }
                    });
                });

                document.getElementById('close-time-picker').addEventListener('click', closeTimePicker);
                document.getElementById('cancel-time').addEventListener('click', closeTimePicker);
                document.getElementById('ok-time').addEventListener('click', setTimeFromPicker);

                const fullDayToggle = document.getElementById('full_day_toggle');
                const startTimeInput = document.getElementById('start_time');
                const endTimeInput = document.getElementById('end_time');

                startTimeInput.dataset.originalTime = startTimeInput.value || '00:00';
                endTimeInput.dataset.originalTime = endTimeInput.value || '23:59';

                if (fullDayToggle.checked) {
                    toggleTimeInputs(true);
                }

                fullDayToggle.addEventListener('change', function() {
                    toggleTimeInputs(this.checked);
                });

                [startTimeInput, endTimeInput, document.getElementById('start_date'), document.getElementById(
                    'end_date')].forEach(
                    input => {
                        input.addEventListener('change', validateDates);
                    }
                );

                document.getElementById('task-form').addEventListener('submit', function(e) {
                    const startDateInput = document.getElementById('start_date');
                    const endDateInput = document.getElementById('end_date');
                    const startTimeInput = document.getElementById('start_time');
                    const endTimeInput = document.getElementById('end_time');
                    const fullDayToggle = document.getElementById('full_day_toggle');

                    if (startDateInput.value && endDateInput.value && !fullDayToggle.checked) {
                        const startDateTime = new Date(
                            `${startDateInput.value}T${startTimeInput.value || '00:00'}`);
                        const endDateTime = new Date(
                            `${endDateInput.value}T${endTimeInput.value || '23:59'}`);

                        if (endDateTime < startDateTime) {
                            e.preventDefault();
                            showAlert('Tanggal/waktu selesai tidak boleh sebelum tanggal/waktu mulai',
                                'error');
                            return false;
                        }
                    }

                    // Validasi tanggal subtask sebelum submit
                    if (!validateSubtaskDatesBeforeSubmit()) {
                        e.preventDefault();
                        return false;
                    }
                });

                document.querySelectorAll('.subtask-item .start-date-input').forEach(input => {
                    input.addEventListener('change', function() {
                        const subtaskItem = this.closest('.subtask-item');
                        const subtaskId = subtaskItem.dataset.id;
                        const endDateInput = subtaskItem.querySelector('.end-date-input');
                        endDateInput.min = this.value;
                        if (endDateInput.value < this.value) {
                            endDateInput.value = this.value;
                            showAlert(
                                'Tanggal selesai subtask disesuaikan agar tidak sebelum tanggal mulai.',
                                'info');
                        }

                        const dateDisplaySpan = subtaskItem.querySelector('.subtask-date span');
                        if (dateDisplaySpan) {
                            dateDisplaySpan.textContent =
                                `${formatDateDisplay(this.value)} - ${formatDateDisplay(endDateInput.value)}`;
                        }

                        updateChildSubtaskLimits(subtaskId);
                    });
                });

                document.querySelectorAll('.subtask-item .end-date-input').forEach(input => {
                    input.addEventListener('change', function() {
                        const subtaskItem = this.closest('.subtask-item');
                        const subtaskId = subtaskItem.dataset.id;
                        const startDateInput = subtaskItem.querySelector('.start-date-input');

                        const dateDisplaySpan = subtaskItem.querySelector('.subtask-date span');
                        if (dateDisplaySpan) {
                            dateDisplaySpan.textContent =
                                `${formatDateDisplay(startDateInput.value)} - ${formatDateDisplay(this.value)}`;
                        }

                        updateChildSubtaskLimits(subtaskId);
                    });
                });
            }

            setupPrioritySelection();
            initializeEventListeners();
            setupSubtaskScrollIndicator();
            setSubtaskDateLimits();

            window.addSubtask = addSubtask;
            window.removeSubtask = removeSubtask;
            window.showAlert = showAlert;
            window.toggleSubtaskCollapse = function() {};
        });
    </script>
@endpush
