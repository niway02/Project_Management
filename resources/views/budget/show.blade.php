@extends('layout')

@section('title')
    {{ get_label('budget_allocation', 'Budget Allocation') }} - {{ get_label('list_view', 'List view') }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="mb-4 text-primary">{{ get_label('budget_allocation', 'Budget Allocation for Project X') }}</h1>
            <div class="row mb-4 mt-4">
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1">
                            <li class="breadcrumb-item">
                                <a href="{{ url('/home') }}">{{ get_label('home', 'Home') }}</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ url('/budget-allocations') }}">{{ get_label('budget_allocation', 'Budget Allocation') }}</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

<div class="row mb-4">
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title">Planned Budget</h5>
                <p class="card-text display-4 text-success">{{$totalPlannedBudget}}</p>Birr
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title">Actual Budget</h5>
                <p class="card-text display-4 text-primary">{{$totalActualBudget}}</p>Birr
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title">Variance Budget</h5>
                <p class="card-text display-4 text-danger">{{$totalActualBudget-$totalPlannedBudget}}</p>
                <div class="text-center">
                    @php
                        $variance = $totalActualBudget - $totalPlannedBudget;
                        $plannedBudget = $totalPlannedBudget;
                    @endphp
                    @if ($variance >= 0 && $variance <= ($plannedBudget * 0.1))
                        <span class="badge bg-success" data-bs-toggle="tooltip" data-bs-placement="top" title="The actual budget is within 10% of the planned budget.">On Track</span>
                    @elseif ($variance > ($plannedBudget * 0.1) && $variance <= ($plannedBudget * 0.2))
                        <span class="badge bg-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="The actual budget is between 10% and 20% of the planned budget.">At Risk</span>
                    @else
                        <span class="badge bg-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="The actual budget is more than 20% of the planned budget.">Off Track</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Budget Allocation Breakdown</h5>
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="card-title">Material Budget</h5>
                                <div class="row">
                                    <div class="col-6">
                                        <p class="card-text display-5 text-success">{{ number_format($materialPlannedBudget, 2, '.', ',') }} $</p>
                                        <p class="text-muted">Planned</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="card-text display-5 text-primary">{{ number_format($materialActualBudget, 2, '.', ',') }} $</p>
                                        <p class="text-muted">Actual</p>
                                    </div>
                                </div>
                                <div class="text-center">
                                    @php
                                        $variance = $materialActualBudget - $materialPlannedBudget;
                                        $plannedBudget = $materialPlannedBudget;
                                        $status = $variance >= 0 && $variance <= ($plannedBudget * 0.1) ? 'On Track' : ($variance > ($plannedBudget * 0.1) && $variance <= ($plannedBudget * 0.2) ? 'At Risk' : 'Off Track');
                                        $statusClass = $variance >= 0 && $variance <= ($plannedBudget * 0.1) ? 'success' : ($variance > ($plannedBudget * 0.1) && $variance <= ($plannedBudget * 0.2) ? 'warning' : 'danger');
                                        $statusTooltip = $variance >= 0 && $variance <= ($plannedBudget * 0.1) ? 'The actual budget is within 10% of the planned budget.' : ($variance > ($plannedBudget * 0.1) && $variance <= ($plannedBudget * 0.2) ? 'The actual budget is between 10% and 20% of the planned budget.' : 'The actual budget is more than 20% of the planned budget.');
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }} me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $statusTooltip }}">{{ $status }}</span>
                                    <span class="text-muted">{{ number_format($variance, 2, '.', ',') }} $</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="card-title">Equipment Budget</h5>
                                <div class="row">
                                    <div class="col-6">
                                        <p class="card-text display-5 text-success">{{ number_format($equipmentPlannedBudget, 2, '.', ',') }} $</p>
                                        <p class="text-muted">Planned</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="card-text display-5 text-primary">{{ number_format($equipmentActualBudget, 2, '.', ',') }} $</p>
                                        <p class="text-muted">Actual</p>
                                    </div>
                                </div>
                                <div class="text-center">
                                    @php
                                        $variance = $equipmentActualBudget - $equipmentPlannedBudget;
                                        $plannedBudget = $equipmentPlannedBudget;
                                        $status = $variance >= 0 && $variance <= ($plannedBudget * 0.1) ? 'On Track' : ($variance > ($plannedBudget * 0.1) && $variance <= ($plannedBudget * 0.2) ? 'At Risk' : 'Off Track');
                                        $statusClass = $variance >= 0 && $variance <= ($plannedBudget * 0.1) ? 'success' : ($variance > ($plannedBudget * 0.1) && $variance <= ($plannedBudget * 0.2) ? 'warning' : 'danger');
                                        $statusTooltip = $variance >= 0 && $variance <= ($plannedBudget * 0.1) ? 'The actual budget is within 10% of the planned budget.' : ($variance > ($plannedBudget * 0.1) && $variance <= ($plannedBudget * 0.2) ? 'The actual budget is between 10% and 20% of the planned budget.' : 'The actual budget is more than 20% of the planned budget.');
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }} me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $statusTooltip }}">{{ $status }}</span>
                                    <span class="text-muted">{{ number_format($variance, 2, '.', ',') }} $</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="card-title">Labor Budget</h5>
                                <div class="row">
                                    <div class="col-6">
                                        <p class="card-text display-5 text-success">{{ number_format($laborPlannedBudget, 2, '.', ',') }} $</p>
                                        <p class="text-muted">Planned</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="card-text display-5 text-primary">{{ number_format($laborActualBudget, 2, '.', ',') }} $</p>
                                        <p class="text-muted">Actual</p>
                                    </div>
                                </div>
                                <div class="text-center">
                                    @php
                                        $variance = $laborActualBudget - $laborPlannedBudget;
                                        $plannedBudget = $laborPlannedBudget;
                                        $status = $variance >= 0 && $variance <= ($plannedBudget * 0.1) ? 'On Track' : ($variance > ($plannedBudget * 0.1) && $variance <= ($plannedBudget * 0.2) ? 'At Risk' : 'Off Track');
                                        $statusClass = $variance >= 0 && $variance <= ($plannedBudget * 0.1) ? 'success' : ($variance > ($plannedBudget * 0.1) && $variance <= ($plannedBudget * 0.2) ? 'warning' : 'danger');
                                        $statusTooltip = $variance >= 0 && $variance <= ($plannedBudget * 0.1) ? 'The actual budget is within 10% of the planned budget.' : ($variance > ($plannedBudget * 0.1) && $variance <= ($plannedBudget * 0.2) ? 'The actual budget is between 10% and 20% of the planned budget.' : 'The actual budget is more than 20% of the planned budget.');
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }} me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $statusTooltip }}">{{ $status }}</span>
                                    <span class="text-muted">{{ number_format($variance, 2, '.', ',') }} $</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="nav-align-top my-4">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-equipment" aria-controls="navs-top-equipment" aria-selected="true">
                                            <i class="menu-icon tf-icons bx bx-wrench text-warning"></i>{{ get_label('equipment', 'Equipment') }}
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-material" aria-controls="navs-top-material" aria-selected="false">
                                            <i class="menu-icon tf-icons bx bx-box text-info"></i>{{ get_label('material', 'Material') }}
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-labor" aria-controls="navs-top-labor" aria-selected="false">
                                            <i class="menu-icon tf-icons bx bx-user text-danger"></i>{{ get_label('labor', 'Labor') }}
                                        </button>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade active show" id="navs-top-equipment" role="tabpanel">
                                        <h4 class="fw-bold">{{ get_label('equipment', 'Equipment') }}</h4>
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Unit</th>
                                                    <th>Task</th>
                                                    <th>Sub Task</th>
                                                    <th>Planned Quantity</th>
                                                    <th>Actual Quantity</th>
                                                    <th>Quantity Variance</th>
                                                    <th>Planned Budget</th>
                                                    <th>Actual Budget</th>
                                                    <th>Budget Variance</th>
                                                    <th>Needed Budget</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Material A</td>
                                                    <td>Pcs</td>
                                                    <td>Procure equipment</td>
                                                    <td>Research and select suitable equipment </td>
                                                    <td>8</td>
                                                    <td>10</td>
                                                    <td>2</td>
                                                    <td>$120,000</td>
                                                    <td>$120,000</td>
                                                    <td>$0</td>
                                                    <td>$150,000</td>
                                                </tr>
                                                <tr>
                                                    <td>Material B</td>
                                                    <td>Kg</td>
                                                    <td>Install equipment</td>
                                                    <td>Prepare site for equipment installation</td>
                                                    <td>50</td>
                                                    <td>45</td>
                                                    <td>-5</td>
                                                    <td>$80,000</td>
                                                    <td>$75,000</td>
                                                    <td>-$5,000</td>
                                                    <td>$90,000</td>
                                                </tr>
                                                <tr>
                                                    <td>Material C</td>
                                                    <td>Boxes</td>
                                                    <td>Train staff on equipment usage </td>
                                                    <td>Train staff on equipment usage</td>
                                                    
                                                    <td>20</td>
                                                    <td>18</td>
                                                    <td>-2</td>
                                                    <td>$50,000</td>
                                                    <td>$48,000</td>
                                                    <td>-$2,000</td>
                                                    <td>$55,000</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <h5 class="fw-bold">Tasks</h5>
                                                <ul>
                                                    <li>Procure equipment</li>
                                                    <li>Install equipment</li>
                                                    <li>Train staff on equipment usage</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <h5 class="fw-bold">Sub-tasks</h5>
                                                <ul>
                                                    <li>Research and select suitable equipment</li>
                                                    <li>Negotiate with suppliers</li>
                                                    <li>Schedule equipment delivery</li>
                                                    <li>Prepare site for equipment installation</li>
                                                    <li>Coordinate with installation team</li>
                                                    <li>Develop training materials</li>
                                                    <li>Conduct training sessions</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="navs-top-material" role="tabpanel">
                                        <h4 class="fw-bold">{{ get_label('material', 'Material') }}</h4>
                                        <div class="table-responsive">
                <table class="table table-striped table-hover"
                       id="equipment-table"
                       data-url="{{ route('material-costs.getMaterialCosts') }}"
                       data-toggle="table"
                       data-search="true"
                       data-show-columns="true"
                       data-pagination="true"
                       data-side-pagination="server"
                       data-query-params="queryParams"
                       data-icons-prefix="bx bx-"
                       data-icons='{"refresh": "refresh", "plus": "plus", "edit": "edit", "trash": "trash"}'
                       data-trim-on-search="true"
                       data-sort-name="item"
                       data-data-field="rows"
                       data-page-list="[5, 10, 20, 50, 100, 200]"
                       data-mobile-responsive="true"
                       data-sort-order="asc">
                    <thead>
                    <tr>
            <th data-checkbox="true"></th>
            <th data-field="id">{{ get_label('Id', 'Id') }}</th>
            <th data-field="material.item">{{ get_label('Name', 'Name') }}</th>
            <th data-field="material.unit_measure.name">{{ get_label('Unit', 'Unit') }}</th>
            <th data-field="subtask.task.title">{{ get_label('Task', 'Task') }}</th>
            <th data-field="subtask.name">{{ get_label('Task', 'Sub Task') }}</th>
            <th data-field="planned_quantity">{{ get_label('Task', 'PLANNED QUANTITY') }}</th>
            <th data-field="actual_quantity">{{ get_label('Task', 'ACTUAL QUANTITY') }}</th>
            <th data-formatter="quantityVarianceFormatter">{{ get_label('Task', 'QUANTITY VARIANCE') }}</th>
            <th data-field="planned_budget">{{ get_label('Task', 'PLANNED BUDGET') }}</th>
            <th data-field="actual_budget">{{ get_label('Reorder Quantity', 'ACTUAL BUDGET') }}</th>
            <th data-formatter="budgetVarianceFormatter">{{ get_label('Min Quantity', 'BUDGET VARIANCE') }}</th>
            <th data-field="needed_budget">{{ get_label('Min Quantity', 'NEEDED BUDGET') }}</th>
            <th data-field="status" data-formatter="statusFormatter">{{ get_label('status', 'Status') }}</th>
            <th data-formatter="actionsFormatter"><?= get_label('actions', 'Actions') ?></th>
        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <div class="row">
  <div class="col-md-6">
    <h3 class="mb-4">Tasks</h3>
    <ul class="list-unstyled">
      @foreach ($taskData as $task)
        <li class="mb-4 card p-4 bg-light">
          <h4 class="mb-3">{{ $task['task_name'] }}</h4>
          <div class="row">
            <div class="col-sm-6">
              <p class="mb-2">
                <span class="font-weight-bold">Planned Quantity:</span> {{ $task['planned_quantity'] }}
              </p>
              <p class="mb-2">
                <span class="font-weight-bold">Actual Quantity:</span> {{ $task['actual_quantity'] }}
              </p>
            </div>
            <div class="col-sm-6">
              <p class="mb-2">
                <span class="font-weight-bold">Planned Budget:</span> {{ $task['planned_budget'] }}
              </p>
              <p class="mb-2">
                <span class="font-weight-bold">Actual Budget:</span> {{ $task['actual_budget'] }}
              </p>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              @php
                $quantityVariance = $task['actual_quantity'] - $task['planned_quantity'];
                $quantityVarianceColor = $quantityVariance >= 0 ? 'text-success' : 'text-danger';
              @endphp
              <p class="mb-2 {{ $quantityVarianceColor }}">
                <span class="font-weight-bold">Quantity Variance:</span> {{ $quantityVariance }}
              </p>
            </div>
            <div class="col-sm-6">
              @php
                $budgetVariance = $task['actual_budget'] - $task['planned_budget'];
                $budgetVariancePercent = abs($budgetVariance * $task['planned_budget']) * 100;
                $budgetVarianceColor = $budgetVariancePercent <= 10 ? 'text-success' : ($budgetVariancePercent <= 20 ? 'text-warning' : 'text-danger');
              @endphp
              <p class="mb-2 {{ $budgetVarianceColor }}">
                <span class="font-weight-bold">Budget Variance:</span> {{ $budgetVariance }} ({{ number_format($budgetVariancePercent, 2) }}%)
              </p>
              <p class="mb-2 {{ $budgetVarianceColor }}">
                <span class="font-weight-bold">Status:</span>
                @if ($budgetVariancePercent <= 10)
                  On Track (green)
                @elseif ($budgetVariancePercent <= 20)
                  At Risk (orange)
                @else
                  Off Track (red)
                @endif
              </p>
            </div>
          </div>
        </li>
      @endforeach
    </ul>
  </div>
  <div class="col-md-6">
    <h3 class="mb-4">Activites</h3>
    <ul class="list-unstyled">
      @foreach ($subTaskData as $data)
        <li class="mb-4 card p-4 bg-light">
          <h4 class="mb-3">{{ $data['activity_name'] }}</h4>
          <div class="row">
            <div class="col-sm-6">
              <p class="mb-2">
                <span class="font-weight-bold">Planned Quantity:</span> {{ $data['planned_quantity'] }}
              </p>
              <p class="mb-2">
                <span class="font-weight-bold">Actual Quantity:</span> {{ $data['actual_quantity'] }}
              </p>
            </div>
            <div class="col-sm-6">
              <p class="mb-2">
                <span class="font-weight-bold">Planned Budget:</span> {{ $data['planned_budget'] }}
              </p>
              <p class="mb-2">
                <span class="font-weight-bold">Actual Budget:</span> {{ $data['actual_budget'] }}
              </p>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              @php
                $quantityVariance = $data['actual_quantity'] - $data['planned_quantity'];
                $quantityVarianceColor = $quantityVariance >= 0 ? 'text-success' : 'text-danger';
              @endphp
              <p class="mb-2 {{ $quantityVarianceColor }}">
                <span class="font-weight-bold">Quantity Variance:</span> {{ $quantityVariance }}
              </p>
            </div>
            <div class="col-sm-6">
            @php
    $budgetVariance = $data['actual_budget'] - $data['planned_budget'];
    if ($data['planned_budget'] != 0) {
        $budgetVariancePercent = abs($budgetVariance / $data['planned_budget']) * 100;
    } else {
        $budgetVariancePercent = 0; // or handle the case where $data['planned_budget'] is 0 in a different way
    }
    $budgetVarianceColor = $budgetVariancePercent <= 10 ? 'text-success' : ($budgetVariancePercent <= 20 ? 'text-warning' : 'text-danger');
@endphp
              <p class="mb-2 {{ $budgetVarianceColor }}">
                <span class="font-weight-bold">Budget Variance:</span> {{ $budgetVariance }} ({{ number_format($budgetVariancePercent, 2) }}%)
              </p>
              <p class="mb-2 {{ $budgetVarianceColor }}">
                <span class="font-weight-bold">Status:</span>
                @if ($budgetVariancePercent <= 10)
                  On Track (green)
                @elseif ($budgetVariancePercent <= 20)
                  At Risk (orange)
                @else
                  Off Track (red)
                @endif
              </p>
            </div>
          </div>
        </li>
      @endforeach
    </ul>
  </div>
</div>

            </div>
                                    </div>
                                    <div class="tab-pane fade" id="navs-top-labor" role="tabpanel">
                                        <h4 class="fw-bold">{{ get_label('labor', 'Labor') }}</h4>
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Planned Budget</th>
                                                    <th>Actual Budget</th>
                                                    <th>Budget Variance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Labor A</td>
                                                    <td>$100,000</td>
                                                    <td>$100,000</td>
                                                    <td>$0</td>
                                                </tr>
                                                <!-- Add more labor data here -->
                                            </tbody>
                                        </table>
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <h5 class="fw-bold">Tasks</h5>
                                                <ul>
                                                    <li>Hire and manage labor</li>
                                                    <li>Coordinate labor activities</li>
                                                    <li>Ensure labor productivity</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <h5 class="fw-bold">Sub-tasks</h5>
                                                <ul>
                                                    <li>Determine labor requirements</li>
                                                    <li>Recruit and screen candidates</li>
                                                    <li>Onboard and train new hires</li>
                                                    <li>Develop work schedules</li>
                                                    <li>Monitor labor performance</li>
                                                    <li>Provide feedback and coaching</li>
                                                    <li>Address labor issues</li>
                                                    <li>Optimize labor utilization</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="budget-utilization-chart" style="height: 400px;"></div>
@endsection

<script>
function actionsFormatter(value, row, index) {
    return [
        '<a href="#" class="newUpdateModal" data-id="' + row.id + '" data-bs-toggle="modal" data-bs-target="#updateModal" title="{{ get_label("Edit", "Edit") }}">' +
        '<i class="bx bx-edit mx-1"></i>' +
        '</a>',
        '<a href="{{ route("budget.show", "1") }}" class="view-detail" data-id="' + row.id + '">' +
        '<i class="bx bx-info-circle text-primary mx-3"></i>' +
        '</a>'
    ].join('');
}
function statusFormatter(value, row, index) {
    let statusClass = '';
    let statusText = '';
    if (row.status == "paid") {
        statusClass = 'bg-success';
        statusText = 'paid';
    } else if (row.status == "unpaid") {
        statusClass = 'bg-danger';
        statusText = 'unpaid';
    } else if (row.status == "credit") {
        statusClass = 'bg-orange';
        statusText = 'credit';
    } else {
        statusClass = 'bg-danger';
        statusText = 'something';
    }

    return `<span class="badge ${statusClass}">${statusText}</span>`;
}

function amountFormatter(value, row, index) {
    return row.amount + ' ' + row.currency;
}
function quantityVarianceFormatter(value, row, index) {
    return row.actual_quantity - row.planned_quantity;
    }

    function budgetVarianceFormatter(value, row, index) {
        return row.actual_budget - row.planned_budget;
    }

</script>
