{{-- resources/views/tasks/partials/task-modal.blade.php --}}
@if($data['is_owner'])
    @php
        $pendingRevision = $pendingRevisions->firstWhere('task_id', $data['id']);
    @endphp
    @if($pendingRevision)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
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
                <h5 class="font-medium text-gray-800 mb-2">Perubahan:</h5>
                @foreach($pendingRevision->proposed_data as $field => $newValue)
                    @if(isset($pendingRevision->original_data[$field]) && $pendingRevision->original_data[$field] !== $newValue)
                        <div class="flex gap-4 text-sm">
                            <div class="flex-1">
                                <span class="font-medium">{{ ucfirst($field) }}:</span>
                                <div class="text-red-600 line-through">{{ $pendingRevision->original_data[$field] ?? 'Tidak ada' }}</div>
                                <div class="text-green-600 font-medium">{{ $newValue ?? 'Tidak ada' }}</div>
                            </div>
                        </div>
                    @endif
                @endforeach
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
        </div>
    @endif
@endif