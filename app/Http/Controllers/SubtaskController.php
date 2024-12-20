<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    public function index()
    {
           // Retrieve all subtasks with their associated material costs, equipment costs, and labor costs
        $subtasks = Subtask::with('materialCosts', 'equipmentCosts', 'laborCosts')->get();

        $data = $subtasks->map(function ($subtask) {
            return [
                'id' => $subtask->id,
                'name' => $subtask->name,
                'status' => $subtask->status,
                'start_date' => $subtask->start_date,
                'end_date' => $subtask->end_date,
                'total_material_amount' => $subtask->materialCosts->sum('amount'),
                'total_equipment_amount' => $subtask->equipmentCosts->sum('amount'),
                'total_labor_amount' => $subtask->laborCosts->sum('amount'),
            ];
        });

        // Pass the data to the view
       // return view('subtasks.index', ['data' => $data]);

       return $subtasks;
    }

    public function create()
    {
        // Return subtask create view
        return view('subtasks.create');
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'task_id' => 'required|exists:tasks,id',
            'status' =>'',
            'progress' =>'',
            'estimated_date' => '',
            'priority' =>''

        ]);
        $id = $validatedData['task_id'];
        // Create a new subtask instance
        Subtask::create($validatedData);

        // Redirect to the subtasks index page with a success message
      // return redirect()->route('subtasks.index')->with('success', 'Subtask created successfully.');
    // return "hello";


    $tasks = Task::all();
    $task = Task::findOrFail($id);

    $subtasks1 = Task::select('id', 'title')
        ->with(['subtasks' => function ($query) {
            $query->select('start_date', 'end_date', 'name', 'progress', 'task_id', 'status');
        }])
        ->get();

    $subtasks = Subtask::with('materialCosts', 'equipmentCosts', 'laborCosts')
        ->where('task_id', $id)
        ->get();

        $totalCompleted = $subtasks->where('status', 'completed')->count();
        $totalPending = $subtasks->where('status', 'pending')->count();
        $totalnotstarted = $subtasks->where('status', 'not start')->count();
        $totalCancelled = $subtasks->where('status', 'cancel')->count();

    $data = $subtasks->map(function ($subtask) {
        $materialCosts = $subtask->materialCosts ?? collect([]); // Handle potential null materialCosts relationship
        $materialCostsData = $materialCosts->map(function ($materialCost) {
            return [
                'name' => $materialCost->name,
                'unit' => $materialCost->unit,
                'planQty' => $materialCost->qty,
                'ActualQty' => $materialCost->actualQty,
                'plancost' => $materialCost->amount,
                'Actualcost' => $materialCost->actualCost,
            ];
        });

        return [
            'id' => $subtask->id,
            'task_name' => $subtask->name,
            'status' => $subtask->status,
            'lead_time' => $subtask->lead_time,
            'priority' => $subtask->priority,
            'planned' => $subtask->planned,
            'actual' => $subtask->actual,
            'start_date' => $subtask->start_date,
            'estimated_date' => $subtask->estimated_date,
            'end_date' => $subtask->end_date,
            'progress' => $subtask->progress,
            'materialCosts' => $materialCostsData,
            'total_material_amount' => $subtask->materialCosts->sum('amount'),
            'total_equipment_amount' => $subtask->equipmentCosts->sum('amount'),
            'total_labor_amount' => $subtask->laborCosts->sum('amount'),
        ];
    });

    return view('tasks.task_information', [
        'task' => $task,
        'tasks' => $tasks,
        'subtasks1' => $subtasks1,
        'data' => $data,
        'totalCompleted' => $totalCompleted,
        'totalPending' => $totalPending,
        'totalnotstarted' => $totalnotstarted,
        'totalCancelled' => $totalCancelled,
    ]);
    }

    public function show(Subtask $subtask)
    {
        // Return subtask show view with the subtask data
        return view('subtasks.show', compact('subtask'));
    }

    public function edit(Subtask $subtask)
    {
        // Return subtask edit view with the subtask data
        return view('subtasks.edit', compact('subtask'));
    }

    public function update(Request $request, Subtask $subtask)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'task_id' => 'required|exists:tasks,id',
        ]);

        // Update the subtask with the validated data
        $subtask->update($validatedData);

        // Redirect to the subtask show page with a success message
        return redirect()->route('subtasks.show', $subtask)->with('success', 'Subtask updated successfully.');
    }

    public function destroy(Subtask $subtask)
    {
        // Delete the subtask
        $subtask->delete();

        // Redirect to the subtasks index page with a success message
        return redirect()->route('subtasks.index')->with('success', 'Subtask deleted successfully.');
    }
}