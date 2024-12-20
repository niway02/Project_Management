<style>
    .table {
        width: 100%; /* Full width */
        table-layout: auto; /* Allow natural column width */
    }

    .table th, .table td {
        padding: 0.2rem; /* Reduce padding to minimize height */
        height: 30px; /* Set a fixed height for the rows */
        overflow: hidden; /* Hide overflow text */
        text-overflow: ellipsis; /* Show ellipsis for overflowed text */
    }

    .table th {
        background-color: #f8f9fa; /* Optional: lighter background for headers */
        font-weight: bold; /* Keep headers bold for clarity */
    }
</style>
<div class="card">
    <div class="card-body">
        <div class="d-flex flex-column flex-md-row justify-content-between mb-3">
            <div class="mb-3 mb-md-0">
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#create_project_modal">
                    Create Project
                </button>
            </div>
            <div class="input-group mb-3" style="max-width: 300px;">
                <input type="text" class="form-control" id="searchInput" placeholder="Search...">
            </div>
            <div class="mb-3 mb-md-0" style="flex-grow: 1; max-width: 200px;">
                <div class="input-group">
                    <select class="form-select form-select-sm" id="statusSelect">
                        @foreach($statuses as $status)
                        <option value="{{$status->id}}" class="badge bg-label-{{$status->color}}">
                            {{$status->title}}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="d-flex flex-column flex-md-row align-items-start">
                <input type="date" class="form-control mb-3 mb-md-0" id="startDate" placeholder="Start Date" style="max-width: 200px;">
                <input type="date" class="form-control mb-3 mb-md-0 ms-2" id="endDate" placeholder="End Date" style="max-width: 200px;">
                <div class="btn-group ms-2">
                    <button class="btn btn-sm btn-primary" id="exportPDF" type="button">PDF</button>
                    <button class="btn btn-sm btn-primary" id="exportCSV" type="button">CSV</button>
                    <button class="btn btn-sm btn-primary" id="printReport" type="button">Print</button>
                </div>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-hover" id="master-schedule-table">
                <thead class="tablehead">
                    <tr>
                        <th>ID</th>
                        <th>WBS</th>
                        <th>Project</th>
                        <th>Site</th>
                        <th>Priority</th>
                        <th>Start Date</th>
                        <th>Duration</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Created By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <script>
                        const projectsData = @json($projectsData);

                        document.addEventListener('DOMContentLoaded', function() {
                            const tbody = document.querySelector('#master-schedule-table tbody');
                            tbody.innerHTML = renderProjects(projectsData);

                            // Search functionality
                            document.getElementById('searchInput').addEventListener('input', function() {
                                const searchValue = this.value.toLowerCase();
                                const filteredProjects = projectsData.filter(project =>
                                    project.title.toLowerCase().includes(searchValue) || 
                                    project.site.toLowerCase().includes(searchValue)
                                );
                                tbody.innerHTML = renderProjects(filteredProjects);
                            });

                            tbody.addEventListener('click', function(e) {
                                if (e.target.classList.contains('toggle-tasks')) {
                                    const projectId = e.target.getAttribute('data-id');
                                    const tasksRow = tbody.querySelector(`.tasks-row[data-project-id="${projectId}"]`);
                                    tasksRow.style.display = tasksRow.style.display === 'none' ? '' : 'none';
                                    e.target.textContent = tasksRow.style.display === 'none' ? '+' : '-';
                                }
                            });

                            // Export buttons
                            document.getElementById('exportPDF').addEventListener('click', function() {
                                const startDate = document.getElementById('startDate').value;
                                const endDate = document.getElementById('endDate').value;
                                window.location.href = `{{ route('projects.pdf') }}?start=${startDate}&end=${endDate}`;
                            });

                            document.getElementById('exportCSV').addEventListener('click', function() {
                                const startDate = document.getElementById('startDate').value;
                                const endDate = document.getElementById('endDate').value;
                                window.location.href = `{{ route('projects.export.csv') }}?start=${startDate}&end=${endDate}`;
                            });
                        });

                        function renderProjects(rows) {
                            let html = '';
                            rows.forEach(row => {
                                html += `
                                    <tr class="project-row">
                                        <td>${row.id} 
                                            <button class="btn btn-circle toggle-tasks badge bg-success" data-id="${row.id}">+</button>
                                        </td>
                                        <td>${row.wbs}</td>
                                        <td>${row.title.trim().length > 10 ? row.title.trim().slice(0, 10) + '...' : row.title.trim()}</td>
                                        <td>${row.site}</td>
                                        <td>${row.priority}</td>
                                        <td>${row.startDate}</td>
                                        <td>
                                            <div style="text-align: center;">
                                                <div>Dur ${" " + row.duration}</div>
                                                <div style="color: ${row.remaining.includes("Pas") ? 'red' : 'green'};">
                                                    ${" " + row.remaining}
                                                </div>
                                            </div>
                                        </td>
                                        <td>${row.endDate}</td>
                                        <td>${row.status}</td>
                                        <td>${row.assignedTo}</td>
                                        <td>${row.createdDate}</td>
                                        <td>${buildProjectActionDropdown(row.id)}</td>
                                    </tr>
                                    <tr class="tasks-row" data-project-id="${row.id}" style="display: none;">
                                        <td colspan="12">
                                            <table class="table table-bordered">
                                                <thead class="tablehead">
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
                                                    ${renderTasks(row.tasks)}
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                `;
                            });
                            return html;
                        }

                        function renderTasks(tasks) {
                            return tasks.map(task => `
                                <tr class="task-row">
                                    <td>${task.id} 
                                        <button class="btn btn-circle toggle-activities badge bg-primary" data-id="${task.id}">+</button>
                                    </td>
                                    <td>${task.wbs}</td>
                                    <td>${task.title.trim().length > 10 ? task.title.trim().slice(0, 10) + '...' : task.title.trim()}</td>
                                    <td>${task.priority}</td>
                                    <td>${task.startDate}</td>
                                    <td>
                                        <div style="text-align: center;">
                                            <div>Dur ${" " + task.duration}</div>
                                            <div style="color: ${task.remaining.includes("Pas") ? 'red' : 'green'};">
                                                ${task.remaining.includes("Pas") ? '' : 'Rem'} ${" " + task.remaining}
                                            </div>
                                        </div>
                                    </td>
                                    <td>${task.endDate}</td>
                                    <td>${task.status}</td>
                                    <td>${task.assignedTo}</td>
                                    <td>${task.createdBy}</td>
                                    <td>${task.createdDate}</td>
                                    <td>${buildTaskActionDropdown(task.id)}</td>
                                </tr>
                                <tr class="activities-row" data-task-id="${task.id}" style="display: none;">
                                    <td colspan="12">
                                        <table class="table table-bordered">
                                            <thead class="tablehead">
                                                <tr>
                                                    <th>Activity ID</th>
                                                    <th>Description</th>
                                                    <th>Assigned To</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${renderActivities(task.activities)}
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            `).join('');
                        }

                        function buildProjectActionDropdown(id) {
                            return `
                                <div class="dropdown">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="projectActionDropdown${id}" data-bs-toggle="dropdown" aria-expanded="false">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="projectActionDropdown${id}">
                                        <li><a class="dropdown-item" href="#" onclick="editProject(${id})">Edit</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="updateProject(${id})">Update</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="duplicateProject(${id})">Duplicate</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="notifyProject(${id})">Notify</a></li>
                                    </ul>
                                </div>
                            `;
                        }

                        function buildTaskActionDropdown(id) {
                            return `
                                <div class="dropdown">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="taskActionDropdown${id}" data-bs-toggle="dropdown" aria-expanded="false">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="taskActionDropdown${id}">
                                        <li><a class="dropdown-item" href="#" onclick="editTask(${id})">Edit</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="updateTask(${id})">Update</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="duplicateTask(${id})">Duplicate</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="notifyTask(${id})">Notify</a></li>
                                    </ul>
                                </div>
                            `;
                        }

                        function renderActivities(activities) {
                            return activities.map(activity => `
                                <tr>
                                    <td>${activity.id}</td>
                                    <td>${activity.description}</td>
                                    <td>${activity.assignedTo}</td>
                                    <td>${activity.status}</td>
                                    <td>
                                        <button class="btn btn-danger btn-sm delete-activity" data-id="${activity.id}">Delete Activity</button>
                                    </td>
                                </tr>
                            `).join('');
                        }

                        // Placeholder functions for actions
                        function editProject(id) {
                            console.log(`Edit project ${id}`);
                        }
                        function updateProject(id) {
                            console.log(`Update project ${id}`);
                        }
                        function duplicateProject(id) {
                            console.log(`Duplicate project ${id}`);
                        }
                        function notifyProject(id) {
                            console.log(`Notify project ${id}`);
                        }

                        function editTask(id) {
                            console.log(`Edit task ${id}`);
                        }
                        function updateTask(id) {
                            console.log(`Update task ${id}`);
                        }
                        function duplicateTask(id) {
                            console.log(`Duplicate task ${id}`);
                        }
                        function notifyTask(id) {
                            console.log(`Notify task ${id}`);
                        }
                    </script>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Activity Modal -->
<div class="modal fade" id="create_activity_modal" tabindex="-1" aria-labelledby="createActivityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createActivityModalLabel">{{ get_label('create_activity', 'Create Activity') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createActivityForm" method="POST" action="{{ route('activities.store') }}" onsubmit="resetModalInputs()">
                    @csrf
                    <input type="hidden" id="taskId" name="task_id" value="">

                    <div class="mb-3">
                        <label for="activityName" class="form-label">{{ get_label('activity_name', 'Activity Name') }}</label>
                        <input type="text" class="form-control" id="activityName" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="activityPriority" class="form-label">{{ get_label('activity_priority', 'Activity Priority') }}</label>
                        <select class="form-select" id="activityPriority" name="priority" required>
                            <option value="" disabled selected>{{ get_label('select_priority', 'Select Priority') }}</option>
                            @foreach($priority as $pri)
                                <option value="{{ $pri->id }}">{{ $pri->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="status">{{ get_label('status', 'Status') }} <span class="asterisk">*</span></label>
                        <select class="form-select" name="status_id" required>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}">{{ $status->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="user_id">{{ get_label('select_members', 'Select members') }}</label>
                        <select class="form-control js-example-basic-multiple" name="user_id[]" multiple="multiple" data-placeholder="{{ get_label('type_to_search', 'Type to search') }}">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ (collect(old('user_id'))->contains($user->id)) ? 'selected' : '' }}>{{ $user->first_name }} {{ $user->last_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="start_date">{{ get_label('starts_at', 'Starts at') }} <span class="asterisk">*</span></label>
                        <input type="date" id="start_date" name="start_date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="ends_at">{{ get_label('ends_at', 'Ends at') }} <span class="asterisk">*</span></label>
                        <input type="date" id="ends_at" name="ends_at" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ get_label('submit', 'Submit') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
