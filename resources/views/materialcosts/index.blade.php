@extends('layout')

@section('content')
    <h1>Material Costs</h1>
    <a href="{{ route('materialcosts.create') }}" class="btn btn-primary mb-3">Create Material Cost</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Subtask ID</th>
                <th>Material ID</th>
                <th>Unit</th>
                <th>Quantity</th>
                <th>Rate with VAT</th>
                <th>Amount</th>
                <th>Remark</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materialCosts as $materialCost)
                <tr>
                    <td>{{ $materialCost->id }}</td>
                    <td>{{ $materialCost->subtask_id }}</td>
                    <td>{{ $materialCost->material_id }}</td>
                    <td>{{ $materialCost->unit }}</td>
                    <td>{{ $materialCost->qty }}</td>
                    <td>{{ $materialCost->rate_with_vat }}</td>
                    <td>{{ $materialCost->amount }}</td>
                    <td>{{ $materialCost->remark }}</td>
                    <td>
                        <a href="{{ route('materialcosts.edit', $materialCost->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <form action="{{ route('materialcosts.destroy', $materialCost->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this material cost?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection