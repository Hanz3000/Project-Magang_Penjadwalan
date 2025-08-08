@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 p-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">Management Tugas</h1>
                </div>
                <p class="text-gray-600">Kelola tugas dan progres dengan timeline yang jelas dan terstruktur</p>
            </div>
            <div class="flex gap-3">
                <!-- Collaboration Invites Button -->
                <a href="{{ route('collaboration.invites') }}"
                    class="relative bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-4 py-3 rounded-xl font-medium shadow-lg hover:shadow-xl transition-all duration-300 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Kolaborasi
                    <span id="invite-badge" class="hidden absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                </a>
                
                <a href="{{ route('tasks.create') }}"
                    class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-xl font-medium shadow-lg hover:shadow-xl transition-all duration-300 flex items-center gap-2 transform hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tugas Baru
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <!-- Calendar section -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span id="calendar-title">Timeline Proyek</span>
                        </h2>
                        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                            <button id="today-btn" class="px-4 py-2 text-sm bg-white text-blue-600 rounded-lg shadow-sm border border-blue-200 font-medium flex items-center justify-center gap-2 hover:bg-blue-50 transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Hari Ini
                            </button>
                            <div class="flex bg-gray-100 rounded-lg p-1 w-full sm:w-auto">
                                <button id="day-view" class="view-btn flex-1 sm:flex-none px-3 py-2 text-sm text-gray-600 hover:text-blue-600 rounded-md font-medium transition-all duration-200">
                                    Harian
                                </button>
                                <button id="week-view" class="view-btn flex-1 sm:flex-none px-3 py-2 text-sm text-gray-600 hover:text-blue-600 rounded-md font-medium transition-all duration-200">
                                    Mingguan
                                </button>
                                <button id="month-view" class="view-btn flex-1 sm:flex-none px-3 py-2 text-sm bg-white text-blue-600 rounded-md shadow-sm font-medium active-view transition-all duration-200">
                                    Bulanan
                                </button>
                                <button id="year-view" class="view-btn flex-1 sm:flex-none px-3 py-2 text-sm text-gray-600 hover:text-blue-600 rounded-md font-medium transition-all duration-200">
                                    Tahunan
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="calendar-error" class="hidden bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700" id="calendar-error-message">
                                    Gagal memuat kalender. Silakan refresh halaman.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div id="calendar" style="height: 700px;" class="rounded-xl overflow-hidden border border-gray-200 shadow-inner bg-white gantt-timeline"></div>
                    
                    <div id="calendar-fallback" class="hidden mt-4">
                        <div class="text-center py-8 bg-gray-50 rounded-lg">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Kalender tidak tersedia</h3>
                            <p class="mt-1 text-sm text-gray-500">Kami tidak dapat memuat tampilan kalender.</p>
                            <div class="mt-6">
                                <button onclick="window.location.reload()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Refresh Halaman
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Task list section -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 8l2 2 4-4"></path>
                            </svg>
                            List Jadwal dan Tugas
                            <span id="filtered-count" class="hidden ml-2 px-2 py-1 text-xs bg-blue-100 text-blue-600 rounded-full font-medium"></span>
                            <span id="calendar-completion-indicator"
                                class="hidden ml-2 px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full font-medium border border-green-300 animate-pulse">
                                🎉 Semua tugas selesai!
                            </span>
                        </h2>
                        <div class="flex gap-2 w-full sm:w-auto">
                            <button class="filter-btn active flex-1 sm:flex-none px-4 py-2 text-sm bg-blue-100 text-blue-600 hover:bg-blue-200 rounded-xl transition-all duration-200 border border-blue-300 font-medium" data-filter="all">Semua</button>
                            <button class="filter-btn flex-1 sm:flex-none px-4 py-2 text-sm bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl transition-all duration-200 font-medium" data-filter="active">Aktif</button>
                            <button class="filter-btn flex-1 sm:flex-none px-4 py-2 text-sm bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl transition-all duration-200 font-medium" data-filter="completed">Selesai</button>
                        </div>
                    </div>

                    <div class="space-y-4" id="task-list-container">
                        @php
                        function renderSubtasks($subtasks, $parentId = null, $task = null, $level = 0) {
                            $html = '';

                            // Konversi ke collection jika array
                            if (is_array($subtasks)) {
                                $subtasks = collect($subtasks);
                            }

                            // Status label untuk revisi
                            $statusLabels = [
                                'pending' => ['text' => 'Menunggu Review', 'icon' => '⏳', 'class' => 'bg-yellow-100 text-yellow-800 border-yellow-300'],
                                'pending_delete' => ['text' => 'Akan Dihapus', 'icon' => '🗑️', 'class' => 'bg-red-100 text-red-800 border-red-300'],
                                'has_pending' => ['text' => 'Ada Usulan', 'icon' => '📝', 'class' => 'bg-blue-100 text-blue-800 border-blue-300'],
                                'approved' => ['text' => 'Disetujui', 'icon' => '✅', 'class' => 'bg-green-100 text-green-800 border-green-300'],
                                'rejected' => ['text' => 'Ditolak', 'icon' => '❌', 'class' => 'bg-red-100 text-red-800 border-red-300'],
                            ];

                            foreach ($subtasks->where('parent_id', $parentId) as $subTask) {
                                $children = $subtasks->where('parent_id', $subTask['id']);
                                $isParent = $children->isNotEmpty();
                                $indentClass = $level > 0 ? 'ml-' . ($level * 6) : '';
                                $lineClass = $level > 0 ? 'pl-4 border-l-2 border-gray-200' : '';
                                
                                // Styling untuk preview items
                                $previewClass = '';
                                if (isset($subTask['is_preview']) && $subTask['is_preview']) {
                                    if ($subTask['revision_status'] === 'pending_delete') {
                                        $previewClass = 'opacity-50 bg-red-50 border-red-200';
                                    } else {
                                        $previewClass = 'bg-blue-50 border-blue-200';
                                    }
                                }

                                // Render badge revisi jika ada
                                $revisionBadge = '';
                                if (!empty($subTask['revision_status'])) {
                                    $status = $statusLabels[$subTask['revision_status']] ?? $statusLabels['pending'];
                                    $revisionBadge = '<span 
                                        class="revision-status-badge ml-2 px-2 py-0.5 inline-flex items-center gap-1 rounded-full text-xs font-medium border ' . $status['class'] . '"
                                        data-subtask-id="' . $subTask['id'] . '">
                                        ' . $status['icon'] . ' ' . $status['text'] . '
                                        <button type="button" onclick="removeRevisionBadge(this)" class="ml-1 text-gray-500 hover:text-gray-700">×</button>
                                    </span>';
                                }

                                if ($isParent) {
                                    // === PARENT TASK ===
                                    $html .= '<div class="subtask-parent group relative ' . $lineClass . ' ' . $previewClass . '" data-subtask-id="' . $subTask['id'] . '">';

                                    // Garis vertikal untuk hierarki (kecuali level 0)
                                    if ($level > 0) {
                                        $html .= '<div class="absolute left-0 top-0 w-2 h-6 border-l-2 border-b-2 border-gray-300 rounded-bl-md"></div>';
                                    }

                                    // Judul parent dengan tombol toggle
                                    $html .= '<div class="flex items-center gap-3 py-2 ' . $indentClass . '">';
                                    $html .= '<button 
                                        class="subtask-parent-toggle-btn text-gray-400 hover:text-blue-600 transition-all duration-200 p-1 rounded-lg hover:bg-blue-50"
                                        data-subtask-id="' . $subTask['id'] . '" 
                                        data-expanded="true">';
                                    $html .= '<svg class="w-4 h-4 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                              </svg>';
                                    $html .= '</button>';
                                    $html .= '<div class="flex items-center gap-2">';
                                    $html .= '<div class="w-2 h-2 bg-blue-400 rounded-full"></div>';
                                    $html .= '<span class="text-sm font-semibold text-gray-700 subtask-parent-title hover:text-blue-600 cursor-pointer" data-subtask-id="' . $subTask['id'] . '">' . e($subTask['title']) . '</span>';
                                    $html .= '</div>';
                                    $html .= $revisionBadge;
                                    $html .= '</div>';

                                    // Anak-anak (recursive)
                                    $html .= '<div class="subtask-children ml-4 border-l-2 border-gray-200 mt-1" id="subtask-children-' . $subTask['id'] . '">';
                                    $html .= renderSubtasks($subtasks, $subTask['id'], $task, $level + 1);
                                    $html .= '</div>';

                                    $html .= '</div>';
                                } else {
                                    // === LEAF TASK (bukan parent) ===
                                    $checked = $subTask['completed'] ? 'checked' : '';
                                    $textClass = $subTask['completed'] ? 'line-through text-gray-400' : 'text-gray-700';
                                    
                                    // Untuk preview items, tambahkan indikator visual
                                    if (isset($subTask['is_preview']) && $subTask['is_preview']) {
                                        if ($subTask['revision_status'] === 'pending_delete') {
                                            $textClass .= ' line-through opacity-60';
                                        } else {
                                            $textClass .= ' font-medium';
                                        }
                                    }

                                    $html .= '<div class="subtask-item group relative flex items-center gap-3 py-1 px-3 rounded hover:bg-blue-50 ' . $indentClass . ' ' . $lineClass . ' ' . $previewClass . '" data-subtask-id="' . $subTask['id'] . '">';

                                    // Garis vertikal untuk hierarki
                                    if ($level > 0) {
                                        $html .= '<div class="absolute left-0 top-0 w-2 h-6 border-l-2 border-b-2 border-gray-300 rounded-bl-md"></div>';
                                    }

                                    // Form toggle status (hanya untuk items yang bukan preview atau preview yang bukan delete)
                                    if (!isset($subTask['is_preview']) || $subTask['revision_status'] !== 'pending_delete') {
                                        // Skip checkbox untuk temporary IDs (new items)
                                        if (!str_starts_with($subTask['id'], 'temp_')) {
                                            $html .= '<form action="' . route('subtasks.toggle', $subTask['id']) . '" method="POST" class="subtask-toggle-form inline">';
                                            $html .= csrf_field() . method_field('PATCH');
                                            $html .= '<input type="checkbox" 
                                                class="subtask-checkbox w-4 h-4 text-blue-600 rounded focus:ring-blue-500"
                                                data-sub-task-id="' . $subTask['id'] . '"
                                                data-task-id="' . $task['id'] . '"
                                                data-is-leaf="true"
                                                data-parent-id="' . ($subTask['parent_id'] ?? '') . '"
                                                ' . $checked . '>';
                                            $html .= '</form>';
                                        } else {
                                            // Untuk item baru (preview), tampilkan checkbox disabled
                                            $html .= '<input type="checkbox" class="w-4 h-4 text-blue-600 rounded opacity-50" disabled>';
                                        }
                                    } else {
                                        // Untuk item yang akan dihapus, tampilkan icon delete
                                        $html .= '<div class="w-4 h-4 flex items-center justify-center text-red-500">🗑️</div>';
                                    }

                                    // Icon dan teks
                                    $html .= '<div class="flex items-center gap-2 flex-1">';
                                    $html .= '<div class="w-2 h-2 bg-green-400 rounded-full"></div>';
                                    $html .= '<span class="text-sm ' . $textClass . ' subtask-text">' . e($subTask['title']) . '</span>';
                                    $html .= '</div>';

                                    // Tampilkan badge
                                    $html .= $revisionBadge;

                                    // Tombol edit/hapus jika punya izin (hanya untuk non-preview items)
                                    if (($task['is_owner'] || 
                                        (isset($task['collaborators']) && 
                                         collect($task['collaborators'])->where('user_id', Auth::id())->where('can_edit', true)->isNotEmpty())) &&
                                        (!isset($subTask['is_preview']) || !$subTask['is_preview'])) {
                                        
                                        $html .= '<div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">';
                                       
                                        
                                        $html .= '</div>';
                                    }

                                    $html .= '</div>';
                                }
                            }

                            return $html;
                        }
                        @endphp

                        @foreach($tasks as $task)
                            @php
                            $subTasks = collect($task['sub_tasks']);
                            $leafSubTasks = $subTasks->filter(function ($subTask) use ($subTasks) {
                                return !$subTasks->contains(function ($possibleChild) use ($subTask) {
                                    return $possibleChild['parent_id'] === $subTask['id'];
                                });
                            });
                            $subtaskCompleted = $leafSubTasks->where('completed', true)->count();
                            $subtaskTotal = $leafSubTasks->count();
                            $progressPercentage = $subtaskTotal > 0
                                ? round(($subtaskCompleted / $subtaskTotal) * 100)
                                : ($task['completed'] ? 100 : 0);
                                
                            // Styling untuk task preview
                            $taskPreviewClass = '';
                            $taskRevisionBadge = '';
                            
                            // Status label untuk task revisi
                            $taskStatusLabels = [
                                'pending' => ['text' => 'Perubahan Pending', 'icon' => '⏳', 'class' => 'bg-yellow-100 text-yellow-800 border-yellow-300'],
                                'has_pending' => ['text' => 'Ada Usulan', 'icon' => '📝', 'class' => 'bg-blue-100 text-blue-800 border-blue-300'],
                            ];
                            
                            if (!empty($task['revision_status'])) {
                                if ($task['revision_status'] === 'pending') {
                                    $taskPreviewClass = 'bg-blue-50 border-blue-200';
                                }
                                
                                $status = $taskStatusLabels[$task['revision_status']] ?? $taskStatusLabels['pending'];
                                $taskRevisionBadge = '<span 
                                    class="revision-status-badge ml-2 px-2 py-0.5 inline-flex items-center gap-1 rounded-full text-xs font-medium border ' . $status['class'] . '"
                                    data-task-id="' . $task['id'] . '">
                                    ' . $status['icon'] . ' ' . $status['text'] . '
                                    <button onclick="removeRevisionBadge(this)" class="ml-1 text-gray-500 hover:text-gray-700">×</button>
                                </span>';
                            }
                            @endphp

                            <div class="border border-gray-200 rounded-xl p-5 transition-all duration-300 hover:border-blue-300 hover:shadow-lg bg-white backdrop-blur-sm task-item group {{ $taskPreviewClass }}" 
                                 id="task-item-{{ $task['id'] }}" 
                                 data-task-status="{{ $task['completed'] ? 'completed' : 'active' }}">
                                <div class="flex items-start gap-4">
                                    <form action="{{ route('tasks.toggle', $task['id']) }}" method="POST" class="task-toggle-form">
                                        @csrf
                                        @method('PATCH')
                                        <input type="checkbox"
                                            class="task-checkbox w-5 h-5 text-blue-600 rounded focus:ring-blue-500 focus:ring-2 mt-1"
                                            data-task-id="{{ $task['id'] }}"
                                            {{ $task['completed'] ? 'checked' : '' }}>
                                    </form>
                                    
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <div class="flex items-center gap-3 flex-1">
                                                @if(count($task['sub_tasks']) > 0)
                                                <button class="subtask-toggle-btn text-gray-400 hover:text-blue-600 transition-all duration-200 p-1 rounded-lg hover:bg-blue-50" 
                                                       data-task-id="{{ $task['id'] }}"
                                                       data-expanded="true">
                                                    <svg class="w-5 h-5 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </button>
                                                @endif
                                                
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <h3 class="font-semibold text-gray-800 {{ $task['completed'] ? 'line-through text-gray-400' : '' }} task-title cursor-pointer hover:text-blue-600 transition-colors duration-200" 
                                                            data-task-id="{{ $task['id'] }}" 
                                                            onclick="openTaskModal({{ $task['id'] }})">
                                                            {{ $task['title'] }}
                                                        </h3>
                                                        
                                                        <!-- Task revision badge -->
                                                        {!! $taskRevisionBadge !!}
                                                        
                                                        <!-- Ownership indicator -->
                                                        @if($task['is_owner'])
                                                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full border border-blue-300">
                                                                👑 Owner
                                                            </span>
                                                        @else
                                                            <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full border border-green-300">
                                                                🤝 Collaborator
                                                            </span>
                                                        @endif
                                                        
                                                        <!-- Collaboration indicator -->
                                                        <div class="collaboration-indicators flex gap-1" data-task-id="{{ $task['id'] }}">
                                                            <!-- Will be populated by JavaScript -->
                                                        </div>

                                                        <!-- Pending revisions indicator for collaborators -->
                                                        @if(!$task['is_owner'])
                                                            <div class="pending-revision-indicator hidden" id="pending-revision-{{ $task['id'] }}">
                                                                <span class="text-xs bg-orange-100 text-orange-700 px-2 py-1 rounded-full border border-orange-300 animate-pulse">
                                                                    ⏳ Menunggu Review
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    
                                                    <div class="flex items-center gap-3 text-sm text-gray-500 mt-2 flex-wrap">
                                                        <div class="flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                            </svg>
                                                            <span>{{ $task['start_date_formatted'] }} → {{ $task['end_date_formatted'] }}</span>
                                                        </div>
                                                        <span class="text-xs text-gray-300 hidden sm:inline">•</span>
                                                        <div class="flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            <span>{{ $task['durationDays'] }} hari</span>
                                                        </div>
                                                        @if($subtaskTotal > 0)
                                                        <span class="text-xs text-gray-300 hidden sm:inline">•</span>
                                                        <div class="flex items-center gap-1 text-blue-600">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M3 3v18h18M9 17V9m4 8v-5m4 5v-9" />
                                                            </svg>
                                                            <span class="font-medium task-progress-percentage">{{ $progressPercentage }}%</span>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center gap-2">
                                                <!-- Collaboration button -->
                                                <button onclick="openCollaborationModal({{ $task['id'] }})"
                                                        class="text-gray-400 hover:text-green-600 p-2 rounded-lg hover:bg-green-50 transition-all duration-200"
                                                        title="Kelola Kolaborasi">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                </button>

                                                <button onclick="openTaskModal({{ $task['id'] }})"
                                                        class="text-gray-400 hover:text-blue-600 p-2 rounded-lg hover:bg-blue-50 transition-all duration-200"
                                                        title="Lihat">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>

                                                <a href="{{ route('tasks.edit', $task['id']) }}"
                                                    class="text-gray-400 hover:text-blue-600 p-2 rounded-lg hover:bg-blue-50 transition-all duration-200"
                                                    title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                                                            m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>

                                                @if($task['is_owner'] || (isset($task['collaborators']) && collect($task['collaborators'])->where('user_id', Auth::id())->where('can_edit', true)->isNotEmpty()))
                                                <form action="{{ route('tasks.destroy', $task['id']) }}" method="POST" onsubmit="return confirm('Hapus tugas ini?')" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-gray-400 hover:text-red-600 p-2 rounded-lg hover:bg-red-50 transition-all duration-200"
                                                        title="Hapus">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7
                                                                m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </div>

                                        @if(count($task['sub_tasks']) > 0)
                                        <div class="mt-4 ml-8 pl-4 border-l-2 border-blue-100 task-subtasks-container" id="subtasks-container-{{ $task['id'] }}">
                                            <div class="flex justify-between items-center mb-3">
                                                <div class="text-xs text-gray-500 subtask-progress-text font-medium">
                                                    Subtugas ({{ $subtaskCompleted }}/{{ $subtaskTotal }})
                                                </div>
                                                <div class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                    <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600 subtask-progress-bar transition-all duration-500" style="width: {{ $progressPercentage }}%"></div>
                                                </div>
                                            </div>
                                            <div class="space-y-1 vertical-tree-structure" id="task-tree-{{ $task['id'] }}">
                                                {!! renderSubtasks($task['sub_tasks'], null, $task, 0) !!}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div id="filter-empty-state" class="hidden text-center py-12">
                            <div class="flex flex-col items-center justify-center">
                                <div class="p-4 bg-gray-100 rounded-full mb-4">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-600 mb-2" id="empty-state-title">Tidak ada tugas</h3>
                                <p class="text-gray-500 mb-4" id="empty-state-description">Tidak ada tugas yang sesuai dengan filter yang dipilih.</p>
                                <button onclick="resetFilter()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 font-medium">
                                    Tampilkan Semua Tugas
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right sidebar -->
            <div class="space-y-6">
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-6">Ringkasan</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200">
                            <div class="text-blue-600 text-sm mb-1 font-medium">Total Tugas</div>
                            <div class="text-2xl font-bold text-gray-800" id="total-tasks-count">{{ $totalTasks }}</div>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-xl border border-green-200">
                            <div class="text-green-600 text-sm mb-1 font-medium">Selesai</div>
                            <div class="text-2xl font-bold text-gray-800" id="completed-tasks-count">
                                {{ collect($tasks)->where('completed', true)->count() }}
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-xl border border-purple-200">
                            <div class="text-purple-600 text-sm mb-1 font-medium">Progress</div>
                            <div class="text-2xl font-bold text-gray-800" id="overall-progress-percentage">
                               {{ $totalTasks > 0 ? round((collect($tasks)->where('completed', true)->count() / $totalTasks) * 100) : 0 }}%
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-4 rounded-xl border border-yellow-200">
                            <div class="text-yellow-600 text-sm mb-1 font-medium">Terlambat</div>
                            <div class="text-2xl font-bold text-gray-800">
                                {{ collect($tasks)->where('completed', false)->filter(fn($task) => \Carbon\Carbon::parse($task['end_date'])->lt(now()))->count() }}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pending Revisions -->
                    <div id="pending-revisions-summary" class="mt-4 p-3 bg-orange-50 rounded-lg border border-orange-200 hidden">
                        <div class="flex items-center gap-2 text-orange-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm font-medium">Menunggu Review</span>
                        </div>
                        <div class="text-2xl font-bold text-orange-900" id="pending-revisions-count">0</div>
                    </div>
                </div>

                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-6">Prioritas</h2>
                    <div class="space-y-4">
                        @foreach(['urgent' => 'Urgent', 'high' => 'Tinggi', 'medium' => 'Sedang', 'low' => 'Rendah'] as $key => $label)
                        <div>
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span class="font-medium">{{ $label }}</span>
                                <span>{{ $priorityCounts[$key] ?? 0 }} Tugas</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r 
                                    @if($key == 'urgent') from-red-500 to-red-600 
                                    @elseif($key == 'high') from-yellow-500 to-yellow-600 
                                    @elseif($key == 'medium') from-blue-500 to-blue-600 
                                    @else from-green-500 to-green-600 @endif h-2 rounded-full transition-all duration-500"
                                    style="width: {{ $totalTasks > 0 ? (($priorityCounts[$key] ?? 0) / $totalTasks) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-lg font-semibold text-gray-800">Kategori</h2>
                    </div>
                    <div class="space-y-3">
                        @foreach($categories as $category)
                        <div class="flex items-center justify-between p-3 hover:bg-blue-50 rounded-xl transition-all duration-200 cursor-pointer">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-700">{{ $category->name }}</span>
                            </div>
                            <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded-lg">{{ $category->tasks->count() }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.collaboration-modal')

<!-- Subtask Modal -->
<div id="subtaskModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden flex justify-center items-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[80vh] overflow-hidden">
        <div class="flex justify-between items-center p-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
            <h3 id="subtaskModalTitle" class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Subtask
            </h3>
            <button onclick="closeSubtaskModal()" class="text-gray-500 hover:text-gray-700 p-1 rounded-lg hover:bg-white/50 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="overflow-y-auto max-h-[calc(80vh-100px)]">
            <div class="p-6">
                <form id="subtaskForm" class="space-y-4">
                    <input type="hidden" id="subtask_id" name="subtask_id">
                    <input type="hidden" id="subtask_task_id" name="task_id">
                    
                    <div>
                        <label for="subtask_title" class="block text-sm font-medium text-gray-700 mb-1">Judul Subtask</label>
                        <input type="text" id="subtask_title" name="title" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                    
                    <div>
                        <label for="subtask_description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea id="subtask_description" name="description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="subtask_start_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                            <input type="date" id="subtask_start_date" name="start_date"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label for="subtask_end_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                            <input type="date" id="subtask_end_date" name="end_date"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="subtask_start_time" class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai</label>
                            <input type="time" id="subtask_start_time" name="start_time"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label for="subtask_end_time" class="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai</label>
                            <input type="time" id="subtask_end_time" name="end_time"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                    </div>
                    
                    <div class="flex gap-3 pt-4">
                        <button type="submit" id="subtaskSubmitBtn"
                                class="flex-1 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white py-2 px-4 rounded-lg font-medium transition-all duration-300">
                            Simpan Subtask
                        </button>
                        <button type="button" onclick="closeSubtaskModal()"
                                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded-lg font-medium transition-all duration-300">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Task Modal -->
<div id="taskModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden flex justify-center items-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[80vh] overflow-hidden">
        <div class="flex justify-between items-center p-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                Detail Tugas
                <span id="modal-auto-save-indicator" class="hidden ml-2 px-2 py-1 text-xs bg-green-100 text-green-600 rounded-full font-medium animate-pulse">
                    ✓ Tersimpan otomatis
                </span>
            </h3>
            <button onclick="closeTaskModal()" class="text-gray-500 hover:text-gray-700 p-1 rounded-lg hover:bg-white/50 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="overflow-y-auto max-h-[calc(80vh-100px)]">
            <div id="taskModalContent" class="p-4 space-y-4 text-sm text-gray-700">
            </div>
        </div>
    </div>
</div>

<!-- Tooltip -->
<div id="taskTooltip" class="fixed bg-white rounded-lg shadow-xl border border-gray-200 p-4 z-50 hidden max-w-sm">
    <div id="tooltipContent" class="text-sm">
    </div>
</div>

<!-- Loading -->
<div id="loading-indicator" class="fixed inset-0 bg-black/20 backdrop-blur-sm hidden flex justify-center items-center z-40">
    <div class="bg-white rounded-xl shadow-xl px-6 py-4 flex items-center gap-3">
        <svg class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="text-gray-700 font-medium text-sm">Memperbarui...</span>
    </div>
</div>

<!-- Notification -->
<div id="notification-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales/id.global.min.js'></script>

<script>
let appState = {
    tasksData: @json($tasks ?? []),
    calendarEvents: @json($calendarEvents ?? []),
    pendingRevisions: @json($pendingRevisions ?? []),
    totalTasks: {{ $totalTasks ?? 0 }},
    completedTasks: {{ $completedTasks ?? 0 }},
    overallProgress: {{ $overallProgress ?? 0 }},
    allTasksCompleted: {{ ($completedTasks ?? 0) == ($totalTasks ?? 0) ? 'true' : 'false' }},
    filteredTasksCount: {{ $totalTasks ?? 0 }},
    currentFilter: 'all',
    currentView: 'dayGridMonth',
    isModalOpen: false,
    currentModalTaskId: null,
    tooltip: null,
    isUpdating: false,
    collaborationState: {
        currentTaskId: null,
        currentRevisionId: null
    }
};

// Add collaboration functions
let collaborationState = {
    currentTaskId: null,
    currentRevisionId: null
};

document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing calendar application...');

    

    function initializeApp() {
        if (typeof FullCalendar !== 'undefined') {
            try {
                initializeCalendar();
                initializeEventDelegation();
                initializeTaskFilter();
                initializeTooltip();
                checkAllTasksCompleted();
                
                // Initialize collaboration features
                loadCollaborationIndicators();
                loadPendingRevisions();
                checkInviteNotifications();
                checkPendingRevisionsForCollaborators();
                
                console.log('Calendar application initialized successfully');
            } catch (error) {
                console.error('Failed to initialize calendar application:', error);
                showCalendarError('Gagal menginisialisasi aplikasi timeline: ' + error.message);
            }
        } else {
            console.log('FullCalendar not loaded yet, waiting...');
            setTimeout(initializeApp, 100);
        }
    }

    initializeApp();
});

// Check pending revisions for collaborators
async function checkPendingRevisionsForCollaborators() {
    try {
        const response = await fetch('/collaboration/pending-revisions');
        const data = await response.json();
        
        if (data.success && data.revisions.length > 0) {
            // Show indicators for tasks that have pending revisions
            data.revisions.forEach(revision => {
                const indicator = document.getElementById(`pending-revision-${revision.task.id}`);
                if (indicator) {
                    indicator.classList.remove('hidden');
                }
            });
        }
    } catch (error) {
        console.error('Error checking pending revisions for collaborators:', error);
    }
}

// Collaboration Functions
async function loadCollaborationIndicators() {
    try {
        // Load collaboration status for each task
        const taskElements = document.querySelectorAll('.task-item');
        for (const taskElement of taskElements) {
            const taskId = taskElement.id.replace('task-item-', '');
            const response = await fetch(`/collaboration/task-status/${taskId}`);
            const data = await response.json();
            
            if (data.success) {
                updateCollaborationIndicator(taskId, data);
            }
        }
    } catch (error) {
        console.error('Error loading collaboration indicators:', error);
    }
}

function updateCollaborationIndicator(taskId, data) {
    const container = document.querySelector(`[data-task-id="${taskId}"] .collaboration-indicators`);
    if (!container) return;
    
    let indicators = [];
    
    if (data.collaborators && data.collaborators.length > 0) {
        const approvedCount = data.collaborators.filter(c => c.status === 'approved').length;
        if (approvedCount > 0) {
            indicators.push(`
                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full border border-green-300 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    ${approvedCount} kolaborator
                </span>
            `);
        }
    }
    
    if (data.pending_revisions_count > 0) {
        indicators.push(`
            <span class="text-xs bg-orange-100 text-orange-700 px-2 py-1 rounded-full border border-orange-300 flex items-center gap-1 animate-pulse">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                ${data.pending_revisions_count} menunggu review
            </span>
        `);
    }
    
    container.innerHTML = indicators.join('');
}

async function loadPendingRevisions() {
    try {
        const response = await fetch('/collaboration/pending-revisions');
        const data = await response.json();
        
        if (data.success && data.revisions.length > 0) {
            const summaryElement = document.getElementById('pending-revisions-summary');
            const countElement = document.getElementById('pending-revisions-count');
            
            if (summaryElement && countElement) {
                countElement.textContent = data.revisions.length;
                summaryElement.classList.remove('hidden');
            }
        }
    } catch (error) {
        console.error('Error loading pending revisions:', error);
    }
}

async function checkInviteNotifications() {
    try {
        const response = await fetch('/collaboration/invites');
        const data = await response.json();
        
        if (data.success && data.invites.length > 0) {
            const badge = document.getElementById('invite-badge');
            if (badge) {
                badge.textContent = data.invites.length;
                badge.classList.remove('hidden');
            }
        }
    } catch (error) {
        console.error('Error checking invite notifications:', error);
    }
}

// Collaboration Modal Functions
function openCollaborationModal(taskId) {
    collaborationState.currentTaskId = taskId;
    
    fetch(`/collaboration/task-status/${taskId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderCollaborationModal(data);
                document.getElementById('collaborationModal').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Gagal memuat data kolaborasi', 'error');
        });
}

function renderCollaborationModal(data) {
    const content = document.getElementById('collaborationModalContent');
    
    let collaboratorsHtml = '';
    if (data.collaborators && data.collaborators.length > 0) {
        collaboratorsHtml = `
            <div class="mb-6">
                <h4 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Kolaborator
                </h4>
                <div class="space-y-2">
                    ${data.collaborators.map(collab => `
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-blue-600">${collab.user.name.charAt(0).toUpperCase()}</span>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">${collab.user.name}</div>
                                    <div class="text-xs text-gray-500">${collab.user.email}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs px-2 py-1 rounded-full ${
                                    collab.status === 'approved' ? 'bg-green-100 text-green-700' :
                                    collab.status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                                    'bg-red-100 text-red-700'
                                }">
                                    ${collab.status === 'approved' ? '✅ Aktif' : 
                                      collab.status === 'pending' ? '⏳ Pending' : '❌ Ditolak'}
                                </span>
                                ${collab.can_edit ? '<span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">✏️ Dapat Edit</span>' : ''}
                                ${data.is_owner ? `
                                    <button onclick="removeCollaborator(${collab.id})" 
                                            class="text-red-600 hover:text-red-800 p-1 rounded">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                ` : ''}
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }
    
    let pendingRevisionsHtml = '';
    if (data.pending_revisions_count > 0 && data.is_owner) {
        pendingRevisionsHtml = `
            <div class="mb-6">
                <h4 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Menunggu Review (${data.pending_revisions_count})
                </h4>
                <button onclick="loadPendingRevisionsModal()" 
                        class="w-full p-3 bg-orange-50 border border-orange-200 rounded-lg hover:bg-orange-100 transition-colors text-left">
                    <div class="flex items-center justify-between">
                        <span class="text-orange-800 text-sm font-medium">Lihat usulan perubahan yang menunggu review</span>
                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </button>
            </div>
        `;
    }
    
    content.innerHTML = `
        ${data.is_owner ? `
            <div class="mb-6">
                <h4 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Undang Kolaborator
                </h4>
                <button onclick="openInviteModal()" 
                        class="w-full p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-green-400 hover:bg-green-50 transition-all duration-200 text-gray-600 hover:text-green-600">
                    <div class="flex flex-col items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        <span class="font-medium">Undang kolaborator baru</span>
                        <span class="text-xs">Berikan akses edit dengan sistem review</span>
                    </div>
                </button>
            </div>
        ` : ''}
        
        ${collaboratorsHtml}
        ${pendingRevisionsHtml}
        
        ${!collaboratorsHtml && !data.is_owner ? `
            <div class="text-center py-8 text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p>Belum ada kolaborator untuk tugas ini</p>
            </div>
        ` : ''}
    `;
}

function closeCollaborationModal() {
    document.getElementById('collaborationModal').classList.add('hidden');
    collaborationState.currentTaskId = null;
}

// Invite Modal Functions
function openInviteModal() {
    document.getElementById('inviteModal').classList.remove('hidden');
}

function closeInviteModal() {
    document.getElementById('inviteModal').classList.add('hidden');
    document.getElementById('inviteForm').reset();
}

// Handle invite form submission
if (document.getElementById('inviteForm')) {
    document.getElementById('inviteForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = {
            email: formData.get('email'),
            can_edit: formData.has('can_edit')
        };
        
        try {
            const response = await fetch(`/collaboration/invite/${collaborationState.currentTaskId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                showNotification('Undangan berhasil dikirim!', 'success');
                closeInviteModal();
                // Refresh collaboration modal
                openCollaborationModal(collaborationState.currentTaskId);
            } else {
                showNotification(result.error || 'Gagal mengirim undangan', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan', 'error');
        }
    });
}

async function removeCollaborator(collaboratorId) {
    if (!confirm('Hapus kolaborator ini?')) return;
    
    try {
        const response = await fetch(`/collaboration/remove/${collaborationState.currentTaskId}/${collaboratorId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('Kolaborator berhasil dihapus', 'success');
            openCollaborationModal(collaborationState.currentTaskId); // Refresh
        } else {
            showNotification(data.error || 'Gagal menghapus kolaborator', 'error');
        }
    } catch (error) {
        showNotification('Terjadi kesalahan', 'error');
    }
}

function openTaskModal(taskId) {
    const task = appState.tasksData.find(t => t.id == taskId);
    if (!task) {
        showNotification('Memuat data terbaru...', 'info');
        window.location.reload();
        return;
    }

    appState.isModalOpen = true;
    appState.currentModalTaskId = taskId;

    let priorityText = '';
    let priorityClass = '';
    switch(task.priority) {
        case 'urgent':
            priorityText = 'Sangat Mendesak';
            priorityClass = 'bg-red-100 text-red-800 border border-red-300';
            break;
        case 'high':
            priorityText = 'Tinggi';
            priorityClass = 'bg-orange-100 text-orange-800 border border-orange-300';
            break;
        case 'medium':
            priorityText = 'Sedang';
            priorityClass = 'bg-yellow-100 text-yellow-800 border border-yellow-300';
            break;
        case 'low':
            priorityText = 'Rendah';
            priorityClass = 'bg-green-100 text-green-800 border border-green-300';
            break;
    }

    const leafSubTasks = task.sub_tasks ? task.sub_tasks.filter(st => 
        !task.sub_tasks.some(parent => parent.parent_id === st.id)
    ) : [];
    const subtaskCompleted = leafSubTasks.filter(st => st.completed).length;
    const subtaskTotal = leafSubTasks.length;
    const progressPercentage = subtaskTotal > 0 ? Math.round((subtaskCompleted / subtaskTotal) * 100) : (task.completed ? 100 : 0);

    const timeDisplay = (task.start_time && task.end_time && !task.is_all_day)
        ? `<div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
            <div class="flex items-center gap-2 mb-1">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-xs font-medium text-gray-600">Waktu</span>
            </div>
            <span class="font-semibold text-gray-800 text-sm">${task.start_time} - ${task.end_time}</span>
        </div>`
        : `<div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
            <div class="flex items-center gap-2 mb-1">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 01-2 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="text-xs font-medium text-gray-600">Durasi</span>
            </div>
            <span class="font-semibold text-gray-800 text-sm">Timeline Harian Penuh</span>
        </div>`;

    let subtasksHtml = '';
    if (task.sub_tasks && task.sub_tasks.length > 0) {
        subtasksHtml = `
            <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                <div class="flex justify-between items-center mb-3">
                    <h5 class="font-medium text-gray-800 flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 8l2 2 4-4"></path>
                        </svg>
                        Timeline Subtugas (<span id="modal-subtask-count">${subtaskCompleted}/${subtaskTotal}</span>)
                    </h5>
                    <div class="flex items-center gap-2">
                        <div class="w-20 h-2 bg-white rounded-full overflow-hidden shadow-inner">
                            <div id="modal-progress-bar" class="h-full bg-gradient-to-r from-blue-500 to-blue-600 transition-all duration-500" style="width: ${progressPercentage}%"></div>
                        </div>
                        <span id="modal-progress-percentage" class="text-xs font-semibold text-blue-600">${progressPercentage}%</span>
                    </div>
                </div>
                <div class="space-y-1 max-h-40 overflow-y-auto">
                    ${renderModalSubtasks(task.sub_tasks, null, task, 0)}
                </div>
            </div>
        `;
    }

    const modalContent = document.getElementById('taskModalContent');
    modalContent.innerHTML = `
        <div class="flex items-start gap-3 mb-4">
            <form action="/tasks/${task.id}/toggle" method="POST" class="task-toggle-form-modal">
                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                <input type="hidden" name="_method" value="PATCH">
                <input type="checkbox"
                    class="task-checkbox-modal w-5 h-5 text-blue-600 rounded focus:ring-blue-500 focus:ring-2"
                    data-task-id="${task.id}"
                    ${task.completed ? 'checked' : ''}>
            </form>
            <div class="flex-1">
                <h4 class="font-bold text-lg mb-2 ${task.completed ? 'line-through text-gray-400' : 'text-gray-800'} flex items-center gap-2" id="modal-task-title-${task.id}">
                    📋 ${task.title}
                </h4>
                <div class="flex items-center gap-2 mb-3 flex-wrap">
                    <span class="px-2 py-1 text-xs rounded-lg font-medium ${priorityClass}">
                        ${priorityText}
                    </span>
                    ${task.completed ? '<span class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-lg font-medium border border-gray-300">✅ Selesai</span>' : ''}
                    ${subtaskTotal > 0 ? `<span id="modal-progress-badge" class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-lg font-medium border border-blue-300">📊 ${progressPercentage}% Progress</span>` : ''}
                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-lg font-medium border border-green-300">⏱️ ${task.durationDays} hari</span>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-3 border border-green-200">
                <div class="flex items-center gap-2 mb-1">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-xs font-medium text-green-700">Start Timeline</span>
                </div>
                <span class="font-semibold text-green-800 text-sm">${formatDateString(task.start_date)}</span>
            </div>
            <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-lg p-3 border border-red-200">
                <div class="flex items-center gap-2 mb-1">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 01-2 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-xs font-medium text-red-700">End Timeline</span>
                </div>
                <span class="font-semibold text-red-800 text-sm">${formatDateString(task.end_date)}</span>
            </div>
            ${timeDisplay}
        </div>
        
        <div class="mb-4">
            <h5 class="font-medium text-gray-800 mb-2 flex items-center gap-2 text-sm">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Deskripsi
            </h5>
            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                <p class="text-gray-700 leading-relaxed text-sm">${task.description || 'Tidak ada deskripsi tersedia'}</p>
            </div>
        </div>

        ${subtasksHtml}
        
        <div class="flex gap-2 pt-4 border-t border-gray-200">
            <a href="/tasks/${task.id}/edit" class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-center py-2 px-4 rounded-lg font-medium transition-all duration-300 text-sm">
                Edit Timeline
            </a>
            <button onclick="closeTaskModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded-lg font-medium transition-all duration-300 text-sm">
                Tutup
            </button>
        </div>
    `;
    
    const taskModalEl = document.getElementById('taskModal');
    taskModalEl.classList.remove('hidden');
     document.body.classList.add('no-scroll');
    
    taskModalEl.style.opacity = '0';
    taskModalEl.style.transform = 'scale(0.95)';
    
    setTimeout(() => {
        taskModalEl.style.opacity = '1';
        taskModalEl.style.transform = 'scale(1)';
        taskModalEl.style.transition = 'all 0.3s ease-out';
    }, 10);
}

function renderModalSubtasks(subtasks, parentId = null, task, level = 0) {
    let html = '';
    
    const filteredSubtasks = subtasks.filter(st => st.parent_id === parentId);
    
    filteredSubtasks.forEach(subTask => {
        const isParent = subtasks.some(st => st.parent_id === subTask.id);
        const indentClass = level > 0 ? `ml-${level * 6}` : '';
        const treeLineClass = level > 0 ? 'border-l-2 border-gray-200 pl-4' : '';
        
        const dateInfo = (subTask.start_date || subTask.end_date) ? 
            `<div class="subtask-date" style="margin-top: 2px;">
                <svg style="width: 10px; height: 10px; display: inline; margin-right: 3px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span style="font-size: 8px; color: #6B7280;">
                    ${subTask.start_date ? formatDateString(subTask.start_date) : ''} ${subTask.end_date ? ' → ' + formatDateString(subTask.end_date) : ''}
                </span>
            </div>` : '';
        
        if (isParent) {
            html += `
                <div class="subtask-parent-modal relative bg-white rounded-lg p-2 border border-gray-200 ${treeLineClass}" data-subtask-id="${subTask.id}">
                    ${level > 0 ? `<div class="absolute left-0 top-0 w-3 h-6 border-l-2 border-b-2 border-gray-300 rounded-bl-md"></div>` : ''}
                    <div class="flex items-center gap-2 py-1 ${indentClass}">
                        <button class="subtask-parent-toggle-btn-modal text-gray-400 hover:text-blue-600 transition-all duration-200 p-1 rounded-lg hover:bg-blue-50" 
                                data-subtask-id="${subTask.id}" 
                                data-expanded="true">
                            <svg class="w-3 h-3 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="w-2 h-2 bg-blue-400 rounded-full flex-shrink-0"></div>
                        <span class="text-xs font-semibold text-gray-700">📁 ${subTask.title}</span>
                    </div>
                    ${dateInfo}
                    <div class="subtask-children-modal relative border-l-2 border-gray-200 ml-4" id="modal-subtask-children-${subTask.id}">
                        ${renderModalSubtasks(subtasks, subTask.id, task, level + 1)}
                    </div>
                </div>
            `;
        } else {
            const lineClass = subTask.completed ? 'line-through text-gray-400' : 'text-gray-700';
            html += `
                <div class="subtask-item-modal relative flex items-center gap-2 py-1 px-2 bg-white rounded border border-gray-200 hover:border-blue-300 transition-all duration-200 ${treeLineClass}" data-subtask-id="${subTask.id}">
                    ${level > 0 ? `<div class="absolute left-0 top-0 w-4 h-6 border-l-2 border-b-2 border-gray-300 rounded-bl-md"></div>` : ''}
                    <form action="/subtasks/${subTask.id}/toggle" method="POST" class="subtask-toggle-form-modal">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="checkbox"
                            class="subtask-checkbox-modal w-3 h-3 text-blue-600 rounded focus:ring-blue-500 focus:ring-2"
                            data-sub-task-id="${subTask.id}"
                            data-task-id="${task.id}"
                            ${subTask.completed ? 'checked' : ''}>
                    </form>
                    <div class="flex-1 ${indentClass}">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-green-400 rounded-full flex-shrink-0"></div>
                            <span class="text-xs ${lineClass} subtask-text-modal">📝 ${subTask.title}</span>
                        </div>
                        ${dateInfo}
                    </div>
                </div>
            `;
        }
    });

    return html;
}

function closeTaskModal() {
    const modalEl = document.getElementById('taskModal');
    modalEl.style.opacity = '0';
    modalEl.style.transform = 'scale(0.95)';
    
    setTimeout(() => {
        modalEl.classList.add('hidden');
        modalEl.style.opacity = '';
        modalEl.style.transform = '';
        modalEl.style.transition = '';
        appState.isModalOpen = false;
        appState.currentModalTaskId = null;

        // Hapus kelas no-scroll dari body
        document.body.classList.remove('no-scroll');
    }, 300);
}

// Handle subtask form submission
if (document.getElementById('subtaskForm')) {
    document.getElementById('subtaskForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const subtaskId = formData.get('subtask_id');
        const data = {
            task_id: formData.get('task_id'),
            title: formData.get('title'),
            description: formData.get('description'),
            start_date: formData.get('start_date'),
            end_date: formData.get('end_date'),
            start_time: formData.get('start_time'),
            end_time: formData.get('end_time'),
        };
        
        try {
            let url, method;
            if (subtaskId) {
                url = `/subtasks/${subtaskId}`;
                method = 'PUT';
            } else {
                url = '/subtasks';
                method = 'POST';
            }
            
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                showNotification(result.message, 'success');
                closeSubtaskModal();
                // Refresh the page to show changes
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification(result.error || 'Gagal menyimpan subtask', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan', 'error');
        }
    });
}

// Subtask management functions
function editSubtask(subtaskId, taskId) {
    openSubtaskModal(taskId, subtaskId);
}


// Enhanced Revision Review Functions
async function loadPendingRevisionsModal() {
    try {
        const response = await fetch('/collaboration/pending-revisions');
        const data = await response.json();
        
        if (data.success) {
            renderRevisionModal(data.revisions);
            document.getElementById('revisionModal').classList.remove('hidden');
        }
    } catch (error) {
        console.error('Error loading revisions:', error);
        showNotification('Gagal memuat data revisi', 'error');
    }
}

function renderRevisionModal(revisions) {
    const content = document.getElementById('revisionModalContent');
    
    if (revisions.length === 0) {
        content.innerHTML = `
            <div class="text-center py-12">
                <div class="bg-gray-50 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Tidak Ada Usulan Perubahan</h3>
                <p class="text-gray-500">Saat ini tidak ada usulan perubahan yang menunggu persetujuan Anda</p>
            </div>
        `;
        return;
    }
    
    const fieldLabels = {
        category_id: "Kategori",
        start_time: "Waktu Mulai",
        end_time: "Waktu Selesai",
        is_all_day: "Sepanjang Hari",
        title: "Judul",
        description: "Deskripsi",
        start_date: "Tanggal Mulai",
        end_date: "Tanggal Selesai"
    };

    // Add header with revision count
    const headerHtml = `
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-lg mb-6 border border-blue-100">
            <div class="flex items-center gap-3">
                <div class="bg-blue-100 p-2 rounded-full">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Review Usulan Perubahan</h3>
                    <p class="text-sm text-gray-600">
                        Terdapat <span class="font-medium text-blue-600">${revisions.length}</span> usulan perubahan yang menunggu persetujuan Anda
                    </p>
                </div>
            </div>
        </div>
    `;

    content.innerHTML = headerHtml + revisions.map(revision => {
        let changesHtml = '';
        
        const formatValue = (val) => {
            if (val === true || val === 1 || val === "1" || val === "Ya") return "Ya";
            if (val === false || val === 0 || val === "0" || val === "Tidak") return "Tidak";
            if (val === null || val === undefined || val === '') return "Tidak ada";
            return val;
        };

        // Function to normalize values for consistent comparison
        const normalizeValue = (val, fieldKey) => {
            // Special handling for boolean fields
            if (fieldKey === 'is_all_day') {
                if (val === true || val === 1 || val === "1" || val === "Ya") return "1";
                if (val === false || val === 0 || val === "0" || val === "Tidak") return "0";
            }
            
            // Special handling for category_id - ensure both are strings
            if (fieldKey === 'category_id') {
                return String(val || '');
            }
            
            // For other fields, convert to string and trim
            return String(val || '').trim();
        };

        // Enhanced function to only show changed fields with strict comparison
        const renderChanges = (originalData, proposedData) => {
            const changes = [];
            
            // Only compare fields that exist in both original and proposed data
            Object.keys(proposedData).forEach(key => {
                const original = originalData[key];
                const proposed = proposedData[key];
                
                // Normalize values for comparison
                const normalizedOriginal = normalizeValue(original, key);
                const normalizedProposed = normalizeValue(proposed, key);
                
                // Only add to changes if normalized values are actually different
                if (normalizedOriginal !== normalizedProposed) {
                    // Skip fields that are empty or null in both
                    if (!((!original || original === '') && (!proposed || proposed === ''))) {
                        changes.push({
                            field: fieldLabels[key] || key,
                            original: formatValue(original),
                            proposed: formatValue(proposed),
                            key: key
                        });
                    }
                }
            });

            // Return empty string if no real changes detected
            if (changes.length === 0) {
                return '';
            }

            return changes.map(change => `
                <div class="bg-white p-3 rounded-lg border border-gray-100 mb-3 last:mb-0">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span class="font-medium text-gray-800">${change.field}</span>
                        ${change.key ? `<span class="text-xs text-gray-400 ml-1">(${change.key})</span>` : ''}
                    </div>
                    <div class="ml-6 space-y-1">
                        <div class="flex items-center gap-2 text-sm">
                            <span class="text-red-600 font-medium">Dari:</span>
                            <span class="bg-red-50 px-2 py-1 rounded text-red-800 line-through">${change.original}</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <span class="text-green-600 font-medium">Menjadi:</span>
                            <span class="bg-green-50 px-2 py-1 rounded text-green-800 font-medium">${change.proposed}</span>
                        </div>
                    </div>
                </div>
            `).join('');
        };

        // Handle different revision types with enhanced UI
        if (revision.revision_type === 'create_subtask') {
            changesHtml = `
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-lg border border-green-200">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="bg-green-100 p-1.5 rounded-full">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <h5 class="font-semibold text-green-800">Tambah Subtugas Baru</h5>
                    </div>
                    <div class="bg-white p-3 rounded border border-green-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <span class="text-sm font-medium text-gray-600">Judul:</span>
                                <p class="text-green-700 font-medium">${revision.proposed_data.subtask_data.title}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-600">Periode:</span>
                                <p class="text-green-700">${revision.proposed_data.subtask_data.start_date} → ${revision.proposed_data.subtask_data.end_date}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        } 
        else if (revision.revision_type === 'update_subtask') {
            changesHtml = `
                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 p-4 rounded-lg border border-blue-200">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="bg-blue-100 p-1.5 rounded-full">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <h5 class="font-semibold text-blue-800">Perubahan Subtugas</h5>
                    </div>
                    <div class="space-y-2">
                        ${renderChanges(revision.original_data.subtask_data, revision.proposed_data.subtask_data)}
                    </div>
                </div>
            `;
        } 
        else if (revision.revision_type === 'update_task_with_subtasks') {
            // Only show task changes if there are actual changes
            let taskChangesHtml = '';
            const taskChanges = renderChanges(revision.original_data.task, revision.proposed_data.task);
            
            // Only show task changes section if there are actual changes
            if (taskChanges && taskChanges.trim() !== '') {
                taskChangesHtml = `
                    <div class="bg-gradient-to-r from-yellow-50 to-amber-50 p-4 rounded-lg border border-yellow-200 mb-4">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="bg-yellow-100 p-1.5 rounded-full">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </div>
                            <h5 class="font-semibold text-yellow-800">Perubahan Tugas Utama</h5>
                        </div>
                        <div class="space-y-2">
                            ${taskChanges}
                        </div>
                    </div>
                `;
            }
            
            const subtasks = revision.proposed_data.subtasks || {};
            const deletedSubtasks = revision.proposed_data.deleted_subtasks || {};
            
            let subtaskChangesHtml = '';
            if (Object.keys(subtasks).length > 0) {
                let subtaskItems = '';
                Object.values(subtasks).forEach(subtask => {
                    if (subtask.is_new) {
                        subtaskItems += `
                            <div class="bg-green-50 p-3 rounded-lg border border-green-200">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <span class="text-green-800 font-medium">Tambah: ${subtask.title}</span>
                                </div>
                                <div class="ml-6 text-sm text-green-600">📅 ${subtask.start_date} → ${subtask.end_date}</div>
                            </div>
                        `;
                    } else {
                        subtaskItems += `
                            <div class="bg-blue-50 p-3 rounded-lg border border-blue-200">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span class="text-blue-800 font-medium">Ubah: ${subtask.title}</span>
                                </div>
                                <div class="ml-6 text-sm text-blue-600">📅 ${subtask.start_date} → ${subtask.end_date}</div>
                            </div>
                        `;
                    }
                });
                
                subtaskChangesHtml = `
                    <div class="bg-gradient-to-r from-gray-50 to-slate-50 p-4 rounded-lg border border-gray-200 mb-4">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="bg-gray-100 p-1.5 rounded-full">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h5 class="font-semibold text-gray-800">Perubahan Subtugas</h5>
                        </div>
                        <div class="space-y-3">${subtaskItems}</div>
                    </div>
                `;
            }
            
            let deletedChangesHtml = '';
            if (Object.keys(deletedSubtasks).length > 0) {
                let deletedItems = '';
                Object.values(deletedSubtasks).forEach(subtask => {
                    deletedItems += `
                        <div class="bg-red-50 p-3 rounded-lg border border-red-200">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span class="text-red-800 font-medium">Hapus: ${subtask.title}</span>
                            </div>
                        </div>
                    `;
                });
                
                deletedChangesHtml = `
                    <div class="bg-gradient-to-r from-red-50 to-pink-50 p-4 rounded-lg border border-red-200">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="bg-red-100 p-1.5 rounded-full">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </div>
                            <h5 class="font-semibold text-red-800">Subtugas yang Akan Dihapus</h5>
                        </div>
                        <div class="space-y-3">${deletedItems}</div>
                    </div>
                `;
            }
            
            changesHtml = taskChangesHtml + subtaskChangesHtml + deletedChangesHtml;
        }

        // Enhanced revision type labels
        const getRevisionTypeLabel = (type) => {
            const labels = {
                'create_subtask': { text: 'Tambah Subtugas', icon: '➕', color: 'green' },
                'update_subtask': { text: 'Ubah Subtugas', icon: '✏️', color: 'blue' },
                'update_task_with_subtasks': { text: 'Ubah Tugas & Subtugas', icon: '🔄', color: 'yellow' },
                'update': { text: 'Ubah Tugas', icon: '✏️', color: 'orange' }
            };
            return labels[type] || { text: 'Perubahan', icon: '📝', color: 'gray' };
        };

        const typeInfo = getRevisionTypeLabel(revision.revision_type);

        return `
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200 p-6 mb-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h4 class="text-lg font-semibold text-gray-900">${revision.task.title}</h4>
                            <span class="text-xs px-3 py-1.5 bg-${typeInfo.color}-100 text-${typeInfo.color}-700 rounded-full font-medium border border-${typeInfo.color}-200">
                                ${typeInfo.icon} ${typeInfo.text}
                            </span>
                        </div>
                        <div class="flex items-center gap-4 text-sm text-gray-600">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>Oleh: <span class="font-medium text-gray-800">${revision.collaborator.name}</span></span>
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>${new Date(revision.created_at).toLocaleString('id-ID', { 
                                    day: 'numeric', 
                                    month: 'long', 
                                    year: 'numeric', 
                                    hour: '2-digit', 
                                    minute: '2-digit' 
                                })}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-6">
                    ${changesHtml}
                </div>
                
                <div class="flex gap-3 pt-4 border-t border-gray-100">
                    <button 
                        onclick="reviewRevision(${revision.id}, 'approve')" 
                        class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-100 transition-all duration-200 font-medium"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Setujui Perubahan
                    </button>
                    <button 
                        onclick="reviewRevision(${revision.id}, 'reject')" 
                        class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-100 transition-all duration-200 font-medium"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Tolak Perubahan
                    </button>
                </div>
            </div>
        `;
    }).join('');
}


function closeRevisionModal() {
    document.getElementById('revisionModal').classList.add('hidden');
}

// Fixed reviewRevision function
async function reviewRevision(revisionId, action) {
    console.log('Reviewing revision:', revisionId, 'Action:', action);
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        console.error('CSRF token not found');
        showNotification('Token keamanan tidak ditemukan', 'error');
        return;
    }

    try {
        const response = await fetch(`/collaboration/revisions/${revisionId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ action })
        });

        console.log('Response status:', response.status);
        const responseText = await response.text();
        console.log('Response text:', responseText);

        let result;
        try {
            result = JSON.parse(responseText);
        } catch (parseError) {
            console.error('Failed to parse JSON response:', parseError);
            showNotification('Respons server tidak valid', 'error');
            return;
        }

        if (result.success) {
            showNotification(`Revisi ${action === 'approve' ? 'disetujui' : 'ditolak'}!`, 'success');

            // Close modals
            closeRevisionModal();
            closeCollaborationModal();
            
            // Reload page to reflect changes
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            console.error('Server error:', result);
            showNotification(result.message || result.error || 'Gagal memproses revisi', 'error');
        }
    } catch (error) {
        console.error('Network error:', error);
        showNotification('Terjadi kesalahan jaringan', 'error');
    }
}

// Enhanced notification function
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
    
    // Animation in
    setTimeout(() => {
        notification.classList.remove('translate-x-full', 'opacity-0');
    }, 10);
    
    // Auto remove
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

// Initialize existing calendar and task management functions...
function showCalendarError(message) {
    console.error('Calendar error:', message);
    const errorElement = document.getElementById('calendar-error');
    const messageElement = document.getElementById('calendar-error-message');
    
    if (errorElement && messageElement) {
        messageElement.textContent = message;
        errorElement.classList.remove('hidden');
    }
    
    const fallback = document.getElementById('calendar-fallback');
    if (fallback) {
        fallback.classList.remove('hidden');
    }
    
    const calendar = document.getElementById('calendar');
    if (calendar) {
        calendar.style.display = 'none';
    }
}

function initializeCalendar() {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) {
        console.error('Calendar element not found');
        showCalendarError('Elemen kalender tidak ditemukan');
        return;
    }

    try {
        if (appState.calendar) {
            appState.calendar.destroy();
        }

        appState.calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            height: 700,
            nowIndicator: true,
            editable: false,
            selectable: true,
            selectMirror: true,
            dayMaxEvents: false,
            dayMaxEventRows: false,
            weekends: true,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: false
            },
            views: {
                dayGridMonth: {
                    dayMaxEvents: false,
                    dayMaxEventRows: false,
                    moreLinkClick: 'none',
                    eventDisplay: 'block'
                },
                timeGridWeek: {
                    slotMinTime: '06:00:00',
                    slotMaxTime: '22:00:00',
                    allDaySlot: true,
                    slotDuration: '01:00:00',
                    slotLabelInterval: '02:00:00',
                    dayMaxEvents: false,
                    dayMaxEventRows: false
                },
                timeGridDay: {
                    slotMinTime: '06:00:00',
                    slotMaxTime: '22:00:00',
                    allDaySlot: true,
                    slotDuration: '01:00:00',
                    slotLabelInterval: '02:00:00',
                    dayMaxEvents: false,
                    dayMaxEventRows: false
                },
                multiMonthYear: {
                    type: 'multiMonthYear',
                    duration: { years: 1 },
                    fixedWeekCount: false,
                    showNonCurrentDates: false,
                    dayMaxEvents: false,
                    dayMaxEventRows: false
                }
            },
            events: function(fetchInfo, successCallback, failureCallback) {
                const events = generateCalendarEvents();
                successCallback(events);
            },
            eventContent: renderEventContent,
            eventDidMount: function(info) {
                styleEvent(info);
            },
            eventClick: function(info) {
                const taskId = info.event.extendedProps.taskId;
                if (taskId) {
                    openTaskModal(taskId);
                }
            },
            eventMouseEnter: showTaskTooltip,
            eventMouseLeave: hideTaskTooltip,
            datesSet: function(dateInfo) {
                updateCalendarTitle(dateInfo);
                // Refresh events untuk navigasi
                setTimeout(() => {
                    refreshCalendarEvents();
                }, 100);
            }
        });

        appState.calendar.render();
        setupCalendarControls();
        updateCalendarTitle();
        
        console.log('Calendar initialized successfully');

    } catch (error) {
        console.error('Failed to initialize calendar:', error);
        showCalendarError('Gagal memuat komponen kalender: ' + error.message);
    }
}

function refreshCalendarEvents() {
    if (!appState.calendar) return;
    
    try {
        const events = generateCalendarEvents();
        appState.calendar.removeAllEvents();
        appState.calendar.addEventSource(events);
        console.log('Calendar events refreshed');
    } catch (error) {
        console.error('Error refreshing calendar events:', error);
    }
}

function generateCalendarEvents() {
    let events = [];
    
    appState.tasksData.forEach((task) => {
        const isAllDay = task.is_all_day || !task.start_time || !task.end_time;
        let eventStart, eventEnd;
        
        if (isAllDay) {
            eventStart = task.start_date;
            const endDate = new Date(task.end_date);
            endDate.setDate(endDate.getDate() + 1);
            eventEnd = endDate.toISOString().split('T')[0];
        } else {
            eventStart = `${task.start_date}T${task.start_time}`;
            eventEnd = `${task.end_date}T${task.end_time}`;
        }

        const leafSubTasks = task.sub_tasks ? task.sub_tasks.filter(st => 
            !task.sub_tasks.some(parent => parent.parent_id === st.id)
        ) : [];
        const completedCount = leafSubTasks.filter(st => st.completed).length;
        const totalCount = leafSubTasks.length;
        const progress = totalCount > 0 ? Math.round((completedCount / totalCount) * 100) : (task.completed ? 100 : 0);
        
        const mainEvent = {
            id: `task-${task.id}`,
            title: task.title,
            start: eventStart,
            end: eventEnd,
            allDay: isAllDay,
            backgroundColor: getTaskColor(task),
            borderColor: getTaskBorderColor(task),
            textColor: task.completed ? '#6B7280' : '#FFFFFF',
            display: 'block',
            extendedProps: {
                taskId: task.id,
                priority: task.priority,
                completed: task.completed,
                progress: progress,
                isSubtask: false,
                isMainTask: true,
                category: task.category?.name || 'Uncategorized',
                subtaskCount: totalCount,
                subtaskCompleted: completedCount,
                hasSubtasks: task.sub_tasks && task.sub_tasks.length > 0,
                description: task.description || '',
                durationDays: task.durationDays
            },
            classNames: [
                task.completed ? 'completed-task' : 'active-task',
                `priority-${task.priority}`,
                'main-task-event'
            ]
        };
        
        events.push(mainEvent);

        // Add subtasks hanya untuk view tertentu
        if (task.sub_tasks && task.sub_tasks.length > 0 && shouldShowSubtasks()) {
            task.sub_tasks.forEach((subtask, index) => {
                if (subtask.start_date || subtask.end_date) {
                    let subtaskStart = subtask.start_date || task.start_date;
                    let subtaskEnd = subtask.end_date || task.end_date;
                    
                    if (!isAllDay && subtask.start_time && subtask.end_time) {
                        subtaskStart = `${subtaskStart}T${subtask.start_time}`;
                        subtaskEnd = `${subtaskEnd}T${subtask.end_time}`;
                    } else if (isAllDay) {
                        const endDate = new Date(subtaskEnd);
                        endDate.setDate(endDate.getDate() + 1);
                        subtaskEnd = endDate.toISOString().split('T')[0];
                    }
                    
                    const level = getSubtaskLevel(subtask, task.sub_tasks);
                    
                    const subtaskEvent = {
                        id: `subtask-${subtask.id}`,
                        title: `${subtask.title}`,
                        start: subtaskStart,
                        end: subtaskEnd,
                        allDay: isAllDay,
                        backgroundColor: getSubtaskColor(task, subtask, level),
                        borderColor: getSubtaskBorderColor(task, subtask, level),
                        textColor: subtask.completed ? '#9CA3AF' : '#FFFFFF',
                        display: 'block',
                        extendedProps: {
                            taskId: task.id,
                            subtaskId: subtask.id,
                            priority: task.priority,
                            completed: subtask.completed,
                            isSubtask: true,
                            isMainTask: false,
                            parentCompleted: task.completed,
                            taskLevel: level + 1,
                            parentId: subtask.parent_id,
                            level: level,
                            description: subtask.description || '',
                            durationDays: calculateDurationDays(subtask.start_date || task.start_date, subtask.end_date || task.end_date)
                        },
                        classNames: [
                            subtask.completed ? 'completed-subtask' : 'active-subtask',
                            `priority-${task.priority}`,
                            `subtask-level-${level}`,
                            'subtask-event'
                        ]
                    };
                    
                    events.push(subtaskEvent);
                }
            });
        }
    });
    
    return events;
}

function shouldShowSubtasks() {
    const view = appState.currentView;
    return view === 'timeGridDay' || view === 'timeGridWeek' || view === 'dayGridMonth';
}

function calculateDurationDays(startDate, endDate) {
    const start = new Date(startDate);
    const end = new Date(endDate);
    const diffTime = Math.abs(end - start);
    return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
}

function getSubtaskLevel(subtask, allSubtasks) {
    let level = 0;
    let currentParent = subtask.parent_id;
    
    while (currentParent) {
        level++;
        const parent = allSubtasks.find(st => st.id === currentParent);
        currentParent = parent ? parent.parent_id : null;
    }
    
    return level;
}

function renderEventContent(arg) {
    const event = arg.event;
    const isCompleted = event.extendedProps.completed;
    const isSubtask = event.extendedProps.isSubtask || false;
    const isMainTask = event.extendedProps.isMainTask || false;
    const level = event.extendedProps.level || 0;
    const viewType = arg.view.type;
    const durationDays = event.extendedProps.durationDays || 1;
    const progress = event.extendedProps.progress || 0;

    if (viewType === 'timeGridDay' || viewType === 'timeGridWeek') {
        const today = new Date();
        const endDate = event.end ? new Date(event.end) : new Date(event.start);
        const timeDiff = endDate - today;
        const daysRemaining = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
        const countdownText = daysRemaining > 0 ? `${daysRemaining}d` : 'Today';

        const height = isMainTask ? 28 : 24 - (level * 2);
        const fontSize = isMainTask ? '12px' : `${11 - level}px`;
        const fontWeight = isMainTask ? '600' : '500';

        const priorityColors = {
            urgent: { bg: 'linear-gradient(135deg, #EF4444, #DC2626)', border: '#DC2626' },
            high: { bg: 'linear-gradient(135deg, #F59E0B, #D97706)', border: '#F59E0B' },
            medium: { bg: 'linear-gradient(135deg, #3B82F6, #2563EB)', border: '#3B82F6' },
            low: { bg: 'linear-gradient(135deg, #10B981, #059669)', border: '#10B981' }
        };
        const colors = priorityColors[event.extendedProps.priority] || priorityColors.medium;

        let subtaskInfo = '';
        if (isMainTask && event.extendedProps.subtaskCount > 0) {
            subtaskInfo = `
                <div class="text-xs text-blue-200 mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 8l2 2 4-4"></path>
                    </svg>
                    ${event.extendedProps.subtaskCompleted}/${event.extendedProps.subtaskCount} subtasks
                </div>
            `;
        }

        let timelineInfo = '';
        if (isMainTask) {
            timelineInfo = `
                <div class="text-xs text-blue-200 mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    ${durationDays} hari timeline
                </div>
            `;
        }

        return {
            html: `
                <div class="timegrid-event-container" style="
                    position: relative;
                    height: ${height}px;
                    ${isSubtask ? `margin-left: ${level * 20}px;` : ''}
                ">
                    <div class="timegrid-event-bar" style="
                        position: relative;
                        width: 100%;
                        height: 100%;
                        background: ${isCompleted ? '#E5E7EB' : colors.bg};
                        border-radius: 6px;
                        overflow: hidden;
                        border-left: ${isMainTask ? '4px' : '3px'} solid ${isCompleted ? '#9CA3AF' : colors.border};
                        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                    ">
                        ${!isCompleted && progress > 0 && progress < 100 ? `
                        <div class="timegrid-progress-overlay" style="
                            position: absolute;
                            top: 0;
                            left: 0;
                            height: 100%;
                            width: ${progress}%;
                            background: linear-gradient(90deg, rgba(34, 197, 94, 0.3), rgba(34, 197, 94, 0.5));
                            border-radius: 0 6px 6px 0;
                        "></div>
                        ` : ''}
                        <div class="timegrid-event-content" style="
                            position: absolute;
                            top: 6px;
                            left: 8px;
                            right: 60px;
                            color: ${isCompleted ? '#6B7280' : 'white'};
                            font-size: ${fontSize};
                            font-weight: ${fontWeight};
                            white-space: nowrap;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            text-shadow: ${isCompleted ? 'none' : '0 1px 2px rgba(0,0,0,0.4)'};
                        ">
                            ${event.title}
                            ${subtaskInfo}
                            ${timelineInfo}
                        </div>
                        <div class="timegrid-duration-badge" style="
                            position: absolute;
                            top: 50%;
                            right: 6px;
                            transform: translateY(-50%);
                            background: rgba(255,255,255,${isCompleted ? '0.8' : '0.95'});
                            color: ${isCompleted ? '#6B7280' : '#1F2937'};
                            font-size: ${parseInt(fontSize) - 2}px;
                            font-weight: 700;
                            padding: 2px 6px;
                            border-radius: 4px;
                            border: 1px solid rgba(0,0,0,0.1);
                            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                        ">
                            ${countdownText}
                        </div>
                    </div>
                </div>
            `
        };
    } else {
        const height = isMainTask ? '24px' : '20px';
        const fontSize = isMainTask ? '11px' : '10px';
        const fontWeight = isMainTask ? '600' : '500';

        return {
            html: `
                <div class="gantt-compact-bar" style="
                    width: 100%;
                    height: ${height};
                    background: ${isCompleted ? '#E5E7EB' : (isMainTask ? 'linear-gradient(135deg, #3B82F6, #1D4ED8)' : 'linear-gradient(135deg, #93C5FD, #60A5FA)')};
                    border-radius: 4px;
                    position: relative;
                    border-left: ${isMainTask ? '3px' : '2px'} solid ${getTimelineBorderColor(event.extendedProps.priority, isCompleted)};
                    margin: 2px 0;
                    overflow: hidden;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                ">
                    <div class="gantt-compact-content" style="
                        position: absolute;
                        top: 50%;
                        left: ${isSubtask ? (6 + level * 2) : 6}px;
                        transform: translateY(-50%);
                        color: ${isCompleted ? '#6B7280' : 'white'};  
                        font-size: ${fontSize};
                        font-weight: ${fontWeight};
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        max-width: calc(100% - 40px);
                        text-shadow: 0 1px 2px rgba(0,0,0,0.3);
                    ">
                        ${event.title}
                    </div>
                    <div class="gantt-compact-duration" style="
                        position: absolute;
                        top: 50%;
                        right: 4px;
                        transform: translateY(-50%);
                        color: rgba(255,255,255,0.9);
                        font-size: 7px;
                        font-weight: 700;
                        background: rgba(0,0,0,0.2);
                        padding: 1px 3px;
                        border-radius: 2px;
                    ">
                        ${durationDays}d
                    </div>
                    ${!isCompleted && progress > 0 && progress < 100 ? `
                    <div class="gantt-compact-progress" style="
                        position: absolute;
                        bottom: 2px;
                        left: 2px;
                        right: 2px;
                        height: 2px;
                        background: rgba(255,255,255,0.3);
                        border-radius: 1px;
                        overflow: hidden;
                    ">
                        <div style="
                            width: ${progress}%;
                            height: 100%;
                            background: rgba(34, 197, 94, 0.8);
                            border-radius: 1px;
                        "></div>
                    </div>
                    ` : ''}
                </div>
            `
        };
    }
}

function styleEvent(info) {
    const event = info.event;
    const isCompleted = event.extendedProps.completed;
    const priority = event.extendedProps.priority;
    const isSubtask = event.extendedProps.isSubtask || false;
    const level = event.extendedProps.level || 0;

    info.el.style.border = 'none';
    info.el.style.padding = '0';
    info.el.style.margin = '0';
    info.el.style.backgroundColor = 'transparent';
    info.el.style.cursor = 'pointer';
    info.el.style.transition = 'all 0.2s ease';
    info.el.style.position = 'relative';

    if (isCompleted) {
        info.el.style.opacity = isSubtask ? '0.6' : '0.7';
    }

    info.el.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-2px) scale(1.02)';
        this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.15)';
        this.style.zIndex = '25';
    });
    
    info.el.addEventListener('mouseleave', function() {
        this.style.transform = '';
        this.style.boxShadow = '';
        this.style.zIndex = '';
    });
}

function getTaskColor(task) {
    if (task.completed) return 'linear-gradient(135deg, #E5E7EB, #D1D5DB)';
    
    switch(task.priority) {
        case 'urgent': return 'linear-gradient(135deg, #EF4444, #DC2626)';
        case 'high': return 'linear-gradient(135deg, #F59E0B, #D97706)';
        case 'medium': return 'linear-gradient(135deg, #3B82F6, #2563EB)';
        case 'low': return 'linear-gradient(135deg, #10B981, #059669)';
        default: return 'linear-gradient(135deg, #6B7280, #4B5563)';
    }
}

function getTaskBorderColor(task) {
    if (task.completed) return '#9CA3AF';
    
    switch(task.priority) {
        case 'urgent': return '#DC2626';
        case 'high': return '#D97706';
        case 'medium': return '#2563EB';
        case 'low': return '#059669';
        default: return '#4B5563';
    }
}

function getSubtaskColor(task, subtask, level) {
    const baseOpacity = Math.max(0.6, 0.9 - (level * 0.1));
    
    if (subtask.completed) return `rgba(209, 213, 219, ${baseOpacity})`;
    
    switch(task.priority) {
        case 'urgent': return `rgba(239, 68, 68, ${baseOpacity})`;
        case 'high': return `rgba(245, 158, 11, ${baseOpacity})`;
        case 'medium': return `rgba(59, 130, 246, ${baseOpacity})`;
        case 'low': return `rgba(16, 185, 129, ${baseOpacity})`;
        default: return `rgba(107, 114, 128, ${baseOpacity})`;
    }
}

function getSubtaskBorderColor(task, subtask, level) {
    if (subtask.completed) return '#D1D5DB';
    
    const colors = {
        'urgent': '#FCA5A5',
        'high': '#FCD34D',
        'medium': '#93C5FD',
        'low': '#6EE7B7'
    };
    
    return colors[task.priority] || '#9CA3AF';
}

function getTimelineBorderColor(priority, isCompleted) {
    if (isCompleted) return '#9CA3AF';
    
    switch(priority) {
        case 'urgent': return '#DC2626';
        case 'high': return '#EA580C';
        case 'medium': return '#2563EB';
        case 'low': return '#059669';
        default: return '#6B7280';
    }
}

function setupCalendarControls() {
    const todayBtn = document.getElementById('today-btn');
    if (todayBtn) {
        todayBtn.addEventListener('click', function() {
            if (appState.calendar) {
                appState.calendar.today();
                updateCalendarTitle();
                setTimeout(() => {
                    refreshCalendarEvents();
                }, 100);
            }
        });
    }
    
    const viewButtons = {
        'day-view': 'timeGridDay',
        'week-view': 'timeGridWeek', 
        'month-view': 'dayGridMonth',
        'year-view': 'multiMonthYear'
    };
    
    Object.entries(viewButtons).forEach(([buttonId, viewName]) => {
        const button = document.getElementById(buttonId);
        if (button) {
            button.addEventListener('click', function() {
                try {
                    if (appState.calendar) {
                        appState.calendar.changeView(viewName);
                        appState.currentView = viewName;
                        setActiveViewButton(buttonId);
                        updateCalendarTitle();
                        
                        setTimeout(() => {
                            refreshCalendarEvents();
                        }, 100);
                    }
                } catch (error) {
                    console.error(`Error changing to view ${viewName}:`, error);
                    showNotification(`Tampilan ${buttonId.replace('-view', '')} tidak tersedia`, 'error');
                }
            });
        }
    });
}

function setActiveViewButton(activeButtonId) {
    const viewButtons = document.querySelectorAll('.view-btn');
    viewButtons.forEach(btn => {
        btn.classList.remove('bg-white', 'text-blue-600', 'shadow-sm', 'active-view');
        btn.classList.add('text-gray-600');
    });
    
    const activeButton = document.getElementById(activeButtonId);
    if (activeButton) {
        activeButton.classList.add('bg-white', 'text-blue-600', 'shadow-sm', 'active-view');
        activeButton.classList.remove('text-gray-600');
    }
}

function updateCalendarTitle(dateInfo) {
    const titleElement = document.getElementById('calendar-title');
    if (!titleElement || !appState.calendar) return;
    
    const view = appState.calendar.view;
    const viewDate = dateInfo ? dateInfo.start : view.currentStart;
    let title = 'Timeline Proyek';
    
    try {
        if (view.type === 'timeGridDay') {
            title = `Timeline - ${viewDate.toLocaleDateString('id-ID', { 
                weekday: 'long', 
                day: 'numeric', 
                month: 'long', 
                year: 'numeric' 
            })}`;
        } else if (view.type === 'timeGridWeek') {
            const start = viewDate;
            const end = new Date(view.currentEnd);
            end.setDate(end.getDate() - 1);
            
            if (start.getMonth() === end.getMonth()) {
                title = `Timeline - ${start.getDate()} - ${end.getDate()} ${start.toLocaleDateString('id-ID', { month: 'long' })} ${start.getFullYear()}`;
            } else {
                title = `Timeline - ${start.getDate()} ${start.toLocaleDateString('id-ID', { month: 'short' })} - ${end.getDate()} ${end.toLocaleDateString('id-ID', { month: 'short' })} ${start.getFullYear()}`;
            }
        } else if (view.type === 'dayGridMonth') {
            title = `Timeline - ${viewDate.toLocaleDateString('id-ID', { 
                month: 'long', 
                year: 'numeric' 
            })}`;
        } else if (view.type === 'multiMonthYear' || view.type === 'multiMonth') {
            title = `Timeline - ${viewDate.getFullYear()}`;
        }
    } catch (error) {
        console.error('Error updating calendar title:', error);
        title = 'Timeline Proyek';
    }
    
    titleElement.textContent = title;
}

function initializeTooltip() {
    appState.tooltip = document.getElementById('taskTooltip');
}

function showTaskTooltip(info) {
    const taskId = info.event.extendedProps.taskId;
    const isSubtask = info.event.extendedProps.isSubtask;
    const level = info.event.extendedProps.level || 0;
    const task = appState.tasksData.find(t => t.id == taskId);
    if (!task || !appState.tooltip) return;

    const priorityLabels = {
        'urgent': 'Sangat Mendesak',
        'high': 'Tinggi', 
        'medium': 'Sedang',
        'low': 'Rendah'
    };

    const timeInfo = (task.start_time && task.end_time && !task.is_all_day)
        ? `<div class="flex items-center gap-1 text-xs text-gray-500 mt-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            ${task.start_time} - ${task.end_time}
          </div>`
        : '<div class="text-xs text-gray-500 mt-1">📅 Timeline Harian Penuh</div>';

    const leafSubTasks = task.sub_tasks ? task.sub_tasks.filter(st => 
        !task.sub_tasks.some(parent => parent.parent_id === st.id)
    ) : [];
    const subtaskCompleted = leafSubTasks.filter(st => st.completed).length;
    const subtaskTotal = leafSubTasks.length;
    const progressPercentage = subtaskTotal > 0 ? Math.round((subtaskCompleted / subtaskTotal) * 100) : (task.completed ? 100 : 0);

    const durationInfo = `<div class="flex items-center gap-1 text-xs text-blue-600 mt-1 font-semibold">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7"></path>
        </svg>
        Timeline: ${task.durationDays} hari (${formatDateString(task.start_date)} → ${formatDateString(task.end_date)})
    </div>`;

    const hierarchyInfo = isSubtask ? `
        <div class="text-xs text-purple-600 bg-purple-50 px-2 py-1 rounded-full border border-purple-200">
            📊 Level ${level + 1} Subtask
        </div>
    ` : '';

    const tooltipContent = document.getElementById('tooltipContent');
    tooltipContent.innerHTML = `
        <div class="space-y-3">
            <div class="font-semibold text-gray-800 ${task.completed ? 'line-through' : ''} flex items-center gap-2">
                ${isSubtask ? '📝' : '📋'} ${task.title}
                ${hierarchyInfo}
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <span class="px-2 py-1 text-xs rounded-full ${getPriorityBadgeClass(task.priority)}">
                    ${priorityLabels[task.priority] || 'Normal'}
                </span>
                ${task.completed ? '<span class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">✅ Selesai</span>' : ''}
                ${subtaskTotal > 0 && !isSubtask ? `<span class="px-2 py-1 text-xs bg-blue-100 text-blue-600 rounded-full">📊 ${progressPercentage}% Progress</span>` : ''}
                ${subtaskTotal > 0 && !isSubtask ? `<span class="px-2 py-1 text-xs bg-green-100 text-green-600 rounded-full">📋 ${subtaskTotal} Subtasks</span>` : ''}
            </div>
            <div class="text-xs text-gray-600 bg-gray-50 p-2 rounded-lg border">
                ${durationInfo}
                ${timeInfo}
            </div>
            ${task.description ? `<div class="text-xs text-gray-600 border-t pt-2 mt-2 bg-blue-50 p-2 rounded-lg">${task.description.substring(0, 100)}${task.description.length > 100 ? '...' : ''}</div>` : ''}
            <div class="text-xs text-blue-600 font-medium flex items-center gap-1 pt-2 border-t">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Klik untuk detail lengkap timeline
            </div>
        </div>
    `;

    const rect = info.el.getBoundingClientRect();
    const tooltipRect = appState.tooltip.getBoundingClientRect();
    
    let left = rect.right + 10;
    let top = rect.top;
    
    if (left + tooltipRect.width > window.innerWidth) {
        left = rect.left - tooltipRect.width - 10;
    }
    
    if (top + tooltipRect.height > window.innerHeight) {
        top = window.innerHeight - tooltipRect.height - 10;
    }
    
    appState.tooltip.style.left = left + 'px';
    appState.tooltip.style.top = top + 'px';
    
    appState.tooltip.classList.remove('hidden');
    appState.tooltip.style.opacity = '0';
    appState.tooltip.style.transform = 'translateY(-10px)';
    
    setTimeout(() => {
        appState.tooltip.style.transition = 'all 0.2s ease-out';
        appState.tooltip.style.opacity = '1';
        appState.tooltip.style.transform = 'translateY(0)';
    }, 10);
}

function hideTaskTooltip() {
    if (appState.tooltip) {
        appState.tooltip.style.opacity = '0';
        appState.tooltip.style.transform = 'translateY(-10px)';
        setTimeout(() => {
            appState.tooltip.classList.add('hidden');
        }, 200);
    }
}

function getPriorityBadgeClass(priority) {
    switch(priority) {
        case 'urgent':
            return 'bg-red-100 text-red-800 border border-red-300';
        case 'high':
            return 'bg-orange-100 text-orange-800 border border-orange-300';
        case 'medium':
            return 'bg-blue-100 text-blue-800 border border-blue-300';
        case 'low':
            return 'bg-green-100 text-green-800 border border-green-300';
        default:
            return 'bg-gray-100 text-gray-800 border border-gray-300';
    }
}

function formatDateString(dateString) {
    return new Date(dateString).toLocaleDateString('id-ID', { 
        day: 'numeric', 
        month: 'short', 
        year: 'numeric' 
    });
}

function initializeTaskFilter() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            setActiveFilter(filter);
            filterTasks(filter);
        });
    });
    
    filterTasks('all');
}

function setActiveFilter(filter) {
    appState.currentFilter = filter;
    
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active', 'bg-blue-100', 'text-blue-600', 'border-blue-300');
        btn.classList.add('bg-gray-100', 'text-gray-600');
    });
    
    const activeButton = document.querySelector(`[data-filter="${filter}"]`);
    if (activeButton) {
        activeButton.classList.add('active', 'bg-blue-100', 'text-blue-600', 'border-blue-300');
        activeButton.classList.remove('bg-gray-100', 'text-gray-600');
    }
}

function filterTasks(filter) {
    const taskItems = document.querySelectorAll('.task-item');
    const emptyState = document.getElementById('filter-empty-state');
    const filteredCount = document.getElementById('filtered-count');
    
    let visibleCount = 0;
    
    taskItems.forEach(item => {
        const taskStatus = item.getAttribute('data-task-status');
        let shouldShow = false;
        
        switch(filter) {
            case 'all':
                shouldShow = true;
                break;
            case 'active':
                shouldShow = taskStatus === 'active';
                break;
            case 'completed':
                shouldShow = taskStatus === 'completed';
                break;
        }
        
        if (shouldShow) {
            showTaskItem(item);
            visibleCount++;
        } else {
            hideTaskItem(item);
        }
    });
    
    appState.filteredTasksCount = visibleCount;
    updateFilteredCountDisplay(filter, visibleCount);
    
    if (visibleCount === 0) {
        showEmptyState(filter);
    } else {
        hideEmptyState();
    }
}

function showTaskItem(item) {
    item.style.display = 'block';
    item.style.opacity = '0';
    item.style.transform = 'translateY(20px)';
    
    item.offsetHeight;
    
    item.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
    item.style.opacity = '1';
    item.style.transform = 'translateY(0)';
}

function hideTaskItem(item) {
    item.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
    item.style.opacity = '0';
    item.style.transform = 'translateY(-20px)';
    
    setTimeout(() => {
        item.style.display = 'none';
    }, 300);
}

function updateFilteredCountDisplay(filter, count) {
    const filteredCount = document.getElementById('filtered-count');
    
    if (filter === 'all') {
        filteredCount.classList.add('hidden');
    } else {
        filteredCount.classList.remove('hidden');
        const filterLabels = {
            'active': 'Aktif',
            'completed': 'Selesai'
        };
        filteredCount.textContent = `${count} ${filterLabels[filter]}`;
    }
}

function showEmptyState(filter) {
    const emptyState = document.getElementById('filter-empty-state');
    const title = document.getElementById('empty-state-title');
    const description = document.getElementById('empty-state-description');
    
    if (!emptyState || !title || !description) {
        console.error('Empty state elements not found');
        return;
    }
    
    const defaultMessages = {
        'all': {
            title: 'Belum ada tugas',
            description: 'Mulai dengan membuat tugas pertama Anda.'
        },
        'active': {
            title: 'Tidak ada tugas aktif',
            description: 'Semua tugas telah diselesaikan atau belum ada tugas yang dibuat.'
        },
        'completed': {
            title: 'Belum ada tugas selesai',
            description: 'Selesaikan tugas untuk melihatnya di sini.'
        }
    };
    
    const messages = defaultMessages[filter] || defaultMessages.all;
    
    title.textContent = messages.title;
    description.textContent = messages.description;
    
    emptyState.style.display = 'block';
    emptyState.style.opacity = '0';
    emptyState.style.transform = 'translateY(20px)';
    
    setTimeout(() => {
        emptyState.style.transition = 'all 0.3s ease-out';
        emptyState.style.opacity = '1';
        emptyState.style.transform = 'translateY(0)';
    }, 10);
    
    emptyState.classList.remove('hidden');
}

function hideEmptyState() {
    const emptyState = document.getElementById('filter-empty-state');
    
    emptyState.style.transition = 'all 0.3s ease-out';
    emptyState.style.opacity = '0';
    emptyState.style.transform = 'translateY(-20px)';
    
    setTimeout(() => {
        emptyState.classList.add('hidden');
        emptyState.style.display = 'none';
    }, 300);
}

function resetFilter() {
    setActiveFilter('all');
    filterTasks('all');
}

function initializeEventDelegation() {
    const taskListContainer = document.getElementById('task-list-container');
    if (taskListContainer) {
        taskListContainer.addEventListener('change', handleTaskListChange);
        taskListContainer.addEventListener('click', handleTaskListClick);
    }
    
    document.addEventListener('change', handleModalChange);
    document.addEventListener('click', handleModalClick);
    
    const taskModal = document.getElementById('taskModal');
    if (taskModal) {
        taskModal.addEventListener('click', function(e) {
            if (e.target === this) closeTaskModal();
        });
    }
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeTaskModal();
            hideTaskTooltip();
        }
    });
}

function handleTaskListChange(e) {
    if (appState.isUpdating) return;
    
    if (e.target.classList.contains('task-checkbox')) {
        performTaskUpdate(e.target, 'main');
    } else if (e.target.classList.contains('subtask-checkbox')) {
        performSubtaskUpdate(e.target, 'main');
    }
}

function handleTaskListClick(e) {
    if (e.target.closest('.subtask-toggle-btn')) {
        toggleSubtasks(e.target.closest('.subtask-toggle-btn'));
    } else if (e.target.closest('.subtask-parent-toggle-btn')) {
        toggleSubtaskParent(e.target.closest('.subtask-parent-toggle-btn'));
    } else if (e.target.closest('.task-title')) {
        const taskId = e.target.closest('.task-title').getAttribute('data-task-id');
        openTaskModal(taskId);
    }
}

function handleModalChange(e) {
    if (appState.isUpdating || !appState.isModalOpen) return;
    
    if (e.target.classList.contains('task-checkbox-modal')) {
        performTaskUpdate(e.target, 'modal');
    } else if (e.target.classList.contains('subtask-checkbox-modal')) {
        performSubtaskUpdate(e.target, 'modal');
    }
}

function handleModalClick(e) {
    if (!appState.isModalOpen) return;
    
    if (e.target.closest('.subtask-parent-toggle-btn-modal')) {
        toggleModalSubtaskParent(e.target.closest('.subtask-parent-toggle-btn-modal'));
    }
}

async function performTaskUpdate(checkbox, source) {
    if (appState.isUpdating) return;
    
    const taskId = checkbox.getAttribute('data-task-id');
    const isCompleted = checkbox.checked;
    const form = checkbox.closest('form');
    const url = form.getAttribute('action');
    const token = form.querySelector('input[name="_token"]').value;
    
    appState.isUpdating = true;
    showLoadingIndicator();
    showAutoSaveIndicator();
    
    try {
        const response = await fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ completed: isCompleted })
        });
        
        const data = await response.json();
        
        if (data.success) {
            updateTasksData(taskId, data, 'task');
            syncTaskUIUpdates(taskId, data, source);
            updateCalendarEvent(taskId, data.task.completed);
            updateSummaryUI(data);
            updateTaskFilter(taskId, data.task.completed ? 'completed' : 'active');
            
            showNotification('Tugas berhasil diperbarui!', 'success');
            checkAllTasksCompleted();
        } else {
            throw new Error(data.message || 'Update failed');
        }
    } catch (error) {
        console.error('Error:', error);
        checkbox.checked = !isCompleted;
        showNotification('Gagal memperbarui tugas!', 'error');
    } finally {
        appState.isUpdating = false;
        hideLoadingIndicator();
        hideAutoSaveIndicator();
    }
}

async function performSubtaskUpdate(checkbox, source) {
    if (appState.isUpdating) return;
    
    const subtaskId = checkbox.getAttribute('data-sub-task-id');
    const taskId = checkbox.getAttribute('data-task-id');
    const isCompleted = checkbox.checked;
    const form = checkbox.closest('form');
    
    if (!form) {
        console.error('Form element not found for subtask');
        return;
    }
    
    const url = form.getAttribute('action');
    const token = form.querySelector('input[name="_token"]')?.value;
    
    if (!url || !token) {
        console.error('Missing URL or CSRF token');
        return;
    }
    
    appState.isUpdating = true;
    showLoadingIndicator();
    showAutoSaveIndicator();
    
    try {
        const response = await fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ completed: isCompleted })
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success) {
            updateTasksData(taskId, data, 'subtask');
            syncSubtaskUIUpdates(subtaskId, taskId, data, source);
            updateCalendarEvent(taskId, data.task.completed);
            updateSummaryUI(data);
            updateTaskFilter(taskId, data.task.completed ? 'completed' : 'active');
            
            showNotification('Subtugas berhasil diperbarui!', 'success');
            checkAllTasksCompleted();
        } else {
            throw new Error(data.message || 'Update failed');
        }
    } catch (error) {
        console.error('Error:', error);
        checkbox.checked = !isCompleted;
        showNotification('Gagal memperbarui subtugas! ' + error.message, 'error');
    } finally {
        appState.isUpdating = false;
        hideLoadingIndicator();
        hideAutoSaveIndicator();
    }
}

function syncTaskUIUpdates(taskId, data, source) {
    const task = data.task;
    
    const mainCheckbox = document.querySelector(`#task-item-${taskId} .task-checkbox`);
    const mainTitle = document.querySelector(`#task-item-${taskId} .task-title`);
    
    if (mainCheckbox && source !== 'main') {
        mainCheckbox.checked = task.completed;
    }
    
    if (mainTitle) {
        if (task.completed) {
            mainTitle.classList.add('line-through');
            mainTitle.style.color = '#9ca3af';
        } else {
            mainTitle.classList.remove('line-through');
            mainTitle.style.color = '';
        }
    }
    
    if (appState.isModalOpen && appState.currentModalTaskId == taskId) {
        const modalCheckbox = document.querySelector(`.task-checkbox-modal[data-task-id="${taskId}"]`);
        const modalTitle = document.getElementById(`modal-task-title-${taskId}`);
        
        if (modalCheckbox && source !== 'modal') {
            modalCheckbox.checked = task.completed;
        }
        
        if (modalTitle) {
            if (task.completed) {
                modalTitle.classList.add('line-through');
                modalTitle.style.color = '#9ca3af';
            } else {
                modalTitle.classList.remove('line-through');
                modalTitle.style.color = '';
            }
        }
        
        updateAllModalSubtasks(taskId, task.completed);
    }
    
    updateAllMainSubtasks(taskId, task.completed);
    updateTaskProgress(taskId, data);
}

function syncSubtaskUIUpdates(subtaskId, taskId, data, source) {
    const subtask = data.subtask;

    const mainSubtaskCheckbox = document.querySelector(`[data-sub-task-id="${subtaskId}"]:not(.subtask-checkbox-modal)`);
    const mainSubtaskText = mainSubtaskCheckbox?.parentElement?.nextElementSibling;

    if (mainSubtaskCheckbox && source !== 'main') {
        mainSubtaskCheckbox.checked = subtask.completed;
    }

    if (mainSubtaskText) {
        updateTextStyle(mainSubtaskText, subtask.completed);
    }

    if (appState.isModalOpen && appState.currentModalTaskId == taskId) {
        const modalSubtaskCheckbox = document.querySelector(`.subtask-checkbox-modal[data-sub-task-id="${subtaskId}"]`);
        const modalSubtaskText = modalSubtaskCheckbox?.parentElement?.nextElementSibling;

        if (modalSubtaskCheckbox && source !== 'modal') {
            modalSubtaskCheckbox.checked = subtask.completed;
        }

        if (modalSubtaskText) {
            updateTextStyle(modalSubtaskText, subtask.completed);
        }

        updateModalProgress(taskId);
    }

    updateTaskProgress(taskId, data);

    const mainTaskCheckbox = document.querySelector(`#task-item-${taskId} .task-checkbox`);
    if (mainTaskCheckbox) {
        mainTaskCheckbox.checked = data.task.completed;
    }

    if (appState.isModalOpen && appState.currentModalTaskId == taskId) {
        const modalTaskCheckbox = document.querySelector(`.task-checkbox-modal[data-task-id="${taskId}"]`);
        if (modalTaskCheckbox) {
            modalTaskCheckbox.checked = data.task.completed;
        }
    }

    const task = appState.tasksData.find(t => t.id == taskId);
    if (task && task.sub_tasks) {
        const leafSubTasks = task.sub_tasks.filter(st =>
            !task.sub_tasks.some(parent => parent.parent_id === st.id)
        );
        const completedCount = leafSubTasks.filter(st => st.completed).length;
        const isAllCompleted = leafSubTasks.length > 0 && completedCount === leafSubTasks.length;

        const mainTitle = document.querySelector(`#task-item-${taskId} .task-title`);
        const modalTitle = document.getElementById(`modal-task-title-${taskId}`);

        if (mainTitle) {
            if (isAllCompleted) {
                mainTitle.classList.add('line-through', 'text-gray-400');
            } else {
                mainTitle.classList.remove('line-through', 'text-gray-400');
            }
        }

        if (modalTitle) {
            if (isAllCompleted) {
                modalTitle.classList.add('line-through', 'text-gray-400');
            } else {
                modalTitle.classList.remove('line-through', 'text-gray-400');
            }
        }
    }
}

function updateTextStyle(element, completed) {
    if (completed) {
        element.classList.add('line-through');
        element.style.color = '#9ca3af';
    } else {
        element.classList.remove('line-through');
        element.style.color = '';
    }
}

function updateAllMainSubtasks(taskId, completed) {
    const subtaskCheckboxes = document.querySelectorAll(`#task-item-${taskId} .subtask-checkbox`);
    subtaskCheckboxes.forEach(checkbox => {
        checkbox.checked = completed;
        const text = checkbox.parentElement?.nextElementSibling;
        if (text) updateTextStyle(text, completed);
    });
}

function updateAllModalSubtasks(taskId, completed) {
    const modalSubtaskCheckboxes = document.querySelectorAll(`.subtask-checkbox-modal[data-task-id="${taskId}"]`);
    modalSubtaskCheckboxes.forEach(checkbox => {
        checkbox.checked = completed;
        const text = checkbox.parentElement?.nextElementSibling;
        if (text) updateTextStyle(text, completed);
    });
}

function updateTaskProgress(taskId, data) {
    if (typeof data.progressPercentage !== 'number') return;

    const taskItem = document.getElementById(`task-item-${taskId}`);
    if (!taskItem) return;
    
    const progressPercentage = taskItem.querySelector('.task-progress-percentage');
    const subtaskProgressText = taskItem.querySelector('.subtask-progress-text');
    const subtaskProgressBar = taskItem.querySelector('.subtask-progress-bar');
    
    if (progressPercentage) {
        progressPercentage.textContent = `${data.progressPercentage}%`;
    }
    
    if (subtaskProgressText) {
        subtaskProgressText.textContent = `Subtugas (${data.subtaskCompleted}/${data.subtaskTotal})`;
    }
    
    if (subtaskProgressBar) {
        subtaskProgressBar.style.width = `${data.progressPercentage}%`;
    }
}

function updateModalProgress(taskId) {
    if (!appState.isModalOpen || appState.currentModalTaskId != taskId) return;
    
    const task = appState.tasksData.find(t => t.id == taskId);
    if (!task || !task.sub_tasks) return;
    
    const leafSubTasks = task.sub_tasks.filter(st => 
        !task.sub_tasks.some(parent => parent.parent_id === st.id)
    );
    const subtaskCompleted = leafSubTasks.filter(st => st.completed).length;
    const subtaskTotal = leafSubTasks.length;
    const progressPercentage = subtaskTotal > 0 ? Math.round((subtaskCompleted / subtaskTotal) * 100) : 0;
    
    const progressBar = document.getElementById('modal-progress-bar');
    const progressPercentageEl = document.getElementById('modal-progress-percentage');
    const subtaskCount = document.getElementById('modal-subtask-count');
    const progressBadge = document.getElementById('modal-progress-badge');
    
    if (progressBar) progressBar.style.width = `${progressPercentage}%`;
    if (progressPercentageEl) progressPercentageEl.textContent = `${progressPercentage}%`;
    if (subtaskCount) subtaskCount.textContent = `${subtaskCompleted}/${subtaskTotal}`;
    if (progressBadge) progressBadge.textContent = `${progressPercentage}% Progress`;
}

function updateTasksData(taskId, data, type) {
    const taskIndex = appState.tasksData.findIndex(t => t.id == taskId);
    if (taskIndex === -1) return;
    
    appState.tasksData[taskIndex].completed = data.task.completed;
    
    if (appState.tasksData[taskIndex].sub_tasks) {
        if (type === 'task') {
            appState.tasksData[taskIndex].sub_tasks.forEach(st => {
                st.completed = data.task.completed;
            });
        } else if (type === 'subtask' && data.subtask) {
            const subtaskIndex = appState.tasksData[taskIndex].sub_tasks.findIndex(st => st.id == data.subtask.id);
            if (subtaskIndex !== -1) {
                appState.tasksData[taskIndex].sub_tasks[subtaskIndex].completed = data.subtask.completed;
            }
            const allCompleted = appState.tasksData[taskIndex].sub_tasks.every(st => st.completed);
            appState.tasksData[taskIndex].completed = allCompleted;
        }
    }
        updateModalProgress(taskId);
}
    

function updateCalendarEvent(taskId, completed) {
    if (!appState.calendar) return;
    
    // Refresh all events instead of updating individual ones
    setTimeout(() => {
        refreshCalendarEvents();
    }, 100);
}

function updateSummaryUI(data) {
    if (data.totalTasks !== undefined) {
        const totalTasksEl = document.getElementById('total-tasks-count');
        if (totalTasksEl) totalTasksEl.textContent = data.totalTasks;
        appState.totalTasks = data.totalTasks;
    }
    
    if (data.completedTasks !== undefined) {
        const completedTasksEl = document.getElementById('completed-tasks-count');
        if (completedTasksEl) completedTasksEl.textContent = data.completedTasks;
        appState.completedTasks = data.completedTasks;
    }
    
    if (data.overallProgress !== undefined) {
        const overallProgressEl = document.getElementById('overall-progress-percentage');
        if (overallProgressEl) overallProgressEl.textContent = `${data.overallProgress}%`;
    }
}

function checkAllTasksCompleted() {
    const allCompleted = appState.tasksData.every(task => task.completed);
    const hasActiveTasks = appState.tasksData.length > 0;
    
    if (allCompleted && hasActiveTasks && !appState.allTasksCompleted) {
        appState.allTasksCompleted = true;
        updateCalendarCompletionIndicator(true);
    } else if (!allCompleted && appState.allTasksCompleted) {
        appState.allTasksCompleted = false;
        updateCalendarCompletionIndicator(false);
    }
}

function updateCalendarCompletionIndicator(show) {
    const indicator = document.getElementById('calendar-completion-indicator');
    if (indicator) {
        if (show) {
            indicator.classList.remove('hidden');
        } else {
            indicator.classList.add('hidden');
        }
    }
}

function updateTaskFilter(taskId, newStatus) {
    const taskItem = document.getElementById(`task-item-${taskId}`);
    if (taskItem) {
        taskItem.setAttribute('data-task-status', newStatus);
        filterTasks(appState.currentFilter);
    }
}

function renderModalSubtasks(subtasks, parentId = null, task, level = 0) {
    let html = '';
    
    const filteredSubtasks = subtasks.filter(st => st.parent_id === parentId);
    
    filteredSubtasks.forEach(subTask => {
        const isParent = subtasks.some(st => st.parent_id === subTask.id);
        const indentClass = level > 0 ? `ml-${level * 6}` : '';
        const treeLineClass = level > 0 ? 'border-l-2 border-gray-200 pl-4' : '';
        
        const dateInfo = (subTask.start_date || subTask.end_date) ? 
            `<div class="subtask-date" style="margin-top: 2px;">
                <svg style="width: 10px; height: 10px; display: inline; margin-right: 3px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span style="font-size: 8px; color: #6B7280;">
                    ${subTask.start_date ? formatDateString(subTask.start_date) : ''} ${subTask.end_date ? ' → ' + formatDateString(subTask.end_date) : ''}
                </span>
            </div>` : '';
        
        if (isParent) {
            html += `
                <div class="subtask-parent-modal relative bg-white rounded-lg p-2 border border-gray-200 ${treeLineClass}" data-subtask-id="${subTask.id}">
                    ${level > 0 ? `<div class="absolute left-0 top-0 w-3 h-6 border-l-2 border-b-2 border-gray-300 rounded-bl-md"></div>` : ''}
                    <div class="flex items-center gap-2 py-1 ${indentClass}">
                        <button class="subtask-parent-toggle-btn-modal text-gray-400 hover:text-blue-600 transition-all duration-200 p-1 rounded-lg hover:bg-blue-50" 
                                data-subtask-id="${subTask.id}" 
                                data-expanded="true">
                            <svg class="w-3 h-3 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="w-2 h-2 bg-blue-400 rounded-full flex-shrink-0"></div>
                        <span class="text-xs font-semibold text-gray-700">📁 ${subTask.title}</span>
                    </div>
                    ${dateInfo}
                    <div class="subtask-children-modal relative border-l-2 border-gray-200 ml-4" id="modal-subtask-children-${subTask.id}">
                        ${renderModalSubtasks(subtasks, subTask.id, task, level + 1)}
                    </div>
                </div>
            `;
        } else {
            const lineClass = subTask.completed ? 'line-through text-gray-400' : 'text-gray-700';
            html += `
                <div class="subtask-item-modal relative flex items-center gap-2 py-1 px-2 bg-white rounded border border-gray-200 hover:border-blue-300 transition-all duration-200 ${treeLineClass}" data-subtask-id="${subTask.id}">
                    ${level > 0 ? `<div class="absolute left-0 top-0 w-4 h-6 border-l-2 border-b-2 border-gray-300 rounded-bl-md"></div>` : ''}
                    <form action="/subtasks/${subTask.id}/toggle" method="POST" class="subtask-toggle-form-modal">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="checkbox"
                            class="subtask-checkbox-modal w-3 h-3 text-blue-600 rounded focus:ring-blue-500 focus:ring-2"
                            data-sub-task-id="${subTask.id}"
                            data-task-id="${task.id}"
                            ${subTask.completed ? 'checked' : ''}>
                    </form>
                    <div class="flex-1 ${indentClass}">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-green-400 rounded-full flex-shrink-0"></div>
                            <span class="text-xs ${lineClass} subtask-text-modal">📝 ${subTask.title}</span>
                        </div>
                        ${dateInfo}
                    </div>
                </div>
            `;
        }
    });

    return html;
}

function closeTaskModal() {
    const modalEl = document.getElementById('taskModal');
    modalEl.style.opacity = '0';
    modalEl.style.transform = 'scale(0.95)';
    
    setTimeout(() => {
        modalEl.classList.add('hidden');
        modalEl.style.opacity = '';
        modalEl.style.transform = '';
        modalEl.style.transition = '';
        appState.isModalOpen = false;
        appState.currentModalTaskId = null;

        // Hapus kelas no-scroll dari body
        document.body.classList.remove('overflow-hidden');
    }, 300);
}

function toggleSubtasks(button) {
    const taskId = button.getAttribute('data-task-id');
    const subtasksContainer = document.getElementById(`subtasks-container-${taskId}`);
    const icon = button.querySelector('svg');
    const isExpanded = button.getAttribute('data-expanded') === 'true';

    if (isExpanded) {
        subtasksContainer.style.maxHeight = subtasksContainer.scrollHeight + 'px';
        subtasksContainer.offsetHeight;
        subtasksContainer.style.maxHeight = '0';
        subtasksContainer.style.opacity = '0';
        subtasksContainer.style.transform = 'translateY(-10px)';
        
        icon.style.transform = 'rotate(-90deg)';
        button.setAttribute('data-expanded', 'false');
        
        setTimeout(() => {
            if (button.getAttribute('data-expanded') === 'false') {
                subtasksContainer.style.display = 'none';
            }
        }, 300);
    } else {
        subtasksContainer.style.display = 'block';
        subtasksContainer.style.maxHeight = '0';
        subtasksContainer.style.opacity = '0';
        subtasksContainer.style.transform = 'translateY(-10px)';
        
        subtasksContainer.offsetHeight;
        
        subtasksContainer.style.maxHeight = subtasksContainer.scrollHeight + 'px';
        subtasksContainer.style.opacity = '1';
        subtasksContainer.style.transform = 'translateY(0)';
        
        icon.style.transform = 'rotate(0deg)';
        button.setAttribute('data-expanded', 'true');
        
        setTimeout(() => {
            if (button.getAttribute('data-expanded') === 'true') {
                subtasksContainer.style.maxHeight = '';
            }
        }, 300);
    }
}

function toggleSubtaskParent(button) {
    const subtaskId = button.getAttribute('data-subtask-id');
    const childrenContainer = document.getElementById(`subtask-children-${subtaskId}`);
    const icon = button.querySelector('svg');
    const isExpanded = button.getAttribute('data-expanded') === 'true';

    if (isExpanded) {
        childrenContainer.style.maxHeight = childrenContainer.scrollHeight + 'px';
        childrenContainer.offsetHeight;
        childrenContainer.style.maxHeight = '0';
        childrenContainer.style.opacity = '0';
        childrenContainer.style.transform = 'translateY(-10px)';
        
        icon.style.transform = 'rotate(-90deg)';
        button.setAttribute('data-expanded', 'false');
        
        setTimeout(() => {
            if (button.getAttribute('data-expanded') === 'false') {
                childrenContainer.style.display = 'none';
            }
        }, 300);
    } else {
        childrenContainer.style.display = 'block';
        childrenContainer.style.maxHeight = '0';
        childrenContainer.style.opacity = '0';
        childrenContainer.style.transform = 'translateY(-10px)';
        
        childrenContainer.offsetHeight;
        
        childrenContainer.style.maxHeight = childrenContainer.scrollHeight + 'px';
        childrenContainer.style.opacity = '1';
        childrenContainer.style.transform = 'translateY(0)';
        
        icon.style.transform = 'rotate(0deg)';
        button.setAttribute('data-expanded', 'true');
        
        setTimeout(() => {
            if (button.getAttribute('data-expanded') === 'true') {
                childrenContainer.style.maxHeight = '';
            }
        }, 300);
    }
}

function toggleModalSubtaskParent(button) {
    const subtaskId = button.getAttribute('data-subtask-id');
    const childrenContainer = document.getElementById(`modal-subtask-children-${subtaskId}`);
    const icon = button.querySelector('svg');
    const isExpanded = button.getAttribute('data-expanded') === 'true';

    if (isExpanded) {
        childrenContainer.style.maxHeight = childrenContainer.scrollHeight + 'px';
        childrenContainer.offsetHeight;
        childrenContainer.style.maxHeight = '0';
        childrenContainer.style.opacity = '0';
        childrenContainer.style.transform = 'translateY(-10px)';
        
        icon.style.transform = 'rotate(-90deg)';
        button.setAttribute('data-expanded', 'false');
        
        setTimeout(() => {
            if (button.getAttribute('data-expanded') === 'false') {
                childrenContainer.style.display = 'none';
            }
        }, 300);
    } else {
        childrenContainer.style.display = 'block';
        childrenContainer.style.maxHeight = '0';
        childrenContainer.style.opacity = '0';
        childrenContainer.style.transform = 'translateY(-10px)';
        
        childrenContainer.offsetHeight;
        
        childrenContainer.style.maxHeight = childrenContainer.scrollHeight + 'px';
        childrenContainer.style.opacity = '1';
        childrenContainer.style.transform = 'translateY(0)';
        
        icon.style.transform = 'rotate(0deg)';
        button.setAttribute('data-expanded', 'true');
        
        setTimeout(() => {
            if (button.getAttribute('data-expanded') === 'true') {
                childrenContainer.style.maxHeight = '';
            }
        }, 300);
    }
}

function showLoadingIndicator() {
    const indicator = document.getElementById('loading-indicator');
    if (indicator) indicator.classList.remove('hidden');
}

function hideLoadingIndicator() {
    const indicator = document.getElementById('loading-indicator');
    if (indicator) indicator.classList.add('hidden');
}

function showAutoSaveIndicator() {
    const indicator = document.getElementById('modal-auto-save-indicator');
    if (indicator) {
        indicator.classList.remove('hidden');
        setTimeout(() => {
            indicator.classList.add('hidden');
        }, 2000);
    }
}

function hideAutoSaveIndicator() {
    const indicator = document.getElementById('modal-auto-save-indicator');
    if (indicator) {
        indicator.classList.add('hidden');
    }
}

function removeRevisionBadge(button) {
    const badge = button.closest('.revision-status-badge');
    if (!badge) return;
    
    const subtaskId = badge.getAttribute('data-subtask-id');
    const taskId = badge.getAttribute('data-task-id');
    
    if (subtaskId || taskId) {
        let hidden = JSON.parse(localStorage.getItem('hiddenRevisionBadges') || '[]');
        const itemId = subtaskId || taskId;
        if (!hidden.includes(itemId)) {
            hidden.push(itemId);
            localStorage.setItem('hiddenRevisionBadges', JSON.stringify(hidden));
        }
    }

    badge.remove();
    showNotification('Notifikasi revisi disembunyikan', 'info', 2000);
}

async function refreshRevisionStatuses() {
    try {
        const response = await fetch('/api/tasks/revision-statuses'); // Buat endpoint ini
        const data = await response.json();

        data.subtasks.forEach(st => {
            const badge = document.querySelector(`.revision-status-badge[data-subtask-id="${st.subtask_id}"]`);
            if (badge && st.status) {
                // Update badge sesuai status baru
                const statusLabels = {
                    pending: { text: 'Pending', icon: '⏳', class: 'bg-yellow-100 text-yellow-800 border-yellow-300' },
                    approved: { text: 'Disetujui', icon: '✅', class: 'bg-green-100 text-green-800 border-green-300' },
                    rejected: { text: 'Ditolak', icon: '❌', class: 'bg-red-100 text-red-800 border-red-300' }
                };
                const s = statusLabels[st.status];
                if (s) {
                    badge.className = `revision-status-badge inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium border ${s.class}`;
                    badge.innerHTML = `${s.icon} ${s.text} <button class="ml-1 text-gray-500 hover:text-gray-700 text-xs" onclick="removeRevisionBadge(this)" title="Sembunyikan">&times;</button>`;
                }
            }
        });
    } catch (e) {
        console.error('Gagal refresh status revisi:', e);
    }
}
function getRevisionBadge(subtask) {
    if (!subtask.revision_status) return '';

    const statusMap = {
        pending: { text: 'Pending', icon: '⏳', class: 'bg-yellow-100 text-yellow-800 border-yellow-300' },
        approved: { text: 'Disetujui', icon: '✅', class: 'bg-green-100 text-green-800 border-green-300' },
        rejected: { text: 'Ditolak', icon: '❌', class: 'bg-red-100 text-red-800 border-red-300' }
    };

    const status = statusMap[subtask.revision_status] || statusMap.pending;

    return `
        <span class="revision-status-badge inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium border ${status.class}" 
              data-subtask-id="${subtask.id}">
            ${status.icon} ${status.text}
            <button onclick="removeRevisionBadge(this)" class="ml-1 text-gray-500 hover:text-gray-700 text-xs" title="Sembunyikan">&times;</button>
        </span>
    `;
}


// Cek apakah badge harus disembunyikan
function isBadgeHidden(subtaskId) {
    const hidden = JSON.parse(localStorage.getItem('hiddenRevisionBadges') || '[]');
    return hidden.includes(subtaskId);
}

</script>

<style>
/* Enhanced modal positioning */
.fixed.inset-0 {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    z-index: 9999;
}

.fixed.inset-0 > div {
    position: fixed !important;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important;
    max-height: 90vh;
    overflow-y: auto;
}

/* Subtask styling enhancements */
.subtask-item.group:hover .opacity-0 {
    opacity: 1;
}

.subtask-item {
    position: relative;
}

.subtask-item .opacity-0 {
    transition: opacity 0.2s ease-in-out;
}

/* Modal backdrop blur */
.backdrop-blur-sm {
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
}

/* Enhanced animations */
@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.notification {
    animation: slideInRight 0.3s ease-out;
}

/* Responsive modal positioning */
@media (max-width: 768px) {
    .fixed.inset-0 > div {
        top: 2rem !important;
        left: 1rem !important;
        right: 1rem !important;
        transform: none !important;
        width: calc(100vw - 2rem) !important;
        max-width: none !important;
        max-height: calc(100vh - 4rem) !important;
    }
}

/* Enhanced button hover states */
button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Loading states */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

.spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Task Filter Styles */
.filter-btn {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid transparent;
}

.filter-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.filter-btn.active {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
}

.task-item {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

#filter-empty-state {
    transition: all 0.3s ease-out;
}

/* Calendar Styles */
.gantt-timeline {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

#calendar {
    border-radius: 12px;
    overflow: hidden;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.fc-more-link,
.fc-daygrid-more-link {
    display: none !important;
}

.fc-daygrid-day-bottom {
    display: none !important;
}

.fc-daygrid-day-events {
    margin: 0 !important;
    padding: 4px 2px !important;
}

.fc-daygrid-event-harness {
    margin-bottom: 2px !important;
    position: relative !important;
}

.fc-daygrid-event {
    white-space: nowrap !important;
    overflow: visible !important;
    display: block !important;
    visibility: visible !important;
}

.force-show-event {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.fc-daygrid-day {
    min-height: 120px !important;
    height: auto !important;
    overflow: visible !important;
    padding: 6px 4px !important;
    border: 1px solid #e2e8f0 !important;
    position: relative;
}

.fc-daygrid-day-frame {
    min-height: 120px !important;
    height: auto !important;
    overflow: visible !important;
    position: relative;
}

.timegrid-event-container {
    transition: all 0.2s ease;
}

.timegrid-event-container:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 20 !important;
}

.gantt-compact-bar {
    position: relative;
    border-radius: 4px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    border-left: 3px solid #1e40af;
}

.gantt-compact-content {
    position: absolute;
    top: 50%;
    left: 6px;
    transform: translateY(-50%);
    color: white;  
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    text-shadow: 0 1px 2px rgba(0,0,0,0.3);
}

.gantt-compact-duration {
    position: absolute;
    top: 50%;
    right: 4px;
    transform: translateY(-50%);
    color: rgba(255,255,255,0.9);
    font-weight: 700;
    background: rgba(0,0,0,0.2);
    border-radius: 2px;
}

.gantt-compact-progress {
    position: absolute;
    bottom: 2px;
    left: 2px;
    right: 2px;
    height: 2px;
    background: rgba(255,255,255,0.3);
    border-radius: 1px;
    overflow: hidden;
}

.fc-day-today {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.08), rgba(59, 130, 246, 0.12)) !important;
    border: 2px solid rgba(59, 130, 246, 0.3) !important;
    position: relative;
}

.fc-day-today::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #3b82f6, #1d4ed8);
    z-index: 1;
}

.fc-day-today .fc-daygrid-day-number {
    backgroun: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 12px;
    margin: 4px;
    box-shadow: 0 3px 6px rgba(59, 130, 246, 0.4);
    z-index: 2;
    position: relative;
}

.fc-timegrid-slot {
    height: 40px !important;
    border-color: #f1f5f9 !important;
}

.fc-timegrid-slot-minor {
    border-color: #f8fafc !important;
}

.fc-timegrid-axis {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-right: 2px solid #e2e8f0;
    font-size: 11px;
    color: #64748b;
    font-weight: 600;
}

.fc-timegrid-event {
    border-radius: 6px !important;
    overflow: visible !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
}

.fc-timegrid-event .fc-event-main {
    padding: 3px 6px !important;
}

.fc-col-header {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-bottom: 3px solid #e2e8f0;
    font-weight: 700;
    color: #334155;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.fc-col-header-cell {
    padding: 12px 6px;
    position: relative;
}

.fc-col-header-cell::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 20%;
    right: 20%;
    height: 2px;
    background: linear-gradient(90deg, transparent, #3b82f6, transparent);
}

.fc-multiMonthYear-view .fc-daygrid-day {
    height: 50px !important;
    min-height: 50px !important;
    overflow: visible !important;
    padding: 2px !important;
}

.fc-multiMonthYear-view .fc-daygrid-day-frame {
    height: 50px !important;
    min-height: 50px !important;
    overflow: visible !important;
}

.fc-multiMonthYear-view .fc-event {
    font-size: 8px !important;
    height: 16px !important;
    line-height: 14px !important;
    margin: 0px !important;
    border-radius: 2px !important;
    border-left-width: 2px !important;
}

.fc-multiMonthYear-view .fc-daygrid-day-number {
    font-size: 9px;
    padding: 2px;
    font-weight: 600;
}

.fc-multiMonthYear-view .fc-col-header-cell {
    padding: 4px 2px;
    font-size: 9px;
}

.fc-toolbar {
    margin-bottom: 2rem;
    padding: 0 6px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.fc-toolbar-chunk {
    display: flex;
    align-items: center;
    gap: 10px;
}

.fc-button {
    background: linear-gradient(135deg, #ffffff, #f8fafc) !important;
    border: 1px solid #d1d5db !important;
    color: #374151 !important;
    font-weight: 600 !important;
    padding: 8px 16px !important;
    border-radius: 8px !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
}

.fc-button:hover {
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0) !important;
    border-color: #9ca3af !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15) !important;
}

.fc-button:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3) !important;
}

.fc-button-active {
    background: linear-gradient(135deg, #3b82f6, #2563eb) !important;
    border-color: #1d4ed8 !important;
    color: white !important;
    box-shadow: 0 3px 6px rgba(59, 130, 246, 0.4) !important;
}

.view-btn {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-size: 13px;
    padding: 10px 18px;
    font-weight: 600;
}

.view-btn.active-view,
.view-btn:hover {
    background: linear-gradient(135deg, #ffffff, #f8fafc);
    color: #3b82f6;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
    transform: translateY(-1px);
    border: 1px solid #3b82f6;
}

#taskTooltip {
    z-index: 1000;
    box-shadow: 0 25px 35px -5px rgba(0, 0, 0, 0.15), 0 15px 15px -5px rgba(0, 0, 0, 0.08);
    border: 1px solid #e5e7eb;
    backdrop-filter: blur(12px);
    background: rgba(255, 255, 255, 0.98);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    max-width: 380px;
    border-radius: 12px;
    overflow: hidden;
}

#taskTooltip::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #3b82f6, #1d4ed8);
}

/* Enhanced button hover states */
button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Loading states */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

.spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Task Filter Styles */
.filter-btn {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid transparent;
}

.filter-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.filter-btn.active {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
}

.task-item {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

#filter-empty-state {
    transition: all 0.3s ease-out;
}

/* Calendar Styles */
.gantt-timeline {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

#calendar {
    border-radius: 12px;
    overflow: hidden;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.fc-more-link,
.fc-daygrid-more-link {
    display: none !important;
}

.fc-daygrid-day-bottom {
    display: none !important;
}

.fc-daygrid-day-events {
    margin: 0 !important;
    padding: 4px 2px !important;
}

.fc-daygrid-event-harness {
    margin-bottom: 2px !important;
    position: relative !important;
}

.fc-daygrid-event {
    white-space: nowrap !important;
    overflow: visible !important;
    display: block !important;
    visibility: visible !important;
}

.force-show-event {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.fc-daygrid-day {
    min-height: 120px !important;
    height: auto !important;
    overflow: visible !important;
    padding: 6px 4px !important;
    border: 1px solid #e2e8f0 !important;
    position: relative;
}

.fc-daygrid-day-frame {
    min-height: 120px !important;
    height: auto !important;
    overflow: visible !important;
    position: relative;
}

.timegrid-event-container {
    transition: all 0.2s ease;
}

.timegrid-event-container:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 20 !important;
}

.gantt-compact-bar {
    position: relative;
    border-radius: 4px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    border-left: 3px solid #1e40af;
}

.gantt-compact-content {
    position: absolute;
    top: 50%;
    left: 6px;
    transform: translateY(-50%);
    color: white;  
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    text-shadow: 0 1px 2px rgba(0,0,0,0.3);
}

.gantt-compact-duration {
    position: absolute;
    top: 50%;
    right: 4px;
    transform: translateY(-50%);
    color: rgba(255,255,255,0.9);
    font-weight: 700;
    background: rgba(0,0,0,0.2);
    border-radius: 2px;
}

.gantt-compact-progress {
    position: absolute;
    bottom: 2px;
    left: 2px;
    right: 2px;
    height: 2px;
    background: rgba(255,255,255,0.3);
    border-radius: 1px;
    overflow: hidden;
}

.fc-day-today {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.08), rgba(59, 130, 246, 0.12)) !important;
    border: 2px solid rgba(59, 130, 246, 0.3) !important;
    position: relative;
}

.fc-day-today::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #3b82f6, #1d4ed8);
    z-index: 1;
}

.fc-day-today .fc-daygrid-day-number {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 12px;
    margin: 4px;
    box-shadow: 0 3px 6px rgba(59, 130, 246, 0.4);
    z-index: 2;
    position: relative;
}

.fc-timegrid-slot {
    height: 40px !important;
    border-color: #f1f5f9 !important;
}

.fc-timegrid-slot-minor {
    border-color: #f8fafc !important;
}

.fc-timegrid-axis {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-right: 2px solid #e2e8f0;
    font-size: 11px;
    color: #64748b;
    font-weight: 600;
}

.fc-timegrid-event {
    border-radius: 6px !important;
    overflow: visible !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
}

.fc-timegrid-event .fc-event-main {
    padding: 3px 6px !important;
}

.fc-col-header {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-bottom: 3px solid #e2e8f0;
    font-weight: 700;
    color: #334155;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.fc-col-header-cell {
    padding: 12px 6px;
    position: relative;
}

.fc-col-header-cell::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 20%;
    right: 20%;
    height: 2px;
    background: linear-gradient(90deg, transparent, #3b82f6, transparent);
}

.fc-multiMonthYear-view .fc-daygrid-day {
    height: 50px !important;
    min-height: 50px !important;
    overflow: visible !important;
    padding: 2px !important;
}

.fc-multiMonthYear-view .fc-daygrid-day-frame {
    height: 50px !important;
    min-height: 50px !important;
    overflow: visible !important;
}

.fc-multiMonthYear-view .fc-event {
    font-size: 8px !important;
    height: 16px !important;
    line-height: 14px !important;
    margin: 0px !important;
    border-radius: 2px !important;
    border-left-width: 2px !important;
}

.fc-multiMonthYear-view .fc-daygrid-day-number {
    font-size: 9px;
    padding: 2px;
    font-weight: 600;
}

.fc-multiMonthYear-view .fc-col-header-cell {
    padding: 4px 2px;
    font-size: 9px;
}

.fc-toolbar {
    margin-bottom: 2rem;
    padding: 0 6px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.fc-toolbar-chunk {
    display: flex;
    align-items: center;
    gap: 10px;
}

.fc-button {
    background: linear-gradient(135deg, #ffffff, #f8fafc) !important;
    border: 1px solid #d1d5db !important;
    color: #374151 !important;
    font-weight: 600 !important;
    padding: 8px 16px !important;
    border-radius: 8px !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
}

.fc-button:hover {
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0) !important;
    border-color: #9ca3af !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15) !important;
}

.fc-button:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3) !important;
}

.fc-button-active {
    background: linear-gradient(135deg, #3b82f6, #2563eb) !important;
    border-color: #1d4ed8 !important;
    color: white !important;
    box-shadow: 0 3px 6px rgba(59, 130, 246, 0.4) !important;
}

.view-btn {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-size: 13px;
    padding: 10px 18px;
    font-weight: 600;
}

.view-btn.active-view,
.view-btn:hover {
    background: linear-gradient(135deg, #ffffff, #f8fafc);
    color: #3b82f6;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
    transform: translateY(-1px);
    border: 1px solid #3b82f6;
}

#taskTooltip {
    z-index: 1000;
    box-shadow: 0 25px 35px -5px rgba(0, 0, 0, 0.15), 0 15px 15px -5px rgba(0, 0, 0, 0.08);
    border: 1px solid #e5e7eb;
    backdrop-filter: blur(12px);
    background: rgba(255, 255, 255, 0.98);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    max-width: 380px;
    border-radius: 12px;
    overflow: hidden;
}

#taskTooltip::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #3b82f6, #1d4ed8);
}

/* Enhanced button hover states */
button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Loading states */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

.spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Task Filter Styles */
.filter-btn {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid transparent;
}

.filter-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.filter-btn.active {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
}

.task-item {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

#filter-empty-state {
    transition: all 0.3s ease-out;
}

/* Calendar Styles */
.gantt-timeline {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

#calendar {
    border-radius: 12px;
    overflow: hidden;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.fc-more-link,
.fc-daygrid-more-link {
    display: none !important;
}

.fc-daygrid-day-bottom {
    display: none !important;
}

.fc-daygrid-day-events {
    margin: 0 !important;
    padding: 4px 2px !important;
}

.fc-daygrid-event-harness {
    margin-bottom: 2px !important;
    position: relative !important;
}

.fc-daygrid-event {
    white-space: nowrap !important;
    overflow: visible !important;
    display: block !important;
    visibility: visible !important;
}

.force-show-event {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.fc-daygrid-day {
    min-height: 120px !important;
    height: auto !important;
    overflow: visible !important;
    padding: 6px 4px !important;
    border: 1px solid #e2e8f0 !important;
    position: relative;
}

.fc-daygrid-day-frame {
    min-height: 120px !important;
    height: auto !important;
    overflow: visible !important;
    position: relative;
}

.timegrid-event-container {
    transition: all 0.2s ease;
}

.timegrid-event-container:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 20 !important;
}

.gantt-compact-bar {
    position: relative;
    border-radius: 4px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    border-left: 3px solid #1e40af;
}

.gantt-compact-content {
    position: absolute;
    top: 50%;
    left: 6px;
    transform: translateY(-50%);
    color: white;  
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    text-shadow: 0 1px 2px rgba(0,0,0,0.3);
}

.gantt-compact-duration {
    position: absolute;
    top: 50%;
    right: 4px;
    transform: translateY(-50%);
    color: rgba(255,255,255,0.9);
    font-weight: 700;
    background: rgba(0,0,0,0.2);
    border-radius: 2px;
}

.gantt-compact-progress {
    position: absolute;
    bottom: 2px;
    left: 2px;
    right: 2px;
    height: 2px;
    background: rgba(255,255,255,0.3);
    border-radius: 1px;
    overflow: hidden;
}

.fc-day-today {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.08), rgba(59, 130, 246, 0.12)) !important;
    border: 2px solid rgba(59, 130, 246, 0.3) !important;
    position: relative;
}

.fc-day-today::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #3b82f6, #1d4ed8);
    z-index: 1;
}

.fc-day-today .fc-daygrid-day-number {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 12px;
    margin: 4px;
    box-shadow: 0 3px 6px rgba(59, 130, 246, 0.4);
    z-index: 2;
    position: relative;
}

.fc-timegrid-slot {
    height: 40px !important;
    border-color: #f1f5f9 !important;
}

.fc-timegrid-slot-minor {
    border-color: #f8fafc !important;
}

.fc-timegrid-axis {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-right: 2px solid #e2e8f0;
    font-size: 11px;
    color: #64748b;
    font-weight: 600;
}

.fc-timegrid-event {
    border-radius: 6px !important;
    overflow: visible !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
}

.fc-timegrid-event .fc-event-main {
    padding: 3px 6px !important;
}

.fc-col-header {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-bottom: 3px solid #e2e8f0;
    font-weight: 700;
    color: #334155;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.fc-col-header-cell {
    padding: 12px 6px;
    position: relative;
}

.fc-col-header-cell::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 20%;
    right: 20%;
    height: 2px;
    background: linear-gradient(90deg, transparent, #3b82f6, transparent);
}

.fc-multiMonthYear-view .fc-daygrid-day {
    height: 50px !important;
    min-height: 50px !important;
    overflow: visible !important;
    padding: 2px !important;
}

.fc-multiMonthYear-view .fc-daygrid-day-frame {
    height: 50px !important;
    min-height: 50px !important;
    overflow: visible !important;
}

.fc-multiMonthYear-view .fc-event {
    font-size: 8px !important;
    height: 16px !important;
    line-height: 14px !important;
    margin: 0px !important;
    border-radius: 2px !important;
    border-left-width: 2px !important;
}

.fc-multiMonthYear-view .fc-daygrid-day-number {
    font-size: 9px;
    padding: 2px;
    font-weight: 600;
}

.fc-multiMonthYear-view .fc-col-header-cell {
    padding: 4px 2px;
    font-size: 9px;
}

.fc-toolbar {
    margin-bottom: 2rem;
    padding: 0 6px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.fc-toolbar-chunk {
    display: flex;
    align-items: center;
    gap: 10px;
}

.fc-button {
    background: linear-gradient(135deg, #ffffff, #f8fafc) !important;
    border: 1px solid #d1d5db !important;
    color: #374151 !important;
    font-weight: 600 !important;
    padding: 8px 16px !important;
    border-radius: 8px !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
}

.fc-button:hover {
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0) !important;
    border-color: #9ca3af !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15) !important;
}

.fc-button:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3) !important;
}

.fc-button-active {
    background: linear-gradient(135deg, #3b82f6, #2563eb) !important;
    border-color: #1d4ed8 !important;
    color: white !important;
    box-shadow: 0 3px 6px rgba(59, 130, 246, 0.4) !important;
}

.view-btn {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-size: 13px;
    padding: 10px 18px;
    font-weight: 600;
}

.view-btn.active-view,
.view-btn:hover {
    background: linear-gradient(135deg, #ffffff, #f8fafc);
    color: #3b82f6;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
    transform: translateY(-1px);
    border: 1px solid #3b82f6;
}

#taskTooltip {
    z-index: 1000;
    box-shadow: 0 25px 35px -5px rgba(0, 0, 0, 0.15), 0 15px 15px -5px rgba(0, 0, 0, 0.08);
    border: 1px solid #e5e7eb;
    backdrop-filter: blur(12px);
    background: rgba(255, 255, 255, 0.98);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    max-width: 380px;
    border-radius: 12px;
    overflow: hidden;
}

#taskTooltip::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #3b82f6, #1d4ed8);
}

/* Subtask hierarchy styles */
.vertical-tree-structure {
    display: flex;
    flex-direction: column;
    position: relative;
}

.subtask-parent {
    margin-bottom: 0.75rem;
    position: relative;
}

.subtask-children {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
    position: relative;
}

.subtask-item {
    margin-bottom: 0.5rem;
    transition: all 0.2s ease-in-out;
}

.subtask-item:hover {
    transform: translateX(4px);
}

.subtask-toggle-btn svg,
.subtask-parent-toggle-btn svg {
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.task-subtasks-container {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
}

.task-title,
.subtask-parent-title {
    cursor: pointer;
    transition: all 0.2s ease-in-out;
}

.subtask-parent::before {
    content: '';
    position: absolute;
    left: -16px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(180deg, #e2e8f0, #f1f5f9);
    border-radius: 1px;
}

.subtask-item::before {
    content: '';
    position: absolute;
    left: -16px;
    top: 50%;
    width: 12px;
    height: 2px;
    background: linear-gradient(90deg, #e2e8f0, #f8fafc);
    border-radius: 1px;
    transform: translateY(-50%);
}

.subtask-item::after {
    content: '';
    position: absolute;
    left: -16px;
    top: 0;
    width: 2px;
    height: 50%;
    background: linear-gradient(180deg, #e2e8f0, transparent);
    border-radius: 1px;
}

.ml-6 { margin-left: 1.5rem; }
.ml-12 { margin-left: 3rem; }
.ml-18 { margin-left: 4.5rem; }
.ml-24 { margin-left: 6rem; }

.subtask-parent-modal {
    margin-bottom: 0.5rem;
    position: relative;
}

.subtask-children-modal {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
    position: relative;
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

.subtask-item-modal {
    margin-bottom: 0.25rem;
    transition: all 0.2s ease-in-out;
    position: relative;
}

.subtask-item-modal:hover {
    transform: translateX(2px);
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.1);
}

.subtask-progress-bar {
    transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.backdrop-blur-sm {
    backdrop-filter: blur(4px);
}

.overflow-y-auto::-webkit-scrollbar {
    width: 4px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 2px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 2px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.hover\:scale-105:hover {
    transform: scale(1.05);
}

.bg-clip-text {
    -webkit-background-clip: text;
    background-clip: text;
}

.shadow-2xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.focus\:ring-2:focus {
    outline: 2px solid transparent;
    outline-offset: 2px;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
}

@media (max-width: 768px) {
    .fc-daygrid-day {
        min-height: 100px !important;
        height: auto !important;
        padding: 3px 2px !important;
    }
    
    .fc-col-header-cell {
        padding: 6px 3px;
        font-size: 10px;
    }
    
    .fc-toolbar {
        flex-direction: column;
        gap: 12px;
        padding: 12px;
    }
    
    .fc-toolbar-chunk {
        flex-wrap: wrap;
        justify-content: center;
    }
}

@media (max-width: 640px) {
    .fc-multiMonthYear-view .fc-multiMonthYear-month {
        width: 100% !important;
    }
    
    .fc-daygrid-day {
        min-height: 80px !important;
        padding: 2px 1px !important;
    }
}

.calendar-error {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    border: 1px solid #f87171;
    color: #dc2626;
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 16px;
}

.calendar-fallback {
    background: linear-gradient(135deg, #f9fafb, #f3f4f6);
    border: 2px dashed #d1d5db;
    padding: 40px;
    border-radius: 12px;
    text-align: center;
}

.fc-view-harness {
    overflow: visible !important;
}

.fc-scroller {
    overflow: visible !important;
}

.fc-scroller-liquid {
    overflow: visible !important;
}

.fc-daygrid-day:nth-child(7n) {
    border-right: 2px solid #e2e8f0 !important;
}

.fc-daygrid-day:nth-child(7n-6) {
    border-left: 2px solid #e2e8f0 !important;
}

.fc-day-sat,
.fc-day-sun {
    background: linear-gradient(135deg, rgba(249, 250, 251, 0.8), rgba(243, 244, 246, 0.8)) !important;
}

.fc-daygrid-day[data-date$="-01"] {
    border-left: 3px solid #3b82f6 !important;
}

.fc-event:hover {
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
    z-index: 20 !important;
}

.fc-timegrid-slot {
    height: 24px !important;
}

.fc-timegrid-slot.fc-timegrid-slot-lane {
    height: 24px !important;
}

.fc-timegrid-slot.fc-timegrid-slot-label {
    height: 24px !important;
}

.fc-timegrid-event-harness {
    margin-bottom: 2px !important;
}

.fc-timegrid-axis-frame {
    display: flex;
    align-items: center;
    justify-content: center;
}

.fc-timegrid-axis-cushion {
    font-size: 11px;
    font-weight: 600;
    color: #4B5563;
}

.fc-timeGridWeek-view .fc-timegrid-col-frame {
    padding: 0 2px;
}

.fc-timegrid-event-harness {
    position: relative;
    z-index: 1;
}

.subtask-children-modal::before {
    content: '';
    position: absolute;
    left: -8px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(180deg, #e2e8f0, #f1f5f9, transparent);
    border-radius: 1px;
}

.subtask-parent:hover::before,
.subtask-item:hover::before {
    background: linear-gradient(90deg, transparent, #3b82f6, #60a5fa);
    opacity: 0.8;
}

.subtask-parent-modal:hover::before,
.subtask-item-modal:hover::before {
    background: linear-gradient(90deg, transparent, #3b82f6, #60a5fa);
    opacity: 0.8;
}

.fc-timeGridDay-view .fc-timegrid-event,
.fc-timeGridWeek-view .fc-timegrid-event {
    margin-top: 2px !important;
    margin-bottom: 2px !important;
}

/* Revision status styling */
.revision-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    font-weight: 500;
    border-radius: 0.375rem;
    transition: all 0.3s ease;
    animation: fadeInScale 0.3s ease-out;
}

@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Preview item styling */
.bg-blue-50 {
    background-color: rgba(239, 246, 255, 0.8);
    border: 1px solid rgba(59, 130, 246, 0.3);
    position: relative;
}

.bg-blue-50::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: linear-gradient(180deg, #3b82f6, #1d4ed8);
    border-radius: 0 2px 2px 0;
}

.bg-red-50 {
    background-color: rgba(254, 242, 242, 0.8);
    border: 1px solid rgba(239, 68, 68, 0.3);
    position: relative;
}

.bg-red-50::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: linear-gradient(180deg, #ef4444, #dc2626);
    border-radius: 0 2px 2px 0;
}

/* Status colors */
.bg-yellow-100 { background-color: #fef3c7; }
.text-yellow-800 { color: #92400e; }
.border-yellow-300 { border-color: #fcd34d; }

.bg-green-100 { background-color: #dcfce7; }
.text-green-800 { color: #166534; }
.border-green-300 { border-color: #86efac; }

.bg-red-100 { background-color: #fee2e2; }
.text-red-800 { color: #991b1b; }
.border-red-300 { border-color: #fca5a5; }

.bg-blue-100 { background-color: #dbeafe; }
.text-blue-800 { color: #1e40af; }
.border-blue-300 { border-color: #93c5fd; }

</style>

@endpush