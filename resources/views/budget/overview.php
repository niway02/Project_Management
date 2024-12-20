@extends('layout')

@section('title', get_label('budget_allocation', 'Budget Allocation') . ' - ' . get_label('list_view', 'List view'))

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        <form method="GET" class="shortcut">
                            <label for="date">Select a month:</label>
                            <input type="month" id="date" name="month" value="{{ $selectedMonth }}" onchange="this.form.submit()" class="form-control">
                        </form>

                        <div class="nav-align-top my-4">
                            <ul class="nav nav-tabs" role="tablist">
                                @foreach (['week1', 'week2', 'week3', 'week4'] as $week)
                                    <li class="nav-item">
                                        <button type="button" class="nav-link @if ($loop->first) active @endif" role="tab" data-bs-toggle="tab" data-bs-target="#{{ $week }}" aria-controls="{{ $week }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                            <i class="menu-icon tf-icons bx bx-calendar text-warning"></i>{{ get_label($week, ucfirst($week)) }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content">
                                @foreach (['week1', 'week2', 'week3', 'week4'] as $week)
                                    <div class="tab-pane fade @if ($loop->first) active show @endif" id="{{ $week }}" role="tabpanel">
                                        <h5>{{ ucfirst($week) }} Budget Details</h5>

                                        <div class="row mb-4">
                                            <div class="col-lg-4 col-md-6 mb-4">
                                                <div class="card bg-light">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Planned Cost</h5>
                                                        <p class="card-text display-4 text-success">{{ number_format($totalPlannedBudget[$week] ?? 0, 2) }}</p> Birr
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6 mb-4">
                                                <div class="card bg-light">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Actual Cost</h5>
                                                        <p class="card-text display-4 text-primary">{{ number_format($totalActualBudget[$week] ?? 0, 2) }}</p> Birr
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6 mb-4">
                                                <div class="card bg-light">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Cost Variance</h5>
                                                        @php
                                                            $plannedCost = $totalPlannedBudget[$week] ?? 0;
                                                            $actualCost = $totalActualBudget[$week] ?? 0;
                                                            $costVariance = $actualCost - $plannedCost;
                                                        @endphp
                                                        <p class="card-text display-4 text-danger">{{ number_format($costVariance, 2) }}</p>
                                                        <div class="text-center">
                                                            @if ($costVariance >= 0 && $costVariance <= ($plannedCost * 0.1))
                                                                <span class="badge bg-success">On Track</span>
                                                            @elseif ($costVariance > ($plannedCost * 0.1) && $costVariance <= ($plannedCost * 0.2))
                                                                <span class="badge bg-warning">At Risk</span>
                                                            @else
                                                                <span class="badge bg-danger">Off Track</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="nav-align-top my-4">
                                            <ul class="nav nav-tabs" role="tablist">
                                                @foreach (['material', 'equipment', 'labor'] as $resource)
                                                    <li class="nav-item">
                                                        <button type="button" class="nav-link @if ($loop->first) active @endif" role="tab" data-bs-toggle="tab" data-bs-target="#{{ $week }}-{{ $resource }}" aria-controls="{{ $week }}-{{ $resource }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                                            <i class="menu-icon tf-icons bx bx-box text-info"></i>{{ get_label($resource, ucfirst($resource)) }}
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>

                                            <div class="tab-content">
                                                @foreach (['material', 'equipment', 'labor'] as $resource)
                                                    <div class="tab-pane fade @if ($loop->first) active show @endif" id="{{ $week }}-{{ $resource }}" role="tabpanel">
                                                        <h5>{{ ucfirst($resource) }} Budget Details for {{ ucfirst($week) }}</h5>
                                                        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addEditModal" data-action="add" data-resource="{{ $resource }}">Add New</button>
                                                        <table class="table table-striped table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>ID</th>
                                                                    <th>Activity Name</th>
                                                                    <th>Planned Quantity</th>
                                                                    <th>Actual Quantity</th>
                                                                    <th>Planned Cost</th>
                                                                    <th>Actual Cost</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($resource === 'material' ? $materialCostData[$week] : ($resource === 'equipment' ? $equipmentCostData[$week] : $laborCostData[$week]) as $item)
                                                                    @php
                                                                        $actualQuantity = $item->actual_quantity ?? 0;
                                                                        $actualCost = $item->actual_cost ?? 0;
                                                                    @endphp
                                                                    <tr>
                                                                        <td>{{ $item->id }}</td>
                                                                        <td>{{ $item->activity->name }}</td>
                                                                        <td>{{ $item->planned_quantity }}</td>
                                                                        <td>
                                                                            <input type="number" class="form-control" value="{{ $actualQuantity }}" id="actualQuantity_{{ $item->id }}" readonly>
                                                                        </td>
                                                                        <td>{{ number_format($item->planned_cost, 2) }}</td>
                                                                        <td>
                                                                            <input type="number" class="form-control" value="{{ $actualCost }}" id="actualCost_{{ $item->id }}" readonly>
                                                                        </td>
                                                                        <td>
                                                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#addEditModal" data-action="edit" data-id="{{ $item->id }}" data-name="{{ $item->activity->name }}" data-planned-quantity="{{ $item->planned_quantity }}" data-actual-quantity="{{ $actualQuantity }}" data-planned-cost="{{ $item->planned_cost }}" data-actual-cost="{{ $actualCost }}">Edit</button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Overall Cost Analysis at the End -->
                        <h5>Overall Cost Analysis</h5>
                        <canvas id="overallCostChart"></canvas>
                        <script>
                            const ctxOverall = document.getElementById('overallCostChart').getContext('2d');
                            const overallCostChart = new Chart(ctxOverall, {
                                type: 'bar',
                                data: {
                                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                                    datasets: [{
                                        label: 'Planned Cost',
                                        data: [
                                            {{ $totalPlannedBudget['week1'] ?? 0 }},
                                            {{ $totalPlannedBudget['week2'] ?? 0 }},
                                            {{ $totalPlannedBudget['week3'] ?? 0 }},
                                            {{ $totalPlannedBudget['week4'] ?? 0 }}
                                        ],
                                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                        borderColor: 'rgba(75, 192, 192, 1)',
                                        borderWidth: 1
                                    }, {
                                        label: 'Actual Cost',
                                        data: [
                                            {{ $totalActualBudget['week1'] ?? 0 }},
                                            {{ $totalActualBudget['week2'] ?? 0 }},
                                            {{ $totalActualBudget['week3'] ?? 0 }},
                                            {{ $totalActualBudget['week4'] ?? 0 }}
                                        ],
                                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                        borderColor: 'rgba(153, 102, 255, 1)',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="addEditModal" tabindex="-1" aria-labelledby="addEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEditModalLabel">Edit Actual Quantity and Cost</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="budgetForm">
                    <input type="hidden" name="id" id="itemId">
                    <div class="mb-3">
                        <label for="activityName" class="form-label">Activity Name</label>
                        <input type="text" class="form-control" id="activityName" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="plannedQuantity" class="form-label">Planned Quantity</label>
                        <input type="number" class="form-control" id="plannedQuantity" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="actualQuantity" class="form-label">Actual Quantity</label>
                        <input type="number" class="form-control" id="actualQuantity" name="actualQuantity" required>
                    </div>
                    <div class="mb-3">
                        <label for="actualCost" class="form-label">Actual Cost</label>
                        <input type="number" class="form-control" id="actualCost" name="actualCost" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Handle modal open for edit
    const addEditModal = document.getElementById('addEditModal');
    addEditModal.addEventListener('show.bs.modal', (event) => {
        const button = event.relatedTarget; // Button that triggered the modal
        const itemId = button.getAttribute('data-id'); // Extract info from data-* attributes

        // Set the modal fields
        document.getElementById('itemId').value = itemId;
        document.getElementById('activityName').value = button.getAttribute('data-name');
        document.getElementById('plannedQuantity').value = button.getAttribute('data-planned-quantity');
        document.getElementById('actualQuantity').value = button.getAttribute('data-actual-quantity');
        document.getElementById('actualCost').value = button.getAttribute('data-actual-cost');
    });

    // Handle form submission
    document.getElementById('budgetForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('/your-endpoint', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            // Handle response
            // Update the table or refresh it as needed
            // Close the modal
            $('#addEditModal').modal('hide');
        })
        .catch(error => console.error('Error:', error));
    });
</script>
@endsection