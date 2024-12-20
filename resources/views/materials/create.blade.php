@extends('layout')

@section('title')
  {{ get_label('dashboard', 'Dashboard') }}
@endsection

@section('content')
  <div class="container-fluid py-5">
    <div class="row justify-content-center">
      <div class="col-lg-8 col-md-10">
        <div class="card shadow-sm">
          <div class="card-header bg-primary text-white">
            <h1 class="mb-0">Create Material</h1>
          </div>
          <div class="card-body">
            @if ($errors->any())
              <div class="alert alert-danger">
                <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <form action="{{ route('materials.store') }}" method="POST">
              @csrf

              <div class="row">
                <div class="col-md-6 form-group">
                  <label for="item">Item:</label>
                  <input type="text" id="item" name="item" class="form-control" required>
                </div>
                <div class="col-md-6 form-group">
                  <label for="unit">Unit:</label>
                  <select id="unit" name="unit" class="form-control" required>
                    <option value="">Select Unit</option>
                    <option value="Piece">Piece</option>
                    <option value="Box">Box</option>
                    <option value="Kg">Kg</option>
                    <option value="Liter">Liter</option>
                  </select>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 form-group">
                  <label for="warehouse">Warehouse:</label>
                  <select id="warehouse" name="warehouse" class="form-control" required>
                    <option value="">Select Warehouse</option>
                    <option value="Warehouse A">Warehouse A</option>
                    <option value="Warehouse B">Warehouse B</option>
                    <option value="Warehouse C">Warehouse C</option>
                  </select>
                </div>
                <div class="col-md-6 form-group">
                  <label for="min_quantity">Minimum Quantity:</label>
                  <input type="number" id="min_quantity" name="min_quantity" class="form-control" required>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 form-group">
                  <label for="reorder_quantity">Reorder Quantity:</label>
                  <input type="number" id="reorder_quantity" name="reorder_quantity" class="form-control" required>
                </div>
                <div class="col-md-6 form-group">
                  <label for="material_type">Material Type:</label>
                  <select id="material_type" name="material_type" class="form-control" required>
                    <option value="">Select Material Type</option>
                    <option value="Raw Material">Raw Material</option>
                    <option value="Finished Goods">Finished Goods</option>
                    <option value="Consumable">Consumable</option>
                  </select>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 form-group">
                  <label for="quantity">Quantity:</label>
                  <input type="number" id="quantity" name="quantity" class="form-control" required>
                </div>
                <div class="col-md-6 form-group">
                  <label for="rate_with_vat">Rate with VAT:</label>
                  <input type="number" id="rate_with_vat" name="rate_with_vat" class="form-control" step="0.01" required>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 form-group">
                  <label for="amount">Amount:</label>
                  <input type="number" id="amount" name="amount" class="form-control" step="0.01" required>
                </div>
                <div class="col-md-6 form-group">
                  <label for="remark">Remark:</label>
                  <textarea id="remark" name="remark" class="form-control"></textarea>
                </div>
              </div>

              <div class="text-center">
                <button type="submit" class="btn btn-primary px-4 py-2">Create</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

<style>
  .form-group {
    margin-bottom: 1.5rem;
  }

  .form-control {
    border-radius: 0.25rem;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
  }

  .form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
  }

  .btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    font-size: 1rem;
    padding: 0.75rem 1.5rem;
    transition: background-color 0.3s ease-in-out, border-color 0.3s ease-in-out;
  }

  .btn-primary:hover {
    background-color: #0056b3;
    border-color: #004a9b;
  }

  .card {
    border-radius: 0.5rem;
  }

  .card-header {
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Add any desired JavaScript functionality here
    // For example, you could add form validation or dynamic field interactions
  });
</script>