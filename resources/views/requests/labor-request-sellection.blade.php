@extends('layout')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <h2 class="text-center">Selected Labors</h2>
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

        <form method="POST" action="{{ route('labor-request.store') }}" id="material-request-form">
            @csrf
            <input type="hidden" name="selectedSubtaskId" value="{{ $selectedSubtaskId }}">
            <h1>Subtask ID: {{ $selectedSubtaskId }}</h1>

            <div class="overflow-x-auto">
            <table class="table table-bordered">
                                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Labor Type</th>
                            <th>Available Quantity</th>
                            <th>Needed Quantity</th>
                            <th>Remark</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($selectedLabors as $key => $selectedlabor)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $selectedlabor['labor_type_name'] }}</td>
                            <td>
                                <input type="number" class="form-control availableQuantity"  value="{{ $selectedlabor['total_labor'] }}" readonly>
                                <input type="hidden" name="selected_labors[{{ $key }}][id]"  value="{{ $selectedlabor['labor_type_id'] }}">
                            </td>
                            <td>
                                <input type="number" name="selected_labors[{{ $key }}][quantity]" class="form-control quantity" value="" required>
                            </td>
                            <td>
                                <input type="text" name="selected_labors[{{ $key }}][remark]" class="form-control remark" value="">
                            </td>
                            <td class="text-center">
                                <a href="#" class="btn btn-danger btn-sm remove-row">Remove<i class="fas fa-trash ml-2"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center align-items-center mt-3">
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
                e.preventDefault();
                var isValid = true;
                $('.quantity').each(function() {
                    var $this = $(this);
                    var available = parseFloat($this.closest('tr').find('.availableQuantity').val());
                    var requested = parseFloat($this.val());
                    if (requested > available) {
                        $this.addClass('is-invalid');
                        isValid = false;
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
@endsection