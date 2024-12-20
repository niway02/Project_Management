@extends('layout')

@section('title')
<?= get_label('tasks', 'Tasks') ?> - <?= get_label('list_view', 'List view') ?>
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
                    @isset($project->id)
                    <li class="breadcrumb-item">
                        <a href="{{url('/projects')}}"><?= get_label('projects', 'Projects') ?></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{url('/projects/information/'.$project->id)}}">{{$project->title}}</a>
                    </li>
                    @endisset
                    <li class="breadcrumb-item active"><?= get_label('tasks', 'Tasks') ?></li>
                </ol>
            </nav>
        </div>
        <div>
            @php
            $url = isset($project->id) ? '/projects/tasks/draggable/' . $project->id : '/tasks/draggable';
            $additionalParams = request()->has('project') ? '/projects/tasks/draggable/' . request()->project : '';
            $finalUrl = url($additionalParams ?: $url);
            @endphp

            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_task_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_task', 'Create task') ?>"><i class="bx bx-plus"></i></button></a>
            <a href="{{ $finalUrl }}"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('draggable', 'Draggable') ?>"><i class="bx bxs-dashboard"></i></button></a>
        </div>
        <input type="hidden" id="type">
        <input type="hidden" id="typeId">
  
    </div>
 
    <?php
    $id = isset($project->id) ? 'project_' . $project->id : '';
    ?>
    <x-tasks-card :tasks="$tasks" :id="$id" :users="$users" :clients="$clients" :projects="$projects" :project="$project" />
</div>
@endsection