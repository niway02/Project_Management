@extends('layout')

@section('title')
    <?= get_label('task_details', 'Task details') ?>
@endsection

@section('content')
<style>
    .nav-tabs {
        margin-bottom: -1px;
    }

    .nav-tabs .nav-link {
        padding: 10px 20px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #495057;
        border-radius: 0;
    }

    .nav-tabs .nav-link.active {
        background-color: #007bff;
        border-color: #007bff;
        color: #fff;
    }

    .tab-content {
        padding: 20px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-top: none;
        border-radius: 0 0 5px 5px;
    }

    #activity-content {
        background-color: #fff;
    }

    #schedule-content {
        background-color: #f1f8ff;
    }

    #reminder-content {
        background-color: #fff0f0;
    }

    #timeline-content {
        background-color: #e7f6e7;
    }
</style>
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
        <h2 class="fw-bold">{{ $task->title }}</h2>
        <div class="ml-auto">
            <a href="{{ route('subtasks.create') }}?task_id={{ $task->id }}" class="btn btn-primary">Create Sub Task</a>
        </div>
    </div>
   
    <div class="row">
        <div class="col-md-12">
            
            <div class="card mb-4">
                <div class="card-body">
                <div>
    @include('tasks.task_dashbord')
</div>
                <ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link active" id="activity-tab" data-toggle="tab" href="#activity" onclick="loadTabContent('activity')">Activity</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="schedule-tab" data-toggle="tab" href="#schedule" onclick="loadTabContent('schedule')">Schedule</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="reminder-tab" data-toggle="tab" href="#reminder" onclick="loadTabContent('reminder')">Reminder</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="timeline-tab" data-toggle="tab" href="#timeline" onclick="loadTabContent('timeline')">Timeline</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="kaban-tab" data-toggle="tab" href="#kaban" onclick="loadTabContent('kaban')">kaban</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="gannt-tab" data-toggle="tab" href="#gannt" onclick="loadTabContent('gannt')">Gannt</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="issue-tab" data-toggle="tab" href="#issue" onclick="loadTabContent('issue')">Issue</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="dependency-tab" data-toggle="tab" href="#dependency" onclick="loadTabContent('dependency')">Dependency</a>
    </li>
    
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="activity">
        <h4>Activity</h4>
        <div class="text-end mt-3">
        <a href="{{ url('#') }}" class="btn btn-primary me-2">Export Excel</a>
        <a href="{{ url('#') }}" class="btn btn-primary me-2">Export PDF</a>
        <button onclick="window.print()" class="btn btn-primary">Print</button>
    </div>
        <div id="activity-content">
        @if (count($data) > 0)
        <table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Status</th>
            <th>Priority</th>
            <th>Progress</th>
            <th>Issue</th>
            <th>Start Date</th>
            <th>Duration</th>
            <th>End Date</th>
            <th>Estimated Date</th>
            <th>Lead Time</th>
            <th>View</th>
        </tr>
    </thead>
    <tbody>
        @php
            $materialCounter = 1;
        @endphp
        @foreach ($data as $subtask)
        <tr>
            <td>{{ $subtask['id'] }}</td>
            <td>{{ $subtask['task_name'] }}</td>
            <td>
                <div style="display: inline-block; padding: 5px 10px; border-radius: 5px; background-color: 
                    @switch($subtask['status'])
                        @case('completed')
                            green; // Green color
                            @break
                        @case('pending')
                        #696cff; // Orange color
                            @break
                        @case('cancel')
                            red; // Red color
                            @break
                        @case('notstart')
                            blue; // Blue color
                            @break
                        @default
                        #ffab00; // Yellow color
                    @endswitch
                ">
                    <span style="color: #fff; font-weight: bold;">{{ $subtask['status'] }}</span>
                </div>
            </td>
            <td>{{ $subtask['priority'] }}</td>
            <td>
                <div class="progress">
                    @php
                        $progressColor = '';
                        if ($subtask['progress'] < 25) {
                            $progressColor = 'bg-red'; // Red color
                        } elseif ($subtask['progress'] < 50) {
                            $progressColor = 'bg-blue'; // Blue color
                        } elseif ($subtask['progress'] < 75) {
                            $progressColor = 'bg-orange'; // Orange color
                        } else {
                            $progressColor = 'bg-green'; // Green color
                        }
                    @endphp
                    <div class="progress-bar {{ $progressColor }}" role="progressbar" style="width: {{ $subtask['progress'] }}%" aria-valuenow="{{ $subtask['progress'] }}" aria-valuemin="0" aria-valuemax="100">
                        {{ $subtask['progress'] }}%
                    </div>
                </div>
            </td>
            <td>issue</td>
            <td>{{ $subtask['start_date'] }}</td>
            <td>
                @php
                    $startDate = new DateTime($subtask['start_date']);
                    $endDate = new DateTime($subtask['end_date']);
                    $duration = $startDate->diff($endDate)->format('%a days');
                @endphp
                {{ $duration }}
            </td>
            <td>{{ $subtask['end_date'] }}</td>
            <td>{{ $subtask['estimated_date'] }}</td>
            <td>
                @php
                    $startDateTime = new DateTime($subtask['start_date']);
                    $endDateTime = new DateTime($subtask['end_date']);
                    $leadTime = $startDateTime->diff($endDateTime)->format('%a days');
                @endphp
                {{ $leadTime }}
            </td>
            <td>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#subtaskModal{{ $subtask['id'] }}">
                    cost details
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
                            @else
                                <p>No subtask found.</p>
                            @endif
        </div>
    </div>
    <div class="tab-pane fade" id="schedule">
    <h4>Schedule</h4>
    <div id="schedule-content">
    @include('tasks.task_schedule')
    </div>
</div>
    <div class="tab-pane fade" id="reminder">
        <div id="reminder-content">
        @include('tasks.reminder')
        </div>
    </div>
    <div class="tab-pane fade" id="timeline">
        <h4>Timeline</h4>
        <div id="timeline-content">
            <!-- Timeline content will be loaded dynamically here -->
        </div>
    </div>
    <div class="tab-pane fade" id="kaban">
        <h4>Gannt</h4>
        <div id="kaban-content">
            <!-- Timeline content will be loaded dynamically here -->
            @include('tasks.task_kaban')

        </div>
    </div>
    <div class="tab-pane fade" id="gannt">
        <h4>Gannt</h4>
        <div id="gannt-content">
            <!-- Timeline content will be loaded dynamically here -->
            @include('tasks.task_gannt')

        </div>
    </div>
    <div class="tab-pane fade" id="issue">
        <h4>Issue</h4>
        <div id="issue-content">
            <!-- Timeline content will be loaded dynamically here -->
            @include('tasks.task_issue')

        </div>
    </div>
        <div class="tab-pane fade" id="dependency">
        <div id="dependency-content">
            <!-- Timeline content will be loaded dynamically here -->
            @include('tasks.task_issue2')

        </div>
    </div>
</div>

<script>
    function loadTabContent(tab) {
        // Perform an AJAX request to fetch the corresponding tab content
        // and update the respective tab's content div

        // Example AJAX call using jQuery
        $.ajax({
            url: '/get-tab-content',
            method: 'GET',
            data: { tab: tab },
            success: function(response) {
                // Update the content div of the clicked tab
                $('#' + tab + '-content').html(response);
            },
            error: function() {
                alert('Error occurred while loading tab content.');
            }
        });
    }
</script>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
@foreach ($data as $subtask)
<div class="modal fade" id="subtaskModal{{ $subtask['id'] }}" tabindex="-1" role="dialog" aria-labelledby="subtaskModalLabel{{ $subtask['id'] }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="subtaskModalLabel{{ $subtask['id'] }}">Subtask {{ $subtask['id'] }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
              
                <div class="tab-content">
                <ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link active" id="material-tab" data-toggle="tab" href="#material-pop">Material</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="equipment-tab" data-toggle="tab" href="#equipment-pop">Equipment</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="labor-tab" data-toggle="tab" href="#labor-pop">Labor</a>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="material-pop">
       <h4> Material Cost </h4>
       <a href="{{ route('materialcosts.materialcostsSelect') }}" class="btn btn-primary mb-3">Add Material Cost</a>
       <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Unit</th>
                                <th>Planned Quantity</th>
                                <th>Actual Quantity</th>
                                <th>Planned Cost</th>
                                <th>Actual Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php
            $materialCounter = 1;
        @endphp
                            @php
                                $totalPlanQty = 0;
                                $totalActualQty = 0;
                                $totalPlanCost = 0;
                                $totalActualCost = 0;
                            @endphp
                            @foreach ($subtask['materialCosts'] as $materialCost)
                                @php
                                    $totalPlanQty += $materialCost['planQty'];
                                    $totalActualQty += $materialCost['ActualQty'];
                                    $totalPlanCost += $materialCost['plancost'];
                                    $totalActualCost += $materialCost['Actualcost'];
                                @endphp
                                <tr>
                                <td>
                Material{{ $materialCounter }}
                @php
                    $materialCounter++;
                @endphp
            </td>
                                    <td>{{ $materialCost['unit'] }}</td>
                                    <td>{{ $materialCost['planQty'] }}</td>
                                    <td>{{ $materialCost['ActualQty'] }}</td>
                                    <td>{{ $materialCost['plancost'] }}</td>
                                    <td>{{ $materialCost['Actualcost'] }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2"><strong>Total</strong></td>
                                <td><strong>{{ $totalPlanQty }}</strong></td>
                                <td><strong>{{ $totalActualQty }}</strong></td>
                                <td><strong>${{ $totalPlanCost }}</strong></td>
                                <td><strong>${{ $totalActualCost }}</strong></td>
                            </tr>
                        </tbody>
                    </table>

                    @php
                        $progress = $totalPlanQty != 0 ? ($totalActualQty / $totalPlanQty) * 100 : 0;
                    @endphp
                    <div class="progress">
                        <div id="progress-bar" class="progress-bar bg-success" role="progressbar"
                            style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                            {{ $progress }}% Complete
                        </div>
                    </div>
    </div>
    <div class="tab-pane fade" id="equipment-pop">
        <h4>Equipment Cost<h4>
        <a href="{{ route('equipmentcosts.create') }}" class="btn btn-primary">Add Equpiment </a>
        <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Unit</th>
                                <th>Planned Quantity</th>
                                <th>Actual Quantity</th>
                                <th>Planned Cost</th>
                                <th>Actual Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php
            $materialCounter = 1;
        @endphp
                            @php
                                $totalPlanQty = 0;
                                $totalActualQty = 0;
                                $totalPlanCost = 0;
                                $totalActualCost = 0;
                            @endphp
                            @foreach ($subtask['materialCosts'] as $materialCost)
                                @php
                                    $totalPlanQty += $materialCost['planQty'];
                                    $totalActualQty += $materialCost['ActualQty'];
                                    $totalPlanCost += $materialCost['plancost'];
                                    $totalActualCost += $materialCost['Actualcost'];
                                @endphp
                                <tr>
                                <td>
                Equipment{{ $materialCounter }}
                @php
                    $materialCounter++;
                @endphp
            </td>
                                    <td>{{ $materialCost['unit'] }}</td>
                                    <td>{{ $materialCost['planQty'] }}</td>
                                    <td>{{ $materialCost['ActualQty'] }}</td>
                                    <td>{{ $materialCost['plancost'] }}</td>
                                    <td>{{ $materialCost['Actualcost'] }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2"><strong>Total</strong></td>
                                <td><strong>{{ $totalPlanQty }}</strong></td>
                                <td><strong>{{ $totalActualQty }}</strong></td>
                                <td><strong>${{ $totalPlanCost }}</strong></td>
                                <td><strong>${{ $totalActualCost }}</strong></td>
                            </tr>
                        </tbody>
                    </table>


                    @php
                        $progress = $totalPlanQty != 0 ? ($totalActualQty / $totalPlanQty) * 100 : 0;
                    @endphp
                    <div class="progress">
                        <div id="progress-bar" class="progress-bar bg-success" role="progressbar"
                            style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                            {{ $progress }}% Complete
                        </div>
                    </div>
    </div>
    <div class="tab-pane fade" id="labor-pop">
    <h4> Labor Cost <h4><a href="{{ route('materials.create') }}" class="btn btn-primary">Add Labor</a>
    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Planned Quantity</th>
                                <th>Actual Quantity</th>
                                <th>Planned Cost</th>
                                <th>Actual Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php
            $materialCounter = 1;
        @endphp
                            @php
                                $totalPlanQty = 0;
                                $totalActualQty = 0;
                                $totalPlanCost = 0;
                                $totalActualCost = 0;
                            @endphp
                            @foreach ($subtask['materialCosts'] as $materialCost)
                                @php
                                    $totalPlanQty += $materialCost['planQty'];
                                    $totalActualQty += $materialCost['ActualQty'];
                                    $totalPlanCost += $materialCost['plancost'];
                                    $totalActualCost += $materialCost['Actualcost'];
                                @endphp
                                <tr>
                                <td>
                user {{ $materialCounter }}
                @php
                    $materialCounter++;
                @endphp
            </td>
                                    <td>{{ $materialCost['planQty'] }}</td>
                                    <td>{{ $materialCost['ActualQty'] }}</td>
                                    <td>{{ $materialCost['plancost'] }}</td>
                                    <td>{{ $materialCost['Actualcost'] }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2"><strong>Total</strong></td>
                                <td><strong>{{ $totalPlanQty }}</strong></td>
                                <td><strong>{{ $totalActualQty }}</strong></td>
                                <td><strong>${{ $totalPlanCost }}</strong></td>
                                <td><strong>${{ $totalActualCost }}</strong></td>
                            </tr>
                        </tbody>
                    </table>

                    @php
                        $progress = $totalPlanQty != 0 ? ($totalActualQty / $totalPlanQty) * 100 : 0;
                    @endphp
                    <div class="progress">
                        <div id="progress-bar" class="progress-bar bg-success" role="progressbar"
                            style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                            {{ $progress }}% Complete
                        </div>
                    </div>
    </div>
</div>
</div>


                </div>
            </div>
        </div>
    </div>
@endforeach

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function showTabPopUp(tabId) {
        $('#myModal .tab-pane').removeClass('show active');
        $('#' + tabId).addClass('show active');
    }

    // Automatically analyze the material data when the modal is shown
    $('#myModal').on('shown.bs.modal', function () {
        analyzeMaterialData();
    });
</script>
<script src="{{asset('assets/js/pages/project-information.js')}}"></script>
@endsection
