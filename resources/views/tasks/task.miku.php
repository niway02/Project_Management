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
        <h2 class="fw-bold">{{ $task->title }}</h2>
        <div class="ml-auto">
            <a href="{{ route('subtasks.create') }}?task_id={{ $task->id }}" class="btn btn-primary">Create Sub Task</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                   

                    <div class="tab-content mt-4">
                        <div id="subtasks" class="tab-pane fade show active">
                            @if (count($data) > 0)
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Status</th>
                                            <th>Priority</th>
                                            <th>Resource Spent (Planned)</th>
                                            <th>Resource Spent (Actual)</th>
                                            <th>Progress</th>
                                            <th>start Date</th>
                                            <th>End Date</th>
                                            <th>view</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $subtask)
                                            <tr>
                                                <td>{{ $subtask['id'] }}</td>
                                                <td>{{ $subtask['task_name'] }}</td>
                                                <td style="color:green">
                                                    {{ $subtask['status'] }}
                                                </td>
                                                <td  style="color:red">
                                                    {{ $subtask['priority'] }}
                                                </td>
                                                <td>{{ $subtask['planned'] }}</td>
                                                <td>{{ $subtask['actual'] }}</td>
                                                <td>{{$subtask['progress']}}</td>
                                                <td>{{$subtask['start_date']}}</td>
                                                <td>{{$subtask['end_date']}}</td>
                                                <td>   
                                                <button type="button" class="btn btn-primary" id="openModalButton" data-id="{{$subtask['end_date']}}" data-toggle="modal" data-target="#myModal">
    Open Modal
</button>
                                                </td>
                                                <td>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#subtaskModal{{$subtask['id']}}">
        Subtask {{$subtask['id']}}
    </button>
</td>
                                            </tr>
             <!-- Modal -->
      <!-- Modal -->
      <div class="modal fade" id="subtaskModal{{$subtask['id']}}" tabindex="-1" role="dialog" aria-labelledby="subtaskModalLabel{{$subtask['id']}}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="subtaskModalLabel{{$subtask['id']}}">Subtask {{$subtask['id']}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" id="material-tab" data-toggle="tab" href="#material-pop" onclick="showTabPopUp('material-pop')">Material</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="equipment-tab" data-toggle="tab" href="#equipment-pop" onclick="showTabPopUp('equipment-pop')">Equipment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="labor-tab" data-toggle="tab" href="#labor-pop" onclick="showTabPopUp('labor-pop')">Labor</a>
                    </li>
                </ul>
                <h5>Material Costs</h5>
                          <div>
                          @foreach ($subtask['materialCosts'] as $materialCost)
                           {{$materialCost['name']}}
                            {{$materialCost['unit']}}
                            @endforeach
</div>
                </div>
            </div>
        </div>
    </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>No subtask found.</p>
                            @endif
                        </div>
                        <div id="resource" class="tab-pane fade">
                        
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
            </div>
        </div>
    </div>
</div>
<!-- Modal -->


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Modal Title</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" id="material-tab" data-toggle="tab" href="#material-pop" onclick="showTabPopUp('material-pop')">Material</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="equipment-tab" data-toggle="tab" href="#equipment-pop" onclick="showTabPopUp('equipment-pop')">Equipment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="labor-tab" data-toggle="tab" href="#labor-pop" onclick="showTabPopUp('labor-pop')">Labor</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="material-pop" role="tabpanel" aria-labelledby="material-tab">
                        <h2>Material</h2>
                        
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Unit</th>
                                    <th>Plan Qty</th>
                                    <th>Actual Qty</th>
                                    <th>Plan Cost</th>
                                    <th>Actual Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Item 1</td>
                                    <td>m3</td>
                                    <td>10</td>
                                    <td>10</td>
                                    <td>$100</td>
                                    <td>$90</td>
                                </tr>
                                <tr>
                                    <td>Item 3</td>
                                    <td>m3</td>
                                    <td>10</td>
                                    <td>10</td>
                                    <td>$100</td>
                                    <td>$90</td>
                                </tr>
                                <!-- Add more rows here -->
                            </tbody>
                        </table>
                        
                        <div id="analysis-results">
                          
                        <div style="display: flex; flex-wrap: wrap;">
    <div style="flex-basis: 50%;">
        <p>Total Plan Qty: <span id="total-plan-qty">0</span></p>
        <p>Total Actual Qty: <span id="total-actual-qty">0</span></p>
    </div>
    <div style="flex-basis: 50%;">
        <p>Total Plan Cost: $<span id="total-plan-cost">0.00</span></p>
        <p>Total Actual Cost: $<span id="total-actual-cost">0.00</span></p>
    </div>
</div>
                            <div class="progress">
                                <div id="progress-bar" class="progress-bar bg-success" role="progressbar"
                                    style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="equipment-pop" role="tabpanel" aria-labelledby="equipment-tab">
                        <h2>Equipment</h2>
                        <p>This is the equipment data for the selected item.</p>
                        <!-- Add your equipment data here -->
                    </div>
                    <div class="tab-pane fade" id="labor-pop" role="tabpanel" aria-labelledby="labor-tab">
                        <h2>Labor</h2>
                        <p>This is the labor data for the selected item.</p>
                        <!-- Add your labor data here -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function showTabPopUp(tabId) {
            $('#myModal .tab-pane').removeClass('show active');
            $('#' + tabId).addClass('showactive');
        }
        function analyzeMaterialData() {
    var totalPlanQty = 0;
    var totalActualQty = 0;
    var totalPlanCost = 0;
    var totalActualCost = 0;
    
    // Iterate over each table row
    $('#material-pop tbody tr').each(function() {
        var planQty = parseFloat($(this).find('td:nth-child(3)').text());
        var actualQty = parseFloat($(this).find('td:nth-child(4)').text());
        var planCost = parseFloat($(this).find('td:nth-child(5)').text().replace('$', ''));
        var actualCost = parseFloat($(this).find('td:nth-child(6)').text().replace('$', ''));
        
        totalPlanQty += planQty;
        totalActualQty += actualQty;
        totalPlanCost += planCost;
        totalActualCost += actualCost;
    });
    
    // Update analysis results
    $('#total-plan-qty').text(totalPlanQty);
    $('#total-actual-qty').text(totalActualQty);
    $('#total-plan-cost').text(totalPlanCost.toFixed(2));
    $('#total-actual-cost').text(totalActualCost.toFixed(2));
    
    // Calculate progress percentage
    var progressPercentage = (totalActualQty / totalPlanQty) * 100;
    progressPercentage = Math.min(progressPercentage, 100);  // Ensure progress doesn't exceed 100%
    
    // Update progress bar
    var progressBar = $('#progress-bar');
    progressBar.css('width', progressPercentage + '%');
    progressBar.attr('aria-valuenow', progressPercentage);
    progressBar.text(progressPercentage.toFixed(2) + '%');

    // Change progress bar color based on progress percentage
    progressBar.removeClass('bg-danger bg-info bg-success'); // Remove existing color classes
    
    if (progressPercentage > 90) {
        progressBar.addClass('bg-success');
    } else if (progressPercentage > 70) {
        progressBar.addClass('bg-info');
    } else if (progressPercentage < 40) {
        progressBar.addClass('bg-danger');
    }
}

    // Automatically analyze the material data when the modal is shown
    $('#myModal').on('shown.bs.modal', function () {
        analyzeMaterialData();
    });
    </script>

@endsection
