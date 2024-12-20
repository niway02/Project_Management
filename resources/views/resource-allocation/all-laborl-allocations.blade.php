@extends('layout')

@section('content')
<div class="container">
    <h1>Allocated labors </h1>
    <div class="card">
        <div class="card-header">
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Cost</th>
                        <th>Quantity</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materials as $material)
                        <tr>
                            <td>{{ $material->id }}</td>
                            <td>{{ $material->name }}</td>
                            <td>${{ number_format($material->cost, 2) }}</td>
                            <td>{{ $material->quantity }}</td>
                            <td>{{ $material->created_at }}</td>
                            <td>{{ $material->updated_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

   
</div>
@endsection