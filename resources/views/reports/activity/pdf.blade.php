<!DOCTYPE html>
<html>
<head>
    <title>Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Report</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>WBS</th>
                <th>Name</th>
                <th>Priority</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Progress</th>
                <th>status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->wbs }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->priority }}</td>
                    <td>{{ $row->start_date }}</td>
                    <td>{{ $row->end_date }}</td>
                    <td>{{ $row->duration }}</td>
                    <td>{{ $row->progress }}</td>
                    <td>{{ $row->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>