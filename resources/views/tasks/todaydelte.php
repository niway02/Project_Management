@extends('layout')

@section('title')
{{ get_label('tasks', 'Tasks') }} - {{ get_label('list_view', 'List view') }}
@endsection

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb Navigation -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-style1">
                <li class="breadcrumb-item"><a href="{{ url('/home') }}">{{ get_label('home', 'Home') }}</a></li>
                @isset($project->id)
                <li class="breadcrumb-item"><a href="{{ url('/projects') }}">{{ get_label('projects', 'Projects') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/projects/information/'.$project->id) }}">{{ $project->title }}</a></li>
                @endisset
                <li class="breadcrumb-item active" aria-current="page">{{ get_label('tasks', 'Tasks') }}</li>
            </ol>
        </nav>
        <div>
            @php
            $url = isset($project->id) ? '/projects/tasks/draggable/' . $project->id : '/tasks/draggable';
            @endphp
            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_activity_modal" class="btn btn-sm btn-primary" title="{{ get_label('create_Activity', 'Create Activity') }}">
                <i class="bx bx-plus"></i> {{ get_label('create_Activity', 'Create Activity') }}
            </a>
        </div>
    </div>

    <!-- Tasks Overview -->
    <div class="row mb-4">
        @foreach ($statusData as $status => $dtatusdata)
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="avatar flex-shrink-0 mb-2">
                        <i class="menu-icon tf-icons bx bx-briefcase-alt-2 bx-md" style="color: {{ $dtatusdata['color'] }};"></i>
                    </div>
                    <span class="fw-semibold d-block mb-1">{{ get_label($status, ucfirst(str_replace('_', ' ', $status))) }}</span>
                    <h3 class="card-title mb-2">{{ $dtatusdata['count'] }}</h3>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <input type="text" id="task_start_date_between" name="task_start_date_between" class="form-control datepicker" placeholder="{{ get_label('start_date_between', 'Start date between') }}" autocomplete="off">
            </div>
            <div class="col-md-4 mb-3">
                <input type="text" id="task_end_date_between" name="task_end_date_between" class="form-control datepicker" placeholder="{{ get_label('end_date_between', 'End date between') }}" autocomplete="off">
            </div>
            @can('manage_projects')
            <div class="col-md-4 mb-3">
                <select class="form-select" id="tasks_project_filter">
                    <option value="">{{ get_label('select_project', 'Select project') }}</option>
                    @foreach ($projects as $proj)
                    <option value="{{ $proj->id }}">{{ $proj->title }}</option>
                    @endforeach
                </select>
            </div>
            @endcan
            <div class="col-md-4 mb-3">
                <select class="form-select" id="tasks_user_filter">
                    <option value="">{{ get_label('select_user', 'Select user') }}</option>
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->first_name . ' ' . $user->last_name }}</option>
                    @endforeach
                </select>
            </div>
           
            <div class="col-md-4 mb-3">
                <select class="form-select" id="task_status_filter">
                    <option value="">{{ get_label('select_status', 'Select status') }}</option>
                    @foreach ($statuses as $status)
                    <option value="{{ $status->id }}">{{ $status->title }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Button -->
            <div class="col-md-4 mb-3">
                <button type="button" class="btn btn-primary" id="filterTasksBtn">Filter Tasks</button>
            </div>
        </div>

        <form action="{{ route('activity.checklist') }}" method="POST" id="materialForm">
            @csrf
            <table class="table table-striped table-bordered" id="tasksTable">
                <thead class="table-header">
                    <tr>
                        <th>No</th>
                        <th>wbs</th>
                        <th>{{ get_label('activity_name', 'Activity Name') }}</th>
                        <th>{{ get_label('priority', 'Priority') }}</th>
                        <th>{{ get_label('start_date', 'Start Date') }}</th>
                        <th>{{ get_label('end_date', 'End Date') }}</th>
                        <th>{{ get_label('duration', 'Duration') }}</th>
                        <th>{{ get_label('progress', 'Progress') }}</th>
                        <th>{{ get_label('status', 'Status') }}</th>
                        <th>{{ get_label('Approval', 'Approval') }}</th>
                        <th><input type="checkbox" id="selectAllCheckbox" /></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $detail)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $detail['wbs'] }}</td>
                        <td>{{ $detail['activity_name'] }}</td>
                        <td>
                            <span class='badge bg-label-{{ trim($detail['priority_color']) }}'>{{ $detail['priority'] }}</span>
                        </td>
                        <td>{{ $detail['start_date'] }}</td>
                        <td>{{ $detail['end_date'] }}</td>
                        <td>{{ $detail['duration'] }}</td>
                        <td>{{ $detail['progress'] }}%</td>
                        <td>
                            <span class='badge bg-label-{{ trim($detail['status_color']) }}'>{{ trim($detail['status']) }}</span>
                        </td>
                        <td>Approval</td>
                        <td>
                            <input type="checkbox" class="task-checkbox" name="selected_tasks[]" value="{{ $detail['id'] }}" />
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-success">Submit Selected</button>
        </form>
    </div>

    <!-- Modal for Creating Activity -->
    <div class="modal fade" id="create_activity_modal" tabindex="-1" aria-labelledby="createActivityModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createActivityModalLabel">{{ get_label('create_activity', 'Create Activity') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createActivityForm" method="POST" action="{{ route('activities.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="activityName" class="form-label">{{ get_label('activity_name', 'Activity Name') }}</label>
                            <input type="text" class="form-control" id="activityName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="activityPriority" class="form-label">{{ get_label('activity_priority', 'Activity Priority') }}</label>
                            <select class="form-select" id="activityPriority" name="priority" required>
                                <option value="" disabled selected>{{ get_label('select_priority', 'Select Priority') }}</option>
                                @foreach($priority as $pri)
                                <option value="{{ $pri->id }}">{{ $pri->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ get_label('submit', 'Submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script>
        $(document).ready(function() {
            // Datepicker initialization
            $(".datepicker").datepicker({
                dateFormat: "yy-mm-dd"
            });

            // Function to filter tasks
            
            function filterTasks() {
                const startDate = $('#task_start_date_between').val();
                const endDate = $('#task_end_date_between').val();
                const projectId = $('#tasks_project_filter').val();
                const userId = $('#tasks_user_filter').val();
                const statusId = $('#task_status_filter').val();

                $.ajax({
                    url: "{{ route('tasks.filter') }}", // Update with your route
                    method: "GET",
                    data: {
                        start_date: startDate,
                        end_date: endDate,
                        project_id: projectId,
                        user_id: userId,
                        status_id: statusId
                    },
                    success: function(response) {
                        // Update the tasks table with filtered data
                        $('#tasksTable tbody').html(response);
                    }
                });
            }

            // Trigger filter on button click
            $('#filterTasksBtn').on('click', filterTasks);

            // Trigger filter on change of any input/select
            $('#task_start_date_between, #task_end_date_between, #tasks_project_filter, #tasks_user_filter, #task_status_filter').on('change', filterTasks);

            // Select/Deselect all checkboxes
            $('#selectAllCheckbox').on('change', function() {
                $('.task-checkbox').prop('checked', this.checked);
            });
        });
    </script>

    <style>
        .table-header {
            background-color: #1B8596;
            color: white !important;
        }
        .table:not(.table-dark) th {
            color: #ffffff !important;
        }
    </style>
</div>
@endsection