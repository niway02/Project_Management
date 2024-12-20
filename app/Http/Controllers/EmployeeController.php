<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
  
public function index()
{
    return view('employees.index');
}

public function data(Request $request)
{
    $search = $request->get('search');
    $limit = $request->get('limit');
    $offset = $request->get('offset');
  ///  $sort = $request->get('sort');
   // $order = $request->get('order');


    $sort = (request('sort')) ? request('sort') : "id";
    $order = 'desc';
    if ($sort == 'newest') {
        $sort = 'created_at';
        $order = 'desc';
    } elseif ($sort == 'oldest') {
        $sort = 'created_at';
        $order = 'asc';
    } elseif ($sort == 'recently-updated') {
        $sort = 'updated_at';
        $order = 'desc';
    } elseif ($sort == 'earliest-updated') {
        $sort = 'updated_at';
        $order = 'asc';
    }

    // Fetch the data from the database or any other source
    $employees = [
        ['id' => 1, 'name' => 'John Doe', 'email' => 'john.doe@example.com'],
        ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane.smith@example.com'],
        // Add more employees here
    ];

    // Filter, sort, and paginate the data as needed
    $filteredEmployees = $employees;
    if ($search) {
        $filteredEmployees = array_filter($filteredEmployees, function ($employee) use ($search) {
            return stripos($employee['name'], $search) !== false || stripos($employee['email'], $search) !== false;
        });
    }

    if ($sort && $order) {
        usort($filteredEmployees, function ($a, $b) use ($sort, $order) {
            $result = strcmp($a[$sort], $b[$sort]);
            return $order === 'desc' ? -$result : $result;
        });
    }

    $totalItems = count($filteredEmployees);
    $filteredEmployees = array_slice($filteredEmployees, $offset, $limit);

    return response()->json([
        'total' => $totalItems,
        'rows' => $filteredEmployees
    ]);
}
}