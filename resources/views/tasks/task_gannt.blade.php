<!DOCTYPE html>
<html>
<head>
    <title>Gantt Chart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .gantt-chart {
            display: flex;
            flex-direction: column;
            width: 100%;
            height: 500px;
            border: 1px solid #ccc;
            overflow-x: auto;
        }

        .gantt-row {
            display: flex;
            height: 50px;
            border-bottom: 1px solid #ccc;
        }

        .gantt-label {
            flex: 1;
            display: flex;
            align-items: center;
            padding-left: 10px;
            font-weight: bold;
        }

        .gantt-bar {
            position: relative;
            background-color: #007bff;
            margin: 10px;
            height: 30px;
            border-radius: 5px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .gantt-bar::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #007bff;
            transform: translateY(-50%);
            z-index: -1;
        }

        .gantt-bar::after {
            content: "";
            position: absolute;
            top: 50%;
            left: 100%;
            width: 20px;
            height: 20px;
            background-color: #007bff;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            z-index: -1;
        }

        .gantt-bar:hover {
            background-color: #0056b3;
            cursor: pointer;
        }

        .gantt-bar:hover::before,
        .gantt-bar:hover::after {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="gantt-chart">
        @php
            $tasks = [
                ['name' => 'Task 1', 'duration' => 5],
                ['name' => 'Task 2', 'duration' => 10],
                ['name' => 'Task 3', 'duration' => 8],
                ['name' => 'Task 4', 'duration' => 12],
                ['name' => 'Task 5', 'duration' => 7],
            ];
        @endphp

        @foreach($tasks as $task)
        <div class="gantt-row">
            <div class="gantt-label">{{ $task['name'] }}</div>
            <div class="gantt-bar" style="width: {{ $task['duration'] * 50 }}px;">
                <i class="fas fa-tasks"></i> {{ $task['name'] }} ({{ $task['duration'] }} days)
            </div>
        </div>
        @endforeach
    </div>
</body>
</html>