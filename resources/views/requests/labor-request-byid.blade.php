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

                <div class="table-responsive text-nowrap">
                    <form action="{{ route('request.store-labor-request-response') }}" method="POST">
                        @csrf
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Position</th>
                                    <th>Hourly Rate</th>
                                    <th>Available Quantity</th>
                                    <th>Requested Quantity</th>
                                    <th>Approved Quantity</th>
                                    <th>Remark</th>
                                    <th>Select</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($laborRequestResult as $index => $laborRequest)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $laborRequest['labor_type_name'] }}</td>
                                        <td>{{ $laborRequest['hourly_rate'] }}</td>
                                        <td>{{ $laborRequest['avallable_quantity'] }}</td>
                                        <td>{{ $laborRequest['quantity_requested'] }}</td>
                                        <td>
                                            <input type="number" name="approved_quantity[]" value="{{ old('approved_quantity.' . $index) }}" min="0" class="form-control" required />
                                        </td>
                                        <td>
                                            <input type="text" name="remark[]" value="{{ old('remark.' . $index) }}" class="form-control" placeholder="Enter remark" />
                                        </td>
                                        <td>
                                            @if($laborRequest['status'] != 'allocated')
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="selected_materials[]" value="{{ json_encode($laborRequest['id']) }}">
                                                    <label class="form-check-label"></label>
                                                </div>
                                            @else
                                           allocated
                                            @endif
                                        </td>
                                        <!-- Hidden input to pass the materialRequest ID -->
                                        <input type="hidden" name="material_request_ids[]" value="{{ $laborRequest['id'] }}">
                                        <input type="hidden" name="resource_request_id" value="{{ $laborRequest['resource_request_id'] }}">
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" 
                                @if($laborRequestResult->where('status', 'Pending')->isEmpty()) disabled @endif>
                                Continue
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection