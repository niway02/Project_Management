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
            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_leave_request_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_leave_request', 'Create leave request') ?>"><i class="bx bx-plus"></i></button></a>
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
                <table id="lr_table" data-toggle="table" data-loading-template="loadingTemplate" data-url="/leave-requests/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParamsLr">
                    <thead>
                        <tr>
                            <th data-checkbox="true"></th>
                            <th data-sortable="true" data-field="id"><?= get_label('id', 'ID') ?></th>
                            <th data-sortable="false" data-field="user_name"><?= get_label('member', 'Member') ?></th>
                            <th data-field="from_date" data-sortable="true"><?= get_label('from_date', 'From date') ?></th>
                            <th data-field="to_date" data-sortable="true"><?= get_label('to_date', 'To date') ?></th>
                            <th data-sortable="false" data-field="duration"><?= get_label('duration', 'Duration') ?></th>
                            <th data-sortable="true" data-field="reason"><?= get_label('reason', 'Reason') ?></th>
                            <th data-sortable="true" data-field="status"><?= get_label('status', 'Status') ?></th>
                            <th data-sortable="true" data-field="action_by"><?= get_label('action_by', 'Action by') ?></th>
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
</div>
<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
</script>
<script src="{{asset('assets/js/pages/leave-requests.js')}}"></script>
@endsection