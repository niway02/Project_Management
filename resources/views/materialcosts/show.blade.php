@extends('layout')

@section('title')
    {{ get_label('Material Cost', 'Material Cost') }} - {{ get_label('list_view', 'List view') }}
@endsection

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <h1>Subtasks Material Cost</h1>

        <a href="{{ route('materialcosts.create') }}" class="btn btn-primary">Add Material</a>

        @if ($materialCosts->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Unit</th>
                        <th>Qty</th>
                        <th>Rate with VAT</th>
                        <th>Amount</th>
                        <th>Remark</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalAmount = 0; // Initialize the total amount variable
                    @endphp
                    @foreach ($materialCosts as $material)
                        <tr>
                            <td>{{ $material->material->item }}</td>
                            <td>{{ $material->unit }}</td>
                            <td>{{ $material->qty }}</td>
                            <td>{{ $material->rate_with_vat }}</td>
                            <td>{{ $material->amount }}</td>
                            <td>{{ $material->remark }}</td>
                            <td>
                                <a href="#" class="btn btn-primary">Edit</a>
                                <form action="#" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @php
                            $totalAmount += $material->amount; // Increment the total amount
                        @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4"></td>
                        <th>Total Amount:</th>
                        <td>{{ number_format($totalAmount, 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        @else
            <p>No Materials found.</p>
        @endif
    </div>
@endsection