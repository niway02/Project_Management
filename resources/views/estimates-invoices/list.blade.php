@extends('layout')

@section('title')
<?= get_label('etimates_invoices', 'Estimates/Invoices') ?>
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
                        <?= get_label('etimates_invoices', 'Estimates/Invoices') ?>
                    </li>

                </ol>
            </nav>
        </div>
        <div>
            <a href="{{url('/estimates-invoices/create')}}"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title=" <?= get_label('create_estimate_invoice', 'Create estimate/invoice') ?>"><i class="bx bx-plus"></i></button></a>
        </div>
    </div>

    @if ($estimates_invoices > 0)
    <div class="card mt-4">
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <div class="row mb-3">
                    <!-- Button with Badges -->
                    <div class="col-lg">
                        <!-- <div class="row gy-3"> -->
                        <div class="col-sm-4">
                            <small class="text-light fw-semibold"><?= get_label('estimates', 'Estimates') ?></small>
                            <div class="demo-inline-spacing">
                                @php
                                $possibleStatuses = ['sent', 'accepted', 'draft', 'declined', 'expired', 'not_specified'];
                                @endphp
                                <button type="button" class="btn btn-outline-success status-badge" data-status="" data-type="estimate">
                                    {{ get_label('all','All') }}
                                    <span class="badge bg-white text-success">{{ getStatusCount('', 'estimate') }}</span>
                                </button>
                                @foreach($possibleStatuses as $status)
                                <button type="button" class="btn btn-outline-{{ getStatusColor($status) }} status-badge" data-status="{{ $status }}" data-type="estimate">
                                    {{ get_label($status,ucfirst(str_replace('_', ' ', $status))) }}
                                    <span class="badge bg-white text-{{ getStatusColor($status) }}">{{ getStatusCount($status, 'estimate') }}</span>
                                </button>
                                @endforeach
                            </div>
                        </div>
                        <!-- </div> -->
                    </div>
                </div>

                <div class="row mb-5">
                    <!-- Button with Badges -->
                    <div class="col-lg">
                        <div class="col-sm-4">
                            <small class="text-light fw-semibold"><?= get_label('invoices', 'Invoices') ?></small>

                            <div class="demo-inline-spacing">
                                @php
                                $possibleStatuses = ['partially_paid', 'fully_paid', 'draft', 'cancelled', 'due', 'not_specified'];
                                @endphp
                                <button type="button" class="btn btn-outline-success status-badge" data-status="" data-type="invoice">
                                    {{ get_label('all','All') }}
                                    <span class="badge bg-white text-success">{{ getStatusCount('', 'invoice') }}</span>
                                </button>
                                @foreach($possibleStatuses as $status)
                                <button type="button" class="btn btn-outline-{{ getStatusColor($status) }} status-badge" data-status="{{ $status }}" data-type="invoice">
                                    {{ get_label($status,ucfirst(str_replace('_', ' ', $status))) }}
                                    <span class="badge bg-white text-{{ getStatusColor($status) }}">{{ getStatusCount($status, 'invoice') }}</span>
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="input-group input-group-merge">
                            <input type="text" id="start_date_between" class="form-control" placeholder="<?= get_label('from_date_between', 'From date between') ?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="input-group input-group-merge">
                            <input type="text" id="end_date_between" class="form-control" placeholder="<?= get_label('to_date_between', 'To date between') ?>" autocomplete="off">
                        </div>
                    </div>
                    @if (!isClient())
                    <div class="col-md-4 mb-3">
                        <select class="form-select" id="client_filter" aria-label="Default select example">
                            <option value=""><?= get_label('select_client', 'Select client') ?></option>
                            @foreach ($clients as $client)
                            <option value="{{$client->id}}">{{$client->first_name.' '.$client->last_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="col-md-4">
                        <select class="form-select" id="type_filter" aria-label="Default select example">
                            <option value=""><?= get_label('select_type', 'Select type') ?></option>
                            <option value="estimate"><?= get_label('estimates', 'Estimates') ?></option>
                            <option value="invoice"><?= get_label('invoices', 'Invoices') ?></option>
                        </select>
                    </div>
                </div>
                <input type="hidden" id="start_date_from">
                <input type="hidden" id="start_date_to">

                <input type="hidden" id="end_date_from">
                <input type="hidden" id="end_date_to">

                <input type="hidden" id="hidden_status">

                <input type="hidden" id="data_type" value="estimates-invoices">
                <table id="table" data-toggle="table" data-loading-template="loadingTemplate" data-url="/estimates-invoices/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParams">
                    <thead>
                        <tr>
                            <th data-checkbox="true"></th>
                            <th data-sortable="true" data-formatter="idFormatter"><?= get_label('id', 'ID') ?></th>
                            <th data-sortable="true" data-field="type" data-visible="false"><?= get_label('type', 'Type') ?></th>
                            <th data-sortable="false" data-field="client"><?= get_label('client', 'Client') ?></th>
                            <th data-sortable="true" data-field="from_date"><?= get_label('from_date', 'From date') ?></th>
                            <th data-sortable="true" data-field="to_date"><?= get_label('to_date', 'To date') ?></th>
                            <th data-sortable="true" data-field="total" data-visible="false"><?= get_label('sub_total', 'Sub total') ?></th>
                            <th data-sortable="true" data-field="tax_amount" data-visible="false"><?= get_label('tax', 'Tax') ?></th>
                            <th data-sortable="true" data-field="final_total"><?= get_label('final_total', 'Final total') ?></th>
                            <th data-sortable="true" data-field="status"><?= get_label('status', 'Status') ?></th>
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
    $type = 'Estimates/Invoices';
    $link = 'estimates-invoices/create';
    ?>
    <x-empty-state-card :type="$type" :link="$link" />
    @endif
</div>


<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
    var label_duplicate = '<?= get_label('duplicate', 'Duplicate') ?>';
    var label_estimate_id_prefix = '<?= get_label('estimate_id_prefix', 'ESTMT-') ?>';
    var label_invoice_id_prefix = '<?= get_label('invoice_id_prefix', 'INVC-') ?>';
    var label_sent = '<?= get_label('sent', 'Sent') ?>';
    var label_accepted = '<?= get_label('accepted', 'Accepted') ?>';
    var label_partially_paid = '<?= get_label('partially_paid', 'Partially paid') ?>';
    var label_fully_paid = '<?= get_label('fully_paid', 'Fully paid') ?>';
    var label_draft = '<?= get_label('draft', 'Draft') ?>';
    var label_declined = '<?= get_label('declined', 'Declined') ?>';
    var label_expired = '<?= get_label('expired', 'Expired') ?>';
    var label_cancelled = '<?= get_label('cancelled', 'Cancelled') ?>';
    var label_due = '<?= get_label('due', 'Due') ?>';
</script>
<script src="{{asset('assets/js/pages/estimates-invoices.js')}}"></script>
@endsection