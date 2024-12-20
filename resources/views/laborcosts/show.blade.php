@extends('layout')

@section('title')
    <?= get_label('Labor Cost', 'Labor Cost') ?> - <?= get_label('list_view', 'List view') ?>
@endsection

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <h1>Subtasks Labor Cost</h1>

        <a href="{{ route('laborcosts.create') }}" class="btn btn-primary">Add Labor cost</a>

        <div class="container">
            <h1>Labor Costs</h1>

            <table class="table">
                <thead>
                    <tr>
                        <th>Worker ID</th>
                        <th>Hours</th>
                        <th>Rate with VAT</th>
                        <th>Amount</th>
                        <th>Remark</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalAmount = 0;
                    @endphp
                    @forelse ($laborCosts as $laborCost)
                        <tr>
                            <td>labor1</td>
                            <td>{{ $laborCost->hours }}</td>
                            <td>{{ $laborCost->rate_with_vat }}</td>
                            <td>{{ $laborCost->amount }}</td>
                            <td>{{ $laborCost->remark }}</td>
                        </tr>
                        @php
                            $totalAmount += $laborCost->amount;
                        @endphp
                    @empty
                        <tr>
                            <td colspan="5">No labor costs found.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"></td>
                        <td><strong>Total:</strong></td>
                        <td><strong>{{ $totalAmount }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection