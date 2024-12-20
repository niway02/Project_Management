@extends('layout')

@section('title')
    {{ get_label('dashboard', 'Dashboard') }}
@endsection

@section('content')
    <style>
        .custom-card {
            background-color: #5a67d8; /* Updated primary color */
            border-radius: 0.5rem;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s; /* Add hover effect */
        }

        .custom-card:hover {
            transform: scale(1.05); /* Slightly enlarge on hover */
        }

        .custom-card .card-body {
            padding: 1.5rem;
            text-align: center; /* Center content */
        }

        .custom-card-title {
            font-size: 1.5rem; /* Adjusted title font size */
            color: white;
            margin-bottom: 0.5rem; /* Spacing below title */
        }

        .custom-footer {
            color: white;
        }

        .custom-footer-value {
            color: #ffc107; /* Bootstrap warning color for footer values */
            font-weight: bold;
        }

        .card-header-custom {
            background-color: #2d3748; /* Darker header */
            color: white;
        }

        .text-highlight {
            color: #ffc107; /* Bright highlight yellow for accent */
        }

        .icon {
            font-size: 3rem; /* Larger icon size */
            color: #ffffff; /* White icon color */
        }

        .container-fluid {
            padding: 20px; /* Added padding to container */
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .custom-card-title {
                font-size: 1.25rem; /* Smaller title on mobile */
            }

            .icon {
                font-size: 2.5rem; /* Smaller icon on mobile */
            }
        }
    </style>

    <div class="container-fluid">
        
        <div class="row mt-4">
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card custom-card shadow-lg rounded">
                    <div class="card-body">
                        <i class="menu-icon tf-icons bx ssss icon"></i>
                        <div class="custom-card-title">Pending Requests</div>
                        <div class="mt-3 custom-footer-value">{{$totalPendingRequest}}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card custom-card shadow-lg rounded">
                    <div class="card-body">
                        <i class="menu-icon tf-icons bx ssss icon"></i>
                        <div class="custom-card-title">Approved Requests</div>
                        <div class="mt-3 custom-footer-value">{{$totalApprovedRequest}}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card custom-card shadow-lg rounded">
                    <div class="card-body">
                        <i class="menu-icon tf-icons bx ssss icon"></i>
                        <div class="custom-card-title">Unallocated Labor</div>
                        <div class="mt-3 custom-footer-value">{{$unallocatedLabor}}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card custom-card shadow-lg rounded">
                    <div class="card-body">
                        <i class="menu-icon tf-icons bx ssss icon"></i>
                        <div class="custom-card-title">allocated Labor</div>
                        <div class="mt-3 custom-footer-value">{{ $allocatedLabor }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <x-labor-card />
        </div>
    </div>
    <script src="{{ asset('assets/js/pages/priority.js') }}"></script>
@endsection