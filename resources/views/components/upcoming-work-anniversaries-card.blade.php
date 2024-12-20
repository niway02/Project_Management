<!-- projects card -->

<div class="align-items-baseline d-flex gap-1">
    <div class="col-md-4">
        <select class="form-select" id="wa_user_filter" aria-label="Default select example">
            <option value=""><?= get_label('select_member', 'Select member') ?></option>
            @foreach ($users as $user)
            <option value="{{$user->id}}">{{$user->first_name.' '.$user->last_name}}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3 col-md-4">
        <div class="input-group input-group-merge">
            <input type="number" id="upcoming_days_wa" name="upcoming_days" class="form-control" min="0" placeholder="<?= get_label('till_upcoming_days_def_30', 'Till upcoming days : default 30') ?>" autocomplete="off">
        </div>
    </div>
    <div class="col-md-1">
        <div>
            <button type="button" id="upcoming_days_wa_filter" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('filter', 'Filter') ?>"><i class='bx bx-filter-alt'></i></button>

        </div>
    </div>
</div>
<div class="table-responsive text-nowrap">
    <input type="hidden" id="data_type" value="users">
    <input type="hidden" id="data_table" value="wa_table">
    <table id="wa_table" data-toggle="table" data-loading-template="loadingTemplate" data-url="/home/upcoming-work-anniversaries" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="doj" data-sort-order="asc" data-mobile-responsive="true" data-query-params="queryParamsUpcomingWa">
        <div class="alert alert-info alert-dismissible" role="alert"></a>{{get_label('delete_selected_will_delete_selected_team_members_alert','Delete selected will delete selected team members.')}}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
        <thead>
            <tr>
                <th data-checkbox="true"></th>
                <th data-field="id"><?= get_label('id', 'ID') ?></th>
                <th data-field="member"><?= get_label('member', 'Member') ?></th>
                <th data-field="wa_date" data-sortable="true"><?= get_label('work_anniversary_date', 'Work anniversary date') ?></th>
                <th data-field="days_left"><?= get_label('days_left', 'Days left') ?></th>
            </tr>
        </thead>
    </table>
</div>