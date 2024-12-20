@extends('layout')

@section('content')
<div class="container my-5">
    <h1 class="text-center mb-4 text-dark">Work Progress Detail</h1>

    <h2>Employee: {{ $employee['name'] }} ({{ $employee['department'] }})</h2>

    <h3>Work Progress</h3>
    <div class="progress mb-3">
        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%;">
            75%
        </div>
    </div>

    @foreach ($projects as $project)
        <h3>Project: {{ $project['name'] }}</h3>
        <table class="table table-striped table-responsive">
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Subtask</th>
                    <th>Status</th>
                    <th>Issue</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($project['tasks'] as $task)
                    @foreach ($task['subtasks'] as $subtask)
                        <tr class="{{ $subtask['status'] === 'not started' ? 'bg-danger text-white' : '' }}">
                            <td>{{ $task['name'] }}</td>
                            <td>{{ $subtask['name'] }}</td>
                            <td>
                                @if ($subtask['status'] === 'not started')
                                    <span class="badge" style="background-color: red; color: white;">Not Started</span>
                                @elseif ($subtask['status'] === 'in progress')
                                    <span class="badge" style="background-color: yellow; color: black;">In Progress</span>
                                @elseif ($subtask['status'] === 'completed')
                                    <span class="badge" style="background-color: green; color: white;">Completed</span>
                                @else
                                    {{ $subtask['status'] }}
                                @endif
                            </td>
                            <td>
                                @if ($subtask['status'] === 'not started')
                                    <input type="text" class="form-control" placeholder="Enter issue">
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @endforeach
</div>
@endsection