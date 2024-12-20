<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Workspace;
use App\Models\Subtask;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\MaterialsInventory;
use App\Models\Task;
use App\Models\Equipment;
use App\Models\Labor;
use App\Models\LaborType;
use App\Models\MaterialRequest;
use App\Models\ResourceRequest;
use App\Models\EquipmentRequest;
use App\Models\LaborRequestResponse;
use App\Models\LaborRequest;
use App\Models\Activity;
use App\Models\Warehouse;
use App\Models\MaterialRequestResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Status;
use App\Models\Priority;
class RequestsController extends Controller
{
    protected $workspace;
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->workspace = Workspace::find(session()->get('workspace_id'));
        //    $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index()
    {
        //$materials = ResourceRequest::all()->orderBy('created_at', 'desc')->paginate(10); 
        $materials = ResourceRequest::with('activity')->orderBy('created_at', 'desc')->get(); 
        $equipments = EquipmentRequest::orderBy('created_at', 'desc')->paginate(10); 
        $labors = LaborRequest::orderBy('created_at', 'desc')->paginate(10); 
    
        // Create an array of categories
        $categoryColors = [
            'materials' => 'bg-info', // Default color for materials
            'equipments' => 'bg-warning', // Default color for equipment
            'labors' => 'bg-success', // Default color for labor
        ];
     /*  return response()->json([
            'total' => $materials,
        ]);*/
       return view('requests.incoming', compact('materials', 'equipments', 'labors', 'categoryColors'));
    }
    public function resourceRequest()
    {
        // Fetching tasks along with their related activities  
        $tasks = Task::with('activity')->get();
        // Fetch Projects        
        $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects : $this->user->projects;
        return view('requests.resource', compact('tasks'));
    }
    public function resourceTaskAllocation($id){
        $tasks = Task::with('activity')->where('project_id', $id)->get();
        return view('resource-allocation.resource-task-allocation', compact('tasks'));
    }
    /*public function materilRequestById($requestId)
    {    
         // Fetch the resource request with its related material requests
         $total = MaterialRequest::with('material','resourceRequest.activity')->where('resource_request_id',$requestId)->get();
         return view('requests.material-request-byid', compact('total'));
         return response()->json([
             'total' => $total,
         ]);
    }*/   
    /*public function materilRequestById($requestId)
    {
         // Eager load relationships to reduce the number of queries
         $materialRequests = MaterialRequest::with([
            'material.unitMeasure',
            'material.warehouse.materialsInventory',
            'resourceRequest.activity' // Eager load resource_request relationship
        ])
        ->where('resource_request_id', $requestId)
        ->get();

        return view('requests.material-request-byid', compact('materialRequests'));


    }*/
    public function materilRequestById($requestId)
{
    // Eager load relationships to reduce the number of queries
    $finace_status = ResourceRequest::where('id', $requestId)->value('status');  // Use value() to get a single field

    $materialRequests = MaterialRequest::with([
        'material.unitMeasure',
        'material.warehouse.materialsInventory',
        'resourceRequest.activity' // Eager load resource_request relationship
    ])
    ->where('resource_request_id', $requestId)
    ->paginate(10); // Adjust the number of items per page as needed

    return view('requests.material-request-byid', compact('materialRequests','finace_status'));
}
    /*
    public function materilRequestById($requestId)
    {
       // Fetch the material request
       $materialRequest = MaterialRequest::with([
        'material.unitMeasure',
        'material.warehouse.materialsInventory',
        'resourceRequest.activity'
    ])
    ->where('resource_request_id', $requestId)
    ->first();

    if (!$materialRequest) {
        return response()->json(['message' => 'Material request not found'], 404);
    }

    // Transform the data based on the specified structure
    $materialRequests = [
        'id' => $materialRequest->id,
        'material_request_id' => $materialRequest->resource_request_id,
        'item_id' => $materialRequest->material->id ?? null,
        'item_quantity' => $materialRequest->item_quantity,
        'material_description' => $materialRequest->material_description,
        'status' => $materialRequest->status,
        'created_at' => $materialRequest->created_at,
        'updated_at' => $materialRequest->updated_at,
        'material' => [
            'id' => $materialRequest->material->id ?? null,
            'item' => $materialRequest->material->item ?? null,
            'rate_with_vat' => $materialRequest->material->rate_with_vat ?? null,
            'unit_measure' => $materialRequest->material->unitMeasure->name ?? null,
            'warehouse' => $materialRequest->material->warehouse ? [
                'id' => $materialRequest->material->warehouse->id,
                'name' => $materialRequest->material->warehouse->name,
                'materials_inventory' => $materialRequest->material->warehouse->materialsInventory->map(function ($inventory) {
                    return [
                        'id' => $inventory->id,
                        'quantity' => $inventory->quantity,
                        'depreciation' => $inventory->depreciation,
                    ];
                }),
            ] : null,
        ],
        'resource_request' => $materialRequest->resourceRequest ? [
            'id' => $materialRequest->resourceRequest->id,
            'finance_status' => $materialRequest->resourceRequest->finance_status,
            'activity' => $materialRequest->resourceRequest->activity ? [
                'id' => $materialRequest->resourceRequest->activity->id,
                'name' => $materialRequest->resourceRequest->activity->name,
                'status' => $materialRequest->resourceRequest->activity->status,
            ] : null,
        ] : null,
    ];

    // Pass the data to the view as materialRequests
    return view('requests.material-request-byid', compact('materialRequests'));
    }
    */
    /*public function materilRequestToFinance($requestId)
    {
        // Fetch the material requests for materials in the specified warehouse
        $materialRequests = MaterialRequest::whereHas('material.unitMeasure') // Check for unit measure
            ->where('resource_request_id', $requestId)
            ->with([
                'material.unitMeasure', // Eager load unitMeasure
                'material.warehouse.materialsInventory', // Ensure we include the materialsInventory
                'resourceRequest.activity',
            ])
            ->get();
    /*return response()->json([
        $materialRequests
    ]);*/
        // Return the view with the fetched material requests
     //   return view('requests.finance.approve-request', compact('materialRequests'));
    //}*/
  
  /*  public function materilRequestToFinance($requestId)
{
    // Fetch the material requests for materials in the specified warehouse
    $materialRequests = MaterialRequest::whereHas('material.unitMeasure') // Check for unit measure
        ->where('resource_request_id', $requestId)
        ->with([
            'material.unitMeasure', // Eager load unitMeasure
            'material.warehouse.materialsInventory', // Ensure we include the materialsInventory
            'resourceRequest.activity',
        ])
        ->get();

    // Transform the material requests to include finance_status
    $materialRequestsWithFinanceStatus = $materialRequests->map(function ($request) {
        // Check if the resource_request exists
        $resourceRequest = $request->resourceRequest;

        // Check if the material's warehouse exists
        $warehouse = $request->material->warehouse ?? null;

        return [
            'id' => $request->id,
            'material_request_id' => $request->resource_request_id,
            'item_id' => $request->material->id ?? null,  // Check for null
            'item_quantity' => $request->item_quantity,
            'material_description' => $request->material_description,
            'status' => $request->status,
            'created_at' => $request->created_at,
            'updated_at' => $request->updated_at,
            'material' => [
                'id' => $request->material->id ?? null,  // Check for null
                'item' => $request->material->item ?? null,  // Check for null
                'rate_with_vat' => $request->material->rate_with_vat ?? null,  // Check for null
                'unit_measure' => $request->material->unitMeasure->name ?? null,  // Check for null
                'warehouse' => $warehouse ? [
                    'id' => $warehouse->id,
                    'name' => $warehouse->name,
                    'materials_inventory' => $warehouse->materialsInventory->map(function ($inventory)->where('material_id',material.id) {
                        return [
                            'id' => $inventory->id,
                            'material_id' => $inventory->material_id,
                            'quantity' => $inventory->quantity,
                            'depreciation' => $inventory->depreciation,
                        ];
                    }),
                ] : null,  // Handle case where warehouse does not exist
            ],
            'resource_request' => $resourceRequest ? [
                'id' => $resourceRequest->id,
                'finance_status' => $resourceRequest->finance_status,  // Should have a value if resource_request exists
                'activity' => $resourceRequest->activity ? [
                    'id' => $resourceRequest->activity->id,
                    'name' => $resourceRequest->activity->name,
                    'status' => $resourceRequest->activity->status,
                ] : null,  // Handle case where activity might not exist
            ] : null,  // Handle case where resource_request does not exist
        ];
    });
    return response()->json($materialRequestsWithFinanceStatus);
   return view('requests.finance.approve-request', compact('materialRequestsWithFinanceStatus'));
    //return response()->json($materialRequestsWithFinanceStatus);
}
*/
public function materilRequestToFinance($requestId)
{
    // Fetch the material requests for materials in the specified warehouse
    $materialRequests = MaterialRequest::whereHas('material.unitMeasure') // Check for unit measure
        ->where('resource_request_id', $requestId)
        ->with([
            'material.unitMeasure', // Eager load unitMeasure
            'material.warehouse.materialsInventory', // Ensure we include the materialsInventory
            'resourceRequest.activity',
        ])
        ->get();

    // Transform the material requests to include finance_status
    $materialRequestsWithFinanceStatus = $materialRequests->map(function ($request) {
        // Check if the resource_request exists
        $resourceRequest = $request->resourceRequest;

        // Check if the material's warehouse exists
        $warehouse = $request->material->warehouse ?? null;

        return [
            'id' => $request->id,
            'material_request_id' => $request->resource_request_id,
            'item_id' => $request->material->id ?? null,  // Check for null
            'item_quantity' => $request->item_quantity,
            'material_description' => $request->material_description,
            'status' => $request->status,
            'created_at' => $request->created_at,
            'updated_at' => $request->updated_at,
            'material' => [
                'id' => $request->material->id ?? null,  // Check for null
                'item' => $request->material->item ?? null,  // Check for null
                'rate_with_vat' => $request->material->rate_with_vat ?? null,  // Check for null
                'unit_measure' => $request->material->unitMeasure->name ?? null,  // Check for null
                'warehouse' => $warehouse ? [
                    'id' => $warehouse->id,
                    'name' => $warehouse->name,
                    'materials_inventory' => $warehouse->materialsInventory->filter(function ($inventory) use ($request) {
                        return $inventory->material_id == $request->material->id; // Use the correct reference
                    })->map(function ($inventory) {
                        return [
                            'id' => $inventory->id,
                            'material_id' => $inventory->material_id,
                            'quantity' => $inventory->quantity,
                            'depreciation' => $inventory->depreciation,
                        ];
                    }),
                ] : null,  // Handle case where warehouse does not exist
            ],
            'resource_request' => $resourceRequest ? [
                'id' => $resourceRequest->id,
                'finance_status' => $resourceRequest->finance_status,  // Should have a value if resource_request exists
                'activity' => $resourceRequest->activity ? [
                    'id' => $resourceRequest->activity->id,
                    'name' => $resourceRequest->activity->name,
                    'status' => $resourceRequest->activity->status,
                ] : null,  // Handle case where activity might not exist
            ] : null,  // Handle case where resource_request does not exist
        ];
    });
    return view('requests.finance.approve-request', compact('materialRequestsWithFinanceStatus'));
    return response()->json($materialRequestsWithFinanceStatus);
}
public function showMaterialRequestById($requestId)
{
    
    $materialRequests = ResourceRequest::findOrFail($requestId);
    return view('requests.show-material-request-byid', compact('materialRequests'));
}
    public function equipmentRequestById($requestId)
    {    
        $total = LaborRequest::with('LaborType')->findOrFail($requestId);
       // return view('requests.equipment-request-byid', compact('total'));
        //eturn view('requests.material-detail');
       return response()->json([
            'total' => $material,
        ]);
  
    }
/*    public function laborRequestById($requestId)
    {    
$laborRequest = LaborRequest::with('laborType')->where('resource_request_id', $requestId)->get();
   //return view('requests.labor-request-byid', compact('laborRequest'));
       return response()->json([
           $laborRequest,
        ]);
  
    }
    */
    public function laborRequestById($requestId)
{    
    /*$laborRequests = LaborRequest::with('laborType')
        ->where('resource_request_id', $requestId)
        ->get(['id', 'labor_type_id', 'resource_request_id', 'quantity_requested', 'status']);

    // Transform the data to include labor_type_name
    
    $laborRequestResult = $laborRequests->map(function($request) {
        return [
            'id' => $request->id,
            'labor_type_id' => $request->labor_type_id,
            'resource_request_id' => $request->resource_request_id,
            'quantity_requested' => $request->quantity_requested,
            'status' => $request->status,
            'labor_type_name' => $request->laborType->labor_type_name, // Accessing the related labor type name
            'hourly_rate' => $request->laborType->hourly_rate,
        ];
    });*/
  //  return response()->json($laborRequests);
  //  return view('requests.labor-request-byid', compact('laborRequestResult'));
  //  return response()->json($laborRequestResult);
  $laborRequests = LaborRequest::with('laborType')
    ->where('resource_request_id', $requestId)
    ->get(['id', 'labor_type_id', 'resource_request_id', 'quantity_requested', 'status']);

// Group by labor_type_id
$laborRequestCounts = $laborRequests->groupBy('labor_type_id');

// Transform the data to include labor_type_name and count
$laborRequestResult = $laborRequestCounts->flatMap(function($group) {
    $firstRequest = $group->first(); // Get the first request for accessing common properties
    $count = $group->count(); // Count of requests for this labor type

    return $group->map(function($request) use ($firstRequest, $count) {
        return [
            'id' => $request->id,
            'labor_type_id' => $firstRequest->labor_type_id,
            'resource_request_id' => $request->resource_request_id,
            'quantity_requested' => $request->quantity_requested,
            'status' => $request->status,
            'labor_type_name' => $firstRequest->laborType->labor_type_name, // Accessing the related labor type name
            'avallable_quantity' => $count, // Count of requests for this labor type
            'hourly_rate' => $firstRequest->laborType->hourly_rate,
        ];
    });
});

// Return the result as JSON
//return response()->json($laborRequestResult);
return view('requests.labor-request-byid', compact('laborRequestResult'));
}
    /*public function storeMaterialRequestResponse(Request $request)
{
    $request->validate([
        'approved_quantity.*' => 'required|numeric|min:0',
        'remark.*' => 'nullable|string|max:255',
        'selected_materials' => 'required|array',
        'selected_materials.*' => 'json',
        'material_request_ids' => 'required|array',
        'resource_request_id' => 'required|integer',
    ]);

    // Initialize the transaction
    DB::beginTransaction();
    try {
        $approvedQuantities = $request->input('approved_quantity');
        $remarks = $request->input('remark');
        $selectedMaterials = $request->input('selected_materials');
        $materialRequestIds = $request->input('material_request_ids');
        $resourceRequestId = $request->input('resource_request_id');

        $createdIds = []; // Store created IDs for later use

        foreach ($selectedMaterials as $index => $material) {
            $materialData = json_decode($material, true);

            // Create a new MaterialRequestResponse entry
            $response = MaterialRequestResponse::create([
                'material_id' => $materialData['id'],
                'approved_quantity' => $approvedQuantities[$index],
                'remark' => $remarks[$index] ?? null,
                'status' => 'Pending',
                'material_request_id' => $materialRequestIds[$index],
                'approved_by' => auth()->id(),
            ]);
            $createdIds[] = $response->id; // Store the created ID

            // Update the quantity in MaterialsInventory
            $inventoryItem = MaterialsInventory::where('material_id', $materialData['id'])
            ->where('warehouse_id', $materialData['warehouse_id'])
            ->first();
            if ($inventoryItem) {
                // Ensure the inventory has enough quantity
                if ($inventoryItem->quantity >= $approvedQuantities[$index]) {
                    $inventoryItem->quantity -= $approvedQuantities[$index];
                    $inventoryItem->save(); // Save the updated inventory item
                } else {
                    throw new \Exception('Approved quantity exceeds available inventory for material ID: ' . $materialData['id']);
                }
            }
        }

        // Update the ResourceRequest status
        ResourceRequest::where('id', $resourceRequestId)->update([
            'status' => 'approved'
        ]);

        // Commit the transaction
        DB::commit();
    return response()->json([
        $request,
     ]);
        // Redirect or return response as appropriate
        return redirect()->route('material.request.response', ['id' => $createdIds[0]])
                         ->with('success', 'Material request responses saved successfully.');
    } catch (\Exception $e) {
        // Rollback the transaction if something went wrong
        DB::rollBack();
        
        return redirect()->back()->withErrors(['error' => 'Failed to save material request responses: ' . $e->getMessage()]);
    }
} */


public function storeMaterialRequestResponse(Request $request)
{
    // Validate the incoming request data
    $validatedData = $request->validate([
        'approved_quantity.*' => 'required|numeric|min:0',
        'remark.*' => 'nullable|string|max:255',
       // 'selected_materials' => 'required|array',
        'warehouse_id' => 'required|integer',
        'item_id' => 'required|integer',
        'selected_materials.*' => 'json',
        'material_request_ids' => 'required|array',
        'resource_request_id' => 'required|integer',
    ]);
    
    // Initialize the transaction
    DB::beginTransaction();
    try {
       
        // Extract validated data
        $approvedQuantities = $validatedData['approved_quantity'];
        $remarks = $validatedData['remark'];
        $warehouse_id = $validatedData['warehouse_id'];
        $item_id = $validatedData['item_id'];
         //$selectedMaterials = $validatedData['selected_materials'];
        $materialRequestIds = $validatedData['material_request_ids'];
        $resourceRequestId = $validatedData['resource_request_id'];
       
        $createdIds = []; // Store created IDs for later use

        foreach ($materialRequestIds as $index => $material) {
            $materialData = json_decode($material, true);

            // Create a new MaterialRequestResponse entry
            $response = MaterialRequestResponse::create([
                'material_id' => $materialRequestIds,
                'approved_quantity' => $approvedQuantities[$index],
                'remark' => $remarks[$index] ?? null,
                'status' => 'Pending',
                'material_request_id' => $materialRequestIds[$index],
                'approved_by' => auth()->id(),
            ]);
            $createdIds[] = $response->id; // Store the created ID
         
            // Update the quantity in MaterialsInventory
            $inventoryItem = MaterialsInventory::where('material_id',$item_id)
                ->where('warehouse_id', $warehouse_id)
                ->first();
             
            if ($inventoryItem) {
                // Ensure the inventory has enough quantity
                if ($inventoryItem->quantity >= $approvedQuantities[$index]) {
                    $inventoryItem->quantity -= $approvedQuantities[$index];
                    $inventoryItem->save(); // Save the updated inventory item
                } else {
                    throw new \Exception('Approved quantity exceeds available inventory for material ID: ' . $materialData['id']);
                }
            }
        }

        // Update the ResourceRequest status
        ResourceRequest::where('id', $resourceRequestId)->update([
            'status' => 'approved',
        ]);
        MaterialRequest::where('id', $materialRequestIds)->update([
            'status' => 'approved',
        ]);
        // Commit the transaction
        DB::commit();

        // Redirect or return response as appropriate
        return redirect()->route('material.request.response', ['id' => $createdIds[0]])
                         ->with('success', 'Material request responses saved successfully.');

    } catch (\Exception $e) {
        // Rollback the transaction if something went wrong
        DB::rollBack();

        return redirect()->back()->withErrors(['error' => 'Failed to save material request responses: ' . $e->getMessage()]);
    }
}
/*public function storeFinanceApprove(Request $request){
    return response()->json([
        "samisams",
     ]);
}*/
public function storeFinanceApprove(Request $request)
{
    // Validate the incoming request
    $validated = $request->validate([
        'status' => 'required|string|in:approved,rejected',
        'resource_request_id' => 'required|array',
        'resource_request_id.*' => 'integer|exists:resource_requests,id',
    ]);

    // Start a transaction to ensure data integrity
    \DB::beginTransaction();

    try {
        // Update the status for each resource request
        foreach ($validated['resource_request_id'] as $id) {
            $resourceRequest = ResourceRequest::findOrFail($id);
            $resourceRequest->finance_status = $validated['status'];
            $resourceRequest->save();
        }
        // Commit the transaction
        \DB::commit();
        // Return a success message
        return response()->json(['message' => 'Status updated successfully!'], 200);
    } catch (\Exception $e) {
        // Rollback the transaction on error
        \DB::rollBack();
        // Return an error message
        return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
    }
}
    public function specificMaterialRequestResponse(Request $request)
    {
        return view('requests.material_request_response');
    }
    public function storeLaborRequestResponse(Request $request)
    {
        $request->validate([
            'approved_quantity.*' => 'required|numeric|min:0',
            'remark.*' => 'nullable|string|max:255',
            'selected_materials' => 'required|array',
            'selected_materials.*' => 'json',
            'material_request_ids' => 'required|array',
            'resource_request_id' => 'required|integer',
        ]);
    
        // Begin a transaction
        DB::beginTransaction();
        try {
            $approvedQuantities = $request->input('approved_quantity');
            $remarks = $request->input('remark');
            $selectedMaterials = $request->input('selected_materials'); // Corrected variable name
            $materialRequestIds = $request->input('material_request_ids'); // Corrected variable name
            $resourceRequestId = $request->input('resource_request_id');
            $createdIds = []; // Store created IDs for later use
       
            foreach ($selectedMaterials as $index => $material) {
                $materialData = json_decode($material, true);
    
                // Create a new LaborRequestResponse entry
                $response = LaborRequestResponse::create([
                    'approved_quantity' => $approvedQuantities[$index],
                    'response_message' => $remarks[$index] ?? null,
                    'labor_request_id' => $materialRequestIds[$index],
                    'approved_by' => auth()->id(),
                ]);
                $createdIds[] = $response->id; // Store the created ID
            }
            LaborRequest::where('id', $materialRequestIds[$index],)->update([
                'status' => 'allocated'
            ]);
    // Update the ResourceRequest status
    ResourceRequest::where('id', $resourceRequestId)->update([
        'status' => 'approved'
    ]);

    // Commit the transaction
    DB::commit();

    
            // Redirect or return response as appropriate
            return redirect()->route('material.request.response', ['id' => $createdIds[0]])
                             ->with('success', 'Labor Approved  successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction if something went wrong
            DB::rollBack();
            
            return redirect()->back()->withErrors(['error' => 'Failed to save material request responses: ' . $e->getMessage()]);
        }
    }
    protected function getCategoryColor($category)
    {
        switch ($category) {
            case 'urgent':
                return 'bg-danger'; // Red for urgent requests
            case 'normal':
                return 'bg-warning'; // Yellow for normal requests
            case 'low':
                return 'bg-success'; // Green for low priority
            default:
                return 'bg-info'; // Default color
        }
    }
   /* public function request1()  
    {  
        // Fetching tasks along with their related activities  
        $tasks = Task::with('activity')->get();  

        // Send the tasks to the view  
        return view('requests.request', [  
            'tasks' => $tasks,  
        ]);  
    }  */
   /* public function requestResourceByProjectId($id)  
    {  
        $tasks = Task::with('activity')->where('project_id', $id)->get();  
        return view('requests.request', [  
            'tasks' => $tasks,  
        ]);  
    }*/
    public function requestResourceByProjectId($id)  
    {  
        // Fetch tasks with their associated activities for the specified project ID
        $tasks = Task::with('activity')->where('project_id', $id)->get();  
        
        // Format the tasks to match the desired output
        $formattedTasks = $tasks->map(function ($task) {
            return [
                'id' => $task->id,
                'workspace_id' => $task->workspace_id,
                'project_id' => $task->project_id,
                'title' => $task->title,
                'description' => $task->description,
                'status_id' => $task->status_id,
                'priority_id' => $task->priority_id,
                'start_date' => $task->start_date,
                'due_date' => $task->due_date,
                'note' => $task->note,
                'created_by' => $task->created_by,
                'created_at' => $task->created_at,
                'updated_at' => $task->updated_at,
                'open' => $task->open,
                'activity' => $task->activity->map(function ($activity) {
                    $priority = Priority::find($activity->priority);
                    $status = Status::find($activity->status);
                    return [
                        'id' => $activity->id,
                        'task_id' => $activity->task_id,
                        'name' => $activity->name,
                        'progress' => $activity->progress,
                        'priority' => $priority ? "<span class='badge bg-label-{$priority->color}'>{$priority->title}</span>" : "<span class='badge bg-label-secondary'>No Priority</span>",
                        'start_date' => $activity->start_date,
                        'end_date' => $activity->end_date,
                        'created_at' => $activity->created_at,
                        'updated_at' => $activity->updated_at,
                        'status' => $status ? "<span class='badge bg-label-{$status->color}'>{$status->title}</span>" : "<span class='badge bg-label-secondary'>No Status</span>",
                    ];
                }),
            ];
        });
        
        return view('requests.request', [  
            'tasks' => $formattedTasks,  
        ]);  
    }
    public function requestResource($activityId)
    {

    $activity = Activity::with('task.project')->findOrFail($activityId);

    $materials = MaterialsInventory::with('Material.UnitMeasure')->orderBy('id', 'desc')->get();
    $equipments = Equipment::with('UnitMeasure')->orderBy('id', 'desc')->get();
   // $labors = Labor::with('LaborType')->orderBy('id', 'desc')->get();

   $labors = Labor::with('LaborType')
   ->select('labor_type_id')
   ->get()
   ->groupBy('labor_type_id');

// Prepare the result array
$result = [];
foreach ($labors as $laborTypeId => $laborGroup) {
   $laborType = $laborGroup->first()->LaborType; // Get the LaborType details
   $laborResult[] = [
       'labor_type_id' => $laborTypeId,
       'total_labor' => $laborGroup->count(),
       'labor_type_name' => $laborType->labor_type_name,
       'hourly_rate' => $laborType->hourly_rate,
       'skill_level' => $laborType->skill_level,
   ];
}
    $laborTypes = LaborType::all();
   /* return response()->json([
        $laborResult,
    ]);*/
    $material_result = $materials->map(function ($result) {
        return [
            'id' => $result->id,
            'quantity'=>$result->quantity,
            'material'=>$result->material->item,
            'unit'=>$result->rate_with_vat,
            'rate_with_vat'=>$result->material->rate_with_vat
        ];
    });
   /* return response()->json([
        'total' => $materials,
    ]);*/
    return view('requests.activity-resource', [
        'activity' => $activity,
         'materials' => $materials,
         'equipment'=>$equipments,
         'auth_user' => $this->user,
         'labors'=>$laborResult,
    ]);
    return response()->json([
        'total' => $activity,
    ]);
}
    public function show($id)
    {
        // Static data for demonstration
        $request = [
            'id' => $id,
            'title' => 'Sample Request',
            'description' => 'This is a sample request.',
            'created_at' => '2024-05-08 10:00:00',
        ];

        return view('requests.show', compact('request'));
    }

    public function create()
    {
        return view('requests.create');
    }

    public function store(Request $request)
    {
        // Process and store the request data

        return redirect()->route('requests.index')->with('success', 'Request created successfully!');
    }
    /*public function requestMaterial(Request $request)
    {
        $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects : $this->user->projects;
        $tasks = isAdminOrHasAllDataAccess() ? $this->workspace->tasks : $this->user->tasks;
        $subTasks = Subtask::all();
    
        $query = MaterialsInventory::with('Material','Warehouse');
    
        $draw = $request->get('draw', 1);
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        $search = $request->get('search', ['value' => '']);
        $order = $request->get('order', [['column' => 0, 'dir' => 'asc']]);
    
        $totalRecords = $query->count();
    
        $materialsInventory = $query
            ->skip($start)
            ->take($length)
            ->orderBy('id', 'desc')
            ->get();
    
        $contracts = $materialsInventory->count();
    
        return view('requests.material-request', [
            'contracts' => $contracts,
            'materialsInventory' => $materialsInventory,
            'projects' => $projects,
            'tasks' => $tasks,
            'subTasks' => $subTasks
        ]);
    }*/
   /* public function requestEquipment(Request $request)
    {
        $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects : $this->user->projects;
        $tasks = isAdminOrHasAllDataAccess() ? $this->workspace->tasks : $this->user->tasks;
        $subTasks = Subtask::all();
    
        $query = MaterialsInventory::with('Material','Warehouse');
    
        $draw = $request->get('draw', 1);
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        $search = $request->get('search', ['value' => '']);
        $order = $request->get('order', [['column' => 0, 'dir' => 'asc']]);
    
        $totalRecords = $query->count();
    
        $materialsInventory = $query
            ->skip($start)
            ->take($length)
            ->orderBy('id', 'desc')
            ->get();
    
        $contracts = $materialsInventory->count();
    
        return view('requests.equipment-request', [
            'contracts' => $contracts,
            'materialsInventory' => $materialsInventory,
            'projects' => $projects,
            'tasks' => $tasks,
            'subTasks' => $subTasks
        ]);
    }*/
    /*public function requestLabor(Request $request)
    {
        $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects : $this->user->projects;
        $tasks = isAdminOrHasAllDataAccess() ? $this->workspace->tasks : $this->user->tasks;
        $subTasks = Subtask::all();
    
        $query = MaterialsInventory::with('Material','Warehouse');
    
        $draw = $request->get('draw', 1);
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        $search = $request->get('search', ['value' => '']);
        $order = $request->get('order', [['column' => 0, 'dir' => 'asc']]);
    
        $totalRecords = $query->count();
    
        $materialsInventory = $query
            ->skip($start)
            ->take($length)
            ->orderBy('id', 'desc')
            ->get();
    
        $contracts = $materialsInventory->count();
    
        return view('requests.labor-request', [
            'contracts' => $contracts,
            'materialsInventory' => $materialsInventory,
            'projects' => $projects,
            'tasks' => $tasks,
            'subTasks' => $subTasks
        ]);
    }*/
    public function materialRequestSelection(Request $request)
    {
        $selectedMaterials = $request->input('selected_materials', []);
        $selectedSubtaskId =  $request->input('selected_subtask_id');
        $materials = [];
        foreach ($selectedMaterials as $serializedMaterial) {
            $material = json_decode($serializedMaterial, true);
            $materials[] = $material;
        }
    /*return response()->json([
            'total' => $materials,
        ]);*/
      return view('requests.material-request-edit', [
            'selectedMaterials' => $materials,
            'selectedSubtaskId' => $selectedSubtaskId
        ]);  

      /*  return response()->json([
            'total' => $materials,
        ]);*/
    }
   /* public function laborRequestSelection(Request $request)
    {
        $selectedlabors = $request->input('selected_labors', []);
        $selectedSubtaskId =  $request->input('selected_subtask_id');
        $labors = [];
        foreach ($selectedlabors as $serializedMaterial) {
            $labor = json_decode($serializedMaterial, true);
            $laborls[] = $labor;
        }
      return view('requests.labor-request-sellection', [
            'selectedLabors' => $labor,
            'selectedSubtaskId' => $selectedSubtaskId
        ]);  
    }*/
    public function laborRequestSelection(Request $request)
{
    $selectedlabors = $request->input('selected_labors', []);
    $selectedSubtaskId = $request->input('selected_subtask_id');

    $labors = [];
    foreach ($selectedlabors as $serializedMaterial) {
        $labor = json_decode($serializedMaterial, true);
        if (is_array($labor)) {
            $labors[] = $labor; // Store valid labor data
        }
    }

    return view('requests.labor-request-sellection', [
        'selectedLabors' => $labors, // Use the correct variable
        'selectedSubtaskId' => $selectedSubtaskId
    ]);
}
public function storeMaterialRequest(Request $request)
{
    // Fetch selected materials and activity ID from the request
    $selectedMaterialRequests = $request->input('selected_materials', []);
    $activity_id = $request->input('selectedSubtaskId');

    DB::beginTransaction(); // Start a database transaction

    try {
        // Create a new ResourceRequest instance
        $resourceRequest = new ResourceRequest();
        $resourceRequest->activity_id = $activity_id;
        $resourceRequest->requested_by = auth()->id(); // Use the authenticated user's ID
        $resourceRequest->status = 'Pending'; // Default status
        $resourceRequest->type = 'material';
        $resourceRequest->save();

        foreach ($selectedMaterialRequests as $material) {
            // Create a new MaterialRequest instance
            $materialRequest = new MaterialRequest();
            $materialRequest->resource_request_id = $resourceRequest->id;  // The created ResourceRequest ID
            $materialRequest->item_id = $material['material_id']; // Material ID from the input
            $materialRequest->item_quantity = $material['quantity']; // Quantity from the input
            $materialRequest->status = 'Pending'; // Default status
            // Save the material request
            $materialRequest->save();
        }

        DB::commit(); // Commit the transaction

        // Set a success flash message
        Session::flash('message', 'Material request allocated successfully.');

        // Redirect to the requests.material route with the correct parameter
        return redirect()->route('requests.showMaterial', ['id' => $resourceRequest->id]);

    } catch (\Exception $e) {
        DB::rollback(); // Rollback the transaction

        // Set an error flash message
        Session::flash('error', $e->getMessage());

        // Redirect to a valid route in your application
        return redirect()->route('material-selection')->withErrors(['error' => $e->getMessage()]);
    }
}
    public function storeEquipmentRequest(Request $request)
    {
        // Fetch selected materials and activity ID from the request
        $selectedMaterialRequests = $request->input('selected_labors', []);
        $activity_id = $request->input('selectedSubtaskId');
      /*  return response()->json([
            'total' => $activity_id,
        ]);*/
        DB::beginTransaction(); // Start a database transaction
    
        try {
            foreach ($selectedMaterialRequests as $material) {
                // Create a new MaterialRequest instance
                $materialRequest = new MaterialRequest();
                $materialRequest->activity_id = $activity_id;
                $materialRequest->item_id = $material['labor_id']; // Correctly reference material_id from the input
                $materialRequest->requested_by = 1; // Example value, adjust as needed
                $materialRequest->item_quantity = $material['quantity']; // Assuming quantity is part of the input
                $materialRequest->status = 'Pending'; // Default status
                
                // Save the material request
                $materialRequest->save();
            }
    
            DB::commit(); // Commit the transaction
    
            // Set a success flash message
            Session::flash('message', 'Labor request allocated successfully.');
    
            // Redirect to a valid route
            return redirect()->route('equipment.where', ['equipment' => $equipmentCost->id]); // Ensure $equipmentCost is defined
    
        } catch (\Exception $e) {
            DB::rollback(); // Rollback the transaction
    
            // Set an error flash message
            Session::flash('error', $e->getMessage());
    
            // Redirect to a valid route in your application
            return redirect()->route('material-selection')->withErrors(['error' => $e->getMessage()]);
        }
    }
    public function storeLaborRequest(Request $request)
    {
        // Fetch selected materials and activity ID from the request
        $selectedMaterialLabors = $request->input('selected_labors', []);
        $activity_id = $request->input('selectedSubtaskId');
        /*return response()->json([
           $selectedMaterialRequests,
        ]);*/
        DB::beginTransaction(); // Start a database transaction
    
        try {
            // Create a new ResourceRequest instance
            $resourceRequest = new ResourceRequest();
            $resourceRequest->activity_id = $activity_id;
            $resourceRequest->requested_by = auth()->id(); // Use the authenticated user's ID
            $resourceRequest->status = 'Pending'; // Default status
            $resourceRequest->type = 'labor';
            $resourceRequest->save();
    
           foreach ($selectedMaterialLabors as $labor) {
                // Create a new MaterialRequest instance
                $materialRequest = new LaborRequest();
                $materialRequest->resource_request_id = $resourceRequest->id;  // The created ResourceRequest ID
                $materialRequest->labor_type_id = $labor['id']; // Material ID from the input
                $materialRequest->quantity_requested = $labor['quantity']; // Quantity from the input
                $materialRequest->status = 'Pending'; // Default status
                // Save the material request
                $materialRequest->save();
            }
    
            DB::commit(); // Commit the transaction
    
            // Set a success flash message
            Session::flash('message', 'Material request allocated successfully.');
            // Redirect to a valid route
          /*  return response()->json([
                $selectedMaterialRequests,
            ]);*/
            return redirect()->route('material.where', ['equipment' => $resourceRequest->id]); // Ensure $equipmentCost is defined
    
        } catch (\Exception $e) {
            DB::rollback(); // Rollback the transaction
    
            // Set an error flash message
            Session::flash('error', $e->getMessage());
    
            // Redirect to a valid route in your application
            return redirect()->route('material-selection')->withErrors(['error' => $e->getMessage()]);
        }
    }
    public function laborEquipmentRequest(Request $request)
    {
        // Fetch selected materials and activity ID from the request
        $selectedMaterialRequests = $request->input('selected_materials', []);
        $activity_id = $request->input('selectedSubtaskId');
      /*  return response()->json([
            'total' => $activity_id,
        ]);*/
        DB::beginTransaction(); // Start a database transaction
    
        try {
            foreach ($selectedMaterialRequests as $material) {
                // Create a new MaterialRequest instance
                $materialRequest = new MaterialRequest();
                $materialRequest->activity_id = $activity_id;
                $materialRequest->item_id = $material['material_id']; // Correctly reference material_id from the input
                $materialRequest->requested_by = 1; // Example value, adjust as needed
                $materialRequest->item_quantity = $material['quantity']; // Assuming quantity is part of the input
                $materialRequest->status = 'Pending'; // Default status
                
                // Save the material request
                $materialRequest->save();
            }
    
            DB::commit(); // Commit the transaction
    
            // Set a success flash message
            Session::flash('message', 'Material request allocated successfully.');
    
            // Redirect to a valid route
            return redirect()->route('equipment.where', ['equipment' => $equipmentCost->id]); // Ensure $equipmentCost is defined
    
        } catch (\Exception $e) {
            DB::rollback(); // Rollback the transaction
    
            // Set an error flash message
            Session::flash('error', $e->getMessage());
    
            // Redirect to a valid route in your application
            return redirect()->route('material-selection')->withErrors(['error' => $e->getMessage()]);
        }
    }
}