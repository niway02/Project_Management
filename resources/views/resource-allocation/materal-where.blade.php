@extends('layout')

@section('content')
<div class="container">
    <h1>Allocated Materials </h1>
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
                    <td>{{ $material->id }}</td>
                    <td>{{ $material->subtask_id }}</td>
                    <td>{{ $material->equipment_id }}</td>
                    <td>{{ $material->planned_quantity }}</td>
                    <td>{{ $material->actual_quantity }}</td>
                    <td>{{ $material->planned_cost }}</td>
                    <td>{{ $material->actual_cost }}</td>
                    <td>{{ $material->remark }}</td>
                    <td>{{ $material->created_at }}</td>
                    <td>{{ $material->updated_at }}</td>
                </tr>
            </tbody>
        </table>
        </div>
    </div>

   
</div>
@endsection