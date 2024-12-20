
<!DOCTYPE html>
<html>
<head>
    <title>Task Management Schedule</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
        }

        .calendar {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .calendar th {
            padding: 10px;
            text-align: center;
            background-color: #f2f2f2;
            color: #333;
        }

        .calendar td {
            padding: 10px;
            border: 1px solid #ccc;
            vertical-align: top;
        }

        .selected-day {
            background-color: #a9c9f9;
            font-weight: bold;
        }

        .schedule {
            margin-bottom: 10px;
        }

        .schedule-title {
            font-weight: bold;
            margin-bottom: 5px;
            cursor: pointer;
        }

        .schedule-type {
            font-size: 12px;
        }

        .red-line {
            background-color: #ff6b6b;
        }

        .blue-line {
            background-color: #6bb9ff;
        }

        .green-line {
            background-color: #b9ff6b;
        }

        /* Add more color classes as needed */

        .selected-schedule {
            background-color: #ffecb3;
        }

        .shortcut {
            text-align: center;
            margin-top: 10px;
        }

        .shortcut a {
            color: #666;
            text-decoration: none;
            margin: 0 5px;
            border-bottom: 2px solid transparent;
            transition: border-color 0.3s;
        }

        .shortcut a:hover {
            border-color: #666;
        }
    </style>
</head>
<body>
    <?php
    // Example schedule data
    $schedules = [
        [
            'title' => 'Schedule 1',
            'start' => '2024-05-15',
            'end' => '2024-05-21',
            'type' => 'Type A',
            'color' => 'red'
        ],
        [
            'title' => 'Schedule 2',
            'start' => '2024-05-24',
            'end' => '2024-05-29',
            'type' => 'Type B',
            'color' => 'blue'
        ]
    ];

    // Get the available start and end days from the schedule data
    $startDays = array_column($schedules, 'start');
    $endDays = array_column($schedules, 'end');

    // Get the unique days from the start and end days
    $days = array_unique(array_merge($startDays, $endDays));

    // Sort the days in ascending order
    sort($days);

    // Set default start and end date to 2 weeks range
    $defaultStartDate = date('Y-m-d');
    $defaultEndDate = date('Y-m-d', strtotime('+2 weeks'));

    // Retrieve the selected month from the query parameters
    $selectedMonth = $_GET['month'] ?? date('Y-m');

    // Create a range of dates for the selected month
    $startDate = new DateTime($selectedMonth . '-01');
    $endDate = new DateTime($selectedMonth . '-01');
    $endDate->modify('last day of this month');

    // Create a list of dates in the range
    $dateRange = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
    $dates = [];
    foreach ($dateRange as $date) {
        $dates[] = $date->format('Y-m-d');
    }

    // Create a 2D array representing the calendar rows and columns
    $calendar = [];
    $numDays = count($dates);
    $numWeeks = ceil($numDays / 7);
    for ($row = 0; $row < $numWeeks; $row++) {
        $calendar[$row] = array_slice($dates, $row * 7, 7);
    }
    ?>

    <h1>Task Management Schedule</h1>

    <form method="GET" class="shortcut">
        <a href="?month=<?php echo date('Y-m', strtotime($selectedMonth . '-1 month')); ?>">&lt;</a>
        <a href="?month=<?php echo date('Y-m', strtotime($selectedMonth . '+1 month')); ?>">&gt;</a>
    </form>

    <table class="calendar">
        <tr>
            <th>Sun</th>
            <th>Mon</th>
            <th>Tue</th>
            <th>Wed</th>
            <th>Thu</th>
            <th>Fri</th>
            <th>Sat</th>
        </tr>
        <?php foreach ($calendar as $week): ?>
            <tr>
                <?php foreach ($week as $day): ?>
                    <?php
                    $isCurrentMonth = (substr($day, 0, 7) === $selectedMonth);
                    $isToday = ($day === date('Y-m-d'));
                    $hasSchedule = in_array($day, $days);
                    ?>
                    <td <?php if ($isCurrentMonth): ?>class="selected-month"<?php endif; ?>>
                        <?php if ($isToday): ?>
                            <div class="selected-day">
                                <?php echo date('j', strtotime($day)); ?>
                            </div>
                        <?php else: ?>
                            <div>
                                <?php echo date('j', strtotime($day)); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($hasSchedule): ?>
                            <div class="schedule">
                                <?php foreach ($schedules as $schedule): ?>
                                    <?php if ($day >= $schedule['start'] && $day <= $schedule['end']): ?>
                                        <div class="schedule-title <?php echo $schedule['color'] ?>-line">
                                            <?php echo $schedule['title']; ?>
                                        </div>
                                        <div class="schedule-type">
                                            <?php echo $schedule['type']; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>

    <form method="GET" class="shortcut">
        <a href="?month=<?php echo date('Y-m', strtotime($selectedMonth . '-1 month')); ?>">&lt;</a>
        <a href="?month=<?php echo date('Y-m', strtotime($selectedMonth . '+1 month')); ?>">&gt;</a>
    </form>
</body>
</html>