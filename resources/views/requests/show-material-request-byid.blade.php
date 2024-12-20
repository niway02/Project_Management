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
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Material</th>
                                    <th>Unit</th>
                                    <th>Rate with VAT</th>
                                    <th>Available Quantity</th>
                                    <th>Requested Quantity</th>
                                    <th>Approved Quantity</th>
                                    <th>Remark</th>
                                    <th>Select</th>
                                </tr>
                            </thead>
                            <tbody>
                              
                            </tbody>
                        </table>
                   
                </div>
            </div>
        </div>
    </div>
@endsection