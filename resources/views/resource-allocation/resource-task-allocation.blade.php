@extends('layout')

@section('title')
{{ get_label('Resource Allocation', 'Resource Allocation') }}
@endsection

@section('content')
<div class="container-fluid">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb breadcrumb-style1">
            <li class="breadcrumb-item">
                <a href="{{ url('/home') }}">{{ get_label('Home', 'Home') }}</a>
            </li>
            <li class="breadcrumb-item active">
                {{ get_label('Resource Allocation', 'Resource Allocation') }}
            </li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Task</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks as $taskIndex => $task)
                        <tr data-toggle="collapse" data-target="#task{{ $taskIndex }}" class="clickable-row">
                            <td>
                                <a href="javascript:void(0);" class="toggle-arrow" data-toggle="collapse" data-target="#task{{ $taskIndex }}" aria-expanded="true" aria-controls="task{{ $taskIndex }}">
                                    <i class="bx bx-chevron-down accordion-icon"></i> {{ $task->title }}
                                </a>
                            </td>
                        </tr>
                        <tr class="collapse show" id="task{{ $taskIndex }}">
                            <td colspan="1">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Activity</th>
                                            <th>Priority</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($task->activity as $activity)
                                        <tr>
                                            <td>{{ $activity->name }}</td>
                                            <td>
                                                <span class="badge bg-label-{{ $activity->priority_name->color ?? 'default' }}">
                                                    {{ $activity->priority_name->title ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>{{ $activity->start_date }}</td>
                                            <td>{{ $activity->end_date }}</td>
                                            <td>
                                                <span class="badge bg-label-{{ $activity->status_name->color ?? 'default' }}">
                                                    {{ $activity->status_name->title ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('materialAlloation.activity', $activity->id) }}" class="btn btn-primary btn-sm">
                                                    <i class="bx bx-detail"></i> Allocate Material
                                                </a>
                                                <a href="{{ route('equipmentAlloation.activity', $activity->id) }}" class="btn btn-info btn-sm">
                                                    <i class="bx bx-detail"></i> Allocate Equipment
                                                </a>
                                                <a href="{{ route('laborAlloation.activity', $activity->id) }}" class="btn btn-warning btn-sm">
                                                    <i class="bx bx-detail"></i> Allocate Labor
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No Activities Added</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.clickable-row').forEach(row => {
        row.addEventListener('click', function() {
            const target = this.nextElementSibling;
            target.classList.toggle('show');
            this.querySelector('.accordion-icon').classList.toggle('rotate-180');
        });
    });
</script>
@endsection