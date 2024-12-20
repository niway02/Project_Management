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
    <div class="container my-4">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
@elseif (session()->has('error'))
    <div class="container my-4">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
@endif
        <div class="overflow-x-auto">
            <form method="POST" action="{{ route('materialcosts.allocate') }}">
                @csrf
                <table class="table table-striped table-bordered table-hover">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Employeer</th>
                            <th class="px-4 py-2">Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($selectedMaterials as $key => $selectedMaterial)
                        <tr>
                            <td class="px-4 py-2">{{ $selectedMaterial['id'] }}</td>
                            <td class="px-4 py-2">{{ $selectedMaterial['name'] }}</td>
                            <td class="px-4 py-2">
                                <a href="#" class="btn btn-danger btn-sm remove-row">Remove<i class="fas fa-trash ml-2"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
                var availableQuantity = parseFloat($row.find('.avalabileQuantity').val());
                var rateWithVat = parseFloat($row.find('.rate-with-vat').val()) || 0;
                var amount = quantity * rateWithVat;

                // Validate the quantity
                if (quantity > availableQuantity) {
                    $row.find('.quantity').addClass('is-invalid');
                    $row.find('.quantity').after('<div class="invalid-feedback">Your quantity is too much</div>');
                } else {
                    $row.find('.quantity').removeClass('is-invalid');
                    $row.find('.quantity + .invalid-feedback').remove();
                }

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