<!-- tasks -->
@php
$flag = (Request::segment(1) == 'home' ||
Request::segment(1) == 'users' ||
Request::segment(1) == 'clients' ||
(Request::segment(1) == 'projects' && Request::segment(2) == 'information' && Request::segment(3) != null)) ? 0 : 1;

@endphp
@if ($tasks > 0 || (isset($emptyState) && $emptyState == 0))
<div class="<?= $flag == 1 ? 'card ' : '' ?>mt-2">
    @endif
    @if ($flag == 1 && ($tasks > 0 || (isset($emptyState) && $emptyState == 0)))
    <div class="card-body">
        @endif

        {{$slot}}
        @if ($tasks > 0 || (isset($emptyState) && $emptyState == 0))
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="input-group input-group-merge">
                    <input type="text" id="task_start_date_between" name="task_start_date_between" class="form-control" placeholder="<?= get_label('start_date_between', 'Start date between') ?>" autocomplete="off">
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="input-group input-group-merge">
                    <input type="text" id="task_end_date_between" name="task_end_date_between" class="form-control" placeholder="<?= get_label('end_date_between', 'End date between') ?>" autocomplete="off">
                </div>
            </div>
            @if (getAuthenticatedUser()->can('manage_projects'))
            @isset($projects)
            <div class="col-md-4 mb-3">
                <select class="form-select" id="tasks_project_filter" aria-label="Default select example">
                    <option value=""><?= get_label('select_project', 'Select project') ?></option>
                    @foreach ($projects as $proj)
                    <option value="{{$proj->id}}" @if(request()->has('project') && request()->project == $proj->id) selected @endif>{{$proj->title}}</option>
                    @endforeach
                </select>
            </div>
            @endisset
            @endif
            @if(isAdminOrHasAllDataAccess())
            @if(explode('_',$id)[0] !='client' && explode('_',$id)[0] !='user')
            <div class="col-md-4 mb-3">
                <select class="form-select" id="tasks_user_filter" aria-label="Default select example">
                    <option value=""><?= get_label('select_user', 'Select user') ?></option>
                    @foreach ($users as $user)
                    <option value="{{$user->id}}">{{$user->first_name.' '.$user->last_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <select class="form-select" id="tasks_client_filter" aria-label="Default select example">
                    <option value=""><?= get_label('select_client', 'Select client') ?></option>
                    @foreach ($clients as $client)
                    <option value="{{$client->id}}">{{$client->first_name.' '.$client->last_name}}</option>
                    @endforeach
                </select>
            </div>
            @endif
            @endif

            <div class="col-md-4">
                <select class="form-select" id="task_status_filter" aria-label="Default select example">
                    <option value=""><?= get_label('select_status', 'Select status') ?></option>
                    @foreach ($statuses as $status)
                    @php
                    $selected = (request()->has('status') && request()->status == $status->id) ? 'selected' : '';
                    @endphp
                    <option value="{{ $status->id }}" {{ $selected }}>{{ $status->title }}</option>
                    @endforeach
                </select>
            </div>


        </div>

        <input type="hidden" name="task_start_date_from" id="task_start_date_from">
        <input type="hidden" name="task_start_date_to" id="task_start_date_to">

        <input type="hidden" name="task_end_date_from" id="task_end_date_from">
        <input type="hidden" name="task_end_date_to" id="task_end_date_to">

        <div class="table-responsive text-nowrap">
            <input type="hidden" id="data_type" value="tasks">
            <input type="hidden" id="data_table" value="task_table">
            <table id="task_table" data-toggle="table" data-loading-template="loadingTemplate" data-url="/usertasks/list{{ !empty($id) ? '/' . $id : '' }}" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParamsTasks">
                <thead>
                    <tr>
                        <th data-checkbox="true"></th>
                        <th data-sortable="true" data-field="id"><?= get_label('id', 'ID') ?></th>
                        <th data-sortable="true" data-field="project_id"><?= get_label('project', 'Project') ?></th>
                        <th data-sortable="true" data-formatter="titleFormatter"><?= get_label('task', 'Task') ?></th>
                        <th data-field="users" data-formatter="TaskUserFormatter"><?= get_label('users', 'Users') ?></th>
                        <th data-field="clients" data-formatter="TaskClientFormatter"><?= get_label('clients', 'Clients') ?></th>
                        <th data-field="priority_id" data-formatter="PriorityFormatter"><?= get_label('priority', 'priority') ?></th>
                        <th data-field="progress" class="progress-column" data-formatter="progressFormatter"><?= get_label('progress', 'Progress') ?></th>
                        <th data-field="status" data-formatter="StatusFormatter" ><?= get_label('status', 'Status') ?></th>
                      
                        <th data-sortable="true" data-field="start_date"><?= get_label('starts_at', 'Starts at') ?></th>
                        <th data-sortable="true" data-field="end_date"><?= get_label('ends_at', 'Ends at') ?></th>
                        <th data-sortable="true" data-field="created_at" data-visible="false"><?= get_label('created_at', 'Created at') ?></th>
                        <th data-sortable="true" data-field="updated_at" data-visible="false"><?= get_label('updated_at', 'Updated at') ?></th>
                        <th data-sortable="true" data-field="status_id" ><?= get_label('actions', 'Actions') ?></th>
                        @if(getAuthenticatedUser()->hasVerifiedEmail() && getAuthenticatedUser()->hasRole('admin'))
                        <th data-formatter="actionFormatter"><?= get_label('actions', 'Actions') ?></th>
                        @endif
                    </tr>
                </thead>
            </table>
        </div>
        @else
        @if(!isset($emptyState) || $emptyState != 0)
        <?php
        $type = 'Tasks';
        ?>
        <x-empty-state-card :type="$type" />
        @endif
        @endif
        @if ($flag == 1 && ($tasks > 0 || (isset($emptyState) && $emptyState == 0)))
    </div>
    @endif
    @if ($tasks > 0 || (isset($emptyState) && $emptyState == 0))
</div>
@endif

<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
    var label_duplicate = '<?= get_label('duplicate', 'Duplicate') ?>';
    var label_not_assigned = '<?= get_label('not_assigned', 'Not assigned') ?>';
    var add_favorite = '<?= get_label('add_favorite', 'Click to mark as favorite') ?>';
    var remove_favorite = '<?= get_label('remove_favorite', 'Click to remove from favorite') ?>';
    var id = '<?= $id ?>';
</script>
<script src="{{asset('assets/js/pages/tasks.js')}}"></script>