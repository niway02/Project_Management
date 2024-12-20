@extends('layout')

@section('content')
    <div class="container my-5">
        <h1 class="text-center mb-4">Selected Materials</h1>
        @if($selectedMaterials->isEmpty())
            <div class="alert alert-info text-center">No materials selected.</div>
        @else
            <table class="table table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Unit</th>
                        <th scope="col">Available Quantity</th>
                        <th scope="col">Rate with VAT</th>
                        <th scope="col">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($selectedMaterials as $material)
                        <tr>
                            <td>{{ $material->id }}</td>
                            <td>{{ $material->item }}</td>
                            <td>{{ $material->unitMeasure->name }}</td>
                            <td>{{ $material->quantity }}</td>
                            <td>{{ $material->rate_with_vat }}</td>
                            <td>{{ $material->amount }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection