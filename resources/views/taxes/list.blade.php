@extends('layout')

@section('title')
<?= get_label('taxes', 'Taxes') ?>
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
                        <?= get_label('taxes', 'Taxes') ?>
                    </li>

                </ol>
            </nav>
        </div>
        <div>
            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_tax_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_tax', 'Create tax') ?>"><i class="bx bx-plus"></i></button></a>
        </div>
    </div>

    @if ($taxes > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <input type="hidden" id="data_type" value="taxes">
                <table id="table" data-toggle="table" data-loading-template="loadingTemplate" data-url="/taxes/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParams">
                    <thead>
                        <tr>
                            <th data-checkbox="true"></th>
                            <th data-sortable="true" data-field="id"><?= get_label('id', 'ID') ?></th>
                            <th data-sortable="true" data-field="title"><?= get_label('title', 'Title') ?></th>
                            <th data-sortable="true" data-field="type"><?= get_label('type', 'Type') ?></th>
                            <th data-sortable="true" data-field="amount"><?= get_label('amount', 'Amount') ?></th>
                            <th data-sortable="true" data-field="percentage"><?= get_label('percentage', 'Percentage') ?></th>
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
    $type = 'Taxes'; ?>
    <x-empty-state-card :type="$type" />
    @endif
</div>

<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
    var decimal_points = <?= intval($general_settings['decimal_points_in_currency'] ?? '2') ?>;
</script>
<script src="{{asset('assets/js/pages/taxes.js')}}"></script>
@endsection