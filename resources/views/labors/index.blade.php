@extends('layout')

@section('title')
    <?= get_label('Labors', 'Labors') ?>
@endsection

@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between mb-2 mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-style1">
                <li class="breadcrumb-item">
                    <a href="{{ url('/home') }}">{{ get_label('home', 'Home') }}</a>
                </li>
                <li class="breadcrumb-item active">
                    {{ get_label('labors', 'Labors') }}
                </li>
            </ol>
        </nav>
        <div>
            <button type="button" class="btn btn-sm btn-primary" 
                    data-bs-toggle="modal" 
                    data-bs-target="#create_leave_request_modal" 
                    data-bs-toggle="tooltip" 
                    data-bs-placement="right" 
                    title="{{ get_label('create_leave_request', 'Create leave request') }}">
                <i class="bx bx-plus"></i>
            </button>
        </div>
    </div>

    <div class="row">
        <div class="d-flex justify-content-center">
            <form action="{{ url('/leave-requests/update-editors') }}" class="form-submit-event" method="POST">
                @csrf
                <input type="hidden" name="redirect_url" value="/leave-requests">
                <input type="hidden" name="dnr">
                <!-- You can add more inputs as needed -->
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                @if (is_admin_or_leave_editor())
                <div class="col-md-4 mb-3">
                    <select class="form-select" id="lr_user_filter" aria-label="{{ get_label('select_member', 'Select member') }}">
                        <option value="">{{ get_label('select_member', 'Select member') }}</option>
                        @foreach ($labors as $user)
                        <option value="{{ $user->id }}">{{ $user->first_name . ' ' . $user->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="col-md-4">
                    <select class="form-select" id="lr_status_filter" aria-label="{{ get_label('select_status', 'Select status') }}">
                        <option value="">{{ get_label('select_status', 'Select status') }}</option>
                        <option value="allocate">{{ get_label('allocate', 'allocate') }}</option>
                        <option value="	unallocated">{{ get_label('	unallocated', '	unallocated') }}</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive text-nowrap">
                @if (is_admin_or_leave_editor())
                <input type="hidden" id="data_type" value="leave-requests">
                <input type="hidden" id="data_table" value="lr_table">
                @endif
                
                <table id="lr_table" 
                       data-toggle="table" 
                       data-loading-template="loadingTemplate" 
                       data-url="/labor/list" 
                       data-icons-prefix="bx" 
                       data-icons="icons" 
                       data-show-refresh="true" 
                       data-total-field="total" 
                       data-trim-on-search="false" 
                       data-data-field="rows" 
                       data-page-list="[5, 10, 20, 50, 100, 200]" 
                       data-search="true" 
                       data-side-pagination="server" 
                       data-show-columns="true" 
                       data-pagination="true" 
                       data-sort-name="id" 
                       data-sort-order="desc" 
                       data-mobile-responsive="true" 
                       data-query-params="queryParamsLr">
                    <thead>
                        <tr>
                            <th data-checkbox="true"></th>
                            <th data-sortable="true" data-field="id">{{ get_label('id', 'ID') }}</th>
                            <th data-sortable="true" data-field="skills">{{ get_label('skills', 'skills') }}</th>
                            <th data-sortable="true" data-field="status">{{ get_label('status', 'status') }}</th>
                            @if (is_admin_or_leave_editor())
                            <th data-formatter="actionsFormatter">{{ get_label('actions', 'Actions') }}</th>
                            @endif
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Create Leave Request Modal -->
<div class="modal fade" id="create_leave_request_modal" tabindex="-1" aria-labelledby="createLeaveRequestLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createLeaveRequestLabel">{{ get_label('create_leave_request', 'Create Leave Request') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createLeaveRequestForm">
                    <!-- Form fields for leave request creation go here -->
                    <!-- Example field -->
                    <div class="mb-3">
                        <label for="leaveReason" class="form-label">{{ get_label('reason', 'Reason') }}</label>
                        <input type="text" class="form-control" id="leaveReason" name="reason" required>
                    </div>
                    <!-- Add more fields as necessary -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ get_label('close', 'Close') }}</button>
                <button type="submit" class="btn btn-primary" form="createLeaveRequestForm">{{ get_label('submit', 'Submit') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
</script>
<script src="{{ asset('assets/js/pages/leave-requests.js') }}"></script>
@endsection