@extends('layout')

@section('content')
<div class="container my-5">
    <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-style1">
                <li class="breadcrumb-item">
                    <a href="{{ url('/home') }}">{{ get_label('home', 'Home') }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ get_label('workprogress', 'Work Progress') }}</li>
            </ol>
        </nav>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Today's Progress</h5>
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h6>Completed</h6>
                            <span class="badge bg-success">{{ $completed }}</span>
                        </div>
                        <div>
                            <h6>Not Started</h6>
                            <span class="badge bg-danger">{{ $notStarted }}</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h6>Blocked</h6>
                            <span class="badge bg-warning text-dark">{{ $blocked }}</span>
                        </div>
                        <div>
                            <h6>In Progress</h6>
                            <span class="badge bg-warning">{{ $inProgress }}</span>
                        </div>
                    </div>
                    <div class="text-center">
                        <h6>Total</h6>
                        <span class="badge bg-info">{{ $total }}</span>
                    </div>
                </div>
            </div>

            <div class="card shadow mt-4">
                <div class="card-body text-center">
                    <h5 class="card-title">Top Employee of the Week</h5>
                    <h6 class="text-dark">Melamu Mamo</h6>
                    <p class="text-muted">Top Performer</p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Employee Progress</h5>
                    <table class="table table-striped" id="tasksTable">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>Member</th>
                                <th>Activity</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Issue</th>
                                <th>KPI</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activities as $activity)
                            <tr>
                                <td>{{ $activity['id'] }}</td>
                                <td>{{ $activity['assignedTo'] }}</td>
                                <td>{{ $activity['activity_name'] }}</td>
                                <td>
                                    <span class='badge bg-label-{{ trim($activity['status_color']) }}'>{{ trim($activity['status']) }}</span>
                                </td>
                                <td>
                                    <span class='badge bg-label-{{ trim($activity['priority_color']) }}'>{{ $activity['priority'] }}</span>
                                </td>
                                <td>{{ $activity['issue'] }}</td>
                                <td>{{ $activity['kpi'] }}</td>
                                <td>{{ $activity['start_date'] }}</td>
                                <td>{{ $activity['end_date'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Work Progress Graph</h5>
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('myChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Completed', 'Not Started', 'Blocked', 'In Progress'],
            datasets: [{
                label: 'Work Progress',
                data: [{{ $completed }}, {{ $notStarted }}, {{ $blocked }}, {{ $inProgress }}],
                backgroundColor: ['#71dd37', '#ff3e1d', '#ffab00', '#696cff'],
                borderColor: ['#71dd37', '#ff3e1d', '#ffab00', '#696cff'],
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

<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f7f9fc;
        color: #333;
    }

    .card {
        border: none;
        border-radius: 8px;
    }

    .table {
        background-color: white;
    }

    .table th {
        background-color: #343a40;
        color: white;
        padding: 10px;
        font-weight: bold;
    }

    .table td {
        vertical-align: middle;
        padding: 8px;
    }

    .table tbody tr:hover {
        background-color: #f1f1f1;
    }

    .badge {
        font-size: 0.9rem;
        padding: 0.5em 0.75em;
        border-radius: 0.5rem;
    }

    h1, h5, h6 {
        font-family: 'Helvetica Neue', sans-serif;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#tasksTable').DataTable({
            responsive: true,
            paging: true,
            searching: true,
            ordering: true,
            lengthMenu: [10, 50, 250, 500],
            pageLength: 10,
            language: {
                search: "Filter records:",
                lengthMenu: "Display _MENU_ records per page"
            }
        });
    });
</script>
@endsection