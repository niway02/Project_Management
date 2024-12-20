<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BudgetAllocation;
use App\Models\Warehouse;
use App\Models\EquipmentInventory;
use App\Models\Project;
use App\Models\PaymentMethod;
use App\Models\Priority;
use App\Models\User;
use App\Models\Workspace;
use App\Models\MaterialCost;
use App\Models\EquipmentCost;
use App\Models\LaborCost;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class BudgetAllocationController extends Controller
{
    protected $workspace;
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->workspace = Workspace::find(session()->get('workspace_id'));
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $budgetAllocations = BudgetAllocation::with('project')
            ->when($request->has('project_name'), function ($query) use ($request) {
                $query->whereHas('project', function ($subQuery) use ($request) {
                    $subQuery->where('name', 'like', '%' . $request->project_name . '%');
                });
            })
            ->paginate(10);
        /* return response()->json([
                'total' => $budgetAllocations,
            ]);*/
        $projects = Project::all();
        $paymentMethods = PaymentMethod::all();
        $status = [
            ['id' => 1, 'title' => 'Approved'],
            ['id' => 2, 'title' => 'Pending'],
            ['id' => 3, 'title' => 'Rejected'],
        ];
        $priorities = Priority::all();
        return view('budget.index', compact('budgetAllocations', 'projects', 'paymentMethods', 'status', 'priorities'));
    }

    public function projetBudgetData(Request $request)
    {
        $search = $request->get('search');
        $limit = $request->get('limit');
        $offset = $request->get('offset');

        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');

        $totalEquipment = BudgetAllocation::with('project')->get();
        $filteredEquipment = $totalEquipment;

        if ($search) {
            $filteredEquipment = $totalEquipment->filter(function ($item) use ($search) {
                return stripos($item->item, $search) !== false || stripos($item->unit_name, $search) !== false;
            });
        }

        $filteredEquipment = $filteredEquipment->sortBy($sort, SORT_REGULAR, $order === 'desc');

        $totalItems = $filteredEquipment->count();
        $filteredEquipment = $filteredEquipment->slice($offset, $limit);

        return response()->json([
            'total' => $totalItems,
            'rows' => $filteredEquipment
        ]);
    }

    public function create()
    {
        $projects = Project::all();
        $paymentMethods = PaymentMethod::all();
        $priorities = Priority::all();

        return view('budget.create', compact('projects', 'paymentMethods', 'priorities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'amount' => ['required', 'numeric'],
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'priority' => ['required', 'exists:priorities,id'],
            'planned_budget' => ['required'],
            'status' => ['required', 'in:planned,in_progress,completed'],
        ]);

        $budgetAllocation = new BudgetAllocation();
        $budgetAllocation->project_id = $validated['project_id'];
        $budgetAllocation->amount = $validated['amount'];
        $budgetAllocation->payment_method_id = $validated['payment_method_id'];
        $budgetAllocation->priority = $validated['priority'];
        $budgetAllocation->planned_budget = $validated['planned_budget'];
        $budgetAllocation->status = $validated['status'];
        $budgetAllocation->user_id = $this->user->id;
        $budgetAllocation->save();

        return response()->json([
            'success' => true,
            'message' => 'Budget allocation created successfully.',
            'budget_allocation_id' => $budgetAllocation->id,
        ]);
    }
    public function updateActualCost(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'id' => 'required|integer', // Adjust the validation rules as needed
            'actualQuantity' => 'required|numeric',
            'actualCost' => 'required|numeric',
            'resourceType' => 'required|string', // Validate resource type
        ]);

        // Find the budget item by ID and update it
        if($validatedData['resourceType'] ==  "material"){
            $budgetItem = MaterialCost::find($validatedData['id']);
            if ($budgetItem) {
                $budgetItem->actual_quantity = $validatedData['actualQuantity'];
                $budgetItem->actual_cost = $validatedData['actualCost'];
                $budgetItem->save();
        
                // Return a JSON response
                return response()->json([
                    'success' => true,
                    'message' =>  $validatedData['resourceType'],
                    'data' => $validatedData['actualCost'],
                ]);
                return redirect()->route('budget.update');
            }
        } elseif($validatedData['resourceType'] ==  "equipment"){
            $actualcostequipment = EquipmentCost::find($validatedData['id']);
            if ($actualcostequipment) {
                $actualcostequipment->actual_quantity = $validatedData['actualQuantity'];
                $actualcostequipment->actual_cost = $validatedData['actualCost'];
                $actualcostequipment->save();
        
                // Return a JSON response
                return response()->json([
                    'success' => true,
                    'message' =>  $validatedData['resourceType'],
                    'data' => $validatedData['actualCost'],
                ]);
                return redirect()->route('budget.update');
            }

        }elseif($validatedData['resourceType'] ==  "labor"){
            $actualCostLabor = LaborCost::find($validatedData['id']);
            if ($actualCostLabor) {
                $actualCostLabor->actual_quantity = $validatedData['actualQuantity'];
                $actualCostLabor->actual_cost = $validatedData['actualCost'];
                $actualCostLabor->save();
        
                // Return a JSON response
                return response()->json([
                    'success' => true,
                    'message' =>  $validatedData['resourceType'],
                    'data' => $validatedData['actualCost'],
                ]);
                return redirect()->route('budget.update');
            }

        }else{
            return response()->json([
                'success' => false,
                'message' => 'Resource  not found.',
            ], 404);
        }
        
    return response()->json([
        'success' => false,
        'message' => 'Budget item not found.',
    ], 404);
    }
    public function show($id)
    {
        $budgetAllocation = BudgetAllocation::findOrFail($id);
        $tasks = MaterialCost::query()
            ->select('id', 'planned_quantity', 'actual_quantity', 'planned_cost', 'actual_cost', 'activity_id')
            ->with(['activity' => function ($query) {
                $query->with(['task' => function ($q) {
                    $q->select('id', 'title');
                }]);
            }])
            ->get();
           
        $subTaskData = $tasks->groupBy('activity_id')
            ->map(function ($materialCosts, $subTaskId) {
                $plannedQuantity = $materialCosts->sum('planned_quantity');
                $actualQuantity = $materialCosts->sum('actual_quantity');
                $plannedBudget = $materialCosts->sum('planned_budget');
                $actualBudget = $materialCosts->sum('actual_budget');
                $subTaskName = $materialCosts->first()->activity->name;
    
                return [
                    'activity_id' => $subTaskId,
                    'activity_name' => $subTaskName,
                    'planned_quantity' => $plannedQuantity,
                    'actual_quantity' => $actualQuantity,
                    'planned_budget' => $plannedBudget,
                    'actual_budget' => $actualBudget,
                ];
            })
            ->values()
            ->toArray();
           
        $taskData = $tasks->groupBy('activity.task.id')
            ->map(function ($materialCosts, $taskId) {
                $plannedQuantity = $materialCosts->sum('planned_quantity');
                $actualQuantity = $materialCosts->sum('actual_quantity');
                $plannedBudget = $materialCosts->sum('planned_budget');
                $actualBudget = $materialCosts->sum('actual_budget');
                $taskName = $materialCosts->first()->activity->task->title;
    
                return [
                    'task_id' => $taskId,
                    'task_name' => $taskName,
                    'planned_quantity' => $plannedQuantity,
                    'actual_quantity' => $actualQuantity,
                    'planned_budget' => $plannedBudget,
                    'actual_budget' => $actualBudget,
                ];
            })
            ->values()
            ->toArray();
       /* return response()->json([
                'tasks' => $taskData,
            ]);*/
          
            $equipmentPlannedBudget = 560;
            $materialPlannedBudget = 560;
            $laborPlannedBudget = 560;

            $equipmentActualBudget = 600;
            $materialActualBudget = 700;
            $laborActualBudget = 800;

            $totalPlannedBudget  = $equipmentActualBudget + $materialPlannedBudget + $laborPlannedBudget;
            $totalActualBudget  = $equipmentActualBudget + $materialActualBudget + $laborActualBudget;
        return view('budget.show', compact('budgetAllocation', 'taskData', 'subTaskData','totalPlannedBudget','totalActualBudget','laborPlannedBudget','laborActualBudget','materialPlannedBudget','materialActualBudget','equipmentActualBudget','equipmentPlannedBudget'));
    }
    public function budgetOverview(Request $request)
    {
        $selectedMonth = $request->get('month', date('Y-m'));
    
        if (!preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
            $selectedMonth = date('Y-m');
        }
    
        [$selectedYear, $selectedMonth] = explode('-', $selectedMonth);
        $startDate = \Carbon\Carbon::createFromFormat('Y-m', "$selectedYear-$selectedMonth")->startOfMonth();
        $endDate = \Carbon\Carbon::createFromFormat('Y-m', "$selectedYear-$selectedMonth")->endOfMonth();
    
        $weeks = [
            'week1' => [$startDate->copy()->startOfWeek(), $startDate->copy()->startOfWeek()->addDays(6)],
            'week2' => [$startDate->copy()->addWeek()->startOfWeek(), $startDate->copy()->addWeek()->startOfWeek()->addDays(6)],
            'week3' => [$startDate->copy()->addWeeks(2)->startOfWeek(), $startDate->copy()->addWeeks(2)->startOfWeek()->addDays(6)],
            'week4' => [$startDate->copy()->addWeeks(3)->startOfWeek(), $startDate->copy()->addWeeks(3)->startOfWeek()->addDays(6)],
        ];
    
        $materialTotalPlannedBudget = [];
        $materialTotalActualBudget = [];
        $materialCostData = [];
        $equipmentTotalPlannedBudget = [];
        $equipmentTotalActualBudget = [];
        $equipmentCostData = [];
        $laborTotalPlannedBudget = [];
        $laborTotalActualBudget = [];
        $laborCostData = [];
    
        foreach ($weeks as $key => $dates) {
            $materialCosts = MaterialCost::whereHas('activity', function ($query) use ($dates) {
                $query->whereBetween('start_date', [$dates[0]->format('Y-m-d'), $dates[1]->format('Y-m-d')]);
            })->with('activity')->get();
            $materialCostData[$key] = $materialCosts;
            // Sum planned and actual budgets
            $materialTotalPlannedBudget[$key] = $materialCosts->sum('planned_cost');
            $materialTotalActualBudget[$key] = $materialCosts->sum('actual_cost');
        }
    

        foreach ($weeks as $key => $dates) {
            $equipmentCosts = EquipmentCost::whereHas('activity', function ($query) use ($dates) {
                $query->whereBetween('start_date', [$dates[0]->format('Y-m-d'), $dates[1]->format('Y-m-d')]);
            })->with('activity')->get();
            $equipmentCostData[$key] = $equipmentCosts;
            // Sum planned and actual budgets
            $equipmentTotalPlannedBudget[$key] = $equipmentCosts->sum('planned_cost');
            $equipmentTotalActualBudget[$key] = $equipmentCosts->sum('actual_cost');
        }

       
    
        foreach ($weeks as $key => $dates) {
            $laborCosts = LaborCost::whereHas('activity', function ($query) use ($dates) {
                $query->whereBetween('start_date', [$dates[0]->format('Y-m-d'), $dates[1]->format('Y-m-d')]);
            })->with('activity')->get();
            $laborCostData[$key] = $laborCosts;
            // Sum planned and actual budgets
            $laborTotalPlannedBudget[$key] = $laborCosts->sum('planned_cost');
            $laborTotalActualBudget[$key] = $laborCosts->sum('actual_cost');
        }
        $totalPlannedBudget = $materialTotalPlannedBudget + $equipmentTotalPlannedBudget +   $laborTotalPlannedBudget;
        $totalActualBudget = $materialTotalActualBudget + $equipmentTotalActualBudget + $laborTotalActualBudget;
        
        //return response()->json(['total' => $materialCostData]);
        return view('budget.overview', compact('selectedMonth', 'selectedYear', 'totalPlannedBudget', 'totalActualBudget','materialCostData','equipmentCostData','laborCostData'));
    }
   /* public function budgetOverview(Request $request)
    {
        $selectedMonth = $request->get('month', date('Y-m'));
    
        // Validate the selected month format
        if (!preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
            $selectedMonth = date('Y-m');
        }
    
        [$selectedYear, $selectedMonth] = explode('-', $selectedMonth);
    
        // Define the start and end dates for the selected month
        $startDate = \Carbon\Carbon::createFromFormat('Y-m', "$selectedYear-$selectedMonth")->startOfMonth();
        $endDate = \Carbon\Carbon::createFromFormat('Y-m', "$selectedYear-$selectedMonth")->endOfMonth();
    
        // Calculate budgets for each week
        $weeks = [
            'week1' => [$startDate->copy()->startOfWeek(), $startDate->copy()->startOfWeek()->addDays(6)],
            'week2' => [$startDate->copy()->addWeek()->startOfWeek(), $startDate->copy()->addWeek()->startOfWeek()->addDays(6)],
            'week3' => [$startDate->copy()->addWeeks(2)->startOfWeek(), $startDate->copy()->addWeeks(2)->startOfWeek()->addDays(6)],
            'week4' => [$startDate->copy()->addWeeks(3)->startOfWeek(), $startDate->copy()->addWeeks(3)->startOfWeek()->addDays(6)],
        ];
    
        $total = [
            'week1' => 0,
            'week2' => 0,
            'week3' => 0,
            'week4' => [],
        ];
    
        foreach ($weeks as $key => $dates) {
            // Fetch material costs based on the activity's start_date
            $materialCosts = MaterialCost::whereHas('activity', function ($query) use ($dates) {
                $query->whereBetween('start_date', [$dates[0]->format('Y-m-d'), $dates[1]->format('Y-m-d')]);
            })
            ->with('activity')
            ->get();
    
            // Debugging output
            if ($materialCosts->isEmpty()) {
                \Log::info("No material costs found for {$key} between " . $dates[0] . " and " . $dates[1]);
            } else {
                \Log::info("Material costs found for {$key}: " . $materialCosts->toJson());
            }
    
            // Calculate total planned and actual costs
            if ($key !== 'week4') {
                $total[$key] = $materialCosts->sum('planned_cost');
            } else {
                $total[$key] = $materialCosts; // Store the detailed data for week4
            }
        }
    
        // Return JSON response
        return response()->json(['total' => $total]);
    }*/
/*    public function budgetOverview(Request $request)
{
    $selectedMonth = $request->get('month', date('Y-m')); // Default to current month

    // Validate the selected month format
    if (!preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
        // If the format is not valid, set it to the current month
        $selectedMonth = date('Y-m');
    }

    [$selectedYear, $selectedMonth] = explode('-', $selectedMonth);

    // Define the start and end dates for the selected month
    $startDate = \Carbon\Carbon::createFromFormat('Y-m', "$selectedYear-$selectedMonth")->startOfMonth();
    $endDate = \Carbon\Carbon::createFromFormat('Y-m', "$selectedYear-$selectedMonth")->endOfMonth();

    // Calculate budgets for each week
    $weeks = [
        'week1' => [$startDate->copy()->startOfWeek(), $startDate->copy()->endOfWeek()],
        'week2' => [$startDate->copy()->addWeek()->startOfWeek(), $startDate->copy()->addWeek()->endOfWeek()],
        'week3' => [$startDate->copy()->addWeeks(2)->startOfWeek(), $startDate->copy()->addWeeks(2)->endOfWeek()],
        'week4' => [$startDate->copy()->addWeeks(3)->startOfWeek(), $startDate->copy()->addWeeks(3)->endOfWeek()],
    ];

    $totalPlannedBudget = [];
    $totalActualBudget = [];

    foreach ($weeks as $key => $dates) {
        // Using whereHas to filter based on the related activity
        $totalPlannedBudget[$key] = MaterialCost::with('activity')->get();

        $totalPlannedBudget[$key] = MaterialCost::whereHas('activity', function ($query) use ($dates) {
            $query->whereBetween('start_date', $dates);
        })->sum('planned_cost');

        $totalActualBudget[$key] = MaterialCost::whereHas('activity', function ($query) use ($dates) {
            $query->whereBetween('start_date', $dates);
        })->sum('actual_cost');
    }
    $totalPlannedBudget[$key] = MaterialCost::with('activity')->get();

return response()->json([
            'total' => $totalPlannedBudget,
        ]);
    return view('budget.overview', compact('selectedMonth', 'selectedYear', 'totalPlannedBudget', 'totalActualBudget'));
}*/
    public function getMaterialCosts(Request $request)
    {
        $search = $request->get('search', '');
        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 0);
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');
    
        $query = MaterialCost::query() ->with('Material.UnitMeasure', 'Activity.task');
    
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('item', 'like', '%' . $search . '%')
                  ->orWhere('unit_name', 'like', '%' . $search . '%');
            });
        }
    
        $totalItems = $query->count();
        $materials = $query->orderBy($sort, $order)
                           ->skip($offset)
                           ->take($limit)
                           ->get();
    
        return response()->json([
            'total' => $totalItems,
            'rows' => $materials
        ]);
    }
    // Add more methods as needed (edit, update, delete, etc.)
}