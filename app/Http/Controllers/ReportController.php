<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use PDF; // Ensure you have a PDF library like DomPDF installed
use App\Models\Activity; // Import your Activity model
use App\Models\Project; // Import your Activity model
use Carbon\Carbon; // Import Carbon for date handling

class ReportController extends Controller
{
    public function generate(Request $request)
    {
        // Get the date range, status, and task_id from the request
        $dateRange = trim($request->query('start_date')); // Assuming both dates are passed in the same field
        $status = $request->query('status');
        $taskId = $request->query('task_id'); // Get task_id from the request

        // Initialize startDate and endDate
        $startDate = null;
        $endDate = null;

        // Check if dateRange is provided
        if ($dateRange) {
            // Split the date range string
            $dates = explode(' To ', $dateRange);
            if (count($dates) === 2) {
                try {
                    // Parse and convert to Carbon instances
                    $startDate = Carbon::createFromFormat('d-m-Y', trim($dates[0]))->startOfDay();
                    $endDate = Carbon::createFromFormat('d-m-Y', trim($dates[1]))->endOfDay();
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'Invalid date format. Please use "dd-mm-yyyy To dd-mm-yyyy".');
                }
            } else {
                return redirect()->back()->with('error', 'Invalid date range format. Please use "Start Date To End Date".');
            }
        }

        // Build the query to fetch activities based on filters
        $query = Activity::query();

        // Apply task_id filter if provided
        if ($taskId) {
            $query->where('task_id', $taskId); // Filter by task_id
        }

        // Apply date range filter
        if ($startDate) {
            $query->where('start_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('end_date', '<=', $endDate);
        }

        // Apply status filter
        if ($status) {
            $query->where('status', $status);
        }

        // Fetch the filtered data
        $data = $query->get();
       
        // Assume format is 'pdf' or 'csv', default to 'pdf'
        $format = $request->query('format', 'pdf');

        if ($format === 'pdf') {
            // Generate PDF
            $pdf = PDF::loadView('reports.activity.pdf', ['data' => $data]);
            return $pdf->download('report.pdf');
        } elseif ($format === 'csv') {
            // Generate CSV
            $filename = 'report.csv'; // Adjusted filename
            // Set headers for CSV output
            header('Content-Type: text/csv');
            header("Content-Disposition: attachment; filename=$filename");

            // Open output stream
            $handle = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($handle, ['ID', 'WBS', 'Activity Name', 'Priority', 'Start Date', 'End Date']);

            // Add data rows
            foreach ($data as $row) {
                fputcsv($handle, [
                    $row->id,
                    $row->wbs,
                    $row->activity_name,
                    $row->priority,
                    $row->start_date,
                    $row->end_date,
                ]);
            }

            fclose($handle);
            exit; // Terminate the script to prevent further output
        }

        return redirect()->back()->with('error', 'Unsupported format.');
    }
    public function projectReport(Request $request)
    {
        // Get the date range, status, and task_id from the request
        $dateRange = trim($request->query('start_date')); // Assuming both dates are passed in the same field
        $status = $request->query('status');
        $taskId = $request->query('task_id'); // Get task_id from the request

        // Initialize startDate and endDate
        $startDate = null;
        $endDate = null;

        // Check if dateRange is provided
        if ($dateRange) {
            // Split the date range string
            $dates = explode(' To ', $dateRange);
            if (count($dates) === 2) {
                try {
                    // Parse and convert to Carbon instances
                    $startDate = Carbon::createFromFormat('d-m-Y', trim($dates[0]))->startOfDay();
                    $endDate = Carbon::createFromFormat('d-m-Y', trim($dates[1]))->endOfDay();
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'Invalid date format. Please use "dd-mm-yyyy To dd-mm-yyyy".');
                }
            } else {
                return redirect()->back()->with('error', 'Invalid date range format. Please use "Start Date To End Date".');
            }
        }

        // Build the query to fetch activities based on filters
        $query = Project::query();

        // Apply task_id filter if provided
        if ($taskId) {
            $query->where('task_id', $taskId); // Filter by task_id
        }

        // Apply date range filter
        if ($startDate) {
            $query->where('start_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('end_date', '<=', $endDate);
        }

        // Apply status filter
        if ($status) {
            $query->where('status', $status);
        }

        // Fetch the filtered data
        $data = $query->get();
       
        // Assume format is 'pdf' or 'csv', default to 'pdf'
        $format = $request->query('format', 'pdf');

        if ($format === 'pdf') {
            // Generate PDF
            $pdf = PDF::loadView('reports.activity.pdf', ['data' => $data]);
            return $pdf->download('report.pdf');
        } elseif ($format === 'csv') {
            // Generate CSV
            $filename = 'report.csv'; // Adjusted filename
            // Set headers for CSV output
            header('Content-Type: text/csv');
            header("Content-Disposition: attachment; filename=$filename");

            // Open output stream
            $handle = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($handle, ['ID', 'WBS', 'Activity Name', 'Priority', 'Start Date', 'End Date']);

            // Add data rows
            foreach ($data as $row) {
                fputcsv($handle, [
                    $row->id,
                    $row->wbs,
                    $row->activity_name,
                    $row->priority,
                    $row->start_date,
                    $row->end_date,
                ]);
            }

            fclose($handle);
            exit; // Terminate the script to prevent further output
        }

        return redirect()->back()->with('error', 'Unsupported format.');
    }
}