@extends('layout')

@section('title')
<?= get_label('dashboard', 'Dashboard') ?>
@endsection

@section('content')
@authBoth
<div class="container-fluid">
    <div class="col-lg-12 col-md-12 order-1">
        <div class="row mt-4">
            <div class="col-lg-3 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="menu-icon tf-icons bx bx-briefcase-alt-2 bx-md text-success"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1"><?= get_label('total_projects', 'Total projects') ?></span>
                        <h3 class="card-title mb-2">{{is_countable($projects) && count($projects) > 0?count($projects):0}}</h3>
                        <a href="/projects"><small class="text-success fw-semibold"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="menu-icon tf-icons bx bx-task bx-md text-primary"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1"><?= get_label('total_tasks', 'Total tasks') ?></span>
                        <h3 class="card-title mb-2">{{$tasks}}</h3>
                        <a href="/tasks"><small class="text-primary fw-semibold"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small></a>
                    </div>
                </div>
            </div>
            @hasRole('admin|member')
            <div class="col-lg-3 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="menu-icon tf-icons bx bxs-user-detail bx-md text-warning"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1"><?= get_label('total_users', 'Total users') ?></span>
                        <h3 class="card-title mb-2">{{is_countable($users) && count($users) > 0?count($users):0}}</h3>
                        <a href="/users"><small class="text-warning fw-semibold"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="menu-icon tf-icons bx bxs-user-detail bx-md text-info"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1"><?= get_label('total_clients', 'Total clients') ?></span>
                        <h3 class="card-title mb-2"> {{is_countable($clients) && count($clients) > 0?count($clients):0}}</h3>
                        <a href="/clients"><small class="text-info fw-semibold"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small></a>
                    </div>
                </div>
            </div>
            @else
            <div class="col-lg-3 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="menu-icon tf-icons bx bx-shape-polygon text-success bx-md text-warning"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1"><?= get_label('total_meetings', 'Total meetings') ?></span>
                        <h3 class="card-title mb-2">{{is_countable($meetings) && count($meetings) > 0?count($meetings):0}}</h3>
                        <a href="/meetings"><small class="text-warning fw-semibold"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="menu-icon tf-icons bx bx-list-check bx-md text-info"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1"><?= get_label('total_todos', 'Total todos') ?></span>
                        <h3 class="card-title mb-2"> {{is_countable($total_todos) && count($total_todos) > 0?count($total_todos):0}}</h3>
                        <a href="/todos"><small class="text-info fw-semibold"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small></a>
                    </div>
                </div>
            </div>
            @endhasRole

        </div>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info alert-dismissible" role="alert">
                    <?= get_label('reload_page_to_change_chart_colors', 'Reload the page to change chart colors!') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pt-3 pb-1">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2"><?= get_label('project_statistics', 'Project statistics') ?></h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">

                            <div id="projectStatisticsChart"></div>
                        </div>
                        <?php $total_projects_count = 0; ?>
                        <ul class="p-0 m-0">
                            @foreach ($statuses as $status)
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-briefcase-alt-2 text-{{$status->color}}"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">

                                        <a href="/projects?status={{ $status->id }}">
                                            <h6 class="mb-0">{{ $status->title }}</h6>
                                        </a>
                                    </div>
                                    <div class="user-progress">
                                        <small class="fw-semibold"><?= $count = isAdminOrHasAllDataAccess() ? count($status->projects) : $auth_user->status_projects($status->id)->count(); ?></small>
                                    </div>
                                    <?php $total_projects_count += $count; ?>
                                </div>
                            </li>
                            @endforeach
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-menu"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h5 class="mb-0"><?= get_label('total', 'Total') ?></h5>
                                    </div>
                                    <div class="user-progress">
                                        <div class="status-count">
                                            <h5 class="mb-0">{{$total_projects_count}}</h5>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pt-3 pb-1">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2"><?= get_label('task_statistics', 'Task statistics') ?></h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div id="taskStatisticsChart"></div>
                        </div>
                        <?php $total_tasks_count = 0; ?>
                        <ul class="p-0 m-0">
                            @foreach ($statuses as $status)
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-task text-{{$status->color}}"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <a href="/tasks?status={{ $status->id }}">
                                            <h6 class="mb-0">{{ $status->title }}</h6>
                                        </a>
                                    </div>
                                    <div class="user-progress">
                                        <small class="fw-semibold"><?= $count =  isAdminOrHasAllDataAccess() ? count($status->tasks) : $auth_user->status_tasks($status->id)->count(); ?></small>
                                    </div>
                                    <?php $total_tasks_count += $count; ?>
                                </div>
                            </li>
                            @endforeach
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-menu"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h5 class="mb-0"><?= get_label('total', 'Total') ?></h5>
                                    </div>
                                    <div class="user-progress">
                                        <div class="status-count">
                                            <h5 class="mb-0">{{$total_tasks_count}}</h5>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 order-2 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between pt-3 pb-0">
                        <h5 class="card-title m-0 me-2"><?= get_label('todos_overview', 'Todos overview') ?></h5>
                        <div>
                            <span data-bs-toggle="modal" data-bs-target="#create_todo_modal"><a href="javascript:void(0);" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_todo', 'Create todo') ?>"><i class='bx bx-plus'></i></a></span>
                            <a href="/todos"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('view_more', 'View more') ?>"><i class="bx bx-list-ul"></i></button></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div id="todoStatisticsChart"></div>
                        </div>
                        <ul class="p-0 m-0">
                            @if (is_countable($todos) && count($todos) > 0)
                            @foreach($todos as $todo)
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0">
                                    <input type="checkbox" id="{{$todo->id}}" onclick='update_status(this)' name="{{$todo->id}}" class="form-check-input mt-0" {{$todo->is_completed ? 'checked' : ''}}>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0 <?= $todo->is_completed ? 'striked' : '' ?>" id="{{$todo->id}}_title">{{ $todo->title }}</h6>
                                        <small class="text-muted d-block mb-1">{{ format_date($todo->created_at,true)}}</small>
                                    </div>
                                    <div class="user-progress d-flex align-items-center gap-1">
                                        <a href="javascript:void(0);" class="edit-todo" data-bs-toggle="modal" data-bs-target="#edit_todo_modal" data-id="{{ $todo->id }}" title="<?= get_label('update', 'Update') ?>"><i class='bx bx-edit mx-1'></i></a>
                                        <a href="javascript:void(0);" class="delete" data-id="{{$todo->id}}" data-type="todos" title="<?= get_label('delete', 'Delete') ?>"><i class='bx bx-trash text-danger mx-1'></i></a>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                            @else
                            <div class=" h-100 d-flex justify-content-center align-items-center">
                                <div>
                                    <?= get_label('todos_not_found', 'Todos not found!') ?>
                                </div>
                            </div>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="nav-align-top my-4">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-upcoming-birthdays" aria-controls="navs-top-upcoming-birthdays" aria-selected="true">
                    <i class="menu-icon tf-icons bx bx-cake text-success"></i><?= get_label('upcoming_task', 'Upcoming Taks') ?>
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-upcoming-work-anniversaries" aria-controls="navs-top-upcoming-work-anniversaries" aria-selected="false">
                    <i class="menu-icon tf-icons bx bx-star text-primary"></i><?= get_label('upcoming_request', 'Request') ?>
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-upcoming-work-anniversaries" aria-controls="navs-top-upcoming-work-anniversaries" aria-selected="false">
                    <i class="menu-icon tf-icons bx bx-star text-primary"></i><?= get_label('Contract', 'Contract') ?>
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-upcoming-work-anniversaries" aria-controls="navs-top-upcoming-work-anniversaries" aria-selected="false">
                    <i class="menu-icon tf-icons bx bx-star text-primary"></i><?= get_label('payment', 'Payment') ?>
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-members-on-leave" aria-controls="navs-top-members-on-leave" aria-selected="false">
                    <i class="menu-icon tf-icons bx bx-home text-danger"></i><?= get_label('members_on_leave', 'Members on leave') ?>
                </button>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active show" id="navs-top-upcoming-birthdays" role="tabpanel">
                @if (!$auth_user->dob)
                <div class="alert alert-primary alert-dismissible" role="alert"><?= get_label('dob_not_set_alert', 'You DOB is not set') ?>, <a href="/users/edit/{{$auth_user->id}}" target="_blank"><?= get_label('click_here_to_set_it_now', 'Click here to set it now') ?></a>.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
                @endif
                <x-upcoming-birthdays-card :users="$users" />
            </div>
            <div class="tab-pane fade" id="navs-top-upcoming-work-anniversaries" role="tabpanel">
                @if (!$auth_user->doj)
                <div class="alert alert-primary alert-dismissible" role="alert"><?= get_label('doj_not_set_alert', 'You DOJ is not set') ?>, <a href="/users/edit/{{$auth_user->id}}" target="_blank"><?= get_label('click_here_to_set_it_now', 'Click here to set it now') ?></a>.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
                @endif
                <x-upcoming-work-anniversaries-card :users="$users" />
            </div>
            <div class="tab-pane fade" id="navs-top-members-on-leave" role="tabpanel">
                <x-members-on-leave-card :users="$users" />
            </div>
        </div>
    </div>

    @if ($auth_user->can('manage_projects') || $auth_user->can('manage_tasks'))
    <div class="nav-align-top my-4">
        <ul class="nav nav-tabs" role="tablist">
            @if ($auth_user->can('manage_projects'))
            <li class="nav-item">
                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-projects" aria-controls="navs-top-projects" aria-selected="true">
                    <i class="menu-icon tf-icons bx bx-briefcase-alt-2 text-success"></i><?= get_label('projects', 'Projects') ?>
                </button>
            </li>
            @endif
            @if ($auth_user->can('manage_tasks'))
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-tasks" aria-controls="navs-top-tasks" aria-selected="false">
                    <i class="menu-icon tf-icons bx bx-task text-primary"></i><?= get_label('tasks', 'Tasks') ?>
                </button>
            </li>
            @endif
        </ul>
        <div class="tab-content">
            @if ($auth_user->can('manage_projects'))
            <div class="tab-pane fade active show" id="navs-top-projects" role="tabpanel">
                <div class="d-flex justify-content-between">
                    <h4 class="fw-bold">{{$auth_user->first_name}}'s <?= get_label('projects', 'Projects') ?></h4>
                </div>
                @if (is_countable($projects) && count($projects) > 0)
                <?php
                $type = isUser() ? 'user' : 'client';
                $id = isAdminOrHasAllDataAccess() ? '' : $type . '_' . $auth_user->id;
                ?>
                <x-projects-card :projects="$projects" :id="$id" :users="$users" :clients="$clients" />
                @else
                <?php
                $type = 'Projects'; ?>
                <x-empty-state-card :type="$type" />
                @endif
            </div>
            @endif

            @if ($auth_user->can('manage_tasks'))
            <div class="tab-pane fade {{!$auth_user->can('manage_projects')?'active show':''}}" id="navs-top-tasks" role="tabpanel">
                <div class="d-flex justify-content-between">
                    <h4 class="fw-bold">{{$auth_user->first_name}}'s <?= get_label('tasks', 'Tasks') ?></h4>
                </div>
                @if ($tasks > 0)
                <?php
                $type = isUser() ? 'user' : 'client';
                $id = isAdminOrHasAllDataAccess() ? '' : $type . '_' . $auth_user->id;
                ?>
                <x-tasks-card :tasks="$tasks" :id="$id" :users="$users" :clients="$clients" :projects="$projects" />
                @else
                <?php
                $type = 'Tasks'; ?>
                <x-empty-state-card :type="$type" />
                @endif

            </div>
            @endif

        </div>
    </div>
    @endif
    <!-- ------------------------------------------- -->
    <?php

    $titles = [];
    $project_counts = [];
    $task_counts = [];
    $bg_colors = [];
    $total_projects = 0;
    $total_tasks = 0;

    $total_todos = count($todos);
    $done_todos = 0;
    $pending_todos = 0;
    $todo_counts = [];
    $ran = array('#63ed7a', '#ffa426', '#fc544b', '#6777ef', '#FF00FF', '#53ff1a', '#ff3300', '#0000ff', '#00ffff', '#99ff33', '#003366', '#cc3300', '#ffcc00', '#ff00ff', '#ff9900', '#3333cc', '#ffff00');
    $backgroundColor = array_rand($ran);
    $d = $ran[$backgroundColor];
    foreach ($statuses as $status) {
        $project_count = isAdminOrHasAllDataAccess() ? count($status->projects) : $auth_user->status_projects($status->id)->count();
        array_push($project_counts, $project_count);

        $task_count = isAdminOrHasAllDataAccess() ? count($status->tasks) : $auth_user->status_tasks($status->id)->count();
        array_push($task_counts, $task_count);

        array_push($titles, "'" . $status->title . "'");

        $k = array_rand($ran);
        $v = $ran[$k];
        array_push($bg_colors, "'" . $v . "'");

        $total_projects += $project_count;
        $total_tasks += $task_count;
    }
    $titles = implode(",", $titles);
    $project_counts = implode(",", $project_counts);
    $task_counts = implode(",", $task_counts);
    $bg_colors = implode(",", $bg_colors);

    foreach ($todos as $todo) {
        $todo->is_completed ? $done_todos += 1 : $pending_todos += 1;
    }
    array_push($todo_counts, $done_todos);
    array_push($todo_counts, $pending_todos);
    $todo_counts = implode(",", $todo_counts);
    ?>
</div>
<script>
    var labels = [<?= $titles ?>];
    var project_data = [<?= $project_counts ?>];
    var task_data = [<?= $task_counts ?>];
    var bg_colors = [<?= $bg_colors ?>];
    var total_projects = [<?= $total_projects ?>];
    var total_tasks = [<?= $total_tasks ?>];
    var total_todos = [<?= $total_todos ?>];
    var todo_data = [<?= $todo_counts ?>];
    //labels
    var done = '<?= get_label('done', 'Done') ?>';
    var pending = '<?= get_label('pending', 'Pending') ?>';
    var total = '<?= get_label('total', 'Total') ?>';
</script>
<script src="{{asset('assets/js/apexcharts.js')}}"></script>
<script src="{{asset('assets/js/pages/dashboard.js')}}"></script>
@else
<div class="w-100 h-100 d-flex align-items-center justify-content-center"><span>You must <a href="/login">Log in</a> or <a href="/register">Register</a> to access {{$general_settings['company_title']}}!</span></div>
@endauth
@endsection