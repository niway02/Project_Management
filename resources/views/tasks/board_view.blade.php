@extends('layout')

@section('title')
<?= get_label('tasks', 'Tasks') ?> - <?= get_label('draggable', 'Draggable') ?>
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
                    @if (isset($project->id))
                    <li class="breadcrumb-item">
                        <a href="{{url('/projects')}}"><?= get_label('projects', 'Projects') ?></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{url('/projects/information/'.$project->id)}}">{{$project->title}}</a>
                    </li>
                    @endisset
                    <li class="breadcrumb-item">
                        <a href="{{url(isset($project->id)?'/projects/tasks/list/'.$project->id:'/tasks')}}"><?= get_label('tasks', 'Tasks') ?></a>
                    </li>
                    <li class="breadcrumb-item active"><?= get_label('draggable', 'Draggable') ?></li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_task_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_task', 'Create task') ?>"><i class="bx bx-plus"></i></button></a>
            <a href="{{url(isset($project->id)?'/projects/tasks/list/'.$project->id:'/tasks')}}"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('list_view', 'List view') ?>"><i class="bx bx-list-ul"></i></button></a>
        </div>
    </div>

    @if ($total_tasks > 0)
    <div class="alert alert-primary alert-dismissible" role="alert">
        <?= get_label('drag_drop_update_task_status', 'Drag and drop to update task status') . ' !' ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <div class="d-flex card flex-row" style="overflow-x: scroll; overflow-y:hidden">

        @foreach ($statuses as $status)
        <div class="my-4" style="background-color: none ; min-width: 300px; max-width: 300px;">
        <h4 class="fw-bold mx-4 my-2">{{ ucfirst($status->title) }}</h4>
            <div class="row m-2 d-flex flex-column" id="{{$status->slug}}" style="height: 100%" data-status="{{$status->id}}">

                @foreach ($tasks as $task)
                @if($task->status_id==$status->id)
                <x-kanban :task="$task" />
                @endif
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
    @else
    <?php
    $type = 'Tasks';
    ?>
    <x-empty-state-card :type="$type" />
    @endif
</div>
<script>
    var statusArray = <?php echo json_encode($statuses); ?>;
</script>
<script src="{{asset('assets/js/pages/task-board.js')}}"></script>
@endsection