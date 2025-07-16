<li class="subtask-item" id="subtask-item-{{ $subTask->id }}">
    <div class="flex items-center gap-2">
        <!-- Toggle button untuk child items -->
        @if($allSubTasks->where('parent_id', $subTask->id)->count() > 0)
           <button class="toggle-btn text-gray-500 hover:text-gray-700" data-subtask-id="{{ $subTask->id }}">
    <span class="toggle-icon">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </span>
</button>
        @else
            <span class="inline-block w-4"></span> <!-- Spacer untuk alignment -->
        @endif
        
        <!-- Checkbox -->
        <form action="{{ route('subtasks.toggle', $subTask->id) }}" method="POST" class="subtask-toggle-form">
            @csrf
            @method('PATCH')
            <input type="checkbox"
                class="subtask-checkbox"
                data-sub-task-id="{{ $subTask->id }}"
                data-task-id="{{ $task->id ?? $subTask->task_id }}"
                {{ $subTask->completed ? 'checked' : '' }}>
        </form>
        
        <!-- Task title -->
        <span class="text-sm {{ $subTask->completed ? 'line-through text-gray-400' : 'text-gray-600' }} subtask-text">
            {{ $subTask->title }}
        </span>
    </div>
    
    <!-- Child items container -->
    @if($allSubTasks->where('parent_id', $subTask->id)->count() > 0)
        <ul class="ml-6 space-y-2 child-items" id="child-items-{{ $subTask->id }}">
            @foreach($allSubTasks->where('parent_id', $subTask->id) as $childTask)
                @include('partials.subtask-item', [
                    'subTask' => $childTask,
                    'allSubTasks' => $allSubTasks,
                    'level' => $level + 1
                ])
            @endforeach
        </ul>
    @endif
</li>