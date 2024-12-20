@extends('layout')

@section('title', 'Selected Activities')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4 text-center">Selected Activities</h2>

    <!-- Define tab colors -->
    @php
        $tabColors = [
            'project-progress' => '#1B8596',
            'work-progress' => '#28A745',
            'quality-control' => '#FFC107',
            'labor-at-work' => '#17A2B8',
            'shifting-due-date' => '#DC3545',
            'due-date-tasks' => '#6F42C1',
            'lead-time-lag-time' => '#FFA500',
            'payment-approval' => '#007BFF',
            'completion' => '#20C997',
            'machinery-vehicle-check' => '#E83E8C',
            'material-check' => '#343A40',
            'risk-safety-check' => '#FF5733',
        ];

        $tabData = [
            'project-progress' => [
                'header' => ['No', 'Checked Date', 'Check Point Descriptions', 'Unit','Value','Rate','Progress','Comment','Approved by','Attch','Status'
 
                ],
                'data' => [
                    ['No' => 1, 'Checked Date' => '2024-01-01', 'Check Point Descriptions' => 'Initial Planning', 'Unit' => 'Hours', 'Value' => 20, 'Rate' => '$100', 'Progress' => '45%', 'Comment' => 'Completed on time', 'Approved by' => 'Alice Smith', 'Attch' => 'planning.pdf', 'Status' => 'Approved'],
                    ['No' => 1, 'Checked Date' => '2024-01-01', 'Check Point Descriptions' => 'Initial Planning', 'Unit' => 'Hours', 'Value' => 20, 'Rate' => '$100', 'Progress' => '35%', 'Comment' => 'Completed on time', 'Approved by' => 'Alice Smith', 'Attch' => 'planning.pdf', 'Status' => 'Approved'],
                    ['No' => 1, 'Checked Date' => '2024-01-01', 'Check Point Descriptions' => 'Initial Planning', 'Unit' => 'Hours', 'Value' => 20, 'Rate' => '$100', 'Progress' => '15%', 'Comment' => 'Completed on time', 'Approved by' => 'Alice Smith', 'Attch' => 'planning.pdf', 'Status' => 'Approved'],
                ]
            ],
            'work-progress' => [
                'header' => ['No', 'WBS', 'Activity Name', 'Status'],
                'data' => [
                    ['No' => 1, 'WBS' => '003', 'Activity Name' => 'Development', 'Status' => 'Completed'],
                ]
            ],
            'progress' => [
                'header' => ['Checked Date', 'Check Point Description', 'Unit', 'Value', 'Rate', 'Progress', 'Comment', 'Approved by', 'Attach', 'Status'],
                'data' => [
                    ['Checked Date' => '2024-01-01', 'Check Point Description' => 'Initial Review', 'Unit' => 'Hours', 'Value' => 10, 'Rate' => 1, 'Progress' => '20%', 'Comment' => 'On track', 'Approved by' => 'Alice', 'Attach' => 'Doc.pdf', 'Status' => 'Approved'],
                    ['Checked Date' => '2024-01-10', 'Check Point Description' => 'Mid Review', 'Unit' => 'Hours', 'Value' => 15, 'Rate' => 1, 'Progress' => '50%', 'Comment' => 'Slight delay', 'Approved by' => 'Bob', 'Attach' => 'Report.pdf', 'Status' => 'Pending'],
                ]
            ],
            'quality-control' => [
    'header' => ['No', 'Checked Date', 'Check Point Descriptions', 'Unit', 'Mark', 'Rate', 'Progress', 'Comment', 'Checked by', 'Attch', 'No of Checked', 'Status'],
    'data' => [
        ['No' => 1, 'Checked Date' => '2024-01-15', 'Check Point Descriptions' => 'Initial Inspection', 'Unit' => 'Unit A', 'Mark' => 'Pass', 'Rate' => '95%', 'Progress' => 'Completed', 'Comment' => 'All checks passed', 'Checked by' => 'Bisrat', 'Attch' => 'file1.pdf', 'No of Checked' => 5, 'Status' => 'Approved'],
        ['No' => 2, 'Checked Date' => '2024-02-10', 'Check Point Descriptions' => 'Material Quality', 'Unit' => 'Unit B', 'Mark' => 'Fail', 'Rate' => '70%', 'Progress' => 'Pending', 'Comment' => 'Material needs re-evaluation', 'Checked by' => 'Alemayehu', 'Attch' => 'file2.pdf', 'No of Checked' => 4, 'Status' => 'Rejected'],
        ['No' => 3, 'Checked Date' => '2024-03-05', 'Check Point Descriptions' => 'Safety Compliance', 'Unit' => 'Unit C', 'Mark' => 'Pass', 'Rate' => '50%', 'Progress' => 'Completed', 'Comment' => 'Fully compliant', 'Checked by' => 'Bisrat', 'Attch' => 'file3.pdf', 'No of Checked' => 3, 'Status' => 'Approved'],
        ['No' => 4, 'Checked Date' => '2024-03-20', 'Check Point Descriptions' => 'Final Assembly', 'Unit' => 'Unit D', 'Mark' => 'Warning', 'Rate' => '40%', 'Progress' => 'In Progress', 'Comment' => 'Minor issues found', 'Checked by' => 'Bisrat', 'Attch' => 'file4.pdf', 'No of Checked' => 2, 'Status' => 'Under Review'],
        ['No' => 5, 'Checked Date' => '2024-04-12', 'Check Point Descriptions' => 'Final Review', 'Unit' => 'Unit E', 'Mark' => 'Pass', 'Rate' => '15%', 'Progress' => 'Completed', 'Comment' => 'Ready for delivery', 'Checked by' => 'Yosisabel', 'Attch' => 'file5.pdf', 'No of Checked' => 1, 'Status' => 'Approved'],
    ]
],
        ];
    @endphp

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" id="activityTabs" role="tablist">
        @foreach ($tabColors as $tab => $color)
        <li class="nav-item" role="presentation">
            <a class="nav-link @if($loop->first) active @endif text-light" id="{{ $tab }}-tab" data-bs-toggle="tab" href="#{{ $tab }}" role="tab" aria-controls="{{ $tab }}" aria-selected="@if($loop->first) true @else false @endif" style="background-color: {{ $color }};">{{ ucfirst(str_replace('-', ' ', $tab)) }}</a>
        </li>
        @endforeach
    </ul>

    <!-- Tab content -->
    <div class="tab-content mt-3" id="activityTabsContent">
        @foreach (array_keys($tabColors) as $tab)
        <div class="tab-pane fade @if($loop->first) show active @endif" id="{{ $tab }}" role="tabpanel" aria-labelledby="{{ $tab }}-tab">
            <table class="table table-striped table-bordered mt-3">
                <thead class="table-header">
                    <tr>
                        <th>No</th>
                        <th>WBS</th>
                        <th>Activity Name</th>
                        <th>Status</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th style="color: {{ $tabColors[$tab] }}" class="tab-clickable">{{ $tab }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($selectedActivity as $key => $activity)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $activity['wbs'] }}</td>
                            <td>{{ $activity['activity_name'] }}</td>
                            <td><span class="badge bg-label-danger">{{ $activity['status'] }}</span></td>
                            <td>{{ $activity['start_date'] }}</td>
                            <td>{{ $activity['end_date'] }}</td>
                            <td style="color: {{ $tabColors[$tab] }}" class="tab-clickable">{{ $tab }}</td>
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
            background-color: #1B8596; /* Customize as needed */
            color: white !important;
        }

        .btn-link {
            color: #1B8596;
            font-size: 1rem;
        }

        .btn-link:hover {
            color: #0a6b78;
        }

        .modal-header {
            background-color: #1B8596;
            color: white;
        }

        .tab-clickable {
            cursor: pointer;
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