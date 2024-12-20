@extends('layout')

@section('title')
    {{ get_label('Resource Allocation', 'Resource Allocation') }}
@endsection

@section('content')
    <div class="container-fluid">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb breadcrumb-style1">
                <li class="breadcrumb-item">
                    <a href="{{ url('/home') }}">{{ get_label('home', 'Home') }}</a>
                </li>
                <li class="breadcrumb-item active">
                    {{ get_label('Resource Allocation', 'Resource Allocation') }}
                </li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <a href="{{ route('allMaterialAlloation.activity', $activity->task->id) }}" class="text-success font-weight-bold" style="text-decoration: none;">
                    View Allocate Resource
                </a>
            </div>
            <div class="card-body">
                <div class="row project-task-activity-hierarchy justify-content-end">
                    <div class="col-md-4 text-end">
                        <div class="project">
                            <div class="project-title">{{ $activity->task->project->title }}</div>
                            <div class="project-dates">
                                <span class="start-date">{{ $activity->task->project->start_date }}</span>
                                <span class="arrow">&#8594;</span>
                                <span class="end-date">{{ $activity->task->project->end_date }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="task">
                            <div class="task-title">{{ $activity->task->title }}</div>
                            <div class="task-dates">
                                <span class="start-date">{{ $activity->task->start_date }}</span>
                                <span class="arrow">&#8594;</span>
                                <span class="end-date">{{ $activity->task->end_date }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="activity">
                            <div class="activity-title text-primary">{{ $activity->name }}</div>
                            <div class="activity-dates">
                                <span class="start-date">{{ $activity->start_date }}</span>
                                <span class="arrow">&#8594;</span>
                                <span class="end-date">{{ $activity->end_date }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if(isset($totalRecords) && $totalRecords > 0)
                <h5 class="mt-4">Materials for this Activity</h5>

                <div class="table-responsive text-nowrap">
                    <form action="{{ route('materialcosts.materialSelection') }}" method="POST" id="materialForm">
                        @csrf
                        <input type="hidden" name="selected_subtask_id" id="selected_subtask_id" value="{{ $activity->id }}">

                        <div class="mb-3 d-flex justify-content-end align-items-center">
                            <div class="dropdown me-2">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="columnDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-columns"></i> Columns
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="columnDropdown">
                                    <li><div class="dropdown-item"><input type="checkbox" class="column-toggle" checked data-column="0"> No</div></li>
                                    <li><div class="dropdown-item"><input type="checkbox" class="column-toggle" checked data-column="1"> Material</div></li>
                                    <li><div class="dropdown-item"><input type="checkbox" class="column-toggle" checked data-column="2"> Unit</div></li>
                                    <li><div class="dropdown-item"><input type="checkbox" class="column-toggle" checked data-column="3"> Req_Qty</div></li>
                                    <li><div class="dropdown-item"><input type="checkbox" class="column-toggle" checked data-column="4"> Res_Qty</div></li>
                                    <li><div class="dropdown-item"><input type="checkbox" class="column-toggle" checked data-column="5"> Rate with VAT</div></li>
                                    <li><div class="dropdown-item"><input type="checkbox" class="column-toggle" checked data-column="6"> Status</div></li>
                                    <li><div class="dropdown-item"><input type="checkbox" class="column-toggle" checked data-column="7"> Approved By</div></li>
                                    <li><div class="dropdown-item"><input type="checkbox" class="column-toggle" checked data-column="8"> Approved Date</div></li>
                                    <li><div class="dropdown-item"><input type="checkbox" class="column-toggle" checked data-column="9"> Remark</div></li>
                                    <li><div class="dropdown-item"><input type="checkbox" class="column-toggle" checked data-column="10"> Select</div></li>
                                </ul>
                            </div>

                            <div class="me-2">
                                <label for="dateRange" class="form-label">Select Date Range:</label>
                                <input type="text" class="form-control" id="dateRange" placeholder="YYYY-MM-DD to YYYY-MM-DD">
                            </div>
                        </div>

                        <table class="table table-bordered" id="materialsTable">
                            <thead class="table-header">
                                <tr>
                                    <th>No</th>
                                    <th>Material</th>
                                    <th>Unit</th>
                                    <th>Req_Qty</th>
                                    <th>Res_Qty</th>
                                    <th>Rate with VAT</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Approved By</th>
                                    <th>Approved Date</th>
                                    <th>Remark</th>
                                    <th>Select</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($materialsInventory as $key => $material)
                                    <tr>
                                        <input type="hidden" name="material_request_id" id="material_request_id" value="{{ $material['material_request_id'] }}">
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $material['item'] }}</td>
                                        <td>{{ $material['unit'] }}</td>
                                        <td>{{ $material['requested_quantity'] }}</td>
                                        <td>{{ $material['approved_quantity'] }}</td>
                                        <td>{{ $material['rate_with_vat'] }}</td>
                                        <td>{{  $material['approved_quantity'] * $material['rate_with_vat'] }}</td>
                                        <td>
                                            <span class="badge {{ $material['status'] === 'allocated' ? 'bg-success' : 'bg-warning' }} text-dark rounded-pill px-3 py-2">
                                                {{ $material['status'] }}
                                            </span>
                                        </td>
                                        <td>{{ $material['approved_by'] }}</td>
                                        <td>{{ $material['date'] }}</td>
                                        <td>{{ $material['remark'] }}</td>
                                        <td>
                                            <div class="form-check">
                                                @if($material['status'] !== 'allocated')
                                                    <input class="form-check-input" 
                                                           type="checkbox" 
                                                           name="selected_materials[]" 
                                                           value="{{ json_encode($material) }}">
                                                @else
                                                    {{ $material['status'] }}
                                                @endif
                                                <label class="form-check-label"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="6" class="text-end">Total</th>
                                    <th class="table-total">
                                        {{ $materialsInventory->sum(function($material) {
                                            return $material['approved_quantity'] * $material['rate_with_vat'];
                                        }) }}
                                    </th>
                                    <th colspan="5"></th>
                                </tr>
                            </tfoot>
                        </table>

                        <div class="d-flex justify-content-start mt-3">
                            <div class="dt-buttons">
                                <button type="submit" class="btn btn-primary" id="allocateButton">Allocate</button>
                            </div>
                        </div>
                    </form>
                </div>
                @else
                <div class="text-danger">
                    No material is found for this activity. Please ask from inventory.
                    <div class="text-center">
                        <a href="{{ route('request.activity', $activity->id) }}" class="btn btn-primary btn-sm">
                            <i class="bx bx-detail"></i> Request
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const allocateButton = document.getElementById('allocateButton');
            allocateButton.disabled = false;

            // Initialize DataTable
            const table = $('#materialsTable').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
                lengthMenu: [10, 50, 250, 500],
                pageLength: 10,
                dom: `<"top"lfB>rt<"bottom"ip><"clear">`,
                language: {
                    search: "Filter records:",
                    lengthMenu: "Display _MENU_ records per page"
                }
            });

            // Column visibility toggle functionality
            $('.column-toggle').change(function() {
                var column = $(this).data('column');
                var columnVisibility = $(this).is(':checked');
                table.column(column).visible(columnVisibility);
            });

            // Date range picker initialization
            $('#dateRange').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            // Apply filter on date range selection
            $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
                const startDate = picker.startDate.format('YYYY-MM-DD');
                const endDate = picker.endDate.format('YYYY-MM-DD');

                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        const date = new Date(data[8]);
                        return date >= new Date(startDate) && date <= new Date(endDate);
                    }
                );

                table.draw();
            });

            // Reset filter when date range is cleared
            $('#dateRange').on('cancel.daterangepicker', function() {
                $.fn.dataTable.ext.search.pop();
                table.draw();
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.1/css/buttons.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <style>
        .table {
            border: 2px solid #007bff !important; /* Changed border color */
            border-radius: 5px !important;
            overflow: hidden !important;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
        }

        .table-header {
            background-color: #1B8596 !important; /* Changed header background color */
            color: white !important;
            text-align: center !important;
        }

        .table th {
            background-color: #1B8596 !important; 
            padding: 10px !important;
            font-weight: bold !important;
            color: white !important;
        }

        .table td {
            vertical-align: middle !important;
            padding: 8px !important;
            background-color: #f1f1f1 !important; /* Changed row background color */
        }

        .table tbody tr:hover {
            background-color: #e0f7fa !important; /* Light cyan on hover */
        }

        .table-bordered {
            border: 1px solid #dee2e6 !important;
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid #dee2e6 !important;
        }

        .dt-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .dataTables_filter {
            display: flex;
            align-items: center;
            margin-left: auto;
        }

        .dataTables_filter input {
            margin-left: 0.5rem;
        }
        
    
     
    </style>
@endsection