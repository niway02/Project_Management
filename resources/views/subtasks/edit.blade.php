<h1>Edit Subtask</h1>

@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('subtasks.update', $subtask) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ $subtask->name }}" required>
    </div>
    <div class="form-group">
        <label for="task_id">Task</label>
        <select name="task_id" id="task_id" class="form-control" required>
            @foreach($tasks as $task)
               ```
                <option value="{{ $task->id }}" {{ $task->id == $subtask->task_id ? 'selected' : '' }}>{{ $task->name }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>