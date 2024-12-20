@extends('layout')

@section('content')
    <h1>Create Labor Cost</h1>
    <form action="{{ route('laborcosts.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="subtask_id">Subtask</label>
            <select class="form-control" id="subtask_id" name="subtask_id" required>
                @foreach ($subtasks as $subtask)
                    <option value="{{ $subtask->id }}">{{ $subtask->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="material_id">Labor</label>
            <select class="form-control" id="labor_id" name="labor_id" required>
                @foreach ($materials as $material)
                    <option value="{{ $material->id }}">{{ $material->item }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="unit">Unit<lable/>
            <input type="text" class="form-control" id="unit" name="unit" required>
        </div>
        <div class="form-group">
            <label for="qty">Quantity</label>
            <input type="number" class="form-control" id="qty" name="qty" required>
        </div>
        <div class="form-group">
            <label for="rate_with_vat">Rate with VAT</label>
            <input type="number" step="0.01" class="form-control" id="rate_with_vat" name="rate_with_vat" required>
        </div>
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
        </div>
        <div class="form-group">
            <label for="remark">Remark</label>
            <textarea class="form-control" id="remark" name="remark"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
    <script>
        // Calculate the amount based on quantity and rate with VAT
        document.getElementById('qty').addEventListener('input', calculateAmount);
        document.getElementById('rate_with_vat').addEventListener('input', calculateAmount);

        function calculateAmount() {
            const qty = parseFloat(document.getElementById('qty').value);
            const rateWithVat = parseFloat(document.getElementById('rate_with_vat').value);
            const amount = qty * rateWithVat;
            document.getElementById('amount').value = amount.toFixed(2);
        }
    </script>
@endsection