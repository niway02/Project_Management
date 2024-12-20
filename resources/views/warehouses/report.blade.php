@extends('layout')

@section('title')
    {{ get_label('warehouses', 'Warehouses') }} - {{ get_label('list_view', 'List view') }}
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
                            <a href="{{ url('/departments') }}">{{ get_label('warehouses', 'Warehouses') }}</a>
                        </li> 
                    </ol>
                </nav>
            </div>
      
            <input type="hidden" id="type">
            <input type="hidden" id="typeId">
        </div>

        <x-warehouses-card :Warehouses="$warehouses" />
    </div>
@endsection