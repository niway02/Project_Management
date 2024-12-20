@extends('layout')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ url()->previous() }}" class="btn btn-secondary rounded-pill">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <h2 class="text-center font-weight-bold text-primary">Selected Materials</h2>
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

    <div class="overflow-auto">
        <form method="POST" action="{{ route('material-request.store') }}" id="material-request-form">
            @csrf
            <input type="hidden" name="selectedSubtaskId" value="{{ $selectedSubtaskId }}">

            <div class="mb-3">
                <input type="text" id="search" class="form-control" placeholder="Search materials..." aria-label="Search">
            </div>

            <table class="table table-responsive table-striped table-bordered shadow-sm" id="materials-table">
                <thead class="thead-light">
                    <tr>
                        <th class="sortable" data-column="no">No</th>
                        <th class="sortable" data-column="material">Material</th>
                        <th class="sortable" data-column="available">Available Quantity</th>
                        <th class="sortable" data-column="needed">Needed Quantity</th>
                        <th class="sortable" data-column="rate">Rate With VAT</th>
                        <th class="sortable" data-column="total">Total</th>
                        <th>Remark</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody id="material-table-body">
                    @foreach ($selectedMaterials as $key => $selectedMaterial)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $selectedMaterial['material']['item'] }}</td>
                        <td>
                            <input type="number" class="form-control availableQuantity" value="{{ $selectedMaterial['quantity'] }}" readonly>
                            <input type="hidden" name="selected_materials[{{ $key }}][id]" value="{{ $selectedMaterial['material']['id'] }}">
                        </td>
                        <td>
                            <input type="number" name="selected_materials[{{ $key }}][quantity]" class="form-control quantity" value="0" required>
                            <input type="hidden" name="selected_materials[{{ $key }}][material_id]" value="{{ $selectedMaterial['material_id'] }}">
                        </td>
                        <td>
                            <input type="number" class="form-control rate_with_vat" value="{{ $selectedMaterial['material']['rate_with_vat'] }}" readonly>
                        </td>
                        <td>
                            <input type="text" class="form-control total" name="selected_materials[{{ $key }}][total]" value="0" readonly>
                        </td>
                        <td>
                            <input type="text" name="selected_materials[{{ $key }}][remark]" class="form-control remark">
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm remove-row">Remove <i class="fas fa-trash ml-2"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-right font-weight-bold">Grand Total:</td>
                        <td>
                            <input type="text" id="grand-total" class="form-control" value="0" readonly>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>

            <div class="d-flex justify-content-center align-items-center mt-4">
                <button type="button" class="btn btn-danger rounded-pill mr-2 close-btn">Close</button>
                <button type="submit" class="btn btn-success rounded-pill" id="send-request">Send Request</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Search functionality
        $('#search').on('keyup', function() {
            var searchTerm = $(this).val().toLowerCase();
            $('#material-table-body tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(searchTerm) > -1);
            });
        });

        // Calculate total and grand total
        function calculateTotal() {
            let grandTotal = 0;
            $('#material-table-body tr').each(function() {
                const quantity = parseFloat($(this).find('.quantity').val()) || 0;
                const rateWithVat = parseFloat($(this).find('.rate_with_vat').val()) || 0;
                const total = quantity * rateWithVat;
                $(this).find('.total').val(total.toFixed(2));
                grandTotal += total;
            });
            $('#grand-total').val(grandTotal.toFixed(2));
        }

        // Update total when quantity changes
        $('.quantity').on('input', function() {
            calculateTotal();
        });

        // Column sorting functionality
        $('.sortable').on('click', function() {
            var column = $(this).data('column');
            var rows = $('#material-table-body tr').toArray();

            rows.sort(function(a, b) {
                var A = $(a).find('td:nth-child(' + ($(this).index() + 1) + ')').text();
                var B = $(b).find('td:nth-child(' + ($(this).index() + 1) + ')').text();
                
                if ($.isNumeric(A) && $.isNumeric(B)) {
                    return A - B; // Sort numerically
                }
                return A.localeCompare(B); // Sort alphabetically
            }.bind(this));

            $.each(rows, function(index, row) {
                $('#material-table-body').append(row); // Reappend sorted rows
            });
        });

        $('.remove-row').click(function() {
            $(this).closest('tr').remove();
            calculateTotal(); // Recalculate total after row removal
        });

        $('.close-btn').click(function() {
            window.location.href = '{{ url()->previous() }}';
        });

        $('#material-request-form').submit(function(e) {
            e.preventDefault();
            var isValid = true;
            $('.quantity').each(function() {
                var $this = $(this);
                var available = parseFloat($this.closest('tr').find('.availableQuantity').val());
                var requested = parseFloat($this.val());
                if (requested > available) {
                    $this.addClass('is-invalid');
                    isValid = true;
                } else {
                    $this.removeClass('is-invalid');
                }
            });
            if (isValid) {
                this.submit();
            }
        });
    });
</script>

<style>
    body {
        background-color: #f8f9fa;
    }

    h2 {
        font-family: 'Arial', sans-serif;
        font-weight: bold;
        color: #0056b3;
    }

    .table {
        background-color: white;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    th {
        background-color: #007bff;
        color: white;
        text-align: center;
        cursor: pointer;
    }

    th.sortable:hover {
        background-color: #0056b3;
    }

    td {
        vertical-align: middle;
    }

    .btn {
        transition: background-color 0.3s, transform 0.3s;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .form-control.is-invalid {
        border-color: #dc3545;
    }

    .form-control {
        border-radius: 0.25rem;
    }

    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
        }
    }
</style>
@endsection