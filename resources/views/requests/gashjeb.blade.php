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
                <h5 class="mt-4">Materials for this Activity</h5>

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
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $materialRequests['id'] }}</td>
                                    <td>{{ $materialRequests['material']['item'] ?? 'N/A' }}</td>
                                    <td>{{ $materialRequests['material']['unit_measure'] ?? 'N/A' }}</td>
                                    <td>{{ $materialRequests['material']['rate_with_vat'] ?? 'N/A' }} ETB</td>
                                    <td>
                                        {{ optional($materialRequests['material']['warehouse']['materials_inventory'])[0]->quantity ?? 'N/A' }}
                                    </td>
                                    <td>{{ $materialRequests['item_quantity'] ?? 'N/A' }}</td>
                                    <td>
                                        {{ number_format(($materialRequests['material']['rate_with_vat'] * $materialRequests['item_quantity']), 2) ?? 0 }} ETB
                                    </td>
                                    <td>
                                        <span class="badge 
                                            {{ $materialRequests['resource_request']['finance_status'] === 'pending' ? 'bg-secondary' : 
                                               ($materialRequests['resource_request']['finance_status'] === 'rejected' ? 'bg-danger' : 'bg-success') }} 
                                            text-white rounded-pill px-3 py-2">
                                            {{ $materialRequests['resource_request']['finance_status'] }}
                                        </span>
                                    </td>
                                    <input type="hidden" name="resource_request_id[]" value="{{ $materialRequests['resource_request']['id'] }}">
                                </tr>
                            </tbody>
                        </table>

                        <div class="col-12 d-flex justify-content-center mt-3">
                            <button type="button" class="btn btn-outline-primary" id="approveBtn"
                                {{ $materialRequests['resource_request']['finance_status'] === 'approved' ? 'disabled' : '' }}>
                                Approve
                            </button>
                            <button type="button" class="btn btn-outline-danger" id="rejectBtn"
                                {{ $materialRequests['resource_request']['finance_status'] === 'rejected' ? 'disabled' : '' }}>
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
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script>
        // Initialize DataTable
        $(document).ready(function() {
            $('#materialsTable').DataTable({
                responsive: true,
                paging: false,
                searching: false,
                ordering: false,
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
                        $('#responseMessage').html(`<div class="alert alert-success">${response.message}</div>`);
                        location.reload(); // Reload to see updates
                    },
                    error: function(xhr) {
                        const errorMessage = xhr.responseJSON.message || 'An error occurred';
                        $('#responseMessage').html(`<div class="alert alert-danger">${errorMessage}</div>`);
                    }
                });
            }
        });
    </script>
@endsection