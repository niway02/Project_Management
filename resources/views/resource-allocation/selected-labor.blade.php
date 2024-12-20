@extends('layout')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <h2 class="text-center">Selected Equipment</h2>
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
    <div class="overflow-x-auto">
        <form action="{{ route('laborcostsAllocate.store') }}" method="POST">
            @csrf
            <table class="table table-striped table-bordered table-hover">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>ID</th>
                        <th>Equipment</th>
                        <th>Quantity</th>
                        <th>Rate with VAT</th>
                        <th>Amount</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($selectedMaterials as $key => $selectedMaterial)
                        <tr>
                            <td>{{ $selectedMaterial['id'] }}</td>
                            <td>
                                <input type="number" name="selected_materials[{{ $key }}][quantity]" class="form-control quantity" value="{{ $selectedMaterial['approved_quantity'] }}" readonly>
                                <input type="hidden" name="selected_materials[{{ $key }}][material_request_id]" value="{{ $selectedMaterial['labor_request_id'] }}">
                            </td>
                            <td>
                                <input type="number" name="selected_materials[{{ $key }}][rate_with_vat]" class="form-control rate-with-vat">
                            </td>
                            <td>
                                <input type="number" class="form-control amount" value="0" readonly>
                            </td>
                            <td>
                                <a href="#" class="btn btn-danger btn-sm remove-row">Remove<i class="fas fa-trash ml-2"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <button type="button" class="btn btn-danger" onclick="window.location.href='{{ url()->previous() }}';">Close</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
                <div>
                    <strong>Total Amount:</strong>
                    <input type="text" class="form-control total-amount" value="0.00" readonly>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        var totalAmount = 0;

        $('.remove-row').click(function() {
            var $row = $(this).closest('tr');
            var amount = parseFloat($row.find('.amount').val()) || 0;
            totalAmount -= amount;
            updateTotalAmount();
            $row.remove();
        });

        $('.quantity, .rate-with-vat').on('input', function() {
            var $row = $(this).closest('tr');
            var quantity = parseFloat($row.find('.quantity').val()) || 0;
            var rateWithVat = parseFloat($row.find('.rate-with-vat').val()) || 0;
            var amount = quantity * rateWithVat;

            $row.find('.amount').val(amount.toFixed(2));
            updateTotalAmount();
        });

        function updateTotalAmount() {
            totalAmount = 0;
            $('.amount').each(function() {
                totalAmount += parseFloat($(this).val()) || 0;
            });
            $('.total-amount').val(totalAmount.toFixed(2));
        }
    });
</script>
@endsection