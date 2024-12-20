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
                <h5 class="mt-4">Materials for this Activity (Total: {{ count($materialRequests) }})</h5>

                <div class="mb-3 d-flex justify-content-center">
                    <div class="col-md-6">
                        <input type="text" id="dateRange" class="form-control" placeholder="Select Date Range" />
                    </div>
                </div>

                <div class="table-responsive text-nowrap">
                    <form action="{{ route('request.store-material-request-response') }}" method="POST" id="materialRequestForm">
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
                                    <th>Approved QTY</th>
                                    <th>Requested By</th>
                                    <th>Remark</th>
                                    <th>Status</th>
                                    <th>Select</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($materialRequests as $index => $materialRequest)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $materialRequest->material->item ?? 'N/A' }}</td>
                                        <td>{{ $materialRequest->material->unitMeasure->name ?? 'N/A' }}</td>
                                        <td>{{ $materialRequest->material->rate_with_vat ?? 'N/A' }}</td>
                                        <td>
                                            @php
                                                $inventory = $materialRequest->material->warehouse->materialsInventory
                                                    ->where('material_id', $materialRequest->material->id)
                                                    ->first();
                                            @endphp
                                            {{ $inventory->quantity ?? 'N/A' }}
                                            <input type="hidden" class="available-quantity" value="{{ $inventory->quantity ?? 0 }}">
                                        </td>
                                        <td>{{ $materialRequest->item_quantity }}</td>
                                        <td>
                                            <input type="number" name="approved_quantity[]" value="{{ old('approved_quantity.' . $index) }}" min="0" class="form-control approved-quantity" required disabled />
                                        </td>
                                        <td>
                                            <span class="text-muted">samisams</span>
                                        </td>
                                        <td>
                                            <input type="text" name="remark[]" value="{{ old('remark.' . $index) }}" class="form-control remark-input" placeholder="Enter remark" disabled />
                                            <div class="invalid-feedback">Please provide a remark if approved quantity is less than requested quantity.</div>
                                        </td> <td>
                                            <span class="badge {{ $materialRequest->status === 'Pending' ? 'bg-secondary' : 'bg-success' }} text-white rounded-pill px-3 py-2">
                                            {{$materialRequest->status}}
                                            </span>
                                        </td>
                                        <td>
                                            @if($materialRequest->status === 'Pending')
                                                <div class="form-check">
                                                    <input class="form-check-input select-material" type="checkbox" data-index="{{ $index }}" />
                                                    <label class="form-check-label"></label>
                                                </div>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <input type="hidden" name="material_request_ids[]" value="{{ $materialRequest->id }}">
                                        <input type="hidden" name="resource_request_id" value="{{ $materialRequest->resource_request_id }}">
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Continue</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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

        .table-header {
            background-color: #1B8596 !important;
            color: white !important;
            text-align: center !important;
        }

        .table th {
            padding: 10px !important;
            font-weight: bold !important;
            color: white !important;
        }

        .table td {
            vertical-align: middle !important;
            padding: 8px !important;
            background-color: #f8f9fa !important;
        }

        .table tbody tr:hover {
            background-color: #e2f0e7 !important;
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

        // Handle checkbox selection for approved quantity and remark
        document.querySelectorAll('.select-material').forEach((checkbox) => {
            checkbox.addEventListener('change', function() {
                const row = this.closest('tr');
                const availableQuantity = parseInt(row.querySelector('.available-quantity').value);
                const approvedQuantityInput = row.querySelector('.approved-quantity');
                const remarkInput = row.querySelector('.remark-input');

                if (this.checked) {
                    approvedQuantityInput.removeAttribute('disabled');
                    approvedQuantityInput.setAttribute('max', availableQuantity);
                    remarkInput.removeAttribute('disabled'); // Enable remark input
                } else {
                    approvedQuantityInput.value = '';
                    approvedQuantityInput.setAttribute('disabled', 'disabled');
                    remarkInput.value = ''; // Clear remark
                    remarkInput.setAttribute('disabled', 'disabled'); // Disable remark input
                    approvedQuantityInput.classList.remove('is-invalid');
                    remarkInput.classList.remove('is-invalid');
                }
            });
        });

        document.getElementById('materialRequestForm').addEventListener('submit', function(e) {
            let valid = true;
            document.querySelectorAll('.select-material:checked').forEach((checkbox) => {
                const row = checkbox.closest('tr');
                const availableQuantity = parseInt(row.querySelector('.available-quantity').value);
                const approvedQuantityInput = row.querySelector('.approved-quantity');
                const approvedQuantity = parseInt(approvedQuantityInput.value);
                const requestedQuantity = parseInt(row.querySelector('td:nth-child(6)').innerText);
                const remarkInput = row.querySelector('.remark-input');

                approvedQuantityInput.classList.remove('is-invalid');
                remarkInput.classList.remove('is-invalid');

                if (approvedQuantity > availableQuantity) {
                    valid = false;
                    approvedQuantityInput.classList.add('is-invalid');
                }

                if (approvedQuantity < requestedQuantity && remarkInput.value.trim() === '') {
                    valid = false;
                    remarkInput.classList.add('is-invalid');
                }
            });

            if (!valid) {
                e.preventDefault();
                document.querySelectorAll('.is-invalid').forEach((input) => {
                    input.classList.add('border-danger');
                });
            }
        });
    </script>
@endsection