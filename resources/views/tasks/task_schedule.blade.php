<h1>Task Management Schedule</h1>

<style>
    .calendar {
        width: 100%;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
    }

    .calendar th, .calendar td {
        padding: 10px;
        text-align: center;
        border: 1px solid #ddd;
    }

    .calendar th {
        background-color: #f2f2f2;
    }

    .calendar .selected-month {
        background-color: #f2f2f2;
    }

    .calendar .today {
        background-color: #e6f2ff;
    }

    .calendar-day {
        position: relative;
        height: 80px;
        overflow-y: auto;
    }

    .schedule-item {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
    }

    .schedule-bar {
        flex-grow: 1;
        height: 10px;
        border-radius: 5px;
        margin-left: 5px;
    }

    .schedule-title {
        font-weight: bold;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

<form method="GET" class="shortcut">
    <label for="date">Select a date:</label>
    <input type="month" id="date" name="month" value="{{ date('Y-m') }}" onchange="this.form.submit()">
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
        $selectedMonth = request()->input('month', date('Y-m'));
        $startDate = new DateTime($selectedMonth . '-01');
        $endDate = new DateTime(date('Y-m-t', strtotime($selectedMonth)));
        $dateRange = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
        $weekIndex = 0;
    @endphp

    @foreach ($dateRange as $date)
        @php
            $isCurrentMonth = (substr($date->format('Y-m-d'), 0, 7) === $selectedMonth);
            $isToday = ($date->format('Y-m-d') === date('Y-m-d'));
            $scheduleItems = [
                [
                    'title' => 'Sub Task 1',
                    'start' => '2023-05-01',
                    'end' => '2023-05-10',
                    'color' => '#' . dechex(rand(0x000000, 0xFFFFFF)),
                ],
                [
                    'title' => 'Sub Task 2',
                    'start' => '2023-05-15',
                    'end' => '2023-05-20',
                    'color' => '#' . dechex(rand(0x000000, 0xFFFFFF)),
                ],
            ];
        @endphp

        @if ($weekIndex % 7 == 0)
            <tr>
        @endif

        <td @if ($isCurrentMonth) class="selected-month" @endif>
            <div class="calendar-day @if ($isToday) today @endif @if (count($scheduleItems) > 0) has-schedule @endif">
                <div class="day-number">{{ $date->format('j') }}</div>
                @foreach ($scheduleItems as $scheduleItem)
                    <div class="schedule-item">
                        <div class="schedule-title">{{ $scheduleItem['title'] }}</div>
                        <div class="schedule-bar" style="background-color: {{ $scheduleItem['color'] }}; width: {{ (strtotime($scheduleItem['end']) - strtotime($scheduleItem['start'])) / (strtotime($endDate->format('Y-m-d')) - strtotime($startDate->format('Y-m-d'))) * 100 }}%;"></div>
                    </div>
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
