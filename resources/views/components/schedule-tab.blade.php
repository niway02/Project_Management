<div class="tab-pane fade active show" id="navs-top-schedule" role="tabpanel">
    <!-- Filter Section -->
    <div class="mb-3">
        <form method="GET" action="{{ route('master-schedule.index') }}">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <select name="status" class="form-select">
                        <option value="">Select Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>{{ $status->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <select name="priority" class="form-select">
                        <option value="">Select Priority</option>
                        @foreach($priorities as $priority)
                            <option value="{{ $priority->id }}" {{ request('priority') == $priority->id ? 'selected' : '' }}>{{ $priority->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3 mb-2">
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-1 mb-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Action Buttons -->
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div>
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#create_project_modal">
                Create Project
            </button>
            <input type="text" id="searchInput" class="form-control d-inline-block" placeholder="Search..." onkeyup="filterTable()" style="width: 150px; margin-left: 10px;">
        </div>
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

    <!-- Projects Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="projectsTable">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>WBS</th>
                    <th>Title</th>
                    <th>Site</th>
                    <th>Priority</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Duration</th>
                    <th>Remaining</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Created By</th>
                    <th>Created Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projectsData as $project)
                    <tr>
                        <td>
                            {{ $project['id'] ?? 'N/A' }}
                            <button class="btn btn-circle toggle-tasks badge bg-success" data-id="{{ $project['id'] }}">+</button>
                        </td>
                        <td>{{ $project['wbs'] ?? 'N/A' }}</td>
                        <td>{{ Str::limit(trim($project['title'] ?? 'N/A'), 10) }}</td>
                        <td>{{ $project['site'] ?? 'N/A' }}</td>
                        <td>{!! $project['priority'] ?? 'N/A' !!}</td>
                        <td>{{ $project['startDate'] ?? 'N/A' }}</td>
                        <td>{{ $project['endDate'] ?? 'N/A' }}</td>
                        <td>
                            <div class="text-center">
                                <div>Dur: {{ $project['duration'] ?? 'N/A' }}</div>
                                <div style="color: {{ isset($project['remaining']) && strpos($project['remaining'], 'Past') !== false ? 'red' : 'green' }};">
                                    {{ isset($project['remaining']) && strpos($project['remaining'], 'Past') !== false ? '' : 'Rem: ' }} {{ $project['remaining'] ?? 'N/A' }}
                                </div>
                            </div>
                        </td>
                        <td style="color: {{ $project['remainingColor'] ?? 'black' }}">{{ $project['remaining'] ?? 'N/A' }}</td>
                        <td>{!! $project['status'] ?? 'N/A' !!}</td>
                        <td>{{ $project['assignedTo'] ?? 'N/A' }}</td>
                        <td>{!! $project['createdBy'] ?? 'N/A' !!}</td>
                        <td>{{ $project['createdDate'] ?? 'N/A' }}</td>
                    </tr>
                    <tr class="tasks-row" data-project-id="{{ $project['id'] }}" style="display: none;">
                        <td colspan="14">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Task ID</th>
                                        <th>WBS</th>
                                        <th>Task</th>
                                        <th>Priority</th>
                                        <th>Start Date</th>
                                        <th>Duration</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                        <th>Assigned To</th>
                                        <th>Created By</th>
                                        <th>Created Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($project['tasks']) && is_array($project['tasks']) && count($project['tasks']) > 0)
                                        @foreach($project['tasks'] as $task)
                                            <tr>
                                                <td>{{ $task['id'] ?? 'N/A' }}</td>
                                                <td>{{ $task['wbs'] ?? 'N/A' }}</td>
                                                <td>{{ $task['title'] ?? 'N/A' }}</td>
                                                <td>{{ $task['priority'] ?? 'N/A' }}</td>
                                                <td>{{ $task['startDate'] ?? 'N/A' }}</td>
                                                <td>{{ $task['duration'] ?? 'N/A' }}</td>
                                                <td>{{ $task['endDate'] ?? 'N/A' }}</td>
                                                <td>{{ $task['status'] ?? 'N/A' }}</td>
                                                <td>{{ $task['assignedTo'] ?? 'N/A' }}</td>
                                                <td>{{ $task['createdBy'] ?? 'N/A' }}</td>
                                                <td>{{ $task['createdDate'] ?? 'N/A' }}</td>
                                                <td>
                                                    <button class="btn btn-danger" onclick="deleteTask({{ $task['id'] ?? '0' }})">Delete</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="12" class="text-center">No tasks available for this project.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        {{ $projectsData->appends(request()->query())->links() }}
    </div>
</div>