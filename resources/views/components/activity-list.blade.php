<div class="tab-pane fade active show" id="navs-top-schedule" role="tabpanel">
    <!-- Filter Section -->
    <div class="mb-4">
        <form method="GET" action="/tasks/information/{{ $taskId }}" class="row g-3 align-items-end">
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Select Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>
                            {{ $status->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="priority" class="form-select form-select-sm">
                    <option value="">Select Priority</option>
                    @foreach($priorities as $priority)
                        <option value="{{ $priority->id }}" {{ request('priority') == $priority->id ? 'selected' : '' }}>
                            {{ $priority->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            </div>
        </form>
    </div>

    <!-- Action Buttons -->
    <div class="mb-3 d-flex align-items-center">
        <form method="GET" action="{{ route('activities.export') }}" class="d-inline me-2">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <input type="hidden" name="priority" value="{{ request('priority') }}">
            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
            <button type="submit" class="btn btn-info btn-sm">
                <i class="fas fa-file-pdf"></i> PDF
            </button>
        </form>
        <form method="GET" action="{{ route('activities.exportCsv') }}" class="d-inline me-2">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <input type="hidden" name="priority" value="{{ request('priority') }}">
            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
            <button type="submit" class="btn btn-success btn-sm">
                <i class="fas fa-file-csv"></i> CSV
            </button>
        </form>
        <button class="btn btn-warning btn-sm" onclick="printTable()">
            <i class="fas fa-print"></i> Print
        </button>
    </div>

    <!-- Task Table -->
    <table id="task_table"
           data-toggle="table"
           data-loading-template="loadingTemplate"
           data-url="/activity/listing{{ !empty($taskId) ? '/' . $taskId : '' }}"
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
        <thead class="tablehead">
            <tr>
                <th data-checkbox="true"></th>
                <th data-sortable="true" data-field="id">{{ get_label('id', 'ID') }}</th>
                <th data-sortable="true" data-field="wbs">{{ get_label('wbs', 'WBS') }}</th>
                <th data-sortable="true" data-field="activity_name">{{ get_label('activity_name', 'Activity Name') }}</th>
                <th data-sortable="true" data-field="unit">{{ get_label('unit', 'Unit') }}</th>
                <th data-sortable="true" data-field="quantity">{{ get_label('quantity', 'Quantity') }}</th>
                <th data-sortable="true" data-field="priority" class="priority-column">{{ get_label('priority', 'Priority') }}</th>
                <th data-sortable="true" data-field="start_date">{{ get_label('starts_at', 'Starts at') }}</th>
                <th data-sortable="true" data-field="end_date">{{ get_label('ends_at', 'Ends at') }}</th>
                <th data-field="duration">{{ get_label('duration', 'Duration') }}</th>
                <th data-sortable="true" data-field="progress">{{ get_label('progress', 'Progress') }}</th>
                <th data-sortable="true" data-field="status">{{ get_label('approval', 'Approval') }}</th>
                <th data-sortable="true" data-field="aproval_status">{{ get_label('status', 'Status') }}</th>
                <th data-sortable="true" data-field="created_at" data-visible="false">{{ get_label('created_at', 'Created at') }}</th>
                <th data-sortable="true" data-field="updated_at" data-visible="false">{{ get_label('updated_at', 'Updated at') }}</th>
                @if(getAuthenticatedUser()->hasVerifiedEmail() && getAuthenticatedUser()->hasRole('admin'))
                    <th data-formatter="actionFormatter">{{ get_label('actions', 'Actions') }}</th>
                @endif
            </tr>
        </thead>
    </table>

    <!-- Check Button -->
    <form id="checkForm" method="POST" action="/activity/selection" class="mt-3">
        @csrf
        <button type="submit" class="btn btn-primary" id="checkButton" >Check</button>
    </form>

    <!-- JavaScript for Handling Button Click -->
    <script>
        // Enable/Disable Check button based on checkbox selection
        const checkboxes = document.querySelectorAll('#task_table input[type="checkbox"]');
        const checkButton = document.getElementById('checkButton');

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const anyChecked = Array.from(checkboxes).some(chk => chk.checked);
                checkButton.disabled = !anyChecked; // Enable/disable button based on selection
            });
        });

        // Pass selected tasks to the checklist route
        document.getElementById('checkForm').addEventListener('submit', function(event) {
            const selectedTasks = [];
            const checkboxes = document.querySelectorAll('#task_table input[type="checkbox"]:checked');
            checkboxes.forEach((checkbox) => {
                selectedTasks.push(checkbox.closest('tr').dataset.id); // Assuming each row has a data-id attribute
            });

            if (selectedTasks.length === 0) {
                event.preventDefault(); // Prevent form submission if no tasks are selected
                alert('Please select at least one task to proceed.');
            } else {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_tasks'; // Ensure the name matches what your controller expects
                input.value = JSON.stringify(selectedTasks);
                this.appendChild(input);
            }
        });
    </script>

    <!-- Edit Activity Modal -->
    <!-- (Modal code remains unchanged) -->

    <!-- Delete Confirmation Modal -->
    <!-- (Modal code remains unchanged) -->
</div>