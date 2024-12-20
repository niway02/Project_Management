@extends('layout')

@section('content')
    <h1>Create Material Cost</h1>
    <form action="{{ route('equipments.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="subtask_id">Subtask ID</label>
            <input type="text" class="form-control" id="subtask_id" name="subtask_id" required>
        </div>
        <div class="form-group">
            <label for="material_id">Material ID</label>
            <input type="text" class="form-control" id="material_id" name="material_id" required>
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
@endsection