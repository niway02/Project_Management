@extends('layout')

@section('content')
    <h1>Task Management Schedule</h1>

    <style>
        .calendar {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
        }

        .calendar th, .calendar td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        .calendar th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .calendar .selected-month {
            background-color: #e0f7fa;
        }

        .calendar .today {
            background-color: #b3e5fc;
        }

        .calendar-day {
            position: relative;
            height: 120px; /* Increased height for better visibility */
            overflow: hidden; /* Hide overflow */
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .schedule-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
            padding: 5px;
            border-radius: 4px;
            color: #fff;
            font-size: 0.9em; /* Smaller font size for items */
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis; /* Truncate text */
            transition: background-color 0.3s;
        }

        .schedule-item:hover {
            background-color: #555; /* Darker shade on hover */
        }

        .schedule-color {
            width: 12px;
            height: 12px;
            margin-right: 8px;
            border-radius: 50%;
        }

        .activity-container {
            max-height: 80px; /* Limit height for scrolling */
            overflow-y: auto; /* Enable scrolling */
            margin-top: 5px; /* Space between task and activities */
        }

        .activity-item {
            padding: 5px;
            margin-left: 20px; /* Indent for activities */
            border-radius: 4px;
            font-size: 0.75em; /* Smaller font size for activities */
            color: #fff;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis; /* Truncate text */
            transition: background-color 0.3s;
        }

        .activity-item:hover {
            opacity: 0.8; /* Slightly transparent on hover */
        }

        .shortcut {
            margin-bottom: 20px;
        }

        .day-number {
            font-size: 1.5em;
            font-weight: bold;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .calendar th, .calendar td {
                font-size: 0.8em; /* Smaller font on mobile */
            }

            .day-number {
                font-size: 1.2em; /* Adjust size on mobile */
            }
        }
    </style>

    @php
        $selectedMonth = request()->input('month', date('Y-m'));
        $startDate = new DateTime($selectedMonth . '-01');
        $endDate = new DateTime(date('Y-m-t', strtotime($selectedMonth)));
        $dateRange = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
        $days = [];
        foreach ($dateRange as $date) {
            $days[] = $date->format('Y-m-d');
        }

        // Generate random colors for activities
        function randomColor() {
            return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        }
    @endphp

    <form method="GET" class="shortcut">
        <label for="date">Select a date:</label>
        <input type="month" id="date" name="month" value="{{ $selectedMonth }}" onchange="this.form.submit()" class="form-control">
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
        @php
            $weekIndex = 0;
        @endphp
        @foreach ($days as $day)
            @php
                $isCurrentMonth = (substr($day, 0, 7) === $selectedMonth);
                $isToday = ($day === date('Y-m-d'));
                $scheduleItems = [];
                foreach ($tasks as $task) {
                    if ($day >= $task->start_date && $day <= $task->due_date) {
                        $scheduleItems[] = [
                            'title' => $task->title,
                            'start' => $task->start_date,
                            'end' => $task->due_date,
                            'activities' => $task->activities,
                            'color' => '#' . dechex(rand(0x000000, 0xFFFFFF)), // Task color
                        ];
                    }
                }
            @endphp
            @if ($weekIndex % 7 == 0)
                <tr>
            @endif
            <td @if ($isCurrentMonth) class="selected-month" @endif>
                <div class="calendar-day @if ($isToday) today @endif">
                    <div class="day-number">{{ date('j', strtotime($day)) }}</div>
                    @foreach ($scheduleItems as $scheduleItem)
                        <div class="schedule-item" style="background-color: {{ $scheduleItem['color'] }};">
                            <div class="schedule-color" style="background-color: {{ $scheduleItem['color'] }};"></div>
                            <div class="schedule-title" title="{{ $scheduleItem['title'] }}">{{ $scheduleItem['title'] }}</div>
                        </div>

                        <div class="activity-container">
                            @foreach ($scheduleItem['activities'] as $activity)
                                <div class="activity-item" style="background-color: {{ randomColor() }};" title="{{ $activity['name'] }}">
                                    <div class="activity-title">{{ $activity['name'] }}</div>
                                </div>
                            @endforeach
                        </div>

                        @php
                            $startDate = strtotime($scheduleItem['start']);
                            $endDate = strtotime($scheduleItem['end']);
                            $dayDate = strtotime($day);
                            $totalDuration = $endDate - $startDate;
                        @endphp
                        @if ($totalDuration > 0)
                            <div class="schedule-line" style="
                                left: {{ ($dayDate - $startDate) / $totalDuration * 100 }}%;
                                width: {{ ($endDate - $dayDate) / $totalDuration * 100 }}%;
                                background-color: {{ $scheduleItem['color'] }};
                            "></div>
                        @endif
                    @endforeach
                </div>
            </td>
            @if ($weekIndex % 7 == 6)
                </tr>
            @endif
            @php
                $weekIndex++;
            @endphp
        @endforeach
    </table>
@endsection