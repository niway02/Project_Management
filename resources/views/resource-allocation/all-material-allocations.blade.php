@extends('layout')

@section('title')
    {{ get_label('Material Allocation', 'Material Allocation') }}
@endsection

@section('content')
    <div class="container-fluid">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb breadcrumb-style1">
                <li class="breadcrumb-item">
                    <a href="{{ url('/home') }}">{{ get_label('home', 'Home') }}</a>
                </li>
                <li class="breadcrumb-item active">
                    {{ get_label('Material Allocation', 'Material Allocation') }}
                </li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <a href="{{ route('materialAlloation.activity', $activity->task->id) }}" class="text-success font-weight-bold" style="text-decoration: none;">
                    Allocate New Material
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped">
                        <thead class="table-primary">
                            <tr>
                                <th>No</th>
                                <th>Material</th>
                                <th>Unit</th>
                                <th>Planned Quantity</th>
                                <th>Actual Quantity</th>
                                <th>Variance in Quantity</th>
                                <th>Planned Budget</th>
                                <th>Actual Budget</th>
                                <th>Variance in Budget</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($materials as $key => $material)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $material['material_item'] }}</td>
                                <td>{{ $material['unit_measure'] }}</td>
                                <td>{{ $material['planned_quantity'] }}</td>
                                <td>{{ $material['actual_quantity'] }}</td>
                                <td>{{ $material['actual_quantity'] - $material['planned_quantity'] }}</td>
                                <td>{{ $material['planned_budget'] }}</td>
                                <td>{{ $material['actual_budget'] }}</td>
                                <td>{{ $material['actual_budget'] - $material['planned_budget'] }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-btn" 
                                            data-toggle="modal" 
                                            data-target="#editModal" 
                                            data-item="{{ json_encode($material) }}">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Material</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        @csrf <!-- Include CSRF token -->
                        <input type="hidden" id="materialId" name="id">
                        <div class="form-group">
                            <label for="materialItem">Material Item</label>
                            <input type="text" class="form-control" id="materialItem" name="material_item" required>
                        </div>
                        <div class="form-group">
                            <label for="unitMeasure">Unit Measure</label>
                            <input type="text" class="form-control" id="unitMeasure" name="unit_measure" required>
                        </div>
                        <div class="form-group">
                            <label for="plannedQuantity">Planned Quantity</label>
                            <input type="number" class="form-control" id="plannedQuantity" name="planned_quantity" required>
                        </div>
                        <div class="form-group">
                            <label for="actualQuantity">Actual Quantity</label>
                            <input type="number" class="form-control" id="actualQuantity" name="actual_quantity" required>
                        </div>
                        <div class="form-group">
                            <label for="plannedBudget">Planned Budget</label>
                            <input type="number" class="form-control" id="plannedBudget" name="planned_budget" required>
                        </div>
                        <div class="form-group">
                            <label for="actualBudget">Actual Budget</label>
                            <input type="number" class="form-control" id="actualBudget" name="actual_budget" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveChanges">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    
    <script>
      $(document).ready(function() {
        $('.edit-btn').on('click', function() {
            const material = $(this).data('item');
            $('#materialId').val(material.id); // Set the hidden ID field
            $('#materialItem').val(material.material_item);
            $('#unitMeasure').val(material.unit_measure);
            $('#plannedQuantity').val(material.planned_quantity);
            $('#actualQuantity').val(material.actual_quantity);
            $('#plannedBudget').val(material.planned_budget);
            $('#actualBudget').val(material.actual_budget);
        });

        $('#saveChanges').on('click', function() {
           // const id = $('#materialId').val();
           const id = parseInt($('#materialId').val(), 10); // Convert ID to integer
            console.log('Updating material with ID:', id); // Log the ID

            const updatedData = {
                id: id,
                material_item: $('#materialItem').val(),
                unit_measure: $('#unitMeasure').val(),
                planned_quantity: $('#plannedQuantity').val(),
                actual_quantity: $('#actualQuantity').val(),
                planned_budget: $('#plannedBudget').val(),
                actual_budget: $('#actualBudget').val(),
                _token: '{{ csrf_token() }}' // Include CSRF token
            };

            console.log('Data being sent:', updatedData); // Log the data

            $.ajax({
                type: 'POST',
                url: '{{ route("allocation.update-material") }}',
                data: updatedData,
                success: function(response) {
                    if (response.success) {
                        $('#editModal').modal('hide');
                        location.reload(); // Reloads the page to see the updated data
                    } else {
                        alert('Update failed.');
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('An error occurred while saving the changes: ' + xhr.responseText);
                }
            });
        });
    });
    </script>
@endsection