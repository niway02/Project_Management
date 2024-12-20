@extends('layout')

@section('title')
    {{ get_label('tasks', 'Tasks') }} - {{ get_label('list_view', 'List view') }}
@endsection

@php
    $user = getAuthenticatedUser();
    $isAdmin = $user->hasRole('admin');
    $isLabor = $user->hasRole('labor');
    $isHRManager = $user->hasRole('HR Manager');
    $isWarehouseManager = $user->hasRole('Warehouse Manager');
@endphp

@section('content')
<div class="container my-5">
    <h1 class="text-center mb-4" style="font-size: 2.5rem; font-weight: bold; color: #71dd37;">
        Incoming Requests
    </h1>

    @if (session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <input type="text" class="form-control" placeholder="Search requests..." id="searchInput">
        </div>
        <div>
            <input type="text" class="form-control datepicker" placeholder="Select date" id="datePicker">
        </div>
    </div>

    <div class="row">
        @foreach($materials as $request) 
            @if ($isAdmin || 
                ($isLabor && $request->type === 'labor') || 
                ($isHRManager && $request->type === 'labor') || 
                ($isWarehouseManager && in_array($request->type, ['material', 'equipment'])) ||
                (!$isLabor && !$isHRManager && !$isWarehouseManager && !in_array($request->type, ['labor'])))
                
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 rounded" style="border-left: 5px solid {{ $categoryColors['materials'] }};">
                        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #f8f9fa;">
                            <h5 class="mb-0">
                                <a href="{{ route('requests.show', $request->id) }}" class="text-dark text-decoration-none">{{ $request->title }}</a>
                            </h5>
                            <span class="badge 
                                @if($request->status == 'approved') 
                                    bg-success 
                                @elseif($request->status == 'pending') 
                                    bg-warning 
                                @elseif($request->status == 'rejected') 
                                    bg-danger 
                                @endif">
                                {{ ucfirst($request->status) }}
                            </span>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><strong>Activity:</strong> {{ $request->activity->name }}</p>
                            <p class="mb-1"><strong>Type:</strong> {{ ucfirst($request->type) }}</p>
                            <p class="mb-1"><strong>Date:</strong> {{ $request->created_at->format('F j, Y') }}</p>
                            <p class="mb-1">
                                <strong>Finance Status:</strong> 
                                @if($request->finance_status == 'pending')
                                    <span class="text-warning">Waiting for finance approval</span>
                                @elseif($request->finance_status == 'approved')
                                    <span class="text-success">Approved by Finance</span>
                                @else
                                    {{ ucfirst($request->finance_status) }}
                                @endif
                            </p>
                        </div>
                        <div class="card-footer text-center">
                            <a href="{{ route('requests.' . $request->type, $request->id) }}" class="btn btn-primary btn-sm">View Details</a>
                            <a href="{{ route('request-material-to-finance', $request->id) }}" class="btn btn-secondary btn-sm">View Finance Details</a>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <div class="d-flex justify-content-center">
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize date picker
        $('.datepicker').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true
        });

        // Search functionality
        $('#searchInput').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('.row .col-md-4').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });
</script>
@endsection

<style>
.container {
    background-color: #f0f4f8;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

.card-header {
    font-weight: bold;
    background-color: #ffffff;
    border-bottom: 1px solid #e0e0e0;
}

.card-body {
    padding: 20px;
}

h1 {
    text-align: center;
    font-size: 2.5rem;
    font-weight: bold;
    color: #71dd37;
}

.badge {
    font-size: 0.9rem;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
}

.text-warning {
    font-weight: bold;
}

.text-success {
    font-weight: bold;
}
</style>