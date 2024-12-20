<tbody>
    @foreach ($tasks as $key => $task)
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ $task->wbs }}</td>
        <td>{{ $task->activity_name }}</td>
        <td>
            <span class='badge bg-label-{{ trim($task->priority_color) }}'>{{ $task->priority }}</span>
        </td>
        <td>{{ $task->start_date }}</td>
        <td>{{ $task->end_date }}</td>
        <td>{{ $task->duration }}</td>
        <td>{{ $task->progress }}%</td>
        <td>
            <span class='badge bg-label-{{ trim($task->status_color) }}'>{{ trim($task->status) }}</span>
        </td>
        <td>Approval</td>
        <td>
            <input type="checkbox" class="task-checkbox" name="selected_tasks[]" value="{{ $task->id }}" />
        </td>
    </tr>
    @endforeach
</tbody>