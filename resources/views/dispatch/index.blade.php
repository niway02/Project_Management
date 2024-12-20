@extends('layout')

@section('title')
<?= get_label('leave_requests', 'Leave requests') ?>
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
                        <?= get_label('leave_requests', 'Leave requests') ?>
                    </li>

                </ol>
            </nav>
        </div>
        <div>
            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_damage_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_leave_request', 'Create leave request') ?>"><i class="bx bx-plus"></i></button></a>
        </div>
    </div>
    @php
    $isLeaveEditor = \App\Models\LeaveEditor::where('user_id', $auth_user->id)->exists();
    @endphp
    <div class="row">
        <div class="d-flex justify-content-center">
            @if ($auth_user->hasRole('admin'))
            <form action="{{url('/leave-requests/update-editors')}}" class="form-submit-event" method="POST">
                <input type="hidden" name="redirect_url" value="/leave-requests">
                <input type="hidden" name="dnr">
                <div class="col-12 mb-3">
                    <label class="form-label" for="user_id"><?= get_label('select_leave_editors', 'Select leave editors') ?> <small class="text-muted">(Like admin, selected users will be able to update and create leaves for other members)</small></label>
                    <div class="input-group">
                        <select id="" class="form-control js-example-basic-multiple" name="user_ids[]" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                            @foreach($users as $user)
                            <?php if (!$user->hasRole('admin')) { ?>
                                <option value="{{$user->id}}" @if(count($user->leaveEditors) > 0) selected @endif>{{$user->first_name}} {{$user->last_name}}</option>
                            <?php } ?>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" id="submit_btn" class="btn btn-primary my-2"><?= get_label('update', 'Update') ?></button>
                    </div>
                </div>
            </form>
            @endif
            @if ($isLeaveEditor)
            <span class="badge bg-primary"><?= get_label('leave_editor_info', 'You are leave editor') ?></span>
            @endif
        </div>
    </div>

    @if ($leave_requests > 0)
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="input-group input-group-merge">
                        <input type="text" id="lr_start_date_between" class="form-control" placeholder="<?= get_label('from_date_between', 'From date between') ?>" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="input-group input-group-merge">
                        <input type="text" id="lr_end_date_between" class="form-control" placeholder="<?= get_label('to_date_between', 'To date between') ?>" autocomplete="off">
                    </div>
                </div>
                @if (is_admin_or_leave_editor())
                <div class="col-md-4 mb-3">
                    <select class="form-select" id="lr_user_filter" aria-label="Default select example">
                        <option value=""><?= get_label('select_member', 'Select member') ?></option>
                        @foreach ($users as $user)
                        <option value="{{$user->id}}">{{$user->first_name.' '.$user->last_name}}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-md-4 mb-3">
                    <select class="form-select" id="lr_action_by_filter" aria-label="Default select example">
                        <option value=""><?= get_label('action_by', 'Action by') ?></option>
                        @foreach ($users as $user)
                        <option value="{{$user->id}}">{{$user->first_name.' '.$user->last_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-select" id="lr_status_filter" aria-label="Default select example">
                        <option value=""><?= get_label('select_status', 'Select status') ?></option>
                        <option value="pending"><?= get_label('pending', 'Pending') ?></option>
                        <option value="approved"><?= get_label('approved', 'Approved') ?></option>
                        <option value="rejected"><?= get_label('rejected', 'Rejected') ?></option>
                    </select>
                </div>
            </div>

            <input type="hidden" name="start_date_from" id="lr_start_date_from">
            <input type="hidden" name="start_date_to" id="lr_start_date_to">

            <input type="hidden" name="end_date_from" id="lr_end_date_from">
            <input type="hidden" name="end_date_to" id="lr_end_date_to">
            <div class="table-responsive text-nowrap">
                @if (is_admin_or_leave_editor())
                <input type="hidden" id="data_type" value="leave-requests">
                <input type="hidden" id="data_table" value="lr_table">
                @endif
                <table id="lr_table" data-toggle="table" data-loading-template="loadingTemplate" data-url="/damages/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParamsLr">
                    <thead>
                        <tr>
                            <th data-checkbox="true"></th>
                            <th data-sortable="true" data-field="id"><?= get_label('id', 'ID') ?></th>
                            <th data-sortable="true" data-field="warehouse"><?= get_label('warehouse', 'warehouse') ?></th>
                            <th data-sortable="true" data-field="damage_date"><?= get_label('damaged_date', 'Damaged date') ?></th>
                            <th data-sortable="true" data-field="quantity_damaged"><?= get_label('quantity_damaged"', 'Damaged Qty') ?></th>
                            <th data-sortable="true" data-field="approved_by"><?= get_label('approved_by', 'Approved by') ?></th>
                            <th data-sortable="true" data-field="status"><?= get_label('status', 'Status') ?></th>
                            <th data-sortable="true" data-field="created_at" data-visible="false"><?= get_label('created_at', 'Created at') ?></th>
                            <th data-sortable="true" data-field="updated_at" data-visible="false"><?= get_label('updated_at', 'Updated at') ?></th>
                            @if (is_admin_or_leave_editor())
                            <th data-formatter="actionsFormatter"><?= get_label('actions', 'Actions') ?></th>
                            @endif
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @else
    <?php
    $type = 'Leave requests'; ?>
    <x-empty-state-card :type="$type" />

    @endif
    <div class="modal fade" id="create_damage_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content form-submit-event" action="{{ url('/damages/store') }}" method="POST">
            @csrf
            <input type="hidden" name="dnr">
            <input type="hidden" name="table" value="lr_table">
            <input type="hidden" name="type" value="material"> <!-- Hidden input added -->
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">{{ get_label('create_damage_request', 'Create Damage Request') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="item_id" class="form-label">{{ get_label('item_id', 'Item ID') }} <span class="asterisk">*</span></label>
                        <input type="text" id="item_id" name="item_id" class="form-control" placeholder="{{ get_label('enter_item_id', 'Enter Item ID') }}" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="warehouse_id" class="form-label">{{ get_label('warehouse_id', 'Warehouse ID') }} <span class="asterisk">*</span></label>
                        <input type="text" id="warehouse_id" name="warehouse_id" class="form-control" placeholder="{{ get_label('enter_warehouse_id', 'Enter Warehouse ID') }}" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="approved_by" class="form-label">{{ get_label('approved_by', 'Approved By') }} <span class="asterisk">*</span></label>
                        <input type="text" id="approved_by" name="approved_by" class="form-control" placeholder="{{ get_label('enter_approved_by', 'Enter Approved By') }}" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="quantity_damaged" class="form-label">{{ get_label('quantity_damaged', 'Quantity Damaged') }} <span class="asterisk">*</span></label>
                        <input type="number" id="quantity_damaged" name="quantity_damaged" class="form-control" placeholder="{{ get_label('enter_quantity_damaged', 'Enter Quantity Damaged') }}" required>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="issue" class="form-label">{{ get_label('issue', 'Issue') }} <span class="asterisk">*</span></label>
                        <textarea class="form-control" id="issue" name="issue" placeholder="{{ get_label('please_enter_issue', 'Please enter issue') }}" required></textarea>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="damage_date" class="form-label">{{ get_label('damage_date', 'Damage Date') }} <span class="asterisk">*</span></label>
                        <input type="date" id="damage_date" name="damage_date" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    {{ get_label('close', 'Close') }}
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary">{{ get_label('create', 'Create') }}</button>
            </div>
        </form>
    </div>
</div>

</div>
<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
</script>
<script src="{{asset('assets/js/pages/leave-requests.js')}}"></script>
@endsection