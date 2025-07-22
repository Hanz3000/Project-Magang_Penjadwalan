<div class="subtask-item" data-id="{{ $subtask->id }}">
    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm" style="margin-left: {{ $level * 20 }}px;">
        <div class="flex items-center gap-3">
            <div class="flex-1">
                <input type="hidden" name="subtasks[{{ $subtask->id }}][id]" value="{{ $subtask->id }}">
                <input type="text" name="subtasks[{{ $subtask->id }}][title]" value="{{ $subtask->title }}"
                    placeholder="Masukkan nama subtask"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                    required>
                <input type="hidden" name="subtasks[{{ $subtask->id }}][parent_id]"
                    value="{{ $subtask->parent_id ?? '' }}">
            </div>
            <div class="flex gap-2">
                <button type="button"
                    class="px-3 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors duration-200 text-xs font-medium"
                    onclick="addSubtask(this.closest('.subtask-item'))" title="Tambah Sub-subtask">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </button>
                <button type="button"
                    class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors duration-200 text-xs font-medium"
                    onclick="removeSubtask(this.closest('.subtask-item'))" title="Hapus Subtask">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>
        <div class="mt-3 space-y-3 child-container">
            @if ($subtasksByParent->has($subtask->id))
                @foreach ($subtasksByParent->get($subtask->id) as $childSubtask)
                    @include('tasks.partials.subtask-item', [
                        'subtask' => $childSubtask,
                        'subtasksByParent' => $subtasksByParent,
                        'level' => $level + 1,
                    ])
                @endforeach
            @endif
        </div>
    </div>
</div>
