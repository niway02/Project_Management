<!-- resources/views/reminders/index.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Reminders</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h1 class="mb-4">Reminders</h1>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Due Date</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $reminders = [
                        [
                            'id' => 1,
                            'title' => 'Finish project proposal',
                            'description' => 'Complete the project proposal by Friday',
                            'due_date' => '2024-06-30'
                        ],
                        [
                            'id' => 2,
                            'title' => 'Schedule team meeting',
                            'description' => 'Set up a team meeting to discuss the project timeline',
                            'due_date' => '2024-07-15'
                        ],
                        [
                            'id' => 3,
                            'title' => 'Submit quarterly report',
                            'description' => 'Gather data and submit the quarterly report to the management',
                            'due_date' => '2024-08-01'
                        ]
                    ];
                @endphp

                @foreach($reminders as $reminder)
                <tr>
                    <td>{{ $reminder['title'] }}</td>
                    <td>{{ $reminder['description'] }}</td>
                    <td>{{ date('m/d/Y', strtotime($reminder['due_date'])) }}</td>
                 
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
