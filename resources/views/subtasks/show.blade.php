<h1>Subtask Details</h1>

<p><strong>ID:</strong> {{ $subtask->id }}</p>
<p><strong>Name:</strong> {{ $subtask->name }}</p>
<p><strong>Task:</strong> {{ $subtask->task->name }}</p>

<a href="{{ route('subtasks.edit', $subtask) }}" class="btn btn-primary">Edit</a>
<form action="{{ route('subtasks.destroy', $subtask) }}" method="POST" class="d-inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this subtask?')">Delete</button>
</form>