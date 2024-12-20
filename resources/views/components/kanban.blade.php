@props(['task'])
<div class="card m-2 shadow" data-task-id="{{$task->id}}">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h6 class="card-title"><a href="{{url('/tasks/information/' . $task->id)}}" target="_blank"><strong>{{$task->title}}</strong></a></h6>
            <div>
                <div class="input-group">
                    <a href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class='bx bx-cog'></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-item"><a href="javascript:void(0);" class="card-link edit-task" data-id="{{$task->id}}"><i class='menu-icon tf-icons bx bx-edit'></i> <?= get_label('update', 'Update') ?></a></li>
                        <li class="dropdown-item"><a href="javascript:void(0);" class="card-link delete" data-reload="true" data-type="tasks" data-id="{{ $task->id }}">
                                <i class='menu-icon tf-icons bx bx-trash text-danger'></i> <?= get_label('delete', 'Delete') ?>
                            </a>
                        </li>
                        <li class="dropdown-item">
                            <a href="javascript:void(0);" class="duplicate" data-reload="true" data-type="tasks" data-id="{{$task->id}}">
                                <i class='menu-icon tf-icons bx bx-copy text-warning'></i><?= get_label('duplicate', 'Duplicate') ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card-subtitle text-muted mb-3">{{$task->project->title}}</div>
        <div>
  <label for="statusSelect"><?= get_label('status', 'Status') ?></label>
  @foreach($statuses as $status)
  @if($status->id == $task->status_id)
  <span class="badge bg-label-{{ $status->color }}">{{ $status->title }}</span>
  @endif
  @endforeach
</div>
        <div class="row mt-2">
            <div class="col-md-12">
                <p class="card-text mb-1">
                    <?= get_label('users', 'Users') ?>:
                <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                    <?php
                    $users = $task->users;
                    $count = count($users);
                    $displayed = 0;
                    if ($count > 0) {
                        foreach ($users as $user) {
                            if ($displayed < 9) { ?>
                                <li class="avatar avatar-sm pull-up" title="<?= $user->first_name ?> <?= $user->last_name ?>">
                                    <a href="/users/profile/<?= $user->id ?>" target="_blank">
                                        <img src="<?= $user->photo ? asset('storage/' . $user->photo) : asset('storage/photos/no-image.jpg') ?>" class="rounded-circle" alt="<?= $user->first_name ?> <?= $user->last_name ?>">
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
                        echo '<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-outline-primary btn-sm rounded-circle edit-task update-users-clients" data-id="' . $task->id . '"><span class="bx bx-edit"></span></a>';
                    } else {
                        echo '<span class="badge bg-primary">' . get_label('not_assigned', 'Not assigned') . '</span>';
                        // Add edit option at the end
                        echo '<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-outline-primary btn-sm rounded-circle edit-task update-users-clients" data-id="' . $task->id . '"><span class="bx bx-edit"></span></a>';
                    }
                    ?>
                </ul>

                </p>
            </div>

            <div class="col-md-12">
                <p class="card-text mb-1">
                    Clients:
                <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                    <?php
                    $clients = $task->project->clients;
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
                    } else {
                        // Display "Not assigned" badge
                        echo '<span class="badge bg-primary">' . get_label('not_assigned', 'Not assigned') . '</span>';
                    }
                    ?>
                </ul>

                </p>
            </div>
        </div>
        <div class="d-flex flex-column">
        
            <div>
  <label for="prioritySelect"><?= get_label('priority', 'Priority') ?></label>
  @foreach($priorities as $priority)
  @if($priority->id == $task->priority_id)
  <span class="badge bg-label-{{ $priority->color }}">{{ $priority->title }}</span>
  @endif
  @endforeach
</div>
            <div class="col-md-12">
  <div class="progress" style="height: 20px; ">
    <div class="progress-bar bg-blue progress-bar-lg" role="progressbar" style="width: 40%; padding-top: 5px;" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100">
       <span style="font-size: 15px;">40%</span>
    </div>
  </div>
</div>

            <div class="mt-2">
                <small class="text-muted"><?= get_label('created_at', 'Created At') ?>: {{ format_date($task->created_at) }}</small>
            </div>
        </div>


    </div>
</div>