@extends('layout')

@section('title')
    Project Management
@endsection

@section('content')
<div class="container-fluid">
    <h2 class="mt-4">Master Schedule</h2>

    <!-- Nav Tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-equipments" aria-controls="navs-top-equipments" aria-selected="true">
                <i class="menu-icon tf-icons bx bx-wrench text-warning"></i>{{ get_label('equipment', 'Schedule') }}
            </button>
        </li>
        <li class="nav-item">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-requests" aria-controls="navs-top-requests" aria-selected="false">
                <i class="menu-icon tf-icons bx bx-paper-plane text-success"></i>{{ get_label('requests', 'Gantt chart') }}
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content mt-3">
        <div class="tab-pane fade active show" id="navs-top-equipments" role="tabpanel">
          
        <x-master-schedule-card :projectsData="$projectsData" :priority="$priority" :users="$users" />
            <!-- You can add your equipment-related content here -->
        </div>

        <div class="tab-pane fade" id="navs-top-requests" role="tabpanel">
            <div>
                <h3>Project Gantt Chart</h3>
                <div>
                    <select id="year" onchange="updateSchedule()">  
                        @for ($i = 2020; $i <= date('Y'); $i++)  
                            <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>  
                        @endfor  
                    </select>  
                    <select id="month" onchange="updateSchedule()">  
                        <option value="0">All Months</option>  
                        @for ($i = 1; $i <= 12; $i++)  
                            <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>  
                        @endfor  
                    </select>  
                    <select id="week" onchange="updateSchedule()">  
                        <option value="0">All Weeks</option>  
                        @for ($i = 1; $i <= 52; $i++)  
                            <option value="{{ $i }}">{{ 'Week ' . $i }}</option>  
                        @endfor  
                    </select>  
                </div>

                <!-- Gantt Chart -->  
                <div id="gantt_here" style="width:100%; height:400px;"></div>  

                <!-- Custom Modal for Task Creation -->
                <div id="popup" class="modal-popup">
                    <h3>Create Task</h3>
                    <span class="close-btn" id="closePopup">&times;</span>
                    <form id="taskForm">
                        @foreach (['Task Name' => 'taskName', 'Start Date' => 'taskStartDate', 'End Date' => 'taskEndDate', 'Duration' => 'taskDuration', 'Team Member' => 'taskMember', 'Client' => 'taskClient'] as $label => $id)
                            <label for="{{ $id }}">{{ $label }}:</label>
                            <input type="{{ $id === 'taskDuration' ? 'number' : 'text' }}" id="{{ $id }}" required {{ $id === 'taskDuration' ? 'value=1 min=1' : '' }}>
                        @endforeach
                        
                        <label for="taskStatus">Status:</label>
                        <select id="taskStatus" required>
                            <option value="not-started">Not Started</option>
                            <option value="in-progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>

                        <label for="taskProgress">Progress (%):</label>
                        <input type="number" id="taskProgress" value="0" min="0" max="100" required>

                        <div class="button-group">
                            <button type="button" id="createTask">Save</button>
                            <button type="button" id="cancel">Cancel</button>
                        </div>
                    </form>
                </div>

                <script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>  
                <link href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css" rel="stylesheet">  
                <style>
                    /* Modal Styles */
                    .modal-popup {
                        display: none;
                        position: fixed;
                        background: white;
                        border-radius: 12px;
                        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
                        padding: 30px;
                        z-index: 1000;
                        left: 50%;
                        top: 50%;
                        transform: translate(-50%, -50%);
                        width: 400px;
                        max-width: 90%;
                        max-height: 80%;
                        overflow-y: auto;
                        animation: fadeIn 0.3s;
                        font-family: 'Arial', sans-serif;
                    }

                    /* Additional styles omitted for brevity */
                </style>

                <script type="text/javascript">  
                    const projects = {  
                        data: @json($projects),  
                        links: []  
                    };  

                    gantt.config.date_format = "%Y-%m-%d";  
                    gantt.config.details_on_dblclick = true; 
                    gantt.config.open_tree_initially = true; 
                    gantt.config.tree_cell = true; 
                    gantt.init("gantt_here");  
                    gantt.parse(projects);

                    // Open top-level tasks by default
                    projects.data.forEach(task => {
                        if (!task.parent) {
                            gantt.open(task.id);
                        }
                    });

                    gantt.attachEvent("onTaskClick", function(id) {
                        showPopup(id);
                        return false; 
                    });

                    function showPopup(taskId) {
                        const task = gantt.getTask(taskId);
                        document.getElementById("taskName").value = task.text || ""; 
                        document.getElementById("taskStartDate").value = task.start_date || ""; 
                        document.getElementById("taskEndDate").value = task.end_date || ""; 
                        document.getElementById("taskDuration").value = task.duration || 1; 
                        document.getElementById("taskStatus").value = task.status || "not-started"; 
                        document.getElementById("taskProgress").value = task.progress || 0; 
                        document.getElementById("taskMember").value = task.member || ""; 
                        document.getElementById("taskClient").value = task.client || ""; 
                        
                        document.getElementById("popup").style.display = "block";
                    }

                    function createTask() {
                        const taskData = {
                            id: gantt.getTaskCount() + 1,
                            text: document.getElementById("taskName").value,
                            start_date: document.getElementById("taskStartDate").value,
                            end_date: document.getElementById("taskEndDate").value,
                            duration: document.getElementById("taskDuration").value,
                            status: document.getElementById("taskStatus").value,
                            progress: document.getElementById("taskProgress").value,
                            member: document.getElementById("taskMember").value,
                            client: document.getElementById("taskClient").value,
                        };

                        fetch('/tasks/create', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(taskData)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                gantt.addTask(taskData);
                                closePopup();
                            } else {
                                alert('Failed to create task: ' + (data.message || 'Unknown error'));
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    }

                    function closePopup() {
                        document.getElementById("popup").style.display = "none";
                        document.getElementById("taskForm").reset();
                    }

                    document.getElementById("createTask").onclick = createTask; 
                    document.getElementById("cancel").onclick = closePopup;
                    document.getElementById("closePopup").onclick = closePopup;

                    function updateSchedule() {  
                        const year = document.getElementById('year').value;  
                        const month = document.getElementById('month').value;  
                        const week = document.getElementById('week').value;  
                        // Implement filtering logic if needed
                    }  
                </script>
            </div>
        </div>
    </div>
</div>
@endsection