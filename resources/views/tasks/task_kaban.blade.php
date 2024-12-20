<div class="task">
    <div class="task-header">
        <h4 class="task-title">{{ $task->id }}</h4>
        <div class="task-status">
            @if ($task->status === 'completed')
                <span class="badge badge-success">Completed</span>
            @elseif ($task->status === 'inprogress')
                <span class="badge badge-warning">In Progress</span>
            @else
                <span class="badge badge-danger">Uncompleted</span>
            @endif
        </div>
    </div>
    <div class="task-details">
        <p class="task-description">Description: {{ $task->description }}</p>
        <p class="task-assignee">Assignee: {{ $task->assignee }}</p>
        <p class="task-start-date">Start Date: {{ $task->start_date }}</p>
        <p class="task-due-date">Due Date: {{ $task->due_date }}</p>
        
        <!-- Add more details or properties as needed -->
    </div>
    <div class="task-progress">
        <div class="progress">
            <div class="progress-bar 
                @if ($task->progress < 25)
                    bg-danger
                @elseif ($task->progress < 50)
                    bg-primary
                @elseif ($task->progress < 75)
                    bg-warning
                @else
                    bg-success
                @endif
            " role="progressbar" style="width: {{ $task->progress }}%;" aria-valuenow="{{ $task->progress }}" aria-valuemin="0" aria-valuemax="100"></div>
            <div class="progress-value" style="font-weight: bold; font-size: 16px;">{{ $task->progress }}%</div>
        </div>
    </div>
</div>