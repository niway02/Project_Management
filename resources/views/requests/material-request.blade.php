@extends('layout')

@section('title')
    <?= get_label('contracts', 'Material Allocation') ?>
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
                        <li class="breadcrumb-item active">
                            {{ get_label('material_request', 'Material Request') }}
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        @if ($contracts > 0)
            <div class="card">
                <div class="card-body">
                  

                    <input type="hidden" name="start_date_from" id="contract_start_date_from">
                    <input type="hidden" name="start_date_to" id="contract_start_date_to">
                    <input type="hidden" name="end_date_from" id="contract_end_date_from">
                    <input type="hidden" name="end_date_to" id="contract_end_date_to">
                    <input type="hidden" id="data_type" value="contracts">
                    <input type="hidden" id="data_table" value="contracts_table">

                    <div class="table-responsive text-nowrap">
                        <form action="{{ route('materialRequest.selection') }}" method="POST">
                            @csrf
                            <input type="hidden" name="selected_subtask_id" id="selected_subtask_id" value="selectedSubtaskId">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Material</th>
                                        <th>Quantity</th>
                                        <th>Warehouse</th>
                                        <th>Select</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($materialsInventory as $key => $material)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $material->material->item }}</td>
                                            <td>{{ $material->quantity }}</td>
                                            <td>{{ $material->warehouse->name }}</td>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="selected_materials[]" value="{{ $material }}">
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
        @else
            <x-empty-state-card :type="'Contracts'" />
        @endif
    </div>

    <script src="{{ asset('assets/js/pages/contracts.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const taskSubFilter = document.getElementById('task_sub_filter');
            const selectedSubtaskInput = document.getElementById('selected_subtask_id');

            taskSubFilter.addEventListener('change', function() {
                selectedSubtaskInput.value = this.value;
            });
        });
    </script>
@endsection