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
                    <li class="breadcrumb-item"><a href="{{ url('/projects/information/' . $project->id) }}">{{ $project->title }}</a></li>
                @endisset
                <li class="breadcrumb-item active" aria-current="page">{{ get_label('tasks', 'Tasks') }}</li>
            </ol>
        </nav>
        <div>
            @php
                $url = isset($project->id) ? '/projects/tasks/draggable/' . $project->id : '/tasks/draggable';
            @endphp
            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_activity_modal1" class="btn btn-sm btn-primary" title="{{ get_label('create_Activity', 'Create Activity') }}" data-task-id="{{ $id }}">
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
    <x-activity-list :taskId="$id" :units="$units" />

</div>

<!-- Create Activity Modal -->
<div class="modal fade" id="create_activity_modal1" tabindex="-1" aria-labelledby="createActivityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createActivityModalLabel">{{ get_label('create_activity', 'Create Activity') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createActivityForm" method="POST" action="{{ route('activities.store') }}">
                    @csrf

                    <input type="hidden" id="task_id" name="task_id" value="">

                    <div class="mb-3">
                        <label for="activityName" class="form-label">{{ get_label('activity_name', 'Activity Name') }}</label>
                        <input type="text" class="form-control" id="activityName" name="name" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="activityPriority" class="form-label">{{ get_label('activity_priority', 'Activity Priority') }}</label>
                            <select class="form-select" id="activityPriority" name="priority">
                                <option value="" disabled selected>{{ get_label('select_priority', 'Select Priority') }}</option>
                                @foreach($priorities as $pri)
                                    <option value="{{ $pri->id }}">{{ $pri->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">{{ get_label('status', 'Status') }}</label>
                            <select class="form-select" name="status_id">
                                <option value="" disabled selected>{{ get_label('select_status', 'Select Status') }}</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}">{{ $status->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="unit" class="form-label">{{ get_label('unit', 'Unit') }} <span class="asterisk">*</span></label>
                            <select class="form-select" id="unit" name="unit_id" required>
                                <option value="" disabled selected>{{ get_label('select_unit', 'Select Unit') }}</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="quantity" class="form-label">{{ get_label('quantity', 'Quantity') }} <span class="asterisk">*</span></label>
                            <input type="number" id="quantity" name="quantity" class="form-control" required min="1">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">{{ get_label('starts_at', 'Starts at') }} <span class="asterisk">*</span></label>
                            <input type="text" id="start_date" name="start_date" class="form-control" required placeholder="dd-mm-yyyy">
                            @error('start_date')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">{{ get_label('ends_at', 'Ends at') }} <span class="asterisk">*</span></label>
                            <input type="text" id="end_date" name="end_date" class="form-control" placeholder="dd-mm-yyyy">
                            @error('end_date')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ get_label('submit', 'Submit') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create_activity_modal1').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var taskId = button.data('task-id'); // Extract info from data-* attributes
            
            // Update the modal's hidden input field with the task ID
            var modal = $(this);
            modal.find('#task_id').val(taskId);
        });

        // Intercept form submission
        $('#createActivityForm').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            
            // Gather the form data
            var formData = {
                task_id: $('#task_id').val(),
                name: $('#activityName').val(),
                priority: $('#activityPriority').val(),
                status_id: $('select[name="status_id"]').val(),
                unit_id: $('#unit').val(),
                quantity: $('#quantity').val(),
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val(),
            };
            // Optionally, you can submit the form after logging
            this.submit(); // Comment this line if you only want to log the data without submitting
        });
    });
</script>

@endsection