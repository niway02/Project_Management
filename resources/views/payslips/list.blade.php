@extends('layout')

@section('title')
<?= get_label('payslips', 'Payslips') ?>
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
                        <?= get_label('payslips', 'Payslips') ?>
                    </li>

                </ol>
            </nav>
        </div>
        <div>
            <a href="{{url('/payslips/create')}}"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title=" <?= get_label('create_payslip', 'Create payslip') ?>"><i class="bx bx-plus"></i></button></a>
        </div>
    </div>

    @if ($payslips > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <input class="form-control" type="month" id="filter_payslip_month" name="month">

                    </div>
                    @if(isAdminOrHasAllDataAccess())
                    <div class="col-md-4 mb-3">
                        <select class="form-select" id="user_filter" aria-label="Default select example">
                            <option value=""><?= get_label('select_member', 'Select member') ?></option>
                            @foreach ($users as $user)
                            <option value="{{$user->id}}">{{$user->first_name.' '.$user->last_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <select class="form-select" id="created_by_filter" aria-label="Default select example">
                            <option value=""><?= get_label('select_created_by', 'Select created by') ?></option>
                            @foreach ($users as $user)
                            <option value="{{$user->id}}">{{$user->first_name.' '.$user->last_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="col-md-4">
                        <select class="form-select" id="status_filter" aria-label="Default select example">
                            <option value=""><?= get_label('select_payment_status', 'Select payment status') ?></option>
                            <option value="1"><?= get_label('paid', 'Paid') ?></option>
                            <option value="0"><?= get_label('unpaid', 'Unpaid') ?></option>
                        </select>
                    </div>
                </div>
                <input type="hidden" id="data_type" value="payslips">
                <input type="hidden" id="data_table" value="payslips_table">
                <table id="payslips_table" data-toggle="table" data-loading-template="loadingTemplate" data-url="/payslips/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParams">
                    <thead>
                        <tr>
                            <th data-checkbox="true"></th>
                            <th data-sortable="true" data-formatter="idFormatter"><?= get_label('id', 'ID') ?></th>
                            <th data-sortable="false" data-field="user"><?= get_label('member', 'Member') ?></th>
                            <th data-sortable="true" data-field="month"><?= get_label('month', 'Month') ?></th>
                            <th data-sortable="true" data-field="basic_salary"><?= get_label('basic_salary', 'Basic salary') ?></th>
                            <th data-sortable="true" data-field="working_days" data-visible="false"><?= get_label('working_days', 'Working days') ?></th>
                            <th data-sortable="true" data-field="lop_days" data-visible="false"><?= get_label('lop_days', 'Loss of pay days') ?></th>
                            <th data-sortable="true" data-field="paid_days" data-visible="false"><?= get_label('paid_days', 'Paid days') ?></th>
                            <th data-sortable="true" data-field="leave_deduction" data-visible="false"><?= get_label('leave_deduction', 'Leave deduction') ?></th>
                            <th data-sortable="true" data-field="ot_hours" data-visible="false"><?= get_label('ot_hours', 'Over time hours') ?></th>
                            <th data-sortable="true" data-field="ot_rate" data-visible="false"><?= get_label('ot_rate', 'Over time rate') ?></th>
                            <th data-sortable="true" data-field="ot_payment" data-visible="false"><?= get_label('ot_payment', 'Over time payment') ?></th>
                            <th data-sortable="true" data-field="incentives" data-visible="false"><?= get_label('incentives', 'Incentives') ?></th>
                            <th data-sortable="true" data-field="bonus" data-visible="false"><?= get_label('bonus', 'Bonus') ?></th>
                            <th data-sortable="true" data-field="total_allowance" data-visible="false"><?= get_label('total_allowance', 'Total allowance') ?></th>
                            <th data-sortable="true" data-field="total_deductions" data-visible="false"><?= get_label('total_deductions', 'Total deductions') ?></th>
                            <!-- <th data-sortable="true" data-field="total_earnings" data-visible="false"><?= get_label('total_earnings', 'Total earnings') ?></th> -->
                            <th data-sortable="true" data-field="net_pay"><?= get_label('net_pay', 'Net pay') ?></th>
                            <th data-sortable="true" data-field="payment_method" data-visible="false"><?= get_label('payment_method', 'Payment method') ?></th>
                            <th data-sortable="true" data-field="payment_date" data-visible="false"><?= get_label('payment_date', 'Payment date') ?></th>
                            <th data-sortable="true" data-field="status"><?= get_label('status', 'Status') ?></th>
                            <th data-sortable="true" data-field="note" data-visible="false"><?= get_label('note', 'Note') ?></th>
                            <th data-sortable="false" data-field="created_by" data-visible="false"><?= get_label('created_by', 'Created by') ?></th>
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
    $type = 'Payslips'; ?>
    <x-empty-state-card :type="$type" />
    @endif
</div>


<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
    var label_duplicate = '<?= get_label('duplicate', 'Duplicate') ?>';
    var label_payslip_id_prefix = '<?= get_label('payslip_id_prefix', 'PSL-') ?>';
    var decimal_points = <?= intval($general_settings['decimal_points_in_currency'] ?? '2') ?>;
</script>
<script src="{{asset('assets/js/pages/payslips.js')}}"></script>
@endsection