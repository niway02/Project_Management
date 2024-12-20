<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        h1, h2 {
            text-align: center;
            color: #4A4A4A;
        }
        img {
            display: block;
            margin: 0 auto;
            width: 150px;
            height: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .notes {
            margin: 20px 0;
            font-style: italic;
        }
    </style>
</head>
<body>
    <h1>{{ $company_title }}</h1>
    <img src="{{ $logo }}" alt="Company Logo"/>
    <h2>Project Report</h2>
    <p class="notes">Notes: {!! $notes !!}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Project Name</th>
                <th>Site</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Priority</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $invoice)
                <tr>
                    <td>{{ $index + 1 }}</td> <!-- Incremental numbering -->
                    <td>{{ $invoice->title }}</td>
                    <td>{{ $invoice->site_id }}</td> <!-- Assuming site relation gives name -->
                    <td>{{ \Carbon\Carbon::parse($invoice->start_date)->format('Y-m-d') }}</td> <!-- Convert string to Carbon -->
                    <td>{{ \Carbon\Carbon::parse($invoice->end_date)->format('Y-m-d') }}</td> <!-- Convert string to Carbon -->
                    <td>{{ $invoice->priority_id }}</td> <!-- Assuming priority relation gives name -->
                    <td>{{ $invoice->status_id }}</td> <!-- Assuming status relation gives name -->
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>