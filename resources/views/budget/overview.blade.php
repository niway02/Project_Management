@extends('layout')

@section('title', get_label('budget_allocation', 'Budget Allocation') . ' - ' . get_label('list_view', 'List view'))

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        <form method="GET" class="shortcut mb-4">
                            <label for="date">Select a month:</label>
                            <input type="month" id="date" name="month" value="{{ $selectedMonth }}" onchange="this.form.submit()" class="form-control">
                        </form>

                        <div class="nav-align-top mb-4">
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
                                                            <button type="button" class="btn btn-light" data-toggle="modal" data-target="#forecastingModal">
        <span class="badge bg-warning">Forecasting</span>
    </button>                         </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="nav-align-top mb-4">
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
                                                       @if($resource =="labor")
                                                        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#showUtilizationFactorModal" data-action="add" data-resource="{{ $resource }}">Utilization Factor</button>
                                                       @endif
                                                        <table class="table table-striped table-bordered" id="{{ $week }}-{{ $resource }}-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>ID</th>
                                                                    <th>Activity Name</th>
                                                                    <th>Planned Quantity</th>
                                                                    <th>Actual Quantity</th>
                                                                    <th>Variance Quantity</th>
                                                                    <th>Planned Cost</th>
                                                                    <th>Actual Cost</th>
                                                                    <th>Variance Cost</th>
                                                                    <th>Status</th>
                                                                    <th>Remark</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php
                                                                    $totalPlannedQuantity = 0;
                                                                    $totalActualQuantity = 0;
                                                                    $totalPlannedCost = 0;
                                                                    $totalActualCost = 0;
                                                                    $totalVarianceCost = 0;
                                                                @endphp
                                                                @foreach ($resource === 'material' ? $materialCostData[$week] : ($resource === 'equipment' ? $equipmentCostData[$week] : $laborCostData[$week]) as $item)
                                                                    @php
                                                                        $actualQuantity = $item->actual_quantity ?? 0;
                                                                        $quantityVariance = $item->planned_quantity - $actualQuantity;
                                                                        $actualCost = $item->actual_cost ?? 0;
                                                                        $costVariance = $item->planned_cost - $actualCost;

                                                                        $totalPlannedQuantity += $item->planned_quantity;
                                                                        $totalActualQuantity += $actualQuantity;
                                                                        $totalPlannedCost += $item->planned_cost;
                                                                        $totalActualCost += $actualCost;
                                                                        $totalVarianceCost += $costVariance;
                                                                    @endphp
                                                                    <tr>
                                                                        <td>{{ $item->id }}</td>
                                                                        <td>{{ $item->activity->name }}</td>
                                                                        <td>{{ $item->planned_quantity }}</td>
                                                                        <td>
                                                                            <input type="number" class="form-control" value="{{ $actualQuantity }}" id="actualQuantity_{{ $item->id }}" readonly>
                                                                        </td>
                                                                        <td class="text-warning">{{ $quantityVariance }}</td>
                                                                        <td>{{ number_format($item->planned_cost, 2) }}</td>
                                                                        <td>{{ $actualCost !== 0 ? number_format($actualCost, 2) : 'N/A' }}</td>
                                                                        <td class="text-warning">{{ number_format($costVariance, 2) }}</td>
                                                                        <td>{{ $item->status ?? 'Not specified' }}</td>
                                                                        <td>{{ $item->remark ?? 'None' }}</td>
                                                                        <td>
                                                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#addEditModal" data-action="edit" data-resource="{{ $resource }}" data-id="{{ $item->id }}" data-name="{{ $item->activity->name }}" data-planned-quantity="{{ $item->planned_quantity }}" data-actual-quantity="{{ $actualQuantity }}" data-planned-cost="{{ $item->planned_cost }}" data-actual-cost="{{ $actualCost }}" data-status="{{ $item->status }}" data-remark="{{ $item->remark }}">Edit</button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <td colspan="2"><strong>Total</strong></td>
                                                                    <td><strong>{{ $totalPlannedQuantity }}</strong></td>
                                                                    <td><strong>{{ $totalActualQuantity }}</strong></td>
                                                                    <td><strong>{{ $totalPlannedQuantity - $totalActualQuantity }}</strong></td>
                                                                    <td><strong>{{ number_format($totalPlannedCost, 2) }}</strong></td>
                                                                    <td><strong>{{ number_format($totalActualCost, 2) }}</strong></td>
                                                                    <td><strong>{{ number_format($totalVarianceCost, 2) }}</strong></td>
                                                                    <td colspan="3"></td>
                                                                </tr>
                                                            </tfoot>
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
                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        <script>
                            // Calculate variance
                            const plannedCosts = [
                                {{ $totalPlannedBudget['week1'] ?? 0 }},
                                {{ $totalPlannedBudget['week2'] ?? 0 }},
                                {{ $totalPlannedBudget['week3'] ?? 0 }},
                                {{ $totalPlannedBudget['week4'] ?? 0 }}
                            ];
                            
                            const actualCosts = [
                                {{ $totalActualBudget['week1'] ?? 0 }},
                                {{ $totalActualBudget['week2'] ?? 0 }},
                                {{ $totalActualBudget['week3'] ?? 0 }},
                                {{ $totalActualBudget['week4'] ?? 0 }}
                            ];

                            const variances = actualCosts.map((actual, index) => actual - plannedCosts[index]);

                            const ctxOverall = document.getElementById('overallCostChart').getContext('2d');
                            const overallCostChart = new Chart(ctxOverall, {
                                type: 'bar',
                                data: {
                                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                                    datasets: [{
                                        label: 'Planned Cost',
                                        data: plannedCosts,
                                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                        borderColor: 'rgba(75, 192, 192, 1)',
                                        borderWidth: 1
                                    }, {
                                        label: 'Actual Cost',
                                        data: actualCosts,
                                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                        borderColor: 'rgba(153, 102, 255, 1)',
                                        borderWidth: 1
                                    }, {
                                        label: 'Variance',
                                        data: variances,
                                        backgroundColor: 'rgba(255, 99, 132, 0.2)', // Red color
                                        borderColor: 'rgba(255, 99, 132, 1)', // Darker red
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            title: {
                                                display: true,
                                                text: 'Cost (Birr)'
                                            }
                                        },
                                        x: {
                                            title: {
                                                display: true,
                                                text: 'Weeks'
                                            }
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
                <form class="modal-content form-submit-event" action="{{ url('/budget/update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="itemId">
                    <input type="text" class="form-control" id="resourceType" name="resourceType" readonly>
                    <div class="mb-3">
                        <label for="activityName" class="form-label">Activity Name</label>
                        <input type="text" class="form-control" id="activityName" name="activityName" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="plannedQuantity" class="form-label">Planned Quantity</label>
                        <input type="number" class="form-control" id="plannedQuantity" name="plannedQuantity" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="actualQuantity" class="form-label">Actual Quantity</label>
                        <input type="number" class="form-control" id="actualQuantity" name="actualQuantity" required>
                    </div>
                    <div class="mb-3">
                        <label for="plannedCost" class="form-label">Planned Cost</label>
                        <input type="number" class="form-control" id="plannedCost" name="plannedCost" readonly>
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

<!-- Add/Edit Modal -->
<div class="modal fade" id="showUtilizationFactorModal" tabindex="-1" aria-labelledby="showModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showModalLabel">Utilization Factor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Laborer Name</th>
                <th>Activity Completed</th>
                <th>Utilization Factor Score</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>2024-10-01</td>
                <td>001</td>
                <td>2</td>
                <td>0.2</td>
            </tr>
            <tr>
                <td>2024-10-01</td>
                <td>002</td>
                <td>1</td>
                <td>0.1</td>
            </tr>
            <tr>
                <td>2024-10-01</td>
                <td>003</td>
                <td>0</td>
                <td>0</td>
            </tr>
            <tr>
                <td>2024-10-02</td>
                <td>001</td>
                <td>1</td>
                <td>0.1</td>
            </tr>
            <tr>
                <td>2024-10-02</td>
                <td>002</td>
                <td>2</td>
                <td>0.2</td>
            </tr>
            <tr>
                <td>2024-10-02</td>
                <td>003</td>
                <td>0</td>
                <td>0</td>
            </tr>
        </tbody>
    </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="forecastingModal" tabindex="-1" aria-labelledby="forecastingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="forecastingModalLabel">Forecasting in Progress</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Your forecasting task is currently being processed. Please wait...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap5.min.js"></script>
<script>
    function showForecastingPopup() {
        alert('Your forecasting task is currently being processed. Please wait...');
    }
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.form-submit-event').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Prepare the form data
        const formData = new FormData(this);

        // Send the data using AJAX
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Ensure CSRF token is included
            }
        })
        .then(response => {
            return response.json().then(data => {
                if (response.ok) {
                    // Set flash message in the session and redirect
                    window.location.href = '{{ url('/budgets/overview') }}'; // Redirect to your desired route
                } else {
                    // Handle validation errors or other responses
                    alert(data.message || 'An error occurred.');
                }
            });
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('An error occurred while saving.');
        });
    });
});
</script>
<script>
    // Handle modal open for edit
    const addEditModal = document.getElementById('addEditModal');
    addEditModal.addEventListener('show.bs.modal', (event) => {
        const button = event.relatedTarget; // Button that triggered the modal
        // Set the modal fields
        document.getElementById('itemId').value = button.getAttribute('data-id');
        document.getElementById('activityName').value = button.getAttribute('data-name');
        document.getElementById('plannedQuantity').value = button.getAttribute('data-planned-quantity');
        document.getElementById('actualQuantity').value = button.getAttribute('data-actual-quantity');
        document.getElementById('plannedCost').value = button.getAttribute('data-planned-cost');
        document.getElementById('actualCost').value = button.getAttribute('data-actual-cost');
          // Set resource type
        const resource = button.getAttribute('data-resource');
        document.getElementById('resourceType').value = resource; // Set the resource type
    });
    // Initialize DataTables
    $(document).ready(function() {
        $('table').DataTable({
            responsive: true,
            paging: true,
            searching: true,
            ordering: true,
            lengthMenu: [5, 10, 25, 50],
            language: {
                search: "Filter records:",
                lengthMenu: "Display _MENU_ records per page"
            },
            // Custom styles for the table
            initComplete: function() {
                this.api().columns().every(function() {
                    var column = this;
                    var select = $('<select class="form-select"><option value=""></option></select>')
                        .appendTo($(column.footer()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search(val ? '^'+val+'$' : '', true, false).draw();
                        });

                    column.data().unique().sort().each(function(d, j) {
                        select.append('<option value="'+d+'">'+d+'</option>')
                    });
                });
            }
        });
    });
</script>
@endsection