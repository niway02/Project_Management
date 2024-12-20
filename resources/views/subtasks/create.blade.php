@extends('layout')

@section('content')
    <h1>Create Subtask</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('subtasks.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Subtask Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select class="form-control" id="status" name="status">
                        <option value="not start">not start</option>
                        <option value="completed">completed</option>
                        <option value="canceled">cancel</option>
                        <option value="pending">pnding</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="estimated_date">Estimated Date:</label>
                    <input type="text" class="form-control" id="estimated_date" name="estimated_date">
                </div>
                <div class="form-group">
                    <label for="priority">Priority:</label>
                    <select class="form-control" id="priority" name="priority">
                        @for ($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="progress">Progress:</label>
                    <input type="range" class="form-control-range" id="progress" name="progress" min="1" max="100" step="1" oninput="progressOutput.value = progress.value + '%'">
                    <output name="progressOutput" id="progressOutput">0%</output>
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date:</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ date('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label for="end_date">End Date:</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ date('Y-m-d') }}">
                </div>
                <input type="hidden" name="task_id" value="{{ request('task_id') }}">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
@endsection