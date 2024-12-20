<!-- Activity Section -->
@php
$flag = (Request::segment(1) == 'home' ||
(Request::segment(1) == 'projects' && Request::segment(2) == 'information' && Request::segment(3) != null)) ? 0 : 1;
@endphp

@if ($activities > 0 || (isset($emptyState) && $emptyState == 0))
<div class="{{ $flag == 1 ? 'card ' : '' }}mt-2">
@endif

@if ($flag == 1 && ($activities > 0 || (isset($emptyState) && $emptyState == 0)))
<div class="card-body">
@endif

{{$slot}}

@if ($activities > 0 || (isset($emptyState) && $emptyState == 0))
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

    <div class="col-md-4">
        <div class="btn-group" role="group" aria-label="Report generation buttons">
            <button type="button" class="btn btn-primary" onclick="downloadReport('pdf')">Download PDF</button>
            <button type="button" class="btn btn-success" onclick="downloadReport('csv')">Download CSV</button>
            <button type="button" class="btn btn-info" onclick="printContent()">Print</button>
        </div>
    </div>

    <input type="hidden" name="activity_start_date_from" id="task_start_date_from">
    <input type="hidden" name="task_start_date_to" id="task_start_date_to">
    <input type="hidden" name="task_end_date_from" id="task_end_date_from">
    <input type="hidden" name="task_end_date_to" id="task_end_date_to">

    <form id="activitySelectionForm" action="{{ route('activity.checklist') }}" method="POST">
        @csrf
        <input type="hidden" name="selected_tasks" id="selected_tasks"> <!-- Hidden input for selected tasks -->
        
        <div class="table-responsive text-nowrap">
            <table id="task_table" 
                   data-toggle="table" 
                   data-loading-template="loadingTemplate" 
                   data-url="/activity/listing/{{$id}}" 
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
                   data-query-params="queryParamsTasks">
                <thead>
                    <tr>
                        <th data-checkbox="true"></th>
                        <th data-sortable="true" data-field="id"><?= get_label('id', 'ID') ?></th>
                        <th data-sortable="true" data-field="wbs"><?= get_label('wbs', 'WBS') ?></th>
                        <th data-sortable="true" data-field="activity_name"><?= get_label('activity_name', 'Activity Name') ?></th>
                        <th data-sortable="true" data-field="priority"><?= get_label('priority', 'Priority') ?></th>
                        <th data-sortable="true" data-field="start_date"><?= get_label('starts_at', 'Starts At') ?></th>
                        <th data-sortable="true" data-field="end_date"><?= get_label('ends_at', 'Ends At') ?></th>
                        <th data-formatter="durationFormatter"><?= get_label('duration', 'Duration') ?></th>
                        <th data-field="progress" class="progress-column" data-formatter="progressFormatter"><?= get_label('progress', 'Progress') ?></th>
                        <th data-sortable="true" data-field="status"><?= get_label('status', 'Status') ?></th>
                        <th data-sortable="true" data-field="aproval_status"><?= get_label('aproval_status', 'Approval') ?></th>
                        <th data-sortable="true" data-field="created_at" data-visible="false"><?= get_label('created_at', 'Created At') ?></th>
                        <th data-sortable="true" data-field="updated_at" data-visible="false"><?= get_label('updated_at', 'Updated At') ?></th>
                        @if(getAuthenticatedUser()->hasVerifiedEmail() && getAuthenticatedUser()->hasRole('admin'))
                        <th data-formatter="actionFormatter"><?= get_label('actions', 'Actions') ?></th>
                        @endif
                    </tr>
                </thead>
                <!-- No tbody needed; DataTables handles it -->
            </table>
            <button type="submit" class="btn btn-primary mt-3">Check</button>
        </div>
    </form>

@else
    @if(!isset($emptyState) || $emptyState != 0)
        <?php
        $type = 'Tasks';
        ?>
        <x-empty-state-card :type="$type" />
    @endif
@endif

@if ($flag == 1 && ($activities > 0 || (isset($emptyState) && $emptyState == 0)))
</div>
@endif

@if ($activities > 0 || (isset($emptyState) && $emptyState == 0))
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

    // Gather selected task IDs on form submission
    document.getElementById('activitySelectionForm').onsubmit = function() {
        const selectedTasks = [];
        const checkboxes = document.querySelectorAll('#task_table input[type="checkbox"]:checked');
        checkboxes.forEach((checkbox) => {
            // Assuming each row has a data-id attribute
            selectedTasks.push(checkbox.closest('tr').dataset.id);
        });
        document.getElementById('selected_tasks').value = JSON.stringify(selectedTasks);
    };

    function downloadReport(format) {
        const status = document.getElementById('task_status_filter').value;
        const startDate = document.getElementById('task_start_date_between').value;
        const endDate = document.getElementById('task_end_date_between').value;

        if (!startDate || !endDate) {
            alert('Please enter both start and end dates.');
            return;
        }

        const url = `/reports/generate?format=${format}&status=${status}&start_date=${startDate}&end_date=${endDate}&task_id=${id}`;
        window.location.href = url; // Redirect to the report generation route
    }

    function printContent() {
        const printWindow = window.open('', '_blank');
        const tableHTML = document.getElementById('task_table').outerHTML; // Get the table content
        printWindow.document.write(`
            <html>
                <head>
                    <title>Print View</title>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
                        th { background-color: #f2f2f2; }
                    </style>
                </head>
                <body onload="window.print();">
                    <h1>${document.title}</h1>
                    ${tableHTML}
                </body>
            </html>
        `);
        printWindow.document.close();
    }
</script>

<script src="{{ asset('assets/js/pages/activity.js') }}"></script>