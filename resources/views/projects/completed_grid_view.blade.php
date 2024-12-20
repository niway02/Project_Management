@extends('layout')

@section('title')
<?= $is_favorite == 1 ? get_label('favorite_projects', 'Favorite projects') : get_label('projects', 'Projects') ?> - <?= get_label('grid_view', 'Grid view') ?>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-2 mt-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{url('/home')}}"><?= get_label('home', 'Home') ?></a>
                    </li>
                    @if ($is_favorite==1)
                    <li class="breadcrumb-item"><a href="{{url('/projects')}}"><?= get_label('projects', 'Projects') ?></a></li>
                    <li class="breadcrumb-item active"><?= get_label('favorite', 'Favorite') ?></li>
                    @else
                    <li class="breadcrumb-item active"><?= get_label('projects', 'Projects') ?></li>
                    @endif
                </ol>
            </nav>
        </div>

        <div>
            @php
            $url = $is_favorite == 1 ? url('projects/list/favorite') : url('projects/completed/list');
            $additionalParams = request()->has('status') ? '/projects/list?status=' . request()->status : '';
            $finalUrl = url($additionalParams ?: $url);
            @endphp
            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_project_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_project', 'Create project') ?>"><i class='bx bx-plus'></i></button></a>
            <a href="{{ $finalUrl }}"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('list_view', 'List view') ?>"><i class='bx bx-list-ul'></i></button></a>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-3">
            <select class="form-select" id="status_filter" aria-label="Default select example">
                <option value=""><?= get_label('filter_by_status', 'Filter by status') ?></option>
                @foreach ($statuses as $status)
                <?php $selected = isset($_REQUEST['status']) && $_REQUEST['status'] !== '' && $_REQUEST['status'] == $status->id  ? "selected" : "";
                ?>
                <option value="{{$status->id}}" {{$selected}}>{{$status->title}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="sort" aria-label="Default select example">
                <option value=""><?= get_label('sort_by', 'Sort by') ?></option>
                <option value="newest" <?= request()->sort && request()->sort == 'newest' ? "selected" : "" ?>><?= get_label('newest', 'Newest') ?></option>
                <option value="oldest" <?= request()->sort && request()->sort == 'oldest' ? "selected" : "" ?>><?= get_label('oldest', 'Oldest') ?></option>
                <option value="recently-updated" <?= request()->sort && request()->sort == 'recently-updated' ? "selected" : "" ?>><?= get_label('most_recently_updated', 'Most recently updated') ?></option>
                <option value="earliest-updated" <?= request()->sort && request()->sort == 'earliest-updated' ? "selected" : "" ?>><?= get_label('least_recently_updated', 'Least recently updated') ?></option>
            </select>
        </div>
        <div class="col-md-5">
            <select id="selected_tags" class="form-control js-example-basic-multiple" name="tag[]" multiple="multiple" data-placeholder="<?= get_label('filter_by_tags', 'Filter by tags') ?>">
                @foreach ($tags as $tag)
                <option value="{{$tag->id}}" @if(in_array($tag->id, $selectedTags)) selected @endif>{{$tag->title}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-1">
            <div>
                <button type="button" id="tags_filter" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('filter', 'Filter') ?>"><i class='bx bx-filter-alt'></i></button>

            </div>
        </div>
    </div>
    @if (is_countable($projects) && count($projects) > 0)
    <div class="mt-4 d-flex row">
        @foreach ($projects as $project)
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        @foreach ($project->tags as $tag)
                        <span class="badge bg-{{$tag->color}}">{{$tag->title}}</span>
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title"><a href="{{url('/projects/information/' . $project->id)}}" target="_blank"><strong>{{$project->title}}</strong></a></h4>
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="input-group">
                                <a href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class='bx bx-cog' id="settings-icon"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="dropdown-item">
                                        <a href="javascript:void(0);" class="card-link edit-project" data-id="{{$project->id}}">
                                            <i class='menu-icon tf-icons bx bx-edit'></i><?= get_label('update', 'Update') ?>
                                        </a>
                                    </li>
                                    <li class="dropdown-item">
                                        <a href="javascript:void(0);" class="delete" data-reload="true" data-type="projects" data-id="{{$project->id}}">
                                            <i class='menu-icon tf-icons bx bx-trash text-danger'></i><?= get_label('delete', 'Delete') ?>
                                        </a>
                                    </li>
                                    <li class="dropdown-item">
                                        <a href="javascript:void(0);" class="duplicate" data-type="projects" data-id="{{$project->id}}" data-reload="true">
                                            <i class='menu-icon tf-icons bx bx-copy text-warning'></i><?= get_label('duplicate', 'Duplicate') ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <a href="javascript:void(0);" class="mx-2">
                                <i class='bx {{$project->is_favorite ? "bxs" : "bx"}}-star favorite-icon text-warning' data-id="{{$project->id}}" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="{{$project->is_favorite ? get_label('remove_favorite', 'Click to remove from favorite') : get_label('add_favorite', 'Click to mark as favorite')}}" data-favorite="{{$project->is_favorite}}"></i>
                            </a>
                        </div>
                    </div>
                    @if ($project->budget != '')
                    <span class='badge bg-label-primary me-1'> {{ format_currency($project->budget) }}</span>
                    @endif
                    <div class="my-{{$project->budget != '' ? '3':'2'}}">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <label for="statusSelect" class="form-label"><?= get_label('status', 'Status') ?></label>
                                <div class="input-group">
                                    <select class="form-select form-select-sm" id="statusSelect" data-id="{{ $project->id }}" data-original-status-id="{{$project->status->id}}">
                                        @foreach($statuses as $status)
                                        <option value="{{$status->id}}" class="badge bg-label-{{$status->color}}" {{ $project->status->id == $status->id ? 'selected' : '' }}>
                                            {{$status->title}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="prioritySelect" class="form-label"><?= get_label('priority', 'Priority') ?></label>
                                <div class="input-group">
                                    <select class="form-select form-select-sm" id="prioritySelect" data-id="{{ $project->id }}" data-original-priority-id="{{$project->priority ? $project->priority->id : ''}}">
                                        @foreach($priorities as $priority)
                                        <option value="{{$priority->id}}" class="badge bg-label-{{$priority->color}}" {{ $project->priority && $project->priority->id == $priority->id ? 'selected' : '' }}>
                                            {{$priority->title}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="my-4 d-flex justify-content-between">
                        <span><i class='bx bx-task text-primary'></i> <b><?= isAdminOrHasAllDataAccess() ? count($project->tasks) : $auth_user->project_tasks($project->id)->count(); ?></b> <?= get_label('tasks', 'Tasks') ?></span>
                        <a href="{{url('/projects/tasks/draggable/' . $project->id)}}"><button type="button" class="btn btn-sm rounded-pill btn-outline-primary"><?= get_label('tasks', 'Tasks') ?></button></a>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-6">
                            <p class="card-text">
                                <?= get_label('users', 'Users') ?>:
                            <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                                <?php
                                $users = $project->users;
                                $count = count($users);
                                $displayed = 0;
                                if ($count > 0) {
                                    // Case 1: Users are less than or equal to 10
                                    foreach ($users as $user) {
                                        if ($displayed < 10) { ?>
                                            <li class="avatar avatar-sm pull-up" title="<?= $user->first_name ?> <?= $user->last_name ?>">
                                                <a href="/users/profile/<?= $user->id ?>" target="_blank">
                                                    <img src="<?= $user->photo ? asset('storage/' . $user->photo) : asset('storage/photos/no-image.jpg') ?>" class="rounded-circle" alt="<?= $user->first_name ?> <?= $user->last_name ?>">
                                                </a>
                                            </li>
                                <?php
                                            $displayed++;
                                        } else {
                                            // Case 2: Users are greater than 10
                                            $remaining = $count - $displayed;
                                            echo '<span class="badge badge-center rounded-pill bg-primary mx-1">+' . $remaining . '</span>';
                                            break;
                                        }
                                    }
                                    // Add edit option at the end
                                    echo '<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-outline-primary btn-sm rounded-circle edit-project update-users-clients" data-id="' . $project->id . '"><span class="bx bx-edit"></span></a>';
                                } else {
                                    // Case 3: Not assigned
                                    echo '<span class="badge bg-primary">' . get_label('not_assigned', 'Not assigned') . '</span>';
                                    // Add edit option at the end
                                    echo '<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-outline-primary btn-sm rounded-circle edit-project update-users-clients" data-id="' . $project->id . '"><span class="bx bx-edit"></span></a>';
                                }
                                ?>
                            </ul>
                            </p>
                        </div>

                        <div class="col-md-6">
                            <p class="card-text">
                                <?= get_label('clients', 'Clients') ?>:
                            <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                                <?php
                                $clients = $project->clients;
                                $count = $clients->count();
                                $displayed = 0;
                                if ($count > 0) {
                                    foreach ($clients as $client) {
                                        if ($displayed < 10) { ?>
                                            <li class="avatar avatar-sm pull-up" title="<?= $client->first_name ?> <?= $client->last_name ?>">
                                                <a href="/clients/profile/<?= $client->id ?>" target="_blank">
                                                    <img src="<?= $client->photo ? asset('storage/' . $client->photo) : asset('storage/photos/no-image.jpg') ?>" class="rounded-circle" alt="<?= $client->first_name ?> <?= $client->last_name ?>">
                                                </a>
                                            </li>
                                <?php
                                            $displayed++;
                                        } else {
                                            $remaining = $count - $displayed;
                                            echo '<span class="badge badge-center rounded-pill bg-primary mx-1">+' . $remaining . '</span>';
                                            break;
                                        }
                                    }
                                    // Add edit option at the end
                                    echo '<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-outline-primary btn-sm rounded-circle edit-project update-users-clients" data-id="' . $project->id . '"><span class="bx bx-edit"></span></a>';
                                } else {
                                    // Display "Not assigned" badge
                                    echo '<span class="badge bg-primary">' . get_label('not_assigned', 'Not assigned') . '</span>';
                                    // Add edit option at the end
                                    echo '<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-outline-primary btn-sm rounded-circle edit-project update-users-clients" data-id="' . $project->id . '"><span class="bx bx-edit"></span></a>';
                                }
                                ?>
                            </ul>
                            </p>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-6 text-start">
                            <i class='bx bx-calendar text-success'></i><?= get_label('starts_at', 'Starts at') ?> : {{ format_date($project->start_date)}}
                        </div>

                        <div class="col-md-6 text-end">
                            <i class='bx bx-calendar text-danger'></i><?= get_label('ends_at', 'Ends at') ?> : {{ format_date($project->end_date)}}
                        </div>
                    </div>

                </div>
            </div>
        </div>
        @endforeach

        <div>
            {{$projects->links()}}
        </div>
    </div>
    <!-- delete project modal -->

    @else
    <?php $type = 'projects'; ?>
    <x-empty-state-card :type="$type" />
    @endif
</div>
<script>
    var add_favorite = '<?= get_label('add_favorite', 'Click to mark as favorite') ?>';
    var remove_favorite = '<?= get_label('remove_favorite', 'Click to remove from favorite') ?>';
</script>
<script src="{{asset('assets/js/pages/project-grid.js')}}"></script>

@endsection