@extends('layout')

@section('title', 'Selected Activities')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4 text-center">Selected Activities</h2>

    <!-- Define tab data -->
    @php
        $tabData = [
            'project-progress' => [
                'header' => ['No', 'Checked Date', 'Check Point Descriptions', 'Unit', 'Value', 'Rate', 'Progress', 'Comment', 'Approved by', 'Attach', 'Status'],
                'data' => [
                    ['No' => 1, 'Checked Date' => '2024-01-01', 'Check Point Descriptions' => 'Initial Planning', 'Unit' => 'Hours', 'Value' => 20, 'Rate' => '$100', 'Progress' => '45%', 'Comment' => 'Completed on time', 'Approved by' => 'Alice Smith', 'Attach' => 'planning.pdf', 'Status' => 'Approved'],
                    // More data...
                ]
            ],
            'work-progress' => [
                'header' => ['No', 'WBS', 'Activity Name', 'Status'],
                'data' => [
                    ['No' => 1, 'WBS' => '003', 'Activity Name' => 'Development', 'Status' => 'Completed'],
                    // More data...
                ]
            ],
            // Additional tabs...
        ];
    @endphp

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#project-progress" aria-controls="project-progress" aria-selected="true">
                <i class="menu-icon tf-icons bx bx-clipboard text-primary"></i> Project Progress
            </button>
        </li>
        <li class="nav-item">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#work-progress" aria-controls="work-progress" aria-selected="false">
                <i class="menu-icon tf-icons bx bx-briefcase text-success"></i> Work Progress
            </button>
        </li>
        <li class="nav-item">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#quality-control" aria-controls="quality-control" aria-selected="false">
                <i class="menu-icon tf-icons bx bx-check-circle text-warning"></i> Quality Control
            </button>
        </li>
        <li class="nav-item">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#labor-at-work" aria-controls="labor-at-work" aria-selected="false">
                <i class="menu-icon tf-icons bx bx-user text-danger"></i> Labor at Work
            </button>
        </li>
        <li class="nav-item">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#shifting-due-date" aria-controls="shifting-due-date" aria-selected="false">
                <i class="menu-icon tf-icons bx bx-arrow-right text-info"></i> Shifting Due Date
            </button>
        </li>
        <li class="nav-item">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#due-date-tasks" aria-controls="due-date-tasks" aria-selected="false">
                <i class="menu-icon tf-icons bx bx-calendar text-secondary"></i> Due Date Tasks
            </button>
        </li>
        <li class="nav-item">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#lead-time-lag-time" aria-controls="lead-time-lag-time" aria-selected="false">
                <i class="menu-icon tf-icons bx bx-timer text-dark"></i> Lead/Lag Time
            </button>
        </li>
        <li class="nav-item">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#payment-approval" aria-controls="payment-approval" aria-selected="false">
                <i class="menu-icon tf-icons bx bx-money text-success"></i> Payment Approval
            </button>
        </li>
        <li class="nav-item">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#completion" aria-controls="completion" aria-selected="false">
                <i class="menu-icon tf-icons bx bx-check-double text-primary"></i> Completion
            </button>
        </li>
        <li class="nav-item">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#machinery-vehicle-check" aria-controls="machinery-vehicle-check" aria-selected="false">
                <i class="menu-icon tf-icons bx bx-car text-warning"></i> Machinery Vehicle Check
            </button>
        </li>
        <li class="nav-item">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#material-check" aria-controls="material-check" aria-selected="false">
                <i class="menu-icon tf-icons bx bx-package text-danger"></i> Material Check
            </button>
        </li>
        <li class="nav-item">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#risk-safety-check" aria-controls="risk-safety-check" aria-selected="false">
                <i class="menu-icon tf-icons bx bx-alert-circle text-info"></i> Risk/Safety Check
            </button>
        </li>
    </ul>

    <!-- Tab content -->
    <div class="tab-content mt-3" id="activityTabsContent">
        @foreach (array_keys($tabData) as $tab)
        <div class="tab-pane fade @if($loop->first) show active @endif" id="{{ $tab }}" role="tabpanel" aria-labelledby="{{ $tab }}-tab">
            <table class="table table-striped table-bordered mt-3">
                <thead class="table-header">
                    <tr>
                        @foreach ($tabData[$tab]['header'] as $header)
                            <th>{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tabData[$tab]['data'] as $key => $activity)
                        <tr>
                            @foreach ($activity as $value)
                                <td>{{ $value }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endforeach
    </div>

    <!-- Modal -->
    <div class="modal fade" id="tabModal" tabindex="-1" aria-labelledby="tabModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tabModalLabel">Tab Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 id="modalTabHeader"></h5>
                    <table class="table table-responsive" id="modalTabData">
                        <thead>
                            <tr id="modalTableHeader"></tr>
                        </thead>
                        <tbody id="modalTableBody"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.0.9/css/boxicons.min.css" rel="stylesheet" />

    <style>
        .table-header {
            background-color: #f8f9fa; /* Light background for header */
            color: black !important;
        }

        .modal-header {
            background-color: #1B8596; /* Customize as needed */
            color: white;
        }

        /* Additional styles for modal */
        .modal-lg {
            max-width: 90%;
            margin: auto;
        }

        @media (max-width: 768px) {
            .modal-lg {
                max-width: 100%;
            }
        }
    </style>

    <script>
        $(document).ready(function() {
            $('.tab-clickable').click(function() {
                var tabName = $(this).text();
                var tabData = @json($tabData);
                var modalHeader = 'Details for ' + tabName;

                $('#modalTabHeader').text(modalHeader);
                $('#modalTableBody').empty(); // Clear previous data
                $('#modalTableHeader').empty(); // Clear previous header

                // Populate the modal based on the clicked tab
                if (tabData[tabName]) {
                    // Set table headers
                    tabData[tabName].header.forEach(function(header) {
                        $('#modalTableHeader').append(`<th>${header}</th>`);
                    });

                    // Populate data rows
                    tabData[tabName].data.forEach(function(item) {
                        var row = '<tr>';
                        for (var key in item) {
                            row += `<td>${item[key]}</td>`;
                        }
                        row += '</tr>';
                        $('#modalTableBody').append(row);
                    });
                } else {
                    $('#modalTableBody').append('<tr><td colspan="10">No data available</td></tr>');
                }

                $('#tabModal').modal('show');
            });
        });
    </script>
</div>
@endsection