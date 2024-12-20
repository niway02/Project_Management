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
                <a href="#" class="text-success font-weight-bold" style="text-decoration: none;">
                    View Allocate Resource
                </a>
            </div>
            <div class="card-body">
                <h5 class="mt-4">Materials for this Activity (Total: {{ count($materialRequestsWithFinanceStatus) }})</h5>

                <div class="mb-3 d-flex justify-content-center">
                    <div class="col-md-6">
                        <input type="text" id="dateRange" class="form-control" placeholder="Select Date Range" />
                    </div>
                </div>

                <div class="table-responsive text-nowrap">
                    <form id="materialRequestForm">
                        @csrf
                        <table id="materialsTable" class="table table-striped table-bordered">
                            <thead style="background-color: #1B8596; color: white;">
                                <tr>
                                    <th>No</th>
                                    <th>Material</th>  
                                    <th>Unit</th>
                                    <th>Rate with VAT</th>
                                    <th>Available QTY</th>
                                    <th>Requested QTY</th>
                                    <th>Total</th>
                                    <th>Requested By</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($materialRequestsWithFinanceStatus as $index => $materialRequest)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $materialRequest['material']['item'] ?? 'N/A' }}</td>
                                        <td>{{ $materialRequest['material']['unit_measure'] ?? 'N/A' }}</td>
                                        <td>{{ $materialRequest['material']['rate_with_vat'] ?? 'N/A' }} ETB</td>
                                        <td>
    @if(isset($materialRequest['material']['warehouse']['materials_inventory']))
        @foreach($materialRequest['material']['warehouse']['materials_inventory'] as $inventory)
            <div>{{ $inventory['quantity'] }}</div>
        @endforeach
    @else
        N/A
    @endif
    <input type="hidden" class="available-quantity" value="{{ $materialRequest['material']['warehouse']['materials_inventory'][0]['quantity'] ?? 0 }}">
</td>
                                        <td>{{ $materialRequest['item_quantity'] ?? 'N/A' }}</td>
                                        <td>
                                            {{ ($materialRequest['material']['rate_with_vat'] * $materialRequest['item_quantity']) ?? 0 }} ETB
                                        </td>
                                        <td>
                                            <span class="text-muted">samisams</span>
                                        </td>
                                        <td>
                                            <span class="badge 
                                            {{ $materialRequest['resource_request']['finance_status'] === 'pending' ? 'bg-secondary' : 
                                               ($materialRequest['resource_request']['finance_status'] === 'rejected' ? 'bg-danger' : 'bg-success') }} 
                                            text-white rounded-pill px-3 py-2">
                                            {{ $materialRequest['resource_request']['finance_status'] }}
                                            </span>
                                        </td>
                                        <input type="hidden" name="resource_request_id[]" value="{{ $materialRequest['resource_request']['id'] }}">
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="background-color: #e9ecef;">
                                <tr>
                                    <th colspan="6" class="text-right">Total:</th>
                                    <th>
                                        {{ number_format(collect($materialRequestsWithFinanceStatus)->sum(function($item) {
                                            return $item['material']['rate_with_vat'] * $item['item_quantity'];
                                        }), 2) }} ETB
                                    </th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
                        </table>

                        <div class="col-12 d-flex justify-content-center mt-3">
                            <button type="button" class="btn btn-outline-primary" id="approveBtn" 
                                {{ $materialRequestsWithFinanceStatus->contains(fn($request) => $request['resource_request']['finance_status'] === 'approved') ? 'disabled' : '' }}>
                                Approve
                            </button>
                            <button type="button" class="btn btn-outline-danger" id="rejectBtn"
                                {{ $materialRequestsWithFinanceStatus->contains(fn($request) => $request['resource_request']['finance_status'] === 'rejected') ? 'disabled' : '' }}>
                                Reject
                            </button>
                        </div>
                    </form>
                </div>

                <div id="responseMessage" class="mt-3"></div>
            </div>
        </div>
    </div>

    <!-- Include scripts and styles -->
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
    <script src="https://cdn.datatables.net/buttons/2.3.1/js/buttons.copy.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <style>
        .table {
            border: 2px solid #28a745 !important;
            border-radius: 5px !important;
            overflow: hidden !important;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
        }

        .table th, .table td {
            color: black !important; /* Set text color to black */
            vertical-align: middle !important;
            padding: 8px !important;
            background-color: #f8f9fa !important;
        }

        .table tfoot th {
            font-weight: bold;
            background-color: #e9ecef;
        }

        .table tbody tr:hover {
            background-color: #e2f0e7 !important;
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

    <script>
        // Initialize DataTable
        const table = $('#materialsTable').DataTable({
            responsive: true,
            paging: true,
            searching: true,
            ordering: true,
            lengthMenu: [10, 50, 250, 500],
            pageLength: 10,
            dom: `<"top"lfB>rt<"bottom"ip><"clear">`,
            buttons: [
                {
                    extend: 'copyHtml5',
                    text: 'Copy',
                    className: 'btn btn-secondary'
                },
                {
                    extend: 'pdfHtml5',
                    text: 'Export PDF',
                    title: 'Resource Allocation',
                    className: 'btn btn-success'
                },
                {
                    extend: 'csvHtml5',
                    text: 'Export CSV',
                    title: 'Resource Allocation',
                    className: 'btn btn-info'
                },
                {
                    extend: 'print',
                    text: 'Print',
                    className: 'btn btn-primary'
                }
            ],
            language: {
                search: "Filter records:",
                lengthMenu: "Display _MENU_ records per page"
            }
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
                    const date = new Date(data[8]); // Assuming the date is in the 9th column (index 8)
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

        // Handle approval and rejection
        $('#approveBtn').click(function() {
            submitStatus('approved');
        });

        $('#rejectBtn').click(function() {
            submitStatus('rejected');
        });

        function submitStatus(status) {
            const resourceRequestIds = [];
            $('input[name="resource_request_id[]"]').each(function() {
                resourceRequestIds.push($(this).val());
            });
            $.ajax({
                url: '{{ route("request.store-finace-aprove") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: status,
                    resource_request_id: resourceRequestIds
                },
                success: function(response) {
                    // Display success message from controller
                    $('#responseMessage').html(`<div class="alert alert-success">${response.message}</div>`);
                    location.reload(); // Reload to see updates
                },
                error: function(xhr) {
                    // Display error message from controller
                    const errorMessage = xhr.responseJSON.message || 'An error occurred';
                    $('#responseMessage').html(`<div class="alert alert-danger">${errorMessage}</div>`);
                }
            });
        }
    </script>
@endsection