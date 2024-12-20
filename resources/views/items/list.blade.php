@extends('layout')

@section('title')
<?= get_label('items', 'Items') ?>
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
                    <!-- <li class="breadcrumb-item">
                        <a href="{{url('/payslips')}}"><?= get_label('payslips', 'Payslips') ?></a>
                    </li> -->
                    <li class="breadcrumb-item active">
                        <?= get_label('items', 'Items') ?>
                    </li>

                </ol>
            </nav>
        </div>
        <div>
            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_item_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_item', 'Create item') ?>"><i class="bx bx-plus"></i></button></a>
        </div>
    </div>

    @if ($items > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <select class="form-select" id="unit_filter" aria-label="Default select example">
                            <option value=""><?= get_label('select_unit', 'Select unit') ?></option>
                            @foreach ($units as $unit)
                            <option value="{{$unit->id}}">{{$unit->title}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <input type="hidden" id="data_type" value="items">
                <table id="table" data-toggle="table" data-loading-template="loadingTemplate" data-url="/items/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParams">
                    <thead>
                        <tr>
                            <th data-checkbox="true"></th>
                            <th data-sortable="true" data-field="id"><?= get_label('id', 'ID') ?></th>
                            <th data-sortable="true" data-field="title"><?= get_label('title', 'Title') ?></th>
                            <th data-sortable="true" data-field="price"><?= get_label('price', 'Price') ?></th>
                            <th data-sortable="true" data-field="unit_id" data-visible="false"><?= get_label('unit_id', 'Unit ID') ?></th>
                            <th data-sortable="true" data-field="unit"><?= get_label('unit', 'Unit') ?></th>
                            <th data-sortable="true" data-field="description" data-visible="false"><?= get_label('description', 'Description') ?></th>
                            <th data-sortable="true" data-field="created_at" data-visible="false"><?= get_label('created_at', 'Created at') ?></th>
                            <th data-sortable="true" data-field="updated_at" data-visible="false"><?= get_label('updated_at', 'Updated at') ?></th>
                            <th data-formatter="actionsFormatter"><?= get_label('actions', 'Actions') ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @else
    <?php
    $type = 'Items'; ?>
    <x-empty-state-card :type="$type" />
    @endif
</div>

<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
</script>
<script src="{{asset('assets/js/pages/items.js')}}"></script>
@endsection