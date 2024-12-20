@extends('layout')

@section('title', 'Materials')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h2>Materials</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#materialModal">Add Material</button>
    </div>
    {{-- @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif --}}


    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Rate with VAT</th>
                <th>Remark</th>
                <th>Status</th>
                <th>Warehouse ID</th>
                <th>Material Type</th>
                <th>Reorder Quantity</th>
                <th>Minimum Quantity</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materials as $material)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $material->item}}</td>
                <td>{{ $material->quantity }}</td>
                <td>{{ $material->rate_with_vat }}</td>
                <td>{{ $material->remark }}</td>
                <td>{{ $material->status }}</td>
                <td>{{ $material->warehouse2_id }}</td>
                <td>{{ $material->material_type }}</td>
                <td>{{ $material->reorder_quantity }}</td>
                <td>{{ $material->min_quantity }}</td>
                <td>
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#materialModal" onclick="editMaterial({{ $material }})">Edit</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Material Modal -->
<div class="modal fade" id="materialModal" tabindex="-1" aria-labelledby="materialModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="materialModalLabel">Add/Edit Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('material2.store') }}" method="POST" id="materialForm">
                @csrf
                <input type="hidden" name="id" id="materialId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="materialName" class="form-label">Material Name</label>
                        <input type="text" name="item" id="materialName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" name="amount" id="amount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="rateWithVat" class="form-label">Rate with VAT</label>
                        <input type="text" name="rate_with_vat" id="rateWithVat" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="remark" class="form-label">Remark</label>
                        <textarea name="remark" id="remark" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="Available">Available</option>
                            <option value="Unavailable">Unavailable</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="warehouseId" class="form-label">Warehouse ID</label>
                        <input type="text" name="warehouse2_id" id="warehouseId" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="materialType" class="form-label">Material Type</label>
                        <input type="text" name="material_type" id="materialType" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="reorderQuantity" class="form-label">Reorder Quantity</label>
                        <input type="number" name="reorder_quantity" id="reorderQuantity" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="minQuantity" class="form-label">Minimum Quantity</label>
                        <input type="number" name="min_quantity" id="minQuantity" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function editMaterial(material) {
        document.getElementById('materialId').value = material.id;
        document.getElementById('materialName').value = material.item;
        document.getElementById('quantity').value = material.quantity;
        document.getElementById('rateWithVat').value = material.rate_with_vat;
        document.getElementById('remark').value = material.remark;
        document.getElementById('status').value = material.status;
        document.getElementById('amount').value = material.amount;
        document.getElementById('warehouseId').value = material.warehouse2_id;
        document.getElementById('materialType').value = material.material_type;
        document.getElementById('reorderQuantity').value = material.reorder_quantity;
        document.getElementById('minQuantity').value = material.min_quantity;
        document.getElementById('materialForm').action = `/material2/${material.id}`;
        document.getElementById('materialForm').method = 'POST';
        document.getElementById('materialForm').insertAdjacentHTML('beforeend', '@method("PUT")');
    }
</script>
@endsection
