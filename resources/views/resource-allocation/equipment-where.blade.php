@extends('layout')

@section('content')
<div class="container">
    <h1>Allocated Equipments </h1>
    <div class="card">
        <div class="card-header">
        </div>
        <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Subtask ID</th>
                    <th>Equipment ID</th>
                    <th>Planned Quantity</th>
                    <th>Actual Quantity</th>
                    <th>Planned Cost</th>
                    <th>Actual Cost</th>
                    <th>Remark</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $equipment->id }}</td>
                    <td>{{ $equipment->subtask_id }}</td>
                    <td>{{ $equipment->equipment_id }}</td>
                    <td>{{ $equipment->planned_quantity }}</td>
                    <td>{{ $equipment->actual_quantity }}</td>
                    <td>{{ $equipment->planned_cost }}</td>
                    <td>{{ $equipment->actual_cost }}</td>
                    <td>{{ $equipment->remark }}</td>
                    <td>{{ $equipment->created_at }}</td>
                    <td>{{ $equipment->updated_at }}</td>
                </tr>
            </tbody>
        </table>
        </div>
    </div>

   
</div>
@endsection