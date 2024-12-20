@extends('layout')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @elseif (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <form method="POST" action="{{ route('transfer.store') }}" id="material-request-form" class="mb-4">
            @csrf
            <input type="hidden" name="selectedSubtaskId" value="{{ $selectedSubtaskId }}">

            <div class="form-row mb-4">
                <div class="form-group col-md-6">
                    <label for="fromWarehouse">From Warehouse</label>
                    <select id="fromWarehouse" name="fromWarehouse" class="form-control" required>
                        <option value="">Select Warehouse</option>
                        <!-- Add options dynamically -->
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="toWarehouse">To Warehouse</label>
                    <select id="toWarehouse" name="toWarehouse" class="form-control" required>
                        <option value="">Select Warehouse</option>
                        <!-- Add options dynamically -->
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="toWarehouse">Remark</label>
                    <input type="text" class="form-control" value="" >
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Material</th>
                            <th>Available Quantity</th>
                            <th>Needed Quantity</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($selectedMaterials as $key => $selectedMaterial)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $selectedMaterial['material']['item'] }}</td>
                            <td>
                                <input type="number" class="form-control" value="{{ $selectedMaterial['quantity'] }}" readonly>
                                <input type="hidden" name="selected_materials[{{ $key }}][id]" value="{{ $selectedMaterial['material']['id'] }}">
                            </td>
                            <td>
                                <input type="number" name="selected_materials[{{ $key }}][quantity]" class="form-control quantity" value="0" required>
                                <input type="hidden" name="selected_materials[{{ $key }}][material_id]" value="{{ $selectedMaterial['material_id'] }}">
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm remove-row">Remove <i class="fas fa-trash ml-2"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                <button type="button" class="btn btn-danger mr-2 close-btn">Close</button>
                <button type="submit" class="btn btn-success" id="send-request">Send Request</button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('.remove-row').click(function() {
                $(this).closest('tr').remove();
            });

            $('.close-btn').click(function() {
                window.location.href = '{{ url()->previous() }}';
            });

            $('#material-request-form').submit(function(e) {
                let isValid = true;
                $('.quantity').each(function() {
                    const available = parseFloat($(this).closest('tr').find('.avalabileQuantity').val());
                    const requested = parseFloat($(this).val());
                    if (requested > available) {
                        $(this).addClass('is-invalid');
                        isValid = false;
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
                if (isValid) this.submit();
                else e.preventDefault();
            });
        });
    </script>

    <style>
        .table {
            border-radius: 0.5rem;
            overflow: hidden;
            margin-top: 20px;
        }
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }
        .btn {
            transition: background-color 0.3s ease;
        }
        .btn-danger:hover {
            background-color: #c82333; /* Darker red */
        }
        .btn-success:hover {
            background-color: #218838; /* Darker green */
        }
        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }
        }
    </style>
@endsection