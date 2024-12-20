@extends('layout')

@section('title')
    {{ get_label('Resource Allocation', 'Resource Allocation') }}
@endsection

@section('content')
    <div class="container-fluid">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb breadcrumb-style1">
                <li class="breadcrumb-item">
                    <a href="{{ url('/home') }}">{{ get_label('home', 'Home') }}</a>
                </li>
                <li class="breadcrumb-item active">
                    {{ get_label('Resource Allocation', 'Resource Allocation') }}
                </li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
 view 
            </div>
            <div class="card-body">

            
                <!-- Tabs for Materials, Equipment, Labor -->
                <div class="nav-align-top my-4">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="materials-tab" data-bs-toggle="tab" href="#materials" role="tab">
                                <i class="menu-icon tf-icons bx bx-box text-success"></i> Materials
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="equipment-tab" data-bs-toggle="tab" href="#equipment" role="tab">
                                <i class="menu-icon tf-icons bx bx-wrench text-info"></i> Equipment
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="materials" role="tabpanel">
                            <h5 class="mt-4">Materials for this Activity</h5>
                            <div class="table-responsive text-nowrap">
                                <form action="{{ route('transfer.submit') }}" method="POST">
                                    @csrf
                                  
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Material</th>
                                                <th>Unit</th>
                                                <th>Quantity</th>
                                                <th>Rate with VAT</th>
                                                <th>Select</th>
                                            </tr> 
                                        </thead>
                                        <tbody>
                                        @foreach($transfers as $key => $material)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $material['material']['item'] }}</td>
                                                <td>{{ $material['approved_quantity'] }}</td>
                                                <td>{{ $material['quantity'] }}</td>
                                                <td>{{ $material['material']['rate_with_vat'] }}</td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="selected_materials[]" value="{{ json_encode($material) }}">
                                                        <label class="form-check-label"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Continue</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="equipment" role="tabpanel">
                            <h5 class="mt-4">Equipment for this Activity</h5>
                            <div class="table-responsive text-nowrap">
                                <form action="{{ route('sellect-material.request') }}" method="POST">
                                    @csrf
                             
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Material</th>
                                                <th>Unit</th>
                                                <th>Quantity</th>
                                                <th>Rate with VAT</th>
                                                <th>Reorder Quantity</th>
                                                <th>min_quantity </th>
                                                <th>Select</th>
                                            </tr> 
                                        </thead> 
                                        <tbody>
                                        @foreach($transfers as $key => $material)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $material->item }}</td>
                                                <td>{{ $material->item }}</td>
                                                <td>{{ $material->quantity }}</td>
                                                <td>{{ $material->rate_with_vat }}</td>
                                                <td>{{ $material->reorder_quantity}} </td>
                                                <td>{{ $material->min_quantity}} </td>
                                             
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="selected_materials[]" value="{{ json_encode($material) }}">
                                                        <label class="form-check-label"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Continue</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Include Boxicons for icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.0.9/css/boxicons.min.css">
    <style>
        /* Custom styles for tabs */
        .nav-tabs .nav-link {
            border: 1px solid transparent;
            border-radius: 0.5rem;
            color: #555;
            transition: background-color 0.3s, color 0.3s;
        }

        .nav-tabs .nav-link.active {
            background-color: #71dd37; /* Active tab color */
            color: white;
            border-color: #71dd37; /* Match border to active color */
        }

        .nav-tabs .nav-link:hover {
            background-color: #e2e2e2; /* Hover effect */
        }

        .table {
            margin-top: 20px;
        }
    </style>
@endsection