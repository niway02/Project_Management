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
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-equipment-profile" aria-controls="navs-top-equipment-profile" aria-selected="true">
                    <i class="menu-icon tf-icons bx bx-wrench text-warning"></i>{{ get_label('equipment_profile', 'Equipment Profile') }}
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-maintenance" aria-controls="navs-top-maintenance" aria-selected="false">
                    <i class="menu-icon tf-icons bx bx-cog text-info"></i>{{ get_label('maintenance', 'Maintenance') }}
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
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-shift_management" aria-controls="navs-top-shift_management" aria-selected="false">
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
            <div class="tab-pane fade active show" id="navs-top-equipment-profile" role="tabpanel">
            <div class="equipments-card">
    <h4 class="fw-bold">{{ get_label('equipment_details', 'Equipment Details') }}</h4>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th>{{ get_label('equipment_name', 'Equipment Name') }}</th>
                <td>{{ $equipment->item}}</td>
            </tr>
            <tr>
                <th>{{ get_label('model', 'Model') }}</th>
                <td>{{$equipment->title}}</td>
            </tr>
            <tr>
                <th>{{ get_label('serial_number', 'Serial Number') }}</th>
                <td>{{$equipment->vin_serial}}</td>
            </tr>
            <tr>
                <th>{{ get_label('eqp_condition', 'Equipment Condition') }}</th>
                <td>{{$equipment->eqp_condition}}</td>
            </tr>
            <tr>
                <th>{{ get_label('manufacturer', 'Manufacturer') }}</th>
                <td>{{$equipment->manufacturer}}</td>
            </tr>
            <tr>
                <th>{{ get_label('year', 'Year of Manufacture') }}</th>
                <td>{{$equipment->year}}</td>
            </tr>
            <tr>
                <th>{{ get_label('owner', 'Owner') }}</th>
                <td>{{$equipment->owner}}</td>
            </tr>
            <tr>
                <th>{{ get_label('status', 'Status') }}</th>
                <td>{{$equipment->status}}</td>
            </tr>
            <tr>
                <th>{{ get_label('location', 'Location') }}</th>
                <td>{{$equipment->location}}</td>
            </tr>
        </tbody>
    </table>
</div>
            </div>

            <div class="tab-pane fade" id="navs-top-maintenance" role="tabpanel">
                <h4 class="fw-bold">{{ get_label('maintenance_records', 'Maintenance Records') }}</h4>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ get_label('date', 'Date') }}</th>
                            <th>{{ get_label('description', 'Description') }}</th>
                            <th>{{ get_label('cost', 'Cost') }}</th>
                            <th>{{ get_label('status', 'Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>2023-10-01</td>
                            <td>Oil Change and Filter Replacement</td>
                            <td>$150.00</td>
                            <td>Completed</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>2023-09-15</td>
                            <td>Track Adjustment</td>
                            <td>$200.00</td>
                            <td>Completed</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>2023-08-10</td>
                            <td>Hydraulic System Check</td>
                            <td>$75.00</td>
                            <td>Pending</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="navs-top-requests" role="tabpanel">
                <h4 class="fw-bold">{{ get_label('equipment_requests', 'Equipment Requests') }}</h4>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ get_label('requester', 'Requester') }}</th>
                            <th>{{ get_label('equipment', 'Equipment') }}</th>
                            <th>{{ get_label('date_requested', 'Date Requested') }}</th>
                            <th>{{ get_label('status', 'Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>John Doe</td>
                            <td>Dozer</td>
                            <td>2023-10-05</td>
                            <td>Approved</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Jane Smith</td>
                            <td>Excavator</td>
                            <td>2023-10-07</td>
                            <td>Pending</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="navs-top-schedule" role="tabpanel">
                <h4 class="fw-bold">{{ get_label('maintenance_schedule', 'Maintenance Schedule') }}</h4>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ get_label('task', 'Task') }}</th>
                            <th>{{ get_label('scheduled_date', 'Scheduled Date') }}</th>
                            <th>{{ get_label('assigned_to', 'Assigned To') }}</th>
                            <th>{{ get_label('status', 'Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Quarterly Inspection</td>
                            <td>2023-12-01</td>
                            <td>Mike Johnson</td>
                            <td>Scheduled</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Annual Service</td>
                            <td>2024-02-10</td>
                            <td>Anna Lee</td>
                            <td>Pending</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="navs-top-shift_management" role="tabpanel">
                <h4 class="fw-bold">{{ get_label('shift_management', 'Shift Management') }}</h4>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ get_label('operator', 'Operator') }}</th>
                            <th>{{ get_label('equipment', 'Equipment') }}</th>
                            <th>{{ get_label('shift_date', 'Shift Date') }}</th>
                            <th>{{ get_label('hours_worked', 'Hours Worked') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>John Smith</td>
                            <td>Dozer</td>
                            <td>2023-10-01</td>
                            <td>8</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Emily Davis</td>
                            <td>Dozer</td>
                            <td>2023-10-02</td>
                            <td>7</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="navs-top-vouchers" role="tabpanel">
                <h4 class="fw-bold">{{ get_label('vouchers', 'Vouchers') }}</h4>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ get_label('voucher_code', 'Voucher Code') }}</th>
                            <th>{{ get_label('issued_to', 'Issued To') }}</th>
                            <th>{{ get_label('amount', 'Amount') }}</th>
                            <th>{{ get_label('status', 'Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>V1234</td>
                            <td>John Doe</td>
                            <td>$500.00</td>
                            <td>Used</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>V5678</td>
                            <td>Jane Smith</td>
                            <td>$300.00</td>
                            <td>Pending</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
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

<script>
    // Hide success/error message after 3 seconds  
    setTimeout(() => {  
        document.getElementById('success-message')?.style.display = 'none';  
        document.getElementById('error-message')?.style.display = 'none';  
    }, 3000);  
</script>
@endsection