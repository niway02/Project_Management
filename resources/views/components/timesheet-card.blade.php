@if (is_countable($timesheet) && count($timesheet) > 0)
<div class="card">
    <div class="card-body">
        {{$slot}}
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="input-group input-group-merge">
                    <input type="text" id="timesheet_start_date_between" class="form-control" placeholder="<?= get_label('start_date_between', 'Start date between') ?>" autocomplete="off">
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="input-group input-group-merge">
                    <input type="text" id="timesheet_end_date_between" class="form-control" placeholder="<?= get_label('end_date_between', 'End date between') ?>" autocomplete="off">
                </div>
            </div>
            @if(isAdminOrHasAllDataAccess())
            <div class="col-md-4">
                <select class="form-select" id="timesheet_user_filter" aria-label="Default select example">
                    <option value=""><?= get_label('select_user', 'Select user') ?></option>
                    @foreach ($users as $user)
                    <option value="{{$user->id}}">{{$user->first_name.' '.$user->last_name}}</option>
                    @endforeach
                </select>
            </div>
            @endif

        </div>
        <input type="hidden" id="timesheet_start_date_from">
        <input type="hidden" id="timesheet_start_date_to">

        <input type="hidden" id="timesheet_end_date_from">
        <input type="hidden" id="timesheet_end_date_to">
        <div class="table-responsive text-nowrap">
            <input type="hidden" id="data_type" value="time-tracker">
            <input type="hidden" id="data_table" value="timesheet_table">
            <table id="timesheet_table" data-toggle="table" data-loading-template="loadingTemplate" data-url="/time-tracker/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="time_tracker_query_params">
                <thead>
                    <tr>
                        <th data-checkbox="true"></th>
                        <th data-sortable="true" data-field="id"><?= get_label('id', 'ID') ?></th>
                        <th data-field="user" data-formatter="timeSheetUserFormatter"><?= get_label('user', 'User') ?></th>
                        <th data-sortable="true" data-field="start_date_time"><?= get_label('started_at', 'Started at') ?></th>
                        <th data-sortable="true" data-field="end_date_time"><?= get_label('ended_at', 'Ended at') ?></th>
                        <th data-sortable="false" data-field="duration"><?= get_label('duration', 'Duration') ?></th>
                        <th data-sortable="true" data-field="message"><?= get_label('message', 'Message') ?></th>
                        <th data-sortable="true" data-field="created_at" data-visible="false"><?= get_label('created_at', 'Created at') ?></th>
                        <th data-sortable="true" data-field="updated_at" data-visible="false"><?= get_label('updated_at', 'Updated at') ?></th>
                        @if (getAuthenticatedUser()->can('delete_timesheet'))
                        <th data-formatter="timeSheetActionsFormatter"><?= get_label('actions', 'Actions') ?></th>
                        @endif
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@else
<?php
$type = 'Timesheet'; ?>
<x-empty-state-card :type="$type" />
@endif