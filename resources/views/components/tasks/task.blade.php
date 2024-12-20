<div>
    <div class="mb-3 d-flex justify-content-between align-items-center p-3 bg-light rounded shadow-sm">
        <h5 class="mb-0 text-success">Project: {{ $project['title'] }}</h5>
        <p class="text-muted mb-1">Total Tasks: {{ count($project['tasks'] ?? []) }}</p>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#create_task_modal" data-project-id="{{ $project['id'] }}">
            Create Task  
        </button>
    </div>
    <table class="table table-bordered" id="taskTable">
    <thead class="table-header-custom">
            <tr>
                <th>No</th>
                <th>WBS</th>
                <th>Task</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Duration</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($tasks) && count($tasks) > 0)
                @foreach($tasks as $task)
                    <tr id="task-{{ $task['id'] }}">
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="me-2">{{ $task['id'] ?? 'N/A' }}</span>
                                <button class="btn btn-circle toggle-activities badge bg-primary" data-id="{{ $task['id'] }}" onclick="toggleActivities({{ $task['id'] }}, this)">+</button>
                            </div>
                        </td>
                        <td class="task-wbs">{{ $task['wbs'] ?? 'N/A' }}</td>
                        <td class="task-title">{{ Str::limit(trim($task['title'] ?? 'N/A'), 10) }}</td>
                        <td class="task-start-date">{{ \Carbon\Carbon::parse($task['startDate'] ?? '')->format('d-m-Y') }}</td>
                        <td class="task-end-date">{{ \Carbon\Carbon::parse($task['endDate'] ?? '')->format('d-m-Y') }}</td>
                        <td class="task-duration">{{ $task['duration'] ?? 'N/A' }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="taskDropdownMenuButton{{ $task['id'] }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="taskDropdownMenuButton{{ $task['id'] }}">
                                    <li><a class="dropdown-item" href="#" onclick="viewTask({{ $task['id'] }})"><i class="fas fa-eye"></i> View</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="editTask({{ $task['id'] }})"><i class="fas fa-pencil-alt"></i> Edit</a></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="confirmDeleteTask({{ $task['id'] }})"><i class="fas fa-trash"></i> Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr class="activities-row" id="activities-{{ $task['id'] }}" style="display: none;">
                        <td colspan="7">
                            <x-activity-table :activities="$task['activities']" :taskId="$task['id']" :projectId="$projectId" :taskName="$task['title']" />
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="text-center">No tasks available for this project.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

<!-- View Task Modal -->
<div class="modal fade" id="viewTaskModal" tabindex="-1" aria-labelledby="viewTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewTaskModalLabel">View Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="viewTaskId" class="form-label">Task ID</label>
                        <input type="text" class="form-control" id="viewTaskId" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="viewTaskWBS" class="form-label">WBS</label>
                        <input type="text" class="form-control" id="viewTaskWBS" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="viewTaskTitle" class="form-label">Task Title</label>
                        <input type="text" class="form-control" id="viewTaskTitle" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="viewTaskStartDate" class="form-label">Start Date</label>
                        <input type="text" class="form-control" id="viewTaskStartDate" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="viewTaskEndDate" class="form-label">End Date</label>
                        <input type="text" class="form-control" id="viewTaskEndDate" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="viewTaskDuration" class="form-label">Duration (Days)</label>
                        <input type="text" class="form-control" id="viewTaskDuration" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="viewTaskDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="viewTaskDescription" rows="3" readonly></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTaskForm">
                <div class="modal-body">
                    <input type="hidden" id="editTaskId" name="task_id">
                    <div class="mb-3">
                        <label for="editTaskTitle" class="form-label">Task Title</label>
                        <input type="text" class="form-control" id="editTaskTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="editTaskWBS" class="form-label">WBS</label>
                        <input type="text" class="form-control" id="editTaskWBS" name="wbs" required>
                    </div>
                    <div class="mb-3">
                        <label for="editTaskStartDate" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="editTaskStartDate" name="start_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="editTaskEndDate" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="editTaskEndDate" name="end_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="editTaskDuration" class="form-label">Duration</label>
                        <input type="number" class="form-control" id="editTaskDuration" name="duration" required>
                    </div>
                    <div class="mb-3">
                        <label for="editTaskDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editTaskDescription" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Confirmation Delete Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this task?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    function viewTask(taskId) {
        // Sample data; replace with AJAX call to fetch task details
        const taskDetails = {
            id: taskId,
            wbs: `WBS ${taskId}`,
            title: `Task Title ${taskId}`,
            startDate: new Date().toLocaleDateString('en-GB'),
            endDate: new Date().toLocaleDateString('en-GB'),
            duration: 10,
            description: `Description of task ${taskId}`
        };

        document.getElementById('viewTaskId').value = taskDetails.id;
        document.getElementById('viewTaskWBS').value = taskDetails.wbs;
        document.getElementById('viewTaskTitle').value = taskDetails.title;
        document.getElementById('viewTaskStartDate').value = taskDetails.startDate;
        document.getElementById('viewTaskEndDate').value = taskDetails.endDate;
        document.getElementById('viewTaskDuration').value = taskDetails.duration;
        document.getElementById('viewTaskDescription').value = taskDetails.description;

        const viewModal = new bootstrap.Modal(document.getElementById('viewTaskModal'));
        viewModal.show();
    }

    function editTask(taskId) {
        // Sample data; replace with AJAX call to fetch task details
        const taskDetails = {
            id: taskId,
            wbs: `WBS ${taskId}`,
            title: `Task Title ${taskId}`,
            startDate: new Date().toISOString().split('T')[0],
            endDate: new Date().toISOString().split('T')[0],
            duration: 10,
            description: `Description of task ${taskId}`
        };

        document.getElementById('editTaskId').value = taskDetails.id;
        document.getElementById('editTaskWBS').value = taskDetails.wbs;
        document.getElementById('editTaskTitle').value = taskDetails.title;
        document.getElementById('editTaskStartDate').value = taskDetails.startDate;
        document.getElementById('editTaskEndDate').value = taskDetails.endDate;
        document.getElementById('editTaskDuration').value = taskDetails.duration;
        document.getElementById('editTaskDescription').value = taskDetails.description;

        const editModal = new bootstrap.Modal(document.getElementById('editTaskModal'));
        editModal.show();
    }

    document.getElementById('editTaskForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch('{{ route('tasks.update') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Task updated successfully.');
                
                // Update the task information in the table
                const taskId = document.getElementById('editTaskId').value;
                document.getElementById(`task-${taskId}`).querySelector('.task-title').textContent = document.getElementById('editTaskTitle').value;
                document.getElementById(`task-${taskId}`).querySelector('.task-wbs').textContent = document.getElementById('editTaskWBS').value;
                document.getElementById(`task-${taskId}`).querySelector('.task-start-date').textContent = document.getElementById('editTaskStartDate').value;
                document.getElementById(`task-${taskId}`).querySelector('.task-end-date').textContent = document.getElementById('editTaskEndDate').value;
                document.getElementById(`task-${taskId}`).querySelector('.task-duration').textContent = document.getElementById('editTaskDuration').value;

                // Hide the modal
                const editModal = bootstrap.Modal.getInstance(document.getElementById('editTaskModal'));
                editModal.hide();
            } else {
                alert('Error updating task.');
            }
        })
        .catch(error => {
    if (error.response) {
        // Server responded with a status other than 2xx
        console.error(error.response.data);
        alert('Error: ' + error.response.data.message);
    } else {
        // Network error or other issue
        console.error('Error:', error);
        alert('An error occurred while updating the task.');
    }
});
    });
    function toggleActivities(taskId, button) {
        const activitiesRow = document.getElementById(`activities-${taskId}`);
        activitiesRow.style.display = activitiesRow.style.display === "none" ? "table-row" : "none";
        button.innerHTML = activitiesRow.style.display === "none" ? '+' : '-';
    }
    function confirmDeleteTask(taskId) {
        const confirmDeleteButton = document.getElementById('confirmDeleteButton');
        confirmDeleteButton.onclick = function() {
            deleteTask(taskId);
        };
        const deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        deleteModal.show();
    }

    function deleteTask(taskId) {
        fetch(`/tasks/delete/${taskId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`task-${taskId}`).remove();
                const deleteModal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal'));
                deleteModal.hide();
                alert('Task deleted successfully.');
            } else {
                alert('Error deleting task.');
            }
        });
    }
</script>
<style>
.table-header-custom {
    background-color: #1B8596;
    color: white !important; /* Ensures the text color is white */
}
.table:not(.table-dark) th {
    color: #f5f7f9;
}
</style>