@extends('layout')

@section('title')
    <?= get_label('sub tasks', 'Sub Tasks') ?> - <?= get_label('list_view', 'List view') ?>
@endsection

@section('content')
    <div class="container">

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <h1>SubTasks</h1>

        @if (count($data) > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Activity Task</th>
                        <th>material Cost</th>
                        <th>Equipment Cost</th>
                        <th>Labor Cost</th>
                        <th>Other Cost</th>
                        <th>Sub Total Cost</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalSubtotalCost = 0;
                    @endphp
                    @foreach ($data as $subtask)
                        @php
                            $subtotalCost = $subtask['total_material_amount'] + $subtask['total_equipment_amount'] + $subtask['total_labor_amount'];
                            $totalSubtotalCost += $subtotalCost;
                        @endphp
                        <tr>
                        <td>{{ $subtask['task_name'] }}</td>
                        <td>{{ number_format($subtask['total_material_amount'], 2) }} &nbsp;<a href="{{ route('materialcosts.show', $subtask['id']) }}">view</a></td>
                        <td>{{ number_format($subtask['total_equipment_amount'], 2) }} &nbsp;<a href="{{ route('equipmentcosts.show', $subtask['id']) }}">view</a></td>
                        <td>{{ number_format($subtask['total_labor_amount'], 2) }} &nbsp;<a href="{{ route('laborcosts.show', $subtask['id']) }}">view</a></td>
                        <td>0.00</td>
                         <td>{{ number_format($subtotalCost, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" style="text-align: right;"><strong>Total:</strong></td>
                        <td>{{ number_format($totalSubtotalCost, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        @else
            <p>No sub task found.</p>
        @endif
    </div>
@endsection