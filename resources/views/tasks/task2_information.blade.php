@extends('layout')

@section('title')
<?= get_label('task_details', 'Task details') ?>
@endsection

@section('content')
<div class="container-fluid">
    <div class="align-items-center d-flex justify-content-between m-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{url('/home')}}"><?= get_label('home', 'Home') ?></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{url('/tasks')}}"><?= get_label('tasks', 'Tasks') ?></a>
                    </li>
                    <li class="breadcrumb-item active"><?= get_label('view', 'View') ?></li>
                </ol>
            </nav>
        </div>
        <div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="fw-bold">{{ $task->title }}</h2>
                           
                            <div class="row">
                                <div class="col-md-6 mt-3 mb-3">
                                    <label class="form-label" for="start_date"><?= get_label('users', 'Users') ?></label>
                                    <?php
                                    $users = $task->users;
                                    $clients = $task->project->clients;
                                    if (count($users) > 0) { ?>
                                        <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center flex-wrap">
                                            @foreach($users as $user)
                                            <li class="avatar avatar-sm pull-up" title="{{$user->first_name}} {{$user->last_name}}"><a href="/users/profile/{{$user->id}}" target="_blank">
                                                    <img src="{{$user->photo ? asset('storage/' . $user->photo) : asset('storage/photos/no-image.jpg')}}" class="rounded-circle" alt="{{$user->first_name}} {{$user->last_name}}">
                                                </a></li>
                                            @endforeach
                                            <a href="javascript:void(0)" class="btn btn-icon btn-sm btn-outline-primary btn-sm rounded-circle edit-task update-users-clients" data-id="{{$task->id}}"><span class="bx bx-edit"></span></a>
                                        </ul>
                                    <?php } else { ?>
                                        <p><span class="badge bg-primary"><?= get_label('not_assigned', 'Not assigned') ?></span><a href="javascript:void(0)" class="btn btn-icon btn-sm btn-outline-primary btn-sm rounded-circle edit-task update-users-clients" data-id="{{$task->id}}"><span class="bx bx-edit"></span></a></p>
                                    <?php } ?>
                                </div>
                                <div class="col-md-6  mt-3 mb-3">
                                    <label class="form-label" for="end_date"><?= get_label('clients', 'Clients') ?></label>
                                    <?php
                                    if (count($clients) > 0) { ?>
                                        <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center flex-wrap">
                                            @foreach($clients as $client)
                                            <li class="avatar avatar-sm pull-up" title="{{$client->first_name}} {{$client->last_name}}"><a href="/clients/profile/{{$client->id}}" target="_blank">
                                                    <img src="{{$client->photo ? asset('storage/' . $client->photo) : asset('storage/photos/no-image.jpg')}}" class="rounded-circle" alt="{{$client->first_name}} {{$client->last_name}}">
                                                </a></li>
                                            @endforeach
                                        </ul>
                                    <?php } else { ?>
                                        <p><span class="badge bg-primary"><?= get_label('not_assigned', 'Not assigned') ?></span></p>
                                    <?php } ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= get_label('status', 'Status') ?></label>
                                    <div class="input-group">
                                        <select class="form-select form-select-sm" id="statusSelect" data-id="{{ $task->id }}" data-original-status-id="{{ $task->status->id }}" data-type="task">
                                            @foreach($statuses as $status)
                                            <option value="{{ $status->id }}" class="badge bg-label-{{ $status->color }}" {{ $task->status->id == $status->id ? 'selected' : '' }}>
                                                {{ $status->title }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="prioritySelect" class="form-label"><?= get_label('priority', 'Priority') ?></label>
                                    <div class="input-group">
                                        <select class="form-select form-select-sm" id="prioritySelect" data-id="{{ $task->id }}" data-original-priority-id="{{ $task->priority ? $task->priority->id : '' }}" data-type="task">
                                            @foreach($priorities as $priority)
                                            <option value="{{ $priority->id }}" class="badge bg-label-{{ $priority->color }}" {{ $task->priority && $task->priority->id == $priority->id ? 'selected' : '' }}>
                                                {{ $priority->title }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-0" />
                <div class="card-body">
                    <div class="row">

                        <div class="mb-3 col-md-12">
                            <label class="form-label" for="project"><?= get_label('project', 'Project') ?></label>
                            <div class="input-group input-group-merge">
                                @php
                                $project = $task->project;
                                @endphp
                                <input class="form-control px-2" type="text" id="project" value="{{$project->title}}" readonly="">
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="mb-3">
                            <label class="form-label" for="description"><?= get_label('description', 'Description') ?></label>
                            <div class="input-group input-group-merge">
                                <textarea class="form-control" id="description" name="description" rows="5" readonly>{{ $task->description }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="start_date"><?= get_label('starts_at', 'Starts at') ?></label>
                            <div class="input-group input-group-merge">
                                <input type="text" name="start_date" class="form-control" placeholder="" value="{{ format_date($task->start_date)}}" readonly />
                            </div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="due-date"><?= get_label('ends_at', 'Ends at') ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" name="due_date" placeholder="" value="{{ format_date($task->due_date)}}" readonly="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" id="media_type_id" value="{{$task->id}}">
            <div class="card mb-4">
                <div class="card-body">
                    <form class="form-horizontal" id="media-upload" action="{{url('/tasks/upload-media')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <h4 class="mb-3"><?= get_label('task_media', 'Task media') ?></h4>
                            <div class="alert alert-primary alert-dismissible" role="alert"><?= $media_storage_settings['media_storage_type'] == 's3' ? get_label('storage_type_set_as_aws_s3', 'Storage type is set as AWS S3 storage') : get_label('storage_type_set_as_local', 'Storage type is set as local storage') ?>, <a href="/settings/media-storage" target="_blank"><?= get_label('click_here_to_change', 'Click here to change') ?></a>.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
                            <div class="dropzone dz-clickable" id="media-upload-dropzone">

                            </div>
                            <div class="form-group mt-4 text-center">
                                <button class="btn btn-primary" id="upload_media_btn"><?= get_label('upload', 'Upload') ?></button>
                            </div>
                            <div class="d-flex justify-content-center">
                                <div class="form-group" id="error_box">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <input type="hidden" id="data_type" value="task-media">
                        <table id="table" data-toggle="task_media_table" data-loading-template="loadingTemplate" data-url="/tasks/get-media/{{$task->id}}" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParamsTaskMedia">
                            <thead>
                                <tr>
                                    <th data-checkbox="true"></th>
                                    <th data-sortable="true" data-field="id"><?= get_label('id', 'ID') ?></th>
                                    <th data-sortable="true" data-field="file"><?= get_label('file', 'File') ?></th>
                                    <th data-sortable="true" data-field="file_name" data-visible="false"><?= get_label('file_name', 'File name') ?></th>
                                    <th data-sortable="true" data-field="file_size"><?= get_label('file_size', 'File size') ?></th>
                                    <th data-sortable="true" data-field="created_at" data-visible="false"><?= get_label('created_at', 'Created at') ?></th>
                                    <th data-sortable="true" data-field="updated_at" data-visible="false"><?= get_label('updated_at', 'Updated at') ?></th>
                                    <th data-sortable="false" data-field="actions"><?= get_label('actions', 'Actions') ?></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-5"><?= get_label('task_activity_log', 'Sub Task activity ') ?></h4>
                    <a href="{{ route('subtasks.create') }}?task_id={{ $task->id }}" class="btn btn-primary">Create Sub Task</a>
                    @if (count($data) > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Activity Task</th>
                        <th>material Cost</th>
                        <th>Equipment Cost</th>
                        <th>Labor Cost</th>
                        <th>Other Cost</th>
                        <th>Sub Total Cost</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalSubtotalCost = 0;
                    @endphp
                    @foreach ($data as $subtask)
                        @php
                            $subtotalCost = $subtask['total_material_amount'] + $subtask['total_equipment_amount'] + $subtask['total_labor_amount'];
                            $totalSubtotalCost += $subtotalCost;
                        @endphp
                        <tr>
                        <td>{{ $subtask['task_name'] }}</td>
                        <td>{{ number_format($subtask['total_material_amount'], 2) }} &nbsp;<a href="{{ route('materialcosts.show', $subtask['id']) }}">view</a></td>
                        <td>{{ number_format($subtask['total_equipment_amount'], 2) }} &nbsp;<a href="{{ route('equipmentcosts.show', $subtask['id']) }}">view</a></td>
                        <td>{{ number_format($subtask['total_labor_amount'], 2) }} &nbsp;<a href="{{ route('laborcosts.show', $subtask['id']) }}">view</a></td>
                        <td>0.00</td>
                         <td>{{ number_format($subtotalCost, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" style="text-align: right;"><strong>Total:</strong></td>
                        <td>{{ number_format($totalSubtotalCost, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        @else
            <p>No sub task found.</p>
        @endif
                
                </div>
         </div>
        </div>
    </div>
    <script>
        var label_delete = '<?= get_label('delete', 'Delete') ?>';
    </script>
    <script src="{{asset('assets/js/pages/task-information.js')}}"></script>
    @endsection