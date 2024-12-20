@extends('layout')

@section('title')
    {{ get_label('equipments', 'Equipments') }} - {{ get_label('list_view', 'List View') }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-2 mt-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/home') }}">{{ get_label('home', 'Home') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ url('/equipments') }}">{{ get_label('equipments', 'Equipments') }}</a>
                    </li>
                </ol>
            </nav>

            @if (session('success'))  
                <div class="alert alert-success notification" id="success-message">  
                    {{ session('success') }}  
                </div>  
            @endif  

            @if (session('error'))  
                <div class="alert alert-danger notification" id="error-message">  
                    {{ session('error') }}  
                </div>  
            @endif  
        </div>
    </div>

    <div class="nav-align-top my-4">
        <!-- Add Equipment Button -->
        <div class="mb-3">
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#create_Equipment_modal" data-bs-toggle="tooltip" data-bs-placement="right" title="{{ get_label('create_equipment', 'Add Equipment') }}">
                <i class="bx bx-plus"></i> {{ get_label('add_equipment', 'Add Equipment') }}
            </button>
        </div>

        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-equipments" aria-controls="navs-top-equipments" aria-selected="true">
                    <i class="menu-icon tf-icons bx bx-wrench text-warning"></i>{{ get_label('equipment', 'Equipment') }}
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-requests" aria-controls="navs-top-requests" aria-selected="false">
                    <i class="menu-icon tf-icons bx bx-paper-plane text-success"></i>{{ get_label('requests', 'Requests') }}
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-schedule" aria-controls="navs-top-schedule" aria-selected="false">
                    <i class="menu-icon tf-icons bx bx-calendar text-primary"></i>{{ get_label('schedule', 'Schedule') }}
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-shift-management" aria-controls="navs-top-shift-management" aria-selected="false">
                    <i class="menu-icon tf-icons bx bx-time text-warning"></i>{{ get_label('shift_management', 'Shift Management') }}
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-vouchers" aria-controls="navs-top-vouchers" aria-selected="false">
                    <i class="menu-icon tf-icons bx bx-money text-danger"></i>{{ get_label('vouchers', 'Vouchers') }}
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade active show" id="navs-top-equipments" role="tabpanel">
                <x-equipments-card :equipments="$equipments" :warehouses="$warehouses" :units="$units" />
            </div>
            <div class="tab-pane fade" id="navs-top-requests" role="tabpanel">
                <!-- Content for Requests Tab -->
                <h5>{{ get_label('requests', 'Requests') }}</h5>
                <p>Content for the requests goes here.</p>
            </div>
            <div class="tab-pane fade" id="navs-top-schedule" role="tabpanel">
                <!-- Content for Schedule Tab -->
                <h5>{{ get_label('schedule', 'Schedule') }}</h5>
                <p>Content for the schedule goes here.</p>
            </div>
            <div class="tab-pane fade" id="navs-top-shift-management" role="tabpanel">
                <!-- Content for Shift Management Tab -->
                <h5>{{ get_label('shift_management', 'Shift Management') }}</h5>
                <p>Content for shift management goes here.</p>
            </div>
            <div class="tab-pane fade" id="navs-top-vouchers" role="tabpanel">
                <!-- Content for Vouchers Tab -->
                <h5>{{ get_label('vouchers', 'Vouchers') }}</h5>
                <p>Content for vouchers goes here.</p>
            </div>
        </div>
    </div>
</div>

<!-- Create Equipment Modal -->
<div class="modal fade" id="create_Equipment_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('equipments.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ get_label('create_equipment', 'Create Equipment') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="item" class="form-label">{{ get_label('equipment_name', 'Equipment Name') }}</label>
                    <input type="text" class="form-control" id="item" name="item" required>
                </div>
                <div class="mb-3">
                    <label for="type_id" class="form-label">{{ get_label('equipment_type', 'Equipment Type') }}</label>
                    <select class="form-select" id="type_id" name="type_id" required>
                        <option value="" disabled selected>{{ get_label('select_type', 'Select Equipment Type') }}</option>
                        @foreach($equipmentTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">{{ get_label('quantity', 'Quantity') }}</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" required>
                </div>
                <div class="mb-3">
                    <label for="equipmentModel" class="form-label">{{ get_label('model', 'Model') }}</label>
                    <input type="text" class="form-control" id="equipmentModel" name="equipmentModel" required>
                </div>
                <div class="mb-3">
                    <label for="equipmentSerial" class="form-label">{{ get_label('serial_number', 'Serial Number') }}</label>
                    <input type="text" class="form-control" id="equipmentSerial" name="equipmentSerial" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">{{ get_label('description', 'Description') }}</label>
                    <textarea class="form-control" rows="5" name="description" placeholder="{{ get_label('please_enter_description', 'Please enter description') }}">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ get_label('close', 'Close') }}</button>
                <button type="submit" class="btn btn-primary">{{ get_label('create', 'Create') }}</button>
            </div>
        </form>
    </div>
</div>

<style>
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        padding: 10px 20px;
        border-radius: 4px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        opacity: 1;
        transition: opacity 0.3s ease-in-out;
    }

    .notification.fade-out {
        opacity: 0;
    }
</style>

@endsection