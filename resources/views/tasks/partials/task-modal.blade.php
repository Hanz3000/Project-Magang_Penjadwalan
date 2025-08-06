{{-- resources/views/tasks/partials/task-modal.blade.php --}}
@php
    $task = $tasks->firstWhere('id', $data['id']);
    $pendingRevision = $pendingRevisions->where('task_id', $data['id'])->where('status', 'pending')->first();
    $isOwner = $data['is_owner'];
    $isCollaborator = !$isOwner;
@endphp

@if($pendingRevision)
    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-400 rounded-lg p-4 mb-4 shadow-sm">
        @if($pendingRevision->collaborator_id === Auth::id())
            {{-- Collaborator's own revision --}}
            <h4 class="font-semibold text-yellow-800 flex items-center gap-2 mb-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Usulan Anda Menunggu Review
            </h4>
            <div class="space-y-2 text-sm">
                <p><strong>Status:</strong> Menunggu persetujuan dari pemilik task</p>
                <p><strong>Dikirim:</strong> {{ $pendingRevision->created_at->format('d M Y, H:i') }}</p>
            </div>
            
            <div class="mt-3 border-t pt-3">
                <h5 class="font-medium text-gray-800 mb-2">Perubahan yang Diusulkan:</h5>
                <div class="space-y-2 max-h-40 overflow-y-auto bg-white rounded p-3 border">
                    @foreach($pendingRevision->proposed_data as $field => $newValue)
                        @if(isset($pendingRevision->original_data[$field]) && $pendingRevision->original_data[$field] !== $newValue)
                            <div class="text-sm">
                                <span class="font-medium capitalize">{{ str_replace('_', ' ', $field) }}:</span>
                                <div class="ml-4">
                                    <div class="text-red-600 line-through text-xs">Lama: {{ $pendingRevision->original_data[$field] ?? 'Tidak ada' }}</div>
                                    <div class="text-green-600 font-medium">Baru: {{ $newValue ?? 'Tidak ada' }}</div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            
            <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                <p class="text-blue-800 text-sm">
                    ⏳ Usulan perubahan Anda sedang menunggu review dari <strong>{{ $task->user->name ?? 'pemilik task' }}</strong>
                </p>
            </div>
        @else
            {{-- Owner viewing collaborator's revision --}}
            <h4 class="font-semibold text-yellow-800 flex items-center gap-2 mb-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Revisi Menunggu Persetujuan
            </h4>
            <div class="space-y-2 text-sm">
                <p><strong>Diedit oleh:</strong> {{ $pendingRevision->collaborator->name }}</p>
                <p><strong>Waktu:</strong> {{ $pendingRevision->created_at->format('d M Y, H:i') }}</p>
            </div>
            
            <div class="mt-3 border-t pt-3">
                <h5 class="font-medium text-gray-800 mb-2">Perubahan yang Diusulkan:</h5>
                <div class="space-y-2 max-h-40 overflow-y-auto bg-white rounded p-3 border">
                    @foreach($pendingRevision->proposed_data as $field => $newValue)
                        @if(isset($pendingRevision->original_data[$field]) && $pendingRevision->original_data[$field] !== $newValue)
                            <div class="text-sm">
                                <span class="font-medium capitalize">{{ str_replace('_', ' ', $field) }}:</span>
                                <div class="ml-4">
                                    <div class="text-red-600 line-through text-xs">Lama: {{ $pendingRevision->original_data[$field] ?? 'Tidak ada' }}</div>
                                    <div class="text-green-600 font-medium">Baru: {{ $newValue ?? 'Tidak ada' }}</div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            
            <div class="flex gap-2 mt-4">
                <button 
                    onclick="reviewRevision({{ $pendingRevision->id }}, 'approve')"
                    class="flex-1 px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                    ✅ Setujui
                </button>
                <button 
                    onclick="reviewRevision({{ $pendingRevision->id }}, 'reject')"
                    class="flex-1 px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                    ❌ Tolak
                </button>
            </div>
        @endif
    </div>
@endif