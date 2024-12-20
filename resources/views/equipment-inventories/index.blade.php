@extends('layout')

@section('title')
<?= get_label('equpments', 'Equipment Allocation') ?>
@endsection

@section('content')

<div class="container-fluid">
<h1>Equipment Inventories</h1>
    <div class="d-flex justify-content-between mb-2 mt-4">


<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>id</th>
            <th>Warehouse</th>
            <th>Equipment</th>
            <th>Quantity</th>
            <th>Depreciation</th>
            <th>Maintenance Log</th>
            <th>Cost</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($equipmentInventories as $inventory)
        <tr>
           <td>{{ $inventory->warehouse->id }}</td>
           <td>{{ $inventory->warehouse->name }}</td>
           <td>{{ $inventory->equipment->item }}</td>
            <td>{{ $inventory->quantity }}</td>
            <td>{{ $inventory->depreciation }}</td>
            <td>{{ $inventory->maintenanceLog }}</td>
            <td>{{ $inventory->cost }}</td>
            
            <td>
                <a href="" class="btn btn-primary">View</a>
                <a href="" class="btn btn-warning">Edit</a>
                <form action="" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
    </div>
</div>
@endsection