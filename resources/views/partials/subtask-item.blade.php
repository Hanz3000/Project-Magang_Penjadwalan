<li class="flex items-start gap-2 subtask-item" style="margin-left: {{ $level * 20 }}px;">
    <form action="{{ route('subtasks.toggle', $subTask->id) }}" method="POST" class="subtask-toggle-form">
        @csrf
        @method('PATCH')
        <input type="checkbox"
            class="subtask-checkbox"
            data-sub-task-id="{{ $subTask->id }}"
            data-task-id="{{ $subTask->task_id }}"
            {{ $subTask->completed ? 'checked' : '' }}>
    </form>
    <span class="text-sm {{ $subTask->completed ? 'line-through text-gray-400' : 'text-gray-600' }} subtask-text">
        {{ $subTask->title }}
    </span>

    @if($subTask->children->count() > 0)
    <ul class="ml-6 space-y-2">
        @foreach($subTask->children as $child)
            @include('partials.subtask-item', ['subTask' => $child, 'level' => $level + 1])
        @endforeach
    </ul>
    @endif
</li>
