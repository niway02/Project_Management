<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Equipment;
use App\Models\EquipmentInventory;
use App\Models\MaterialsInventory;
use App\Models\Material;
use App\Models\MaterialRequestResponse;
use App\Models\User;
use App\Models\Workspace;
use App\Models\Labor;
use App\Models\LaborType;
use App\Models\EquipmentCost;
use App\Models\MaterialCost;
use App\Models\Task;
use App\Models\LaborCost;
use App\Models\Status;
use App\Models\Priority;
use App\Models\EquipmentRequestResponse;
use App\Models\LaborRequestResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
class ResourceAllocationController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // fetch session and use it in entire class with constructor
            $this->workspace = Workspace::find(session()->get('workspace_id'));
            $this->user = getAuthenticatedUser();
            return $next($request);
        });
    }
    public function index()
    {
        // Fetching tasks along with their related activities  
        $tasks = Task::with('activity')->get();
        // Fetch Projects        
        $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects : $this->user->projects;
    
        return view('resource-allocation.index', compact('tasks'));
    }
    public function getProjectsData(Request $request)
    {
        $search = $request->get('search');
        $limit = $request->get('limit');
        $offset = $request->get('offset');

        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');

        // Fetch projects based on user role
        $totalProjects = isAdminOrHasAllDataAccess() ? $this->workspace->projects : $this->user->projects;

        // Filter projects based on search input
        $filteredProjects = $totalProjects;

        if ($search) {
            $filteredProjects = $totalProjects->filter(function ($item) use ($search) {
                return stripos($item->name, $search) !== false; // Adjust search logic as needed
            });
        }

        // Sort the filtered projects
        $filteredProjects = $filteredProjects->sortBy($sort, SORT_REGULAR, $order === 'desc');

        // Calculate total items after filtering
        $totalItems = $filteredProjects->count();

        // Paginate the filtered results
        $filteredProjects = $filteredProjects->slice($offset, $limit);

        // Enhance each project with total tasks and activities
        $result = $filteredProjects->map(function ($project) {
            return [
                'id' => $project->id,
                'title' => $project->title,
                'total_tasks' => $project->tasks()->count(), // Ensure relationship exists
                'total_activity' => $project->tasks()->withCount('activities')->first()->activities_count ?? 0, // Adjust according to your model
                'estimated_cost' => $project->estimated_cost, // Adjust according to your model
                'actual_cost' => $project->actual_cost, // Adjust according to your model
                'status' => $project->status->title, // Adjust according to your model
                'color'=>$project->status->color,
            
                
            ];
        });

        return response()->json([
            'total' => $totalItems,
            'rows' => $result
        ]);
    }
    /*public function resourceTaskAllocation($id){
        $status = Status::all();
        $status = Priority::all();
        $tasks =  Task::with('activity')->where('project_id', $id)->get();
        return response()->json([
            $tasks,
        ]);
        return view('resource-allocation.resource-task-allocation', compact('tasks'));
    }*/
    public function resourceTaskAllocation($id) {
        // Fetch all statuses and priorities
        $statuses = Status::all()->keyBy('id'); // Key by status ID
        $priorities = Priority::all()->keyBy('id'); // Key by priority ID
    
        // Fetch tasks with activities
        $tasks = Task::with('activity')->where('project_id', $id)->get();
    
        // Transform the tasks
        $tasks = $tasks->map(function ($task) use ($statuses, $priorities) {
            // Replace task status and priority
            $task->status_name = $statuses[$task->status_id]->title ?? null; // Replace with status name
            $task->priority_name = $priorities[$task->priority_id]->title ?? null; // Replace with priority name
    
            // Transform activities
            $task->activity = $task->activity->map(function ($activity) use ($statuses, $priorities) {
                $activity->status_name = $statuses[$activity->status] ?? null; // Replace with activity status name
                $activity->priority_name = $priorities[$activity->priority] ?? null; // Replace with activity priority name
                return $activity;
            });
    
            return $task;
        });
    
        // Return the view with transformed tasks
       // return response()->json($tasks);
        return view('resource-allocation.resource-task-allocation', compact('tasks'));
    }
    public function equipmentAllocation($activityId)
    {
        $activity = Activity::with('task.project')->findOrFail($activityId);
        $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects : $this->user->projects;
        $tasks = isAdminOrHasAllDataAccess() ? $this->workspace->tasks : $this->user->tasks;
    
        $query = EquipmentRequestResponse::with('EquipmentRequest.Equipment.UnitMeasure');

        $totalRecords = $query->count();
    
        $equipmentsInventory = $query
            ->orderBy('id', 'desc')
            ->get();
            $result = $equipmentsInventory->map(function ($item) {
                return [
                    'id' => $item->id,
                    'equipment_request_id'=>$item->equipment_request_id,
                    'item' => $item->EquipmentRequest->Equipment->item,
                    'rate_with_vat' => $item->EquipmentRequest->Equipment->rate_with_vat,
                    'approved_quantity' => $item->approved_quantity,
                    'unit' => $item->EquipmentRequest->Equipment->UnitMeasure->name,
                    'material_id' => $item->EquipmentRequest->Equipment->id,
                ];
            });
        $contracts = $equipmentsInventory->count();
      /*return response()->json([
            'total' => $result,
        ]); */
        return view('resource-allocation.equipment-allocation', [
            'contracts' => $contracts,
            'equipmentsInventory' => $result,
            'projects' => $projects,
            'tasks' => $tasks,
            'activity' => $activity
        ]);
        //return view('resource-allocation.equipment-allocation', compact('activity'));
    }
    public function equipmentSelection(Request $request)
    {
        $selectedMaterials = $request->input('selected_materials', []);
    
        $materials = [];
        foreach ($selectedMaterials as $serializedMaterial) {
            $material = json_decode($serializedMaterial, true);
            $materials[] = $material;
        }
    /*return response()->json([
            'total' => $materials,
        ]);*/
       // return view('equipmentcost.selectedEquipment', ['selectedMaterials' => $materials]);
       return view('resource-allocation.selected-equipment', ['selectedMaterials' => $materials]);
    }
    public function updateEquipmentAllocation(Request $request)
{
    $request->validate([
        'id' => 'required|integer|exists:materials,id',
        'actual_quantity' => 'required|numeric',
        'actual_budget' => 'required|numeric',
    ]);

    // Find the material by ID
    $material = EquipmentCost::findOrFail($request->id);

     // Update the material with previous values
     $material->update($request->only([
        'actual_quantity' => 'actual_quantity',
        'actual_budget' => 'actual_budget'
    ]));

    return response()->json(['success' => true]);
}
   /* public function allMaterialAlloation() {
        $materials = MaterialCost::with(['material', 'material.unitMeasure'])->get();

        $result = $materials->map(function ($material) {
            return [
                'planned_quantity' => $material->planned_quantity,
                'actual_quantity' => $material->actual_quantity,
                'planned_budget' => $material->planned_budget,
                'actual_budget' => $material->actual_budget,
                'material_item' => $material->material->item, // Accessing the material item
                'unit_measure' => $material->material->unitMeasure->name, // Accessing the unit measure name
            ];
        });
    
       /* return response()->json([
            'total' => $result,
        ]);*/
   /* return view('resource-allocation.all-material-allocations', compact('result'));
    }*/
    public function allMaterialAlloation($activityId)
    {
        // Retrieve and transform the materials
        $activity = Activity::with('task.project')->findOrFail($activityId);
        $materials = MaterialCost::with(['material', 'material.unitMeasure'])
        ->where('subtask_id', $activityId)->get()->map(function ($material) {
            return [
                'id' => $material->id,
                'planned_quantity' => $material->planned_quantity,
                'actual_quantity' => $material->actual_quantity,
                'planned_budget' => $material->planned_budget,
                'actual_budget' => $material->actual_budget,
                'material_item' => $material->material->item,
                'unit_measure' => $material->material->unitMeasure->name,
            ];
        });
    
        // Return the view with the materials data
        return view('resource-allocation.all-material-allocations', compact('materials','activity'));
        
    }
    public function materialAllocation($activityId)
    {
    $activity = Activity::with('task.project')->findOrFail($activityId);
    $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects : $this->user->projects;
    $tasks = isAdminOrHasAllDataAccess() ? $this->workspace->tasks : $this->user->tasks;

    $query = MaterialRequestResponse::with('materialRequest.Material.UnitMeasure','user')
    ->whereHas('materialRequest.resourceRequest', function ($query) use ($activityId) {
        $query->where('activity_id', $activityId);
    });
    $materialsInventory = $query
        ->orderBy('id', 'desc')
        ->get();
    
        $totalRecords = $query->count();
    $result = $materialsInventory->map(function ($item) {
        return [
            'id' => $item->id,
            'material_request_id'=>$item->material_request_id,
            'item' => $item->MaterialRequest->Material->item,
            'rate_with_vat' => $item->MaterialRequest->Material->rate_with_vat,
            'requested_quantity' => $item->MaterialRequest->item_quantity,
            'approved_quantity' => $item->approved_quantity,
            'approved_by' =>$item->user->first_name,
            'remark' =>$item->remark,
            'unit' => $item->MaterialRequest->Material->UnitMeasure->name,
            'material_id' => $item->MaterialRequest->Material->id,
            'status' =>$item->status,
            'date' => $item->created_at->format('Y-m-d'), // Format date to Y-m-d
            'checked' => $item->status === 'allocated',
        ];
    });
    /*return response()->json([
        'total' => $result,
    ]);*/
   return view('resource-allocation.material-allocation', [
        'materialsInventory' => $result,
        'projects' => $projects,
        'tasks' => $tasks,
        'activity' => $activity,
        'totalRecords' =>$totalRecords
    ]);
    return response()->json([
        'total' => $result,
    ]);
}
public function updateMaterialAllocation(Request $request)
{
    $request->validate([
        'id' => 'required|integer|exists:materials,id',
        'actual_quantity' => 'required|numeric',
        'actual_budget' => 'required|numeric',
    ]);

    // Find the material by ID
    $material = MaterialCost::findOrFail($request->id);

     // Update the material with previous values
     $material->update($request->only([
        'actual_quantity' => 'actual_quantity',
        'actual_budget' => 'actual_budget'
    ]));

    return response()->json(['success' => true]);
}
  /*  public function materialAllocation($activityId){
        $activity = Activity::with('task.project')->findOrFail($activityId);
        $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects : $this->user->projects;
        $tasks = isAdminOrHasAllDataAccess() ? $this->workspace->tasks : $this->user->tasks;

        //$query = MaterialsInventory::with('Material.UnitMeasure','Warehouse');
        $query = MaterialRequestResponse::with('MaterialRequest.Material.UnitMeasure');
        $totalRecords = $query->count();
    
        $materialsInventory = $query
            ->orderBy('id', 'desc')
            ->get();
    
        $totals = $materialsInventory->count();
 return response()->json([
            'total' => $materialsInventory,
        ]);
      /*  return view('resource-allocation.material-allocation', [
            'contracts' => $contracts,
            'materialsInventory' => $materialsInventory,
            'projects' => $projects,
            'tasks' => $tasks,
            'activity' => $activity
        ]);*/
    /*}*/
    /*public function storeSelectedMaterials(Request $request)
    {
        // Process the form data and store the selected materials
        $selectedMaterials = $request->input('selected_materials');

        // Perform necessary logic to store the selected materials
        
        // Redirect or return a response
        return redirect()->route('selected_equipment')->with('success', 'Selected materials saved successfully.');
    }
}*/
public function store_selected_materials(Request $request)
{
    $selectedEquipments = $request->input('selected_materials', []);
    $validatedData = $request->validate([
        'selected_materials.*.id' => 'required|integer',
        'selected_materials.*.quantity' => 'required|integer|min:0',
        'selected_materials.*.rate_with_vat' => 'required|numeric|min:0',
    ]);
   /* return response()->json([
        'total' => $selectedEquipments,
    ]);*/
    $response = [
        'selected_materials' => $selectedEquipments,
    ];

    // Here, you can add your logic to store the selected materials, such as saving to the database
    // For example:
    // foreach ($selectedMaterials as $material) {
    //     $material = Material::findOrFail($material['id']);
    //     $material->update(['quantity' => $material['quantity']]);
    // }
    // Return the requested data in JSON format
    //return response()->json($response);

    // Redirect to the 'materialcosts.materialAllocation' route
    // return redirect()->route('materialcosts.materialAllocation')->with('success', 'Selected materials saved successfully.');
    DB::beginTransaction();
        try {
            foreach ($selectedEquipments as $equipment) {
                // Retrieve the MaterialsInventory model for the current material
            //    $equpmentInventory = EquipmentInventory::find($equipment['id']);
           /* return response()->json([
                'total' => $selectedEquipments,
            ]);*/
            $equipmentResponse = EquipmentRequestResponse::where('equipment_request_id', $equipment['equipment_request_id'])->first();
                // Check if the material inventory was found
                if ($equipmentResponse) { 
                    // Create a new MaterialCost record
                    $equipmentCost = new EquipmentCost();
                    $equipmentCost->equipment_id = $equipment['id'];
                    $equipmentCost->planned_quantity = $equipment['quantity'];
                    $equipmentCost->planned_cost = $equipment['rate_with_vat'];
                   // $equipmentCost->subtask_id = $equipment['selectedSubtaskId'];
                    $equipmentCost->subtask_id = 1;
                    $equipmentCost->save();
    
                    // Update the MaterialsInventory quantity
                   // $equipmentResponse->approved_quantity -= $material['quantity'];
                    //$equipmentResponse->save();
                } else {
                    // Handle the case where the MaterialsInventory was not found
                    // You could log an error, skip the current material, or throw an exception
                }
            }
    
            DB::commit();
    
            // Set a success flash message
            Session::flash('message', 'Equipment allocated successfully.');
    
            // Redirect to a valid route in your application
          //  return redirect()->route('materialcosts.materialAllocation');
             // Redirect the user to the "where" page
    return redirect()->route('equipment.where', ['equipment' => $equipmentCost->id]);
        } catch (\Exception $e) {
            DB::rollback();
    
            // Set an error flash message
            Session::flash('error', $e->getMessage());
    
            // Redirect to a valid route in your application
            return redirect()->route('material-selection')->withErrors(['error' => $e->getMessage()]);
        }
}
public function whereMAterial(Request $request, $equipmentId)
{
    $material = MaterialCost::findOrFail($equipmentId);
    
    return view('resource-allocation.materal-where', compact('material'));
}
public function whereEquipment(Request $request, $equipmentId)
{
    $equipment = EquipmentCost::findOrFail($equipmentId);
    
    return view('resource-allocation.equipment-where', compact('equipment'));
}
public function whereLabor(Request $request, $labortId)
{
    $labor = LaborCost::findOrFail($labortId);
    
    return view('resource-allocation.labor-where', compact('labor'));
}
public function allEquipmentAlloation() {
    $equipments = MaterialCost::all();
    /*return response()->json([
        'total' => $equipment,
    ]);*/
return view('resource-allocation.all-equipment-allocations', compact('equipments'));
}
    public function laborAllocation($activityId)
    {    
        $activity = Activity::with('task.project')->findOrFail($activityId);
        $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects : $this->user->projects;
        $tasks = isAdminOrHasAllDataAccess() ? $this->workspace->tasks : $this->user->tasks;
        $labors = Labor::all();
        $totalRecords = $labors->count();
       
        $query = LaborRequestResponse::with('LaborRequest.LaborType');
        $totalRecords = $query->count();
    
        $laborData = $query
            ->orderBy('id', 'desc') 
            ->get();
            $result = $laborData->map(function ($labor) {
                return [
                    'id' => $labor->id,
                    'labor_request_id' => $labor->labor_request_id,
                    'approved_quantity' => $labor->approved_quantity,
                    'position' => $labor->laborRequest->laborType ? $labor->laborRequest->laborType->labor_type_name : 'N/A',
                ];
            });
           /* return response()->json([
                'total' => $result,
            ]);*/
             return view('resource-allocation.labor-allocation', [
            'labors' => $result,
            'projects' => $projects,
            'tasks' => $tasks,
            'totalRecords' => $totalRecords,
            'activity'=>$activity
        ]); 
        return response()->json([
            'total' => $laborData,
        ]);

    } 
    public function laborSelection(Request $request)
    {
        $selectedMaterials = $request->input('selected_materials', []);
    
        $materials = [];
        foreach ($selectedMaterials as $serializedMaterial) {
            $material = json_decode($serializedMaterial, true);
            $materials[] = $material;
        }
    /*return response()->json([
            'total' => $selectedMaterials,
        ]);*/
       // return view('equipmentcost.selectedEquipment', ['selectedMaterials' => $materials]);
       return view('resource-allocation.selected-labor', ['selectedMaterials' => $materials]);
    }
    public function storeLaborAllocation(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'selected_materials' => 'required|array',
            'selected_materials.*.quantity' => 'required|integer|min:1',
            'selected_materials.*.rate_with_vat' => 'required|numeric|min:0',
            'selected_materials.*.material_request_id' => 'required|integer',
        ]);

        $totalAmount = 0;

        foreach ($validatedData['selected_materials'] as $material) {
            $quantity = $material['quantity'];
            $rateWithVat = $material['rate_with_vat'];
            $amount = $quantity * $rateWithVat;
            $totalAmount += $amount;

            // Save each material allocation to the LaborCost model
            $laborCost = new LaborCost();
            $laborCost->activity_id= 1;
            $laborCost->qty = $quantity;
            $laborCost->rate_with_vat = $rateWithVat;
            $laborCost->labor_id = $material['material_request_id'];
            $laborCost->status = "allocated";
          //  $laborCost->amount = $amount; // Assuming you have an amount field in your LaborCost model
            $laborCost->save();
        }
        Session::flash('message', 'Labor allocated successfully.');
    
        return redirect()->route('labor.where', ['labor' => $laborCost->id]);
        // Return response
        return response()->json([
            'success' => true,
            'total_amount' => $totalAmount,
            'message' => 'Labor allocation saved successfully.',
        ]);
    }
    public function allLAborAlloation() {
        $materials = LaborRequestResponse::all();
        /*return response()->json([
            'total' => $equipment,
        ]);*/
    return view('resource-allocation.all-labor-allocations', compact('labors'));
    }
    public function updateLaborAllocation(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:materials,id',
            'actual_quantity' => 'required|numeric',
            'actual_budget' => 'required|numeric',
        ]);
    
        // Find the material by ID
        $material = LaborCost::findOrFail($request->id);
    
         // Update the material with previous values
         $material->update($request->only([
            'actual_quantity' => 'actual_quantity',
            'actual_budget' => 'actual_budget'
        ]));
    
        return response()->json(['success' => true]);
    }
}
