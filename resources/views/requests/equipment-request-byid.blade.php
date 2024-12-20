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
                <a href="#" class="text-success font-weight-bold" style="text-decoration: none;">
                    View Allocate Resource
                </a>
            </div>
            <div class="card-body">
                <div class="row project-task-activity-hierarchy">
                    <div class="col-md-4">
                        <div class="activity">
                            {{ $total->activity->name ?? 'No Activity' }} <!-- Display activity name -->
                        </div>
                    </div>
                </div>

                <h5 class="mt-4">Materials for this Activity (Total: 1)</h5> <!-- Assuming one material -->

                <div class="table-responsive text-nowrap">
                    <form action="{{ route('request.material-request-response') }}" method="POST">
                        @csrf
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Material</th>
                                    <th>Unit</th>
                                    <th>Avallable quantity </th>
                                    <th>Quantity</th>
                                    <th>Rate with VAT</th>
                                    <th>Select</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td> <!-- Static index since only one material -->
                                    <td>{{ $total->material->item }}</td> <!-- Material name -->
                                    <td>{{ $total->material->unit_id }}</td> <!-- Change to unit name if available -->
                                    <td>{{ $total->avallable_quantity }}</td> <!-- Quantity requested -->
                                    <td>{{ $total->item_quantity }}</td> <!-- Quantity requested -->
                                    <td>{{ $total->material->rate_with_vat }}</td> <!-- Rate with VAT -->
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="selected_materials[]" value="{{ json_encode($total->material) }}">
                                            <label class="form-check-label"></label>
                                        </div>
                                    </td>
                                </tr>
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
@endsection