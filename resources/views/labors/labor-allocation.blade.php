@extends('layout')

@section('title')
<?= get_label('labors', 'Labor Allocation') ?>
@endsection

@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between mb-2 mt-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{url('/home')}}"><?= get_label('home', 'Home') ?></a>
                    </li>
                    <li class="breadcrumb-item active">
                        <?= get_label('labor allocation', 'Labor Allocation') ?>
                    </li>

                </ol>
            </nav>
        </div>
        <div>
            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_contract_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title=" <?= get_label('create_contract', 'Create contract') ?>"><i class="bx bx-plus"></i></button></a>
         
        </div>
    </div>
    @if ($totalRecords > 0)
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <select class="form-select" id="project_filter" aria-label="Default select example">
                        <option value=""><?= get_label('select_project', 'Select project') ?></option>
                        @foreach ($projects as $project)
                        <option value="{{$project->id}}">{{$project->title}}</option>
                        @endforeach
                    </select>
                </div>
                @if (!isClient())
                <div class="col-md-4 mb-3">
                    <select class="form-select" id="task_filter" aria-label="Default select example">
                        <option value=""><?= get_label('select_task', 'Select sub task') ?></option>
                        @foreach ($tasks as $task)
                        <option value="{{$task->id}}">{{$task->title}}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-md-4">
                <select class="form-select" id="task_sub_filter" aria-label="Default select example">
                        <option value=""><?= get_label('select_sub_task', 'Select Subtask') ?></option>
                        @foreach ($subTasks as $subTask)
                        <option value="{{$subTask->id}}">{{$subTask->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <input type="hidden" name="start_date_from" id="contract_start_date_from">
            <input type="hidden" name="start_date_to" id="contract_start_date_to">

            <input type="hidden" name="end_date_from" id="contract_end_date_from">
            <input type="hidden" name="end_date_to" id="contract_end_date_to">

            <input type="hidden" id="data_type" value="contracts">
            <input type="hidden" id="data_table" value="contracts_table">

            <div class="table-responsive text-nowrap">
            @csrf
            <div class="table-responsive text-nowrap">  
                <form action="{{ route('materialcosts.equipmentSelection') }}" method="POST">  
                    @csrf  
                    <table class="table">  
                        <thead>  
                            <tr>  
                                <th>No</th>
                                <th>Name </th>  
                                <th>Position</th>  
                                <th>Select</th>  
                            </tr>  
                        </thead>  
                        <tbody>  
                        @foreach($labors as $key => $labor)
                            <tr>  
                               <td>{{ $key + 1 }}</td>
                                <td>{{ $labor->name }}</td>  
                                <td>{{ $labor->position }}</td>  
                                <td>  
                                    <div class="form-check">  
                                        <input class="form-check-input" type="checkbox" name="selected_materials[]"  
                                            value="{{ $labor }}">  
                                        <label class="form-check-label"></label>  
                                    </div>  
                                </td> 
                            </tr>  
                            @endforeach  
                        </tbody>  
                    </table>  
                    <div class="text-center">  
                        <button type="submit" class="btn btn-primary" disabled>Continue</button>  
                    </div>  
                </form>  
            </div>  
    </div>
    @else
    <?php
    $type = 'Contracts'; ?>
    <x-empty-state-card :type="$type" />
    @endif
</div>

<script src="{{asset('assets/js/pages/contracts.js')}}"></script>
@endsection