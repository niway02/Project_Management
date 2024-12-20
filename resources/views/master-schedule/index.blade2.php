@extends('layout')

@section('title')
    {{ get_label('tasks', 'Tasks') }} - {{ get_label('list_view', 'List view') }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-2 mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-style1">
                <li class="breadcrumb-item">
                    <a href="{{ url('/home') }}">{{ get_label('home', 'Home') }}</a>
                </li>
                @isset($project->id)
                    <li class="breadcrumb-item">
                        <a href="{{ url('/projects') }}">{{ get_label('projects', 'Projects') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ url('/projects/information/'.$project->id) }}">{{ $project->title }}</a>
                    </li>
                @endisset
                <li class="breadcrumb-item active">{{ get_label('tasks', 'Tasks') }}</li>
            </ol>
        </nav>
    </div>

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

    <head>  
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">  
        <script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>  
        <link href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css" rel="stylesheet">  
        <style type="text/css">  
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            th, td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
            .gantt_task {
                position: relative;
            }
            .add-new-button {
                position: absolute;
                right: 10px;
                top: 5px;
            }
        </style>  
    </head>  

    <script type="text/javascript">  
        const projects = {  
            data: @json($projects),  
            links: []  
        };  

        gantt.config.date_format = "%Y-%m-%d";  
        gantt.init("gantt_here");  
        gantt.parse(projects);

        gantt.attachEvent("onGanttReady", function() {
            addButtonsToRows();
        });

        function addButtonsToRows() {
            const tasks = gantt.getTaskByTime(); 
            tasks.forEach(task => {
                const rowId = task.id;
                const buttonHtml = `<button class="btn btn-primary add-new-button" onclick="addNewTask(${rowId})">Add New</button>`;
                
                const row = document.querySelector(`.gantt_task[data-id="${rowId}"]`);
                if (row) {
                    row.insertAdjacentHTML('beforeend', buttonHtml);
                }
            });
        }
        const dp = gantt.createDataProcessor({
      url: "/api",
      mode: "REST"
    });
        function updateSchedule() {  
            const year = document.getElementById('year').value;  
            const month = document.getElementById('month').value;  
            const week = document.getElementById('week').value;  

            filterTasks(year, month, week);  
        }  

        function addNewTask(projectId) {
            // Replace with AJAX call to save the task
            const taskData = {
                project_id: projectId,
                // Add any other necessary data here
            };

            fetch('/tasks/store', {
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
                    alert('Task added successfully!');
                    // Optionally refresh the Gantt chart or update it with the new task
                } else {
                    alert('Failed to add task.');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>  

    <x-activities-card :activities="$activities" :id="$id" :users="$users" :clients="$clients" :projects="$projects" />
</div>
@endsection