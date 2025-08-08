@extends('layouts.app')
@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-12">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Header Card -->
            <div class="mb-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                        </path>
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
                                        <input id="title" name="title" type="text" value="{{ old('title') }}"
                                            placeholder="Masukkan judul tugas" required
                                            class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 @error('title') border-red-500 @endif">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    @error('title')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @endif
                                </div>

                                <!-- Category Selection -->
                                <div>
                                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Kategori <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative flex items-center gap-2">
                                        <div class="flex-1 relative">
                                            <select id="category_id" name="category_id" required
                                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 appearance-none @error('category_id') border-red-500 @endif">
                                                <option value="">Pilih Kategori</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                </svg>
                                            </div>
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
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
                                    @error('category_id')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @endif
                                </div>

                                <!-- Priority Selection -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        Prioritas <span class="text-red-500">*</span>
                                    </label>
                                    <div id="priority-container" class="grid grid-cols-2 gap-3">
                                        <!-- Urgent Priority -->
                                        <label class="relative priority-option group">
                                            <input type="radio" name="priority" value="urgent" required class="sr-only peer" {{ old('priority') == 'urgent' ? 'checked' : '' }}>
                                            <div class="px-4 py-3 bg-red-50 border border-red-200 rounded-xl cursor-pointer
                                                        transition-all duration-500 ease-in-out
                                                        peer-checked:ring-2 peer-checked:ring-red-400 peer-checked:ring-opacity-50
                                                        peer-checked:border-red-400 peer-checked:bg-red-100
                                                        hover:border-red-300 hover:bg-red-75 hover:shadow-sm
                                                        peer-checked:shadow-md
                                                        transform-gpu">
                                                <div class="flex items-center justify-center">
                                                    <div class="w-3 h-3 bg-red-500 rounded-full mr-2
                                                                transition-all duration-400 ease-in-out
                                                                peer-checked:scale-110 peer-checked:shadow-sm"></div>
                                                    <span class="text-sm font-medium text-red-700
                                                                transition-colors duration-300 ease-in-out
                                                                peer-checked:text-red-800">Urgent</span>
                                                </div>
                                                <!-- Priority Level Dots -->
                                                <div class="flex justify-center gap-1 mt-2">
                                                    <div class="w-1.5 h-1.5 bg-red-500 rounded-full transition-all duration-300 ease-in-out"></div>
                                                    <div class="w-1.5 h-1.5 bg-red-500 rounded-full transition-all duration-300 ease-in-out delay-75"></div>
                                                    <div class="w-1.5 h-1.5 bg-red-500 rounded-full transition-all duration-300 ease-in-out delay-150"></div>
                                                    <div class="w-1.5 h-1.5 bg-red-500 rounded-full transition-all duration-300 ease-in-out delay-200"></div>
                                                </div>
                                            </div>
                                            <!-- Check Icon with Smooth Animation -->
                                            <div class="priority-check-icon absolute -top-2 -right-2 w-6 h-6
                                                        bg-gradient-to-br from-green-400 to-green-600 rounded-full
                                                        flex items-center justify-center shadow-lg
                                                        opacity-0 scale-0 peer-checked:opacity-100 peer-checked:scale-100
                                                        transition-all duration-400 ease-out delay-100">
                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        </label>

                                        <!-- High Priority -->
                                        <label class="relative priority-option group">
                                            <input type="radio" name="priority" value="high" required class="sr-only peer" {{ old('priority') == 'high' ? 'checked' : '' }}>
                                            <div class="px-4 py-3 bg-yellow-50 border border-yellow-200 rounded-xl cursor-pointer
                                                        transition-all duration-500 ease-in-out
                                                        peer-checked:ring-2 peer-checked:ring-yellow-400 peer-checked:ring-opacity-50
                                                        peer-checked:border-yellow-400 peer-checked:bg-yellow-100
                                                        hover:border-yellow-300 hover:bg-yellow-75 hover:shadow-sm
                                                        peer-checked:shadow-md
                                                        transform-gpu">
                                                <div class="flex items-center justify-center">
                                                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2
                                                                transition-all duration-400 ease-in-out
                                                                peer-checked:scale-110 peer-checked:shadow-sm"></div>
                                                    <span class="text-sm font-medium text-yellow-700
                                                                transition-colors duration-300 ease-in-out
                                                                peer-checked:text-yellow-800">Tinggi</span>
                                                </div>
                                                <!-- Priority Level Dots -->
                                                <div class="flex justify-center gap-1 mt-2">
                                                    <div class="w-1.5 h-1.5 bg-yellow-500 rounded-full transition-all duration-300 ease-in-out"></div>
                                                    <div class="w-1.5 h-1.5 bg-yellow-500 rounded-full transition-all duration-300 ease-in-out delay-75"></div>
                                                    <div class="w-1.5 h-1.5 bg-yellow-500 rounded-full transition-all duration-300 ease-in-out delay-150"></div>
                                                    <div class="w-1.5 h-1.5 bg-gray-300 rounded-full transition-all duration-300 ease-in-out delay-200"></div>
                                                </div>
                                            </div>
                                            <!-- Check Icon with Smooth Animation -->
                                            <div class="priority-check-icon absolute -top-2 -right-2 w-6 h-6
                                                        bg-gradient-to-br from-green-400 to-green-600 rounded-full
                                                        flex items-center justify-center shadow-lg
                                                        opacity-0 scale-0 peer-checked:opacity-100 peer-checked:scale-100
                                                        transition-all duration-400 ease-out delay-100">
                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        </label>

                                        <!-- Medium Priority -->
                                        <label class="relative priority-option group">
                                            <input type="radio" name="priority" value="medium" required class="sr-only peer" {{ old('priority') == 'medium' ? 'checked' : '' }}>
                                            <div class="px-4 py-3 bg-blue-50 border border-blue-200 rounded-xl cursor-pointer
                                                        transition-all duration-500 ease-in-out
                                                        peer-checked:ring-2 peer-checked:ring-blue-400 peer-checked:ring-opacity-50
                                                        peer-checked:border-blue-400 peer-checked:bg-blue-100
                                                        hover:border-blue-300 hover:bg-blue-75 hover:shadow-sm
                                                        peer-checked:shadow-md
                                                        transform-gpu">
                                                <div class="flex items-center justify-center">
                                                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-2
                                                                transition-all duration-400 ease-in-out
                                                                peer-checked:scale-110 peer-checked:shadow-sm"></div>
                                                    <span class="text-sm font-medium text-blue-700
                                                                transition-colors duration-300 ease-in-out
                                                                peer-checked:text-blue-800">Sedang</span>
                                                </div>
                                                <!-- Priority Level Dots -->
                                                <div class="flex justify-center gap-1 mt-2">
                                                    <div class="w-1.5 h-1.5 bg-blue-500 rounded-full transition-all duration-300 ease-in-out"></div>
                                                    <div class="w-1.5 h-1.5 bg-blue-500 rounded-full transition-all duration-300 ease-in-out delay-75"></div>
                                                    <div class="w-1.5 h-1.5 bg-gray-300 rounded-full transition-all duration-300 ease-in-out delay-150"></div>
                                                    <div class="w-1.5 h-1.5 bg-gray-300 rounded-full transition-all duration-300 ease-in-out delay-200"></div>
                                                </div>
                                            </div>
                                            <!-- Check Icon with Smooth Animation -->
                                            <div class="priority-check-icon absolute -top-2 -right-2 w-6 h-6
                                                        bg-gradient-to-br from-green-400 to-green-600 rounded-full
                                                        flex items-center justify-center shadow-lg
                                                        opacity-0 scale-0 peer-checked:opacity-100 peer-checked:scale-100
                                                        transition-all duration-400 ease-out delay-100">
                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        </label>

                                        <!-- Low Priority -->
                                        <label class="relative priority-option group">
                                            <input type="radio" name="priority" value="low" required class="sr-only peer" {{ old('priority') == 'low' ? 'checked' : '' }}>
                                            <div class="px-4 py-3 bg-green-50 border border-green-200 rounded-xl cursor-pointer
                                                        transition-all duration-500 ease-in-out
                                                        peer-checked:ring-2 peer-checked:ring-green-400 peer-checked:ring-opacity-50
                                                        peer-checked:border-green-400 peer-checked:bg-green-100
                                                        hover:border-green-300 hover:bg-green-75 hover:shadow-sm
                                                        peer-checked:shadow-md
                                                        transform-gpu">
                                                <div class="flex items-center justify-center">
                                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2
                                                                transition-all duration-400 ease-in-out
                                                                peer-checked:scale-110 peer-checked:shadow-sm"></div>
                                                    <span class="text-sm font-medium text-green-700
                                                                transition-colors duration-300 ease-in-out
                                                                peer-checked:text-green-800">Rendah</span>
                                                </div>
                                                <!-- Priority Level Dots -->
                                                <div class="flex justify-center gap-1 mt-2">
                                                    <div class="w-1.5 h-1.5 bg-green-500 rounded-full transition-all duration-300 ease-in-out"></div>
                                                    <div class="w-1.5 h-1.5 bg-gray-300 rounded-full transition-all duration-300 ease-in-out delay-75"></div>
                                                    <div class="w-1.5 h-1.5 bg-gray-300 rounded-full transition-all duration-300 ease-in-out delay-150"></div>
                                                    <div class="w-1.5 h-1.5 bg-gray-300 rounded-full transition-all duration-300 ease-in-out delay-200"></div>
                                                </div>
                                            </div>
                                            <!-- Check Icon with Smooth Animation -->
                                            <div class="priority-check-icon absolute -top-2 -right-2 w-6 h-6
                                                        bg-gradient-to-br from-green-400 to-green-600 rounded-full
                                                        flex items-center justify-center shadow-lg
                                                        opacity-0 scale-0 peer-checked:opacity-100 peer-checked:scale-100
                                                        transition-all duration-400 ease-out delay-100">
                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
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
                                        0% { transform: scale(1); }
                                        50% { transform: scale(1.01); }
                                        100% { transform: scale(1); }
                                    }

                                    .hover\:bg-red-75:hover { background-color: rgb(254 242 242); }
                                    .hover\:bg-yellow-75:hover { background-color: rgb(255 251 235); }
                                    .hover\:bg-blue-75:hover { background-color: rgb(239 246 255); }
                                    .hover\:bg-green-75:hover { background-color: rgb(240 253 244); }
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
                                            <input id="start_date" name="start_date" type="date" required value="{{ old('start_date', now()->format('Y-m-d')) }}"
                                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 @error('start_date') border-red-500 @endif">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="relative">
                                            <input id="start_time" name="start_time" type="text" value="{{ old('start_time', '00:00') }}"
                                                class="time-picker-input w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 @error('start_time') border-red-500 @endif">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
                                            <input id="end_date" name="end_date" type="date" required value="{{ old('end_date', now()->format('Y-m-d')) }}"
                                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 @error('end_date') border-red-500 @endif">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="relative">
                                            <input id="end_time" name="end_time" type="text" value="{{ old('end_time', '23:59') }}"
                                                class="time-picker-input w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 @error('end_time') border-red-500 @endif">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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

                            <!-- Full Day Button -->
                            <div class="mt-4">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="full_day_toggle" name="full_day" value="1" class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500" {{ old('full_day') ? 'checked' : '' }}>
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
                                <textarea id="description" name="description" rows="4" placeholder="Masukkan deskripsi detail tugas Anda..."
                                    class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 resize-none @error('description') border-red-500 @endif">{{ old('description') }}</textarea>
                                <div class="absolute top-3 left-3 pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
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
                                            <div
                                                class="bg-gradient-to-r from-indigo-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                                                <div class="flex justify-between items-center">
                                                    <div class="flex items-center">
                                                        <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                                            </path>
                                                        </svg>
                                                        <span class="text-sm font-medium text-indigo-700">Daftar Subtask
                                                            (Maksimal 6 level)</span>
                                                    </div>
                                                    <button type="button" onclick="addSubtask(null)"
                                                        class="inline-flex items-center px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition-colors duration-200 text-sm font-medium shadow-sm">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
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
                                                    <div class="text-center text-gray-500 text-sm py-8" id="no-subtasks">
                                                        <div class="flex flex-col items-center">
                                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 17v-2a4 4 0 00-4-4H5a4 4 0 000 8h1a4 4 0 004-4zm0 0h6m0 0v2a4 4 0 004 4h1a4 4 0 000-8h-1a4 4 0 00-4 4v2" />
                                                            </svg>
                                                            <span class="text-gray-400 text-xs mt-1">Klik tombol "Tambah
                                                                Subtask" untuk mulai menambahkan</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
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

            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
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
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Nama Kategori
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="categories-table-body" class="bg-white divide-y divide-gray-200">
                                        @foreach ($categories as $category)
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
                                <div id="hour-list" class="flex flex-col items-center py-2"></div>
                            </div>
                            <span
                                class="absolute top-1/2 left-1/2 transform -translate-y-1/2 -translate-x-full text-gray-500 text-xl mr-2">:</span>
                        </div>
                        <div class="relative flex flex-col items-center">
                            <div
                                class="w-20 h-40 overflow-y-auto border border-gray-300 rounded-lg bg-white shadow-sm scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100">
                                <div id="minute-list" class="flex flex-col items-center py-2"></div>
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
    </div>

    @push('styles')
        <style>
            /* Scrollbar Styles */
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

            /* Time Picker Styles */
            .time-option {
                padding: 8px;
                text-align: center;
                cursor: pointer;
                transition: background-color 0.2s, color 0.2s;
            }

            .time-option:hover {
                background-color: #EFF6FF;
            }

            .time-option.selected {
                background-color: #DBEAFE;
                color: #1E40AF;
                font-weight: 600;
            }

            /* Subtask Date Styles */
            .subtask-date {
                font-size: 0.7rem;
                color: #6B7280;
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

            /* Time Picker Modal Transition */
            #time-picker-modal {
                transition: opacity 0.2s ease-in-out, transform 0.2s ease-in-out;
            }

            /* Disabled Input Styling */
            .time-picker-input:disabled {
                background-color: #F3F4F6;
                cursor: not-allowed;
                opacity: 0.7;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let subtaskIdCounter = 0;
                let currentTimeInput = null;

                // --- Priority Selection Enhancement ---
                function setupPrioritySelection() {
                    const priorityOptions = document.querySelectorAll('.priority-option');

                    // Highlight selected priority on page load if there's old input
                    const selectedPriority = "{{ old('priority') }}";
                    if (selectedPriority) {
                        const selectedOption = document.querySelector(
                            `input[name="priority"][value="${selectedPriority}"]`);
                        if (selectedOption) {
                            selectedOption.checked = true;
                            highlightSelectedPriority(selectedOption);
                        }
                    }

                    // Add click handlers for all priority options
                    priorityOptions.forEach(option => {
                        const radioInput = option.querySelector('input[type="radio"]');

                        option.addEventListener('click', function() {
                            // Remove highlight from all options
                            priorityOptions.forEach(opt => {
                                opt.classList.remove('ring-2', 'ring-offset-2');
                                const icon = opt.querySelector('.priority-check-icon');
                                if (icon) icon.classList.add('hidden');
                            });

                            // Highlight selected option
                            highlightSelectedPriority(radioInput);

                            // Add visual feedback
                            option.classList.add('transform', 'scale-95');
                            setTimeout(() => {
                                option.classList.remove('transform', 'scale-95');
                            }, 150);
                        });

                        // Change handler for keyboard navigation
                        radioInput.addEventListener('change', function() {
                            if (this.checked) {
                                highlightSelectedPriority(this);
                            }
                        });
                    });
                }

                function highlightSelectedPriority(radioInput) {
                    if (!radioInput) return;

                    const parentOption = radioInput.closest('.priority-option');
                    if (!parentOption) return;

                    // Add highlight classes based on priority
                    parentOption.classList.add('ring-2', 'ring-offset-2');

                    // Show check icon
                    const icon = parentOption.querySelector('.priority-check-icon');
                    if (icon) icon.classList.remove('hidden');

                    // Set ring color based on priority
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
                    if (inputElement.disabled) return; // Prevent opening if disabled
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
                }

                function closeTimePicker() {
                    const modal = document.getElementById('time-picker-modal');
                    modal.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => {
                        modal.classList.add('hidden');
                        modal.classList.remove('opacity-0', 'scale-95', 'opacity-100', 'scale-100');
                        document.body.classList.remove('overflow-hidden');
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
                        // Save original values if not already saved
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
                        // Restore original values
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

                        // Set end time to 30 minutes after start time if on the same day
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

                    // Update subtask date limits
                    setSubtaskDateLimits();
                }

                // --- Subtask Management ---
                function setupSubtaskScrollIndicator() {
                    const container = document.getElementById('subtasks-container');
                    const indicator = document.getElementById('scroll-indicator');

                    if (!container || !indicator) return;

                    container.addEventListener('scroll', function() {
                        indicator.classList.toggle('hidden', this.scrollLeft <= 0);
                    });
                }

                function formatDateDisplay(dateString) {
                    if (!dateString) return '';
                    const parts = dateString.split('-');
                    return parts.length === 3 ? `${parts[2]}/${parts[1]}/${parts[0]}` : dateString;
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
                            if (startInput.value && startInput.value < parentStartDate) startInput.value =
                                parentStartDate;
                            if (startInput.value && startInput.value > parentEndDate) startInput.value =
                                parentEndDate;
                        }

                        if (endInput) {
                            endInput.min = parentStartDate;
                            endInput.max = parentEndDate;
                            if (endInput.value && endInput.value < parentStartDate) endInput.value =
                                parentStartDate;
                            if (endInput.value && endInput.value > parentEndDate) endInput.value =
                                parentEndDate;
                        }

                        if (dateDisplaySpan) {
                            const displayStart = formatDateDisplay(startInput?.value || parentStartDate);
                            const displayEnd = formatDateDisplay(endInput?.value || parentEndDate);
                            dateDisplaySpan.textContent = `${displayStart} - ${displayEnd}`;
                        }
                    });
                }

                function updateChildSubtaskLimits(parentSubtaskId) {
                    const parentItem = document.querySelector(`.subtask-item[data-id="${parentSubtaskId}"]`);
                    if (!parentItem) return;

                    const parentStartInput = parentItem.querySelector('input[name$="[start_date]"]');
                    const parentEndInput = parentItem.querySelector('input[name$="[end_date]"]');
                    const parentStartDate = parentStartInput ? parentStartInput.value : '';
                    const parentEndDate = parentEndInput ? parentEndInput.value : '';

                    if (!parentStartDate || !parentEndDate) return;

                    document.querySelectorAll(`input[name$="[parent_id]"][value="${parentSubtaskId}"]`).forEach(
                        childInput => {
                            const childItem = childInput.closest('.subtask-item');
                            if (!childItem) return;

                            const childStartInput = childItem.querySelector('input[name$="[start_date]"]');
                            const childEndInput = childItem.querySelector('input[name$="[end_date]"]');

                            if (childStartInput) {
                                childStartInput.min = parentStartDate;
                                if (childStartInput.value < parentStartDate) childStartInput.value =
                                    parentStartDate;
                                if (childStartInput.value > parentEndDate) childStartInput.value = parentEndDate;
                            }

                            if (childEndInput) {
                                childEndInput.min = parentStartDate;
                                childEndInput.max = parentEndDate;
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

                function addSubtask(parentId) {
                    const subtasksContainer = document.querySelector('.subtasks-scroll-container');
                    const noSubtasksMessage = document.getElementById('no-subtasks');

                    if (noSubtasksMessage) noSubtasksMessage.style.display = 'none';

                    const subtaskId = 'subtask-' + Date.now();
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>${displayParentStart} - ${displayParentEnd}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="hidden" name="subtasks[${subtaskId}][parent_id]" value="${parentId || ''}">
                        <button type="button" onclick="addSubtask('${subtaskId}')"
                            class="p-2 text-indigo-600 hover:text-indigo-800 transition-colors duration-200 rounded-full hover:bg-indigo-50" title="Tambah Child">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </button>
                        <button type="button" onclick="removeSubtask('${subtaskId}', false)"
                            class="p-2 text-red-600 hover:text-red-800 transition-colors duration-200 rounded-full hover:bg-red-50" title="Hapus">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `;

                    // --- PENAMBAHAN LOGIKA INSERT DI BAWAH PARENT DAN CHILD-NYA ---
                    if (parentId) {
                        // Cari parent
                        const parentItem = document.querySelector(`.subtask-item[data-id="${parentId}"]`);
                        if (parentItem) {
                            // Cari child terakhir dari parent (berdasarkan urutan DOM dan parent_id)
                            let insertAfter = parentItem;
                            let found = true;
                            while (found) {
                                found = false;
                                // Cari child langsung setelah insertAfter
                                const nextSibling = insertAfter.nextElementSibling;
                                if (nextSibling && nextSibling.querySelector(`input[name$="[parent_id]"]`)?.value ===
                                    parentId) {
                                    insertAfter = nextSibling;
                                    found = true;
                                }
                            }
                            insertAfter.after(subtaskElement);
                        } else {
                            subtasksContainer.appendChild(subtaskElement);
                        }
                    } else {
                        subtasksContainer.appendChild(subtaskElement);
                    }

                    // ...existing event listeners for date changes...
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
                            if (childId) removeSubtask(childId, false);
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

                // --- Alert Notification ---
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
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

                // --- Category Management ---
                function openCategoryModal() {
                    document.getElementById('category-modal').classList.remove('hidden');
                }

                function closeCategoryModal() {
                    document.getElementById('category-modal').classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }

                function addCategory() {
                    const nameInput = document.getElementById('new-category-name');
                    const name = nameInput.value.trim();

                    if (!name) {
                        showAlert('Nama kategori tidak boleh kosong', 'error');
                        return;
                    }

                    fetch('{{ route('categories.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                name
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const select = document.getElementById('category_id');
                                const option = document.createElement('option');
                                option.value = data.category.id;
                                option.textContent = data.category.name;
                                select.appendChild(option);

                                const tbody = document.getElementById('categories-table-body');
                                const row = document.createElement('tr');
                                row.dataset.id = data.category.id;
                                row.innerHTML = `
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">${data.category.name}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button type="button" onclick="editCategory(this)" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                        <button type="button" onclick="deleteCategory(${data.category.id})" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </td>
                                `;
                                tbody.appendChild(row);

                                nameInput.value = '';
                                showAlert('Kategori berhasil ditambahkan', 'success');
                            } else {
                                showAlert(data.message || 'Terjadi kesalahan', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showAlert('Terjadi kesalahan saat menambahkan kategori', 'error');
                        });
                }

                function editCategory(button) {
                    const row = button.closest('tr');
                    const id = row.dataset.id;
                    const nameCell = row.querySelector('td:first-child div');
                    const currentName = nameCell.textContent;

                    const newName = prompt('Edit nama kategori:', currentName);
                    if (!newName || newName.trim() === currentName) return;

                    fetch(`/categories/${id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                name: newName.trim()
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                nameCell.textContent = data.category.name;
                                const option = document.querySelector(`#category_id option[value="${id}"]`);
                                if (option) option.textContent = data.category.name;
                                showAlert('Kategori berhasil diperbarui', 'success');
                            } else {
                                showAlert(data.message || 'Terjadi kesalahan', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showAlert('Terjadi kesalahan saat mengedit kategori', 'error');
                        });
                }

                function deleteCategory(id) {
                    if (!confirm('Apakah Anda yakin ingin menghapus kategori ini?')) return;

                    fetch(`/categories/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.querySelector(`#categories-table-body tr[data-id="${id}"]`)?.remove();
                                document.querySelector(`#category_id option[value="${id}"]`)?.remove();
                                showAlert('Kategori berhasil dihapus', 'success');
                            } else {
                                showAlert(data.message || 'Terjadi kesalahan', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showAlert('Terjadi kesalahan saat menghapus kategori', 'error');
                        });
                }

                // --- Initialize Event Listeners ---
                function initializeEventListeners() {
                    // Time picker
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

                    // Full day toggle
                    const fullDayToggle = document.getElementById('full_day_toggle');
                    const startTimeInput = document.getElementById('start_time');
                    const endTimeInput = document.getElementById('end_time');

                    // Initialize original time values
                    startTimeInput.dataset.originalTime = startTimeInput.value || '00:00';
                    endTimeInput.dataset.originalTime = endTimeInput.value || '23:59';

                    // Handle initial state of full day toggle
                    if (fullDayToggle.checked) {
                        toggleTimeInputs(true);
                    }

                    fullDayToggle.addEventListener('change', function() {
                        toggleTimeInputs(this.checked);
                    });

                    // Date and time validation
                    [startTimeInput, endTimeInput, document.getElementById('start_date'), document.getElementById(
                        'end_date')].forEach(input => {
                        input.addEventListener('change', validateDates);
                    });

                    // Form submission validation
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
                    });
                }

                // --- Initialize Everything ---
                setupPrioritySelection();
                initializeEventListeners();
                setupSubtaskScrollIndicator();
                setSubtaskDateLimits();

                // Make functions globally accessible
                window.addSubtask = addSubtask;
                window.removeSubtask = removeSubtask;
                window.openCategoryModal = openCategoryModal;
                window.closeCategoryModal = closeCategoryModal;
                window.addCategory = addCategory;
                window.editCategory = editCategory;
                window.deleteCategory = deleteCategory;
                window.showAlert = showAlert;
            });
        </script>
    @endpush
@endsection
