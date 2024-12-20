<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use App\Models\Material;
use App\Models\MaterialCost;
use Illuminate\Http\Request;
use App\Models\UnitMeasure;
use App\Models\User;
use App\Models\Workspace;
use App\Models\Client;
use App\Models\Project;
use App\Models\MaterialsInventory;
use App\Models\MaterialRequestResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class MaterialCostController extends Controller
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
        $materials = MaterialCost::all();
        return view('materials.index', compact('materials'));
    }
    public function materialcostsSelect(Request $request)
    {
        $query = Material::with('UnitMeasure');
    
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
    
        return view('materialcosts.materialcostsSelect', [
            'materials' => $materials
        ]);
    }

    public function anotherPage(Request $request)
    {
        $materialIds = $request->input('material_ids', []);
        $selectedMaterials = Material::whereIn('id', $materialIds)->get();
        // Perform additional logic or return a view
        return view('another-page', compact('selectedMaterials'));
    }
    public function materialSelection(Request $request)
    {
        $selectedMaterials = $request->input('selected_materials', []);
        $selectedSubtaskId = $request->input('selected_subtask_id');
    
        $materials = [];
        foreach ($selectedMaterials as $serializedMaterial) {
            $material = json_decode($serializedMaterial, true);
            $materials[] = $material;
        }
       /* return response()->json([
            'total' => $materials,
        ]);*/
      return view('materialcosts.material-subtasks', [
            'selectedMaterials' => $materials,
            'selectedSubtaskId' => $selectedSubtaskId
        ]);  

      /*  return response()->json([
            'total' => $materials,
        ]);*/
    }
public function materialSelectionSubmit(Request $request)
{
    $selectedMaterials = $request->input('selected_materials', []);

    // Deserialize the selected materials
    $selectedMaterialsData = array_map('unserialize', $selectedMaterials);

    return view('materialcosts.material-subtasks', ['selectedMaterials' => $selectedMaterialsData]);
}
    public function create()
    {
        $subtasks = Subtask::all();
        $materials = Material::all(); 
       
        // Show the form to create a new material
        return view('materialcosts.create', compact('subtasks', 'materials'));
     //   return view('materialcosts.create');
    }
    public function materialAllocation(Request $request)
    {
        $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects : $this->user->projects;
        $tasks = isAdminOrHasAllDataAccess() ? $this->workspace->tasks : $this->user->tasks;
        $subTasks = Subtask::all();
    
        $query = MaterialsInventory::with('Material.UnitMeasure','Warehouse');
    
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
      /*return response()->json([
            'total' => $materialsInventory,
        ]);*/
        return view('materialcosts.materialAllocation', [
            'contracts' => $contracts,
            'materialsInventory' => $materialsInventory,
            'projects' => $projects,
            'tasks' => $tasks,
            'subTasks' => $subTasks
        ]);
    }
    public function storeMaterialAllocation(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'selectedSubtaskId' => 'required|integer',
            'selected_materials' => 'required|array',  // Ensure the selected materials are provided
        ]);
        
        $selectedMaterials = $request->input('selected_materials', []);
        
        DB::beginTransaction();
        try {
            foreach ($selectedMaterials as $material) {
                $materialResponse = MaterialRequestResponse::where('material_request_id', $material['material_request_id'])->first();
               /* return response()->json([
                    'total' => $materialResponse,
                ]);*/
                // Check if the material response was found
                if ($materialResponse) {
                    // Create a new MaterialCost record
                    $materialCost = new MaterialCost();
                    $materialCost->material_id = $material['id']; // Ensure 'id' exists in $material
                    $materialCost->planned_quantity = $material['quantity'];
                    $materialCost->planned_cost = $material['rate_with_vat'];
                    $materialCost->activity_id = $validatedData['selectedSubtaskId'];
                    $materialCost->save();
    
                    // Update the materialResponse quantity
                   // $materialResponse->approved_quantity -= $material['quantity'];
                    $materialResponse->status = 'allocated'; // Update status to 'allocated'
                 //   $materialResponse->remark = $validatedData['remark'];
                    $materialResponse->save();
                } else {
                    // Handle the case where MaterialRequestResponse was not found
                    // Log or throw exception if necessary
                }
            }
    
            DB::commit();
            
            // Set a success flash message
            Session::flash('message', 'Material allocated successfully.');
            
            // Redirect to a valid route in your application
         //  return redirect()->route('materialcosts.materialAllocation');
         return redirect()->route('material.where', ['material' => $materialCost->id]);
     //    return redirect()->route('material.where', ['material' => $materialCost->id]);
        } catch (\Exception $e) {
            DB::rollback();
            
            // Set an error flash message
            Session::flash('error', 'An error occurred: ' . $e->getMessage());
            
            // Redirect back to the material selection route
            return redirect()->route('material-selection')->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
    /*public function storeMaterialAllocation(Request $request)
    {
        $selectedMaterials = $request->input('selected_materials', []);
    
        try {
        
            foreach ($selectedMaterials as $material) {
                $materialCost = new MaterialCost();
                $materilInventory = new MaterialsInventory();
                $materialCost->material_id = $material['id'];
                $materialCost->planned_quantity = $material['quantity'];
                $materialCost->planned_budget = $material['rate_with_vat'];
                $materialCost->subtask_id = 1;
                $materialCost->save();

                $materilInventory->quantity = $materilInventory->quantity-$material['quantity'];
                $materilInventory->save();
          
            }
            // Set a success flash message
        //   session()->flash('success', 'Material allocated successfully.');
        Session::flash('message', 'Material allocated successfully.');
            // Redirect to a valid route in your application
            return redirect()->route('material-selection');
        } catch (\Exception $e) {
            // Set an error flash message
            session()->flash('error', 'Error occurred while allocating material.');
    
            // Redirect to a valid route in your application
            return redirect()->route('material-selection')->with('error', 'Error occurred while allocating material.');
        }
    }*/
    public function show($id)
    {
        // Retrieve the material cost data based on the provided $id
        $materialCosts = MaterialCost::where('subtask_id', $id)->get();
    
        // Iterate through each material cost and fetch the material name
   
        // Return the view with the material cost data
        return view('materialcosts.show', ['materialCosts' => $materialCosts]);
    }
    public function store(Request $request)
    {
        $materialCost = new MaterialCost;
        $materialCost->subtask_id = $request->subtask_id;
        $materialCost->material_id = $request->material_id;
        $materialCost->unit = $request->unit;
        $materialCost->qty = $request->qty;
        $materialCost->rate_with_vat = $request->rate_with_vat;
        $materialCost->amount = $request->amount;
        $materialCost->remark = $request->remark;
        $materialCost->save();

     //   return redirect()->route('materialcosts');

     $subtasks = Subtask::all();
     $materials = Material::all();
     // Show the form to create a new material
     return view('materialcosts.create', compact('subtasks', 'materials'));
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