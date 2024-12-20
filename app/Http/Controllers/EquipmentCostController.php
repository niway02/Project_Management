<?php

namespace App\Http\Controllers;

use App\Models\EquipmentCost;
use App\Models\Material;
use App\Models\Equipment;
use App\Models\Subtask;
use App\Models\EquipmentInventory;
use App\Models\MaterialCost;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Workspace;
class EquipmentCostController extends Controller
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
        $materials = EquipmentCost::all();
        return view('materials.index', compact('materials'));
    }
  /*  public function equipmentAllocation()
    {
        $materials = EquipmentCost::all();
        return view('equipmentAllocation.index', compact('materials'));
    }
    */
    public function equipmentAllocation(Request $request)
    {  /*
        $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects : $this->user->projects;
        $tasks = isAdminOrHasAllDataAccess() ? $this->workspace->tasks : $this->user->tasks;
        $subTasks = Subtask::all();

        $query = Equipment::with('UnitMeasure');
        
        $draw = $request->get('draw', 1);
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        $search = $request->get('search', ['value' => '']);
        $order = $request->get('order', [['column' => 0, 'dir' => 'asc']]);
    
        $totalRecords = $query->count();
    
        $materials = $query
            ->skip($start)
            ->take($length)
            ->orderBy('id', 'desc')
            ->get();
            $contracts = $materials->count();
        $data = $materials->map(function ($material) {
            return [
                'id' => $material->id,
                'item' => $material->name,
                'unit' => $material->UnitMeasure->name,
                'quantity' => $material->quantity,
                'rate_with_vat' => $material->rate_with_vat,
                'amount' => $material->quantity * $material->rate_with_vat,
            ];
        });
        return view('equipmentcost.equipmentAllocation', ['contracts' => $contracts,'materials' => $materials,'projects' => $projects,'tasks' => $tasks,'subTasks' => $subTasks
        ]);

       */
        //return view('contracts.list', ['contracts' => $contracts, 'users' => $materials, 'clients' => $materials, 'projects' => $materials, 'contract_types' => $materials]);
       // return view('materialcosts.materialAllocation', compact('contracts'));
      /* $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects : $this->user->projects;
       $tasks = isAdminOrHasAllDataAccess() ? $this->workspace->tasks : $this->user->tasks;
       $subTasks = Subtask::all();
   
       $query = EquipmentInventory::with('Equipment.UnitMeasure','Warehouse');
   
       $draw = $request->get('draw', 1);
       $start = $request->get('start', 0);
       $length = $request->get('length', 10);
       $search = $request->get('search', ['value' => '']);
       $order = $request->get('order', [['column' => 0, 'dir' => 'asc']]);
   
       $totalRecords = $query->count();
   
       $equipmentsInventory = $query
           ->skip($start)
           ->take($length)
           ->orderBy('id', 'desc')
           ->get();
   
       $contracts = $equipmentsInventory->count();
     /*return response()->json([
           'total' => $equipmentsInventory,
       ]); */
     /*  return view('equipmentcost.equipmentAllocation', [
           'contracts' => $contracts,
           'equipmentsInventory' => $equipmentsInventory,
           'projects' => $projects,
           'tasks' => $tasks,
           'subTasks' => $subTasks
       ]);*/
    }
   /* public function equipmentSelection(Request $request)
    {
        $selectedMaterials = $request->input('selected_materials', []);
    
        $materials = [];
        foreach ($selectedMaterials as $serializedMaterial) {
            $material = json_decode($serializedMaterial, true);
            $materials[] = $material;
        }
    
        return view('equipmentcost.selectedEquipment', ['selectedMaterials' => $materials]);
    }*/
    public function create()
    {
        $subtasks = Subtask::all();
        $materials = Material::all();
        // Show the form to create a new material
        return view('equipmentcost.create', compact('subtasks', 'materials'));
        // Show the form to create a new material
       // return view('equipmentcost.create');
    }
    public function show($id)
    {
        // Retrieve the material cost data based on the provided $id
        $equipmentcosts = EquipmentCost::where('subtask_id', $id)->get();
        
       
        // Return the view with the material cost data
        return view('equipmentcost.show', ['equipmentcosts' => $equipmentcosts]);
    }
  
    public function store(Request $request)
    {
        $materialCost = new EquipmentCost;
        $materialCost->subtask_id = $request->subtask_id;
        $materialCost->equipment_id = $request->equipment_id;
        $materialCost->unit = $request->unit;
        $materialCost->qty = $request->qty;
        $materialCost->rate_with_vat = $request->rate_with_vat;
        $materialCost->amount = $request->amount;
        $materialCost->remark = $request->remark;
        $materialCost->save();

        return redirect()->route('materialcosts');

    }

    public function edit(Material $material)
    {
        // Show the form to edit the material
        return view('materials.edit', compact('material'));
    }

    public function update(Request $request, Material $material)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'item' => 'required',
            'unit' => 'required',
            'quantity' => 'required|numeric',
            'rate_with_vat' => 'required|numeric',
            'amount' => 'required|numeric',
            'remark' => 'nullable',
        ]);

        // Update the material
        $material->update($validatedData);

        // Redirect to the index page with success message
        return redirect()->route('materials.index')->with('success', 'Material updated successfully.');
    }

    public function destroy(Material $material)
    {
        // Delete the material
        $material->delete();

        // Redirect to the index page with success message
        return redirect()->route('materials.index')->with('success', 'Material deleted successfully.');
    }
}