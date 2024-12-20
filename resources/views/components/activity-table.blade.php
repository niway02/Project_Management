<div class="mb-3 d-flex justify-content-between align-items-center p-3 bg-light rounded shadow-sm">
    <h5 class="mb-0 text-primary">Task: {{ $taskName }}</h5>
    <p class="text-muted mb-1">The following are the list of tasks for this task:</p>
    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#create_activity_modal" data-task-id="{{ $taskId }}">
        Create Activity  
    </button>
</div>

<div class="activity-table-responsive mt-4">
    <table class="activity-table table table-striped table-hover table-bordered">
        <thead class="table-header-custom">
            <tr>
                <th style="min-width: 50px;">No</th>
                <th style="min-width: 50px;">Wbs</th>
                <th style="min-width: 150px;" title="Activity Name">Activity</th>
                <th style="min-width: 100px;">Unit</th>
                <th style="min-width: 100px;">Quantity</th>
                <th style="min-width: 120px;">Start Date</th>
                <th style="min-width: 120px;">End Date</th>
                <th style="min-width: 120px;">Resource Request</th>
                <th style="min-width: 120px;" title="Actions for Activity">Action</th>
            </tr>
        </thead>
        <tbody>
    @if(isset($activities) && count($activities) > 0)
        @foreach($activities as $index => $activity)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $projectId }} .{{ $taskId }} . {{ $index + 1 }}</td>
                <td>{{ $activity['name'] ?? 'N/A' }}</td> <!-- Ensure you're accessing string keys -->
                <td>{{ $activity['unit'] ?? 'N/A' }}</td>
                <td>{{ $activity['quantity'] ?? '0' }}</td>
                <td>{{ \Carbon\Carbon::parse($activity['start_date'] ?? '')->format('d-m-Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($activity['end_date'] ?? '')->format('d-m-Y') }}</td>
                <td>
    <a href="{{ route('request.activity', ['activityId' => $activity['id']]) }}">
      request 
    </a>
</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="actionMenu{{ $activity['id'] }}" data-bs-toggle="dropdown" aria-expanded="false">
                            Actions
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="actionMenu{{ $activity['id'] }}">
                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="editActivity({{ json_encode($activity) }})">Edit</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="deleteActivity({{ $activity['id'] }})">Delete</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="viewActivityDetails({{ json_encode($activity) }})">View Details</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="8" class="text-center">No activities available for this task.</td>
        </tr>
    @endif
</tbody>
    </table>
</div>

<!-- Modal for Editing Activity -->
<div class="modal fade" id="edit_activity_modal" tabindex="-1" role="dialog" aria-labelledby="editActivityModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editActivityModalLabel">Edit Activity</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editActivityForm">
                    @csrf
                    @method('PUT') <!-- This will be included in the AJAX request -->
                    <input type="hidden" id="editActivityId">
                    
                    <div class="form-group">
                        <label for="editActivityName">Activity Name</label>
                        <input type="text" class="form-control" id="editActivityName" required>
                    </div>
                    <div class="form-group">
                        <label for="editActivityUnit">Unit</label>
                        <input type="text" class="form-control" id="editActivityUnit" required>
                    </div>
                    <div class="form-group">
                        <label for="editActivityQuantity">Quantity</label>
                        <input type="number" class="form-control" id="editActivityQuantity" required>
                    </div>
                    <div class="form-group">
                        <label for="editActivityStartDate">Start Date</label>
                        <input type="date" class="form-control" id="editActivityStartDate" required>
                    </div>
                    <div class="form-group">
                        <label for="editActivityEndDate">End Date</label>
                        <input type="date" class="form-control" id="editActivityEndDate" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitEditActivity()">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Viewing Activity Details -->
<div class="modal fade" id="view_activity_modal" tabindex="-1" role="dialog" aria-labelledby="viewActivityModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewActivityModalLabel">Activity Details</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="viewActivityDetails">
                <!-- Activity details will be populated here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    .table-header-custom {
        background-color: #1B8596; /* Set the desired background color */
        color: white; /* Optional: Set text color for better contrast */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var createActivityModal = document.getElementById('create_activity_modal');
        createActivityModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var taskId = button.getAttribute('data-task-id');
            var taskIdInput = document.getElementById('task_id');
            taskIdInput.value = taskId;
        });
    });

    function editActivity(activity) {
        // Populate the modal fields with the activity data
        document.getElementById('editActivityId').value = activity.id;
        document.getElementById('editActivityName').value = activity.name;
        document.getElementById('editActivityUnit').value = activity.unit;
        document.getElementById('editActivityQuantity').value = activity.quantity;
        document.getElementById('editActivityStartDate').value = activity.start_date;
        document.getElementById('editActivityEndDate').value = activity.end_date;

        // Show the modal
        var editActivityModal = new bootstrap.Modal(document.getElementById('edit_activity_modal'));
        editActivityModal.show();
    }

    function submitEditActivity() {
        var activityId = document.getElementById('editActivityId').value;

        // Prepare the data to be sent
        var data = {
            name: document.getElementById('editActivityName').value,
            unit: document.getElementById('editActivityUnit').value,
            quantity: document.getElementById('editActivityQuantity').value,
            start_date: document.getElementById('editActivityStartDate').value,
            end_date: document.getElementById('editActivityEndDate').value,
            _method: 'PUT', // Indicate that this is a PUT request
            _token: '{{ csrf_token() }}' // Include CSRF token
        };

        // Send AJAX request to update the activity
        fetch(`/activities/${activityId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (response.ok) {
                // Close the modal and refresh the page or update the UI
                var editActivityModal = bootstrap.Modal.getInstance(document.getElementById('edit_activity_modal'));
                editActivityModal.hide();
                location.reload(); // Reload the page to see changes or you could dynamically update the UI
            } else {
                alert('Failed to update the activity. Please try again.');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function deleteActivity(activityId) {
    if (confirm('Are you sure you want to delete this activity?')) {
        // Make an AJAX request to delete the activity
        fetch(`/activities/${activityId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}', // Include CSRF token
            },
        })
        .then(response => {
            if (response.ok) {
                // Optionally refresh the page or remove the activity from the DOM
                location.reload(); // Reload the page to see changes
            } else {
                alert('Failed to delete the activity.');
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

    function viewActivityDetails(activity) {
        var detailsHtml = `
            <strong>Activity Name:</strong> ${activity.name}<br>
            <strong>Unit:</strong> ${activity.unit}<br>
            <strong>Quantity:</strong> ${activity.quantity}<br>
            <strong>Start Date:</strong> ${activity.start_date}<br>
            <strong>End Date:</strong> ${activity.end_date}<br>
        `;
        document.getElementById('viewActivityDetails').innerHTML = detailsHtml;
        var viewActivityModal = new bootstrap.Modal(document.getElementById('view_activity_modal'));
        viewActivityModal.show();
    }
</script>