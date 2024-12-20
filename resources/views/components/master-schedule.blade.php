<div class="tab-pane fade active show" id="navs-top-schedule" role="tabpanel">
    <!-- Filter Section -->
    <div class="mb-4">
        <form method="GET" action="{{ route('master-schedule.index') }}" class="row g-3 align-items-end">
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Select Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>{{ $status->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="priority" class="form-select">
                    <option value="">Select Priority</option>
                    @foreach($priorities as $priority)
                        <option value="{{ $priority->id }}" {{ request('priority') == $priority->id ? 'selected' : '' }}>{{ $priority->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>
    </div>

    <!-- Action Buttons -->
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <input type="text" id="searchInput" class="form-control d-inline-block ms-2" placeholder="Search..." onkeyup="filterTable()" style="width: 200px;">
        <div>
            <form method="GET" action="{{ route('master-schedule.export') }}" class="d-inline">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="hidden" name="priority" value="{{ request('priority') }}">
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                <button type="submit" class="btn btn-info me-2"><i class="fas fa-file-pdf"></i> PDF</button>
            </form>
            <form method="GET" action="{{ route('master-schedule.exportCsv') }}" class="d-inline">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="hidden" name="priority" value="{{ request('priority') }}">
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                <button type="submit" class="btn btn-success me-2"><i class="fas fa-file-csv"></i> CSV</button>
            </form>
            <button class="btn btn-warning" onclick="printTable()"><i class="fas fa-print"></i> Print</button>
        </div>
    </div>

    <h3>Available Projects</h3>
    <!-- Projects Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover" id="projectsTable">
            <thead class="table-header-custom">
                <tr>
                    <th>ID</th>
                    <th>WBS</th>
                    <th>Projects</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Duration</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projectsData as $project)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="me-2">{{ $project['id'] ?? 'N/A' }}</span> 
                                <button class="btn btn-circle toggle-tasks badge bg-success" data-id="{{ $project['id'] }}" onclick="toggleTasks({{ $project['id'] }}, this)">+</button>
                            </div>
                        </td>
                        <td>{{ $project['wbs'] ?? 'N/A' }}</td>
                        <td>{{ Str::limit(trim($project['title'] ?? 'N/A'), 10) }}</td>
                        <td class="date-cell">{{ \Carbon\Carbon::parse($project['startDate'] ?? '')->format('d-m-Y') }}</td>
                        <td class="date-cell">{{ \Carbon\Carbon::parse($project['endDate'] ?? '')->format('d-m-Y') }}</td>
                        <td class="text-center">{{ $project['duration'] ?? 'N/A' }}</td>
                        <td>{!! $project['status'] ?? 'N/A' !!}</td>
                    </tr>
                    <tr class="tasks-row" id="tasks-{{ $project['id'] }}" style="display: none;">
                        <td colspan="7">
                            <x-tasks.task :tasks="$project['tasks']" :projectId="$project['id']" :project="$project"/>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
        {{ $projectsData->appends(request()->query())->links() }}
    </div>

    <!-- Report Section -->
    <div class="mt-4">
        <h3>Generate Report</h3>
        <form method="GET" action="" class="row g-3 align-items-end">
            <div class="col-md-3">
                <select name="report_period" class="form-select">
                    <option value="">Select Report Period</option>
                    <option value="today">Today</option>
                    <option value="this_week">This Week</option>
                    <option value="this_month">This Month</option>
                    <option value="this_year">This Year</option>
                    <option value="year">Year</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="project_id" class="form-select">
                    <option value="">Select Project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="project_task_id" class="form-select">
                    <option value="">Select Project Task</option>
                    @foreach($tasks as $task)
                        <option value="{{ $task->id }}">{{ $task->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Generate Report</button>
            </div>
        </form>
    </div>

    <!-- Report Table -->
    <div class="table-responsive mt-3">
        <table class="table table-bordered table-striped" id="reportTable">
            <thead class="table-header-custom">
                <tr>
                    <th>ID</th>
                    <th>Project</th>
                    <th>Task</th>
                    <th>Activity</th>
                    <th>Status</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Request</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportData as $report)
                    <tr>
                        <td>{{ $report->id }}</td>
                        <td>{{ $report->task->project->title }}</td>
                        <td>{{ $report->task->title }}</td>
                        <td>{{ $report->name }}</td>
                        <td>{{ $report->status_id }}</td>
                        <td>{{ \Carbon\Carbon::parse($report->start_date)->format('d-m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($report->end_date)->format('d-m-Y') }}</td>
                        <td>
                            <a href="{{ route('request.activity', ['activityId' => $report->id]) }}">
                                Request 
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
    /* Add any additional styles here */
    .table-header-custom {
        background-color: #1B8596;
        color: white !important; /* Ensures the text color is white */
    }
    .table:not(.table-dark) th {
        color: #f5f7f9;
    }
</style>

<script>
    function toggleTasks(projectId, button) {
        const tasksRow = document.getElementById(`tasks-${projectId}`);
        tasksRow.style.display = tasksRow.style.display === "none" ? "table-row" : "none";
        button.innerHTML = tasksRow.style.display === "none" ? '+' : '-';
    }

    function filterTable() {
        const input = document.getElementById("searchInput").value.toLowerCase();
        const rows = document.querySelectorAll("#projectsTable tbody tr:not(.tasks-row)");
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(input) ? "" : "none";
        });
    }

    function printTable() {
        window.print();
    }
</script>