@extends('layout')

@section('title')
<?= get_label('tasks', 'Tasks') ?> - <?= get_label('list_view', 'List view') ?>
@endsection

@section('content')
    <div class="container">
        <h1>Master Schedule</h1>
    <!DOCTYPE html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>
        <link href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css" rel="stylesheet">
        <style type="text/css">
            html, body{
                height:100%;
                padding:0px;
                margin:0px;
                overflow: hidden;
            }

            .gantt_task_line {
                background-color: #9370DB; /* Purple */
                border-color: #9370DB; /* Purple */
            }

            .gantt_task_progress {
                background-color: #8B008B; /* Dark Purple */
            }

            .gantt_grid_scale .gantt_grid_head_cell {
                background-color: #f1f1f1;
                color: #333;
            }

            .gantt_task_scale .gantt_scale_cell {
                background-color: #f1f1f1;
                color: #333;
            }

            .gantt_task_row:nth-child(even) {
                background-color: #f2f2f2;
            }

            .gantt_task_row:nth-child(odd) {
                background-color: #ffffff;
            }
        </style>
    </head>
    <body>
        <div id="gantt_here" style='width:100%; height:100%;'></div>
        <script type="text/javascript">
            gantt.config.date_format = "%Y-%m-%d";
            gantt.init("gantt_here");
            gantt.load("{{ route('gantt.data') }}")
        </script>
    </body>

    <div class="container">
    <h1>Master Schedule</h1>
    <div>
        <select id="year" onchange="updateSchedule()">
            <!-- Populate with years dynamically -->
            @for ($i = 2020; $i <= date('Y'); $i++)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
        <select id="month" onchange="updateSchedule()">
            @for ($i = 1; $i <= 12; $i++)
                <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
            @endfor
        </select>
        <select id="week" onchange="updateSchedule()">
            @for ($i = 1; $i <= 52; $i++)
                <option value="{{ $i }}">{{ 'Week ' . $i }}</option>
            @endfor
        </select>
    </div>
    
    <div id="schedule">
        <!-- Schedule will be displayed here -->
    </div>
</div>
    </div>
@endsection
<script>
function updateSchedule() {
    const year = document.getElementById('year').value;
    const month = document.getElementById('month').value;
    const week = document.getElementById('week').value;

    fetch(`/api/schedule?year=${year}&month=${month}&week=${week}`)
        .then(response => response.json())
        .then(data => {
            let scheduleHtml = '<ul>';
            data.forEach(project => {
                scheduleHtml += `<li>${project.name} (From: ${project.start_date} To: ${project.end_date})</li>`;
            });
            scheduleHtml += '</ul>';
            document.getElementById('schedule').innerHTML = scheduleHtml;
        });
}
</script>