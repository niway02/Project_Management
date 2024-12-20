<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\EquipmentType;
use App\Models\UnitMeasure;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // fetch session and use it in entire class with constructor
            $this->user = getAuthenticatedUser();
            return $next($request);
        });
    }
    public function index()
    {
        $units = UnitMeasure::all();
        $warehouses = Warehouse::all();
        $equipments = Equipment::with(['createdBy', 'updatedBy'])->get();
        $equipmentTypes = EquipmentType::all();
        return view('equipments.index', compact('equipments', 'warehouses','units','equipmentTypes'));
    }
    public function show($id)
    {
        try {
            // Retrieve the equipment by ID
            $equipment = Equipment::findOrFail($id);
        
            // Fetch related data
            $units = UnitMeasure::all();
            $warehouses = Warehouse::all();
            $equipmentTypes = EquipmentType::all();
            
            // Return the view with the necessary data
           // return response()->json($equipment);

            return view('equipments.show', compact('equipment', 'warehouses', 'units', 'equipmentTypes'));
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle the case where the equipment is not found
            return redirect()->route('equipments.index')->with('error', 'Equipment not found.');
        } catch (\Exception $e) {
            // Handle any other exceptions
            return redirect()->route('equipments.index')->with('error', 'An error occurred while retrieving the equipment: ' . $e->getMessage());
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       // $units = UnitMeasure::all();
        $warehouses = Warehouse::all();
        $users = User::all();
        return view('equipments.create', compact('units', 'warehouses', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user_id = $this->user->id; 
    
        // Retrieve the warehouse managed by the current user
        $warehouse = Warehouse::where('manager', $user_id)->first();
    
        if (!$warehouse) {
            return response()->json([
                'success' => false,
                'message' => 'No warehouse found for the current user.',
            ], 404);
        }
    
        // Get the warehouse ID
        $warehouse_id = $warehouse->id;
    
        try {
            // Validate the input data
            $validatedData = $request->validate([
                'item' => 'required|string|max:255',
                'quantity' => 'numeric',
                'rate_with_vat' => 'nullable|numeric',
                'amount' => 'nullable|numeric',
                'remark' => 'nullable|string|max:255',
                'type_id' => 'required|integer',
                'reorder_quantity' => 'nullable|numeric',
                'min_quantity' => 'nullable|numeric',
            ]);
    
            // Add the warehouse_id, created_by, and updated_by fields
            $validatedData['warehouse_id'] = $warehouse_id;
            $validatedData['created_by'] = $user_id;
            $validatedData['updated_by'] = $user_id;
    
            // Create the new equipment
            $equipment = Equipment::create($validatedData);
    
            // Return a success message as a flash message
            return redirect()->back()->with('success', 'Equipment created successfully.');
        } catch (\Exception $e) {
            // Return an error message as a flash message
            return redirect()->back()->with('error', 'Error creating equipment: ' . $e->getMessage());
        }
    }
public function data()
{
    $warehouses = Equipment::all();
    return response()->json($warehouses);
}
    // Add more methods for update, show, destroy, etc.
}