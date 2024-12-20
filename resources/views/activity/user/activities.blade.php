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
    <div class="row mt-4"> 
        <div class="col-lg-3 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                            <i class="menu-icon tf-icons bx bx-briefcase-alt-2 bx-md text-canceled" style="color: #71dd37;"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1"><?= get_label('completed', 'completed') ?></span>
                        <h3 class="card-title mb-2">2</h3>
                        <a href="/tasks/completed"><small class="text-success fw-semibold" style="color: #71dd37;"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small></a>
                    </div>
                </div>
            </div>
        <div class="col-lg-3 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="menu-icon tf-icons bx bx-briefcase-alt-2 bx-md " style="color: #696cff;"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1"><?= get_label('in progress', 'In progress') ?></span>
                        <h3 class="card-title mb-2">2</h3>
                        <a href="/tasks/inProgress"><small style="color: #696cff;"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small></a>
                    </div>
                </div>
            </div>
        <div class="col-lg-3 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                            <i class="menu-icon tf-icons bx bx-briefcase-alt-2 bx-md text-canceled" style="color: #ffab00;"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1"><?= get_label('Not started', 'Not started') ?></span>
                        <h3 class="card-title mb-2">2</h3>
                        <a href="/tasks/notStarted"><small style="color: #ffab00;"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small></a>
                    </div>
                </div>
            </div>
         <div class="col-lg-3 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                            <i class="menu-icon tf-icons bx bx-briefcase-alt-2 bx-md text-canceled" style="color: #ff3e1d;"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1"><?= get_label('cancelled', 'Cancelled') ?></span>
                        <h3 class="card-title mb-2">2</h3>
                        <a href="/tasks/cancelled"><small style="color: #ff3e1d;"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small></a>
                    </div>
                </div>
            </div>
            <div>
    <div>
    <?php
    $id = isset($project->id) ? 'project_' . $project->id : '';

    ?>
    <x-user-activity :tasks="$tasks" :id="$id" :users="$users" :clients="$clients" :projects="$projects" :project="$project" />
    
    <div class="chart-row">
    <div class="chart-container">
        <canvas id="taskBarChart"></canvas>
    </div>
    <div class="chart-container">
        <canvas id="taskPieChart"></canvas>
    </div>
</div>
</div>
@endsection
<style>
    .chart-row {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }

    .chart-container {
        flex: 1 1 100%;
        margin-bottom: 20px;
        height: 400px;
        max-width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    @media (min-width: 768px) {
        .chart-container {
            flex-basis: 50%;
            max-width: 50%;
        }
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var taskData = @json($taskData);

        // Bar Chart
        var barCtx = document.getElementById('taskBarChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(taskData),
                datasets: [{
                    label: 'Task Count',
                    data: Object.values(taskData),
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',  // Not Started: Blue
                        'rgba(255, 206, 86, 0.7)',  // Canceled: Yellow
                        'rgba(75, 192, 192, 0.7)',  // Completed: Green
                        'rgba(255, 99, 132, 0.7)',  // Started: Red
                      
                    ],
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 5,
                    }
                }
            }
        });

        // Pie Chart
        var pieCtx = document.getElementById('taskPieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: Object.keys(taskData),
                datasets: [{
                    data: Object.values(taskData),
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',    // Started: Red
                        'rgba(255, 206, 86, 0.7)',   // Not Started: Blue
                        'rgba(75, 192, 192, 0.7)',  // Completed: Green
                        'rgba(255, 99, 132, 0.7)',   // Canceled: Yellow
                    ],
                }]
            },
            options: {
                responsive: true,
            }
        });
    });
</script>