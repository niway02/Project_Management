<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Workspace;
use App\Models\User;
use App\Models\Transfer;
use App\Models\EquipmentInventory;
use App\Models\MaterialsInventory;
use Illuminate\Support\Facades\DB;
class TransferController extends Controller
{
    protected $workspace;
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
        $transfers = MaterialsInventory::with(['material.unitMeasure', 'warehouse'])->get();
        return view('transfer.incoming', compact('transfers'));
    }
    public function incomingTransfer()
    {
        return view('transfer.incoming');
    }
    public function transferList()
    {
        $search = request('search');
        $sort = request('sort') ?: "id";
        $order = request('order') ?: "DESC";
        $status = request('status') ?: "";
        // Start the query without any joins
       // $materials_inventories = MaterialsInventory::select('materials_inventories.*'); // Adjust to include necessary fields
        $materials_inventories = MaterialsInventory::with(['material.unitMeasure', 'warehouse']);
    
        if ($status != '') {
            $materials_inventories = $materials_inventories->where('status', $status);
        }

        if ($search) {
            $materials_inventories = $materials_inventories->where(function ($query) use ($search) {
                $query->where('reason', 'like', '%' . $search . '%')
                      ->orWhere('id', 'like', '%' . $search . '%');
            });
        }
    
        $total = $materials_inventories->count();
        $total2 = $materials_inventories->get();
    
        $materials_inventory = $materials_inventories->orderBy($sort, $order)
            ->paginate(request("limit"))
            ->through(function ($materials_inventory) {
                $statusBadges = [
                    'allocate' => '<span class="badge bg-warning">' . get_label('allocate', 'Allocate') . '</span>',
                    'approved' => '<span class="badge bg-success">' . get_label('approved', 'Approved') . '</span>',
                    'rejected' => '<span class="badge bg-danger">' . get_label('rejected', 'Rejected') . '</span>',
                ];
                $statusBadge = $statusBadges[$materials_inventory->status] ?? '';
                return [
                    'id' => $materials_inventory->id,
                    'quantity' => $materials_inventory->quantity,
                    'name' =>$materials_inventory->material->item,
                   // 'unit'=>$materials_inventory->material->unit_measure->name,
                    'hourly_rate' =>$materials_inventory->material->rate_with_vat,
                    'reorder_quantity' =>$materials_inventory->material->reorder_quantity,
                    'min_quantity' =>$materials_inventory->material->min_quantity,
                    'created_at' => format_date($materials_inventory->created_at, true),
                    'updated_at' => format_date($materials_inventory->updated_at, true),
                    'status' => $materials_inventory->status,
                ];
            });
    
        return response()->json([
            "rows" => $materials_inventory->items(),
            "total" => $total,
        ]);
    }
    public function submitTransfers(Request $request)
    {
        $selectedMaterials = $request->input('selected_materials', []);
        $selectedSubtaskId =  $request->input('selected_subtask_id');
        $materials = [];
        foreach ($selectedMaterials as $serializedMaterial) {
            $material = json_decode($serializedMaterial, true);
            $materials[] = $material;
        }
    
      return view('transfer.selectedMaterial', [
            'selectedMaterials' => $materials,
            'selectedSubtaskId' => $selectedSubtaskId
        ]);  

      /*  return response()->json([
            'total' => $materials,
        ]);*/
    }
    public function transferStore(Request $request)
    {
        // Fetch selected materials and activity ID from the request
        $selectedMaterialRequests = $request->input('selected_materials', []);
        $activity_id = $request->input('selectedSubtaskId');
    
        return response()->json([
            'total' => $selectedMaterialRequests,
        ]);
        /*DB::beginTransaction(); // Start a database transaction
    
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
    
            // Redirect to a valid route
            return redirect()->route('material.where', ['equipment' => $resourceRequest->id]); // Ensure $equipmentCost is defined
    
        } catch (\Exception $e) {
            DB::rollback(); // Rollback the transaction
    
            // Set an error flash message
            Session::flash('error', $e->getMessage());
    
            // Redirect to a valid route in your application
            return redirect()->route('material-selection')->withErrors(['error' => $e->getMessage()]);
        }*/
    }
  /*  public function index(Request $request)
    {
        $transfers = Transfer::with(['fromUser', 'toUser'])
            ->where('workspace_id', $this->workspace->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('transfers.index', compact('transfers'));
    }

    public function create()
    {
        $users = User::where('workspace_id', $this->workspace->id)
            ->where('id', '!=', $this->user->id)
            ->get();

        return view('transfers.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'from_user_id' => 'required|exists:users,id',
            'to_user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'reason' => 'required|string|max:255',
        ]);

        $transfer = Transfer::create([
            'workspace_id' => $this->workspace->id,
            'from_user_id' => $request->from_user_id,
            'to_user_id' => $request->to_user_id,
            'amount' => $request->amount,
            'reason' => $request->reason,
        ]);

        return redirect()->route('transfers.index')
            ->with('success', 'Transfer created successfully.');
    }*/
}