<?php
namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\Workspace;
use App\Models\User;
use App\Models\EquipmentInventory;
use App\Models\MaterialsInventory;
use App\Models\Labor;
class WarehouseController extends Controller
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
    /**
     * Display a listing of the warehouses.
     *
     * @return \Illuminate\View\View
     */
  /*  public function index()
    {

        $warehouses = Warehouse::with('User')->get();
        return view('warehouses.index', compact('warehouses'));
    }
   */
  public function index(Request $request)
  {
      /*$warehouses = Warehouse::with('user')
                    ->latest()
                    ->paginate($request->input('per_page', 10));
                   return view('warehouses.index', compact('warehouses')); */

                   $warehouses = Warehouse::with('user')->get();
               //    return response()->json($warehouses);
      return view('warehouses.index', compact('warehouses'));
    //return response()->json($warehouses);
  }
  public function warehouseData()
  {
      $warehouseData = Warehouse::select(
          'warehouses.id',
          'warehouses.name',
          'warehouses.location',
          'warehouses.contact_info',
          \DB::raw('COUNT(equipment_inventories.id) AS total_equipment'),
          \DB::raw('SUM(equipment_inventories.quantity) AS equipments'),
          \DB::raw('(SELECT COUNT(*) FROM materials_inventories mi WHERE mi.warehouse_id = warehouses.id) AS total_materials'),
          \DB::raw('(SELECT SUM(quantity) FROM materials_inventories mi WHERE mi.warehouse_id = warehouses.id) AS materials')
      )
      ->leftJoin('equipment_inventories', 'warehouses.id', '=', 'equipment_inventories.warehouse_id')
      ->groupBy('warehouses.id')
      ->get();
  
      return view('warehouses.warehouse_data', compact('warehouseData'));
  }
  public function show($id)
  {
      $warehouse = Warehouse::findOrFail($id);
      $laborCount = Labor::count();
      $minEquipmentCount = EquipmentInventory::selectRaw('COUNT(*) as count')
      ->join('equipment', 'equipment_inventories.equipment_id', '=', 'equipment.id')
      ->where('equipment_inventories.warehouse_id', $warehouse->id)
      ->where('equipment.min_quantity', '>', 'equipment_inventories.quantity')
      ->value('count');
      $minMaterial = MaterialsInventory::where('warehouse_id', $warehouse->id)
      ->whereHas('material', function ($query) {
          $query->where('min_quantity', '>', 'warehouse_quantity');
      })
      ->count();
      
      $totalEquipment = EquipmentInventory::where('warehouse_id', $warehouse->id)->sum('quantity');
      $totalMaterial = MaterialsInventory::where('warehouse_id', $warehouse->id)->sum('quantity');
      $equipmentInventories = EquipmentInventory::where('equipment_inventories.warehouse_id', $warehouse->id)
      ->join('equipment', 'equipment_inventories.equipment_id', '=', 'equipment.id')
      ->leftJoin('unit_measures', 'equipment.unit_id', '=', 'unit_measures.id')
      ->select(
          'equipment.item',
          'unit_measures.name as unit_name',
          'equipment.reorder_quantity',
          'equipment.min_quantity',
          'equipment.unit_id',
          'equipment_inventories.quantity'
      )
      ->get();
  
      $materialInventories = MaterialsInventory::where('materials_inventories.warehouse_id', $warehouse->id)
          ->join('materials', 'materials_inventories.material_id', '=', 'materials.id')
          ->select('materials.item','materials.reorder_quantity','materials.min_quantity','materials.unit_id', 'materials_inventories.quantity')
          //->groupBy('materials.id')
          ->get();
      $inactiveLabor = 25;
      $permLabor  = 15;
      $activeLabor = 7;
      $contractLabor = 8;
      $laborByProfession  = 11;
      return view('warehouses.show', compact('warehouse','minMaterial','minEquipmentCount','inactiveLabor','permLabor','activeLabor','contractLabor','laborByProfession','totalEquipment','laborCount', 'totalMaterial', 'equipmentInventories', 'materialInventories'));
  } 
  public function myWarehouses()
  {
      // Get all warehouses created by the authenticated user
      $warehouses = Warehouse::where('created_by', auth()->id())->get();
  
      // Initialize an array to store warehouse data
      $warehouseData = [];
  
      // Loop through each warehouse
      foreach ($warehouses as $warehouse) {
          // Calculate min equipment count
          $minEquipmentCount = EquipmentInventory::selectRaw('COUNT(*) as count')
              ->join('equipment', 'equipment_inventories.equipment_id', '=', 'equipment.id')
              ->where('equipment_inventories.warehouse_id', $warehouse->id) // Specify the table
              ->where('equipment.min_quantity', '>', 'equipment_inventories.quantity')
              ->value('count');
  
          // Calculate min material count
          $minMaterial = 78;
          // Calculate total equipment and material quantities
          $totalEquipment = EquipmentInventory::where('equipment_inventories.warehouse_id', $warehouse->id) // Specify the table
              ->sum('quantity');
          $totalMaterial = MaterialsInventory::where('materials_inventories.warehouse_id', $warehouse->id) // Specify the table
              ->sum('quantity');
  
          // Get equipment inventories
          $equipmentInventories = EquipmentInventory::where('equipment_inventories.warehouse_id', $warehouse->id) // Specify the table
              ->join('equipment', 'equipment_inventories.equipment_id', '=', 'equipment.id')
              ->leftJoin('unit_measures', 'equipment.unit_id', '=', 'unit_measures.id')
              ->select(
                  'equipment.item',
                  'equipment.manufacturer',
                  'equipment.vin_serial',
                  'equipment.eqp_condition',
                  'equipment.owner',
                  'equipment.year',
                  'unit_measures.name as unit_name',
                  'equipment.reorder_quantity',
                  'equipment.min_quantity',
                  'equipment.unit_id',
                  'equipment_inventories.quantity'
              )
              ->get();
  
          // Get material inventories
          $materialInventories = MaterialsInventory::where('materials_inventories.warehouse_id', $warehouse->id) // Specify the table
              ->join('materials', 'materials_inventories.material_id', '=', 'materials.id')
              ->leftJoin('unit_measures', 'materials.unit_id', '=', 'unit_measures.id')
              ->select(
                  'materials.item', 
                  'materials.reorder_quantity', 
                  'materials.min_quantity', 
                  'materials.unit_id',
                  'materials_inventories.quantity'
              )
              ->get();
  
          // Store the results in the array
          $warehouseData[] = [
              'warehouse' => $warehouse,
              'minMaterial' => $minMaterial,
              'minEquipmentCount' => $minEquipmentCount,
              'totalEquipment' => $totalEquipment,
              'totalMaterial' => $totalMaterial,
              'equipmentInventories' => $equipmentInventories,
              'materialInventories' => $materialInventories,
          ];
      }
      // Return the view with the warehouse data
      /*return response()->json([
        'total' => $equipmentInventories,
    ]);*/
      return view('warehouses.mywarehouse', compact('warehouseData','totalEquipment','minEquipmentCount','totalMaterial','minMaterial'));
  }
  public function warehousesMaterials(Request $request)
  {
    $id = 1;
      $search = $request->get('search');
      $limit = $request->get('limit');
      $offset = $request->get('offset');
  
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
     
      $warehouse = Warehouse::findOrFail($id);
      $totalEquipment = MaterialsInventory::where('materials_inventories.warehouse_id', $warehouse->id)
      ->join('materials', 'materials_inventories.material_id', '=', 'materials.id')
      ->select('materials.item','materials.reorder_quantity','materials.min_quantity','materials.unit_id', 'materials_inventories.quantity')
      //->groupBy('materials.id')
      ->get();
      // Filter, sort, and paginate the data as needed
      $filteredEquipment = $totalEquipment;
      if ($search) {
          $filteredEquipment = $totalEquipment->filter(function ($item) use ($search) {
              return stripos($item->item, $search) !== false || stripos($item->unit_name, $search) !== false;
          });
      }
  
      if ($sort && $order) {
          if ($sort == 'id') {
              $filteredEquipment = $filteredEquipment->sortBy($sort, SORT_REGULAR, $order === 'desc');
          } else {
              $filteredEquipment = $filteredEquipment->sortBy($sort, SORT_REGULAR, $order === 'desc');
          }
      }
  
      $totalItems = $filteredEquipment->count();
      $filteredEquipment = $filteredEquipment->slice($offset, $limit);
  
      return response()->json([
          'total' => $totalItems,
          'rows' => $filteredEquipment
      ]);
  }
  public function warehousesEquipments(Request $request)
  {
    $id = 1;
      $search = $request->get('search');
      $limit = $request->get('limit');
      $offset = $request->get('offset');
  
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
     
      $warehouse = Warehouse::findOrFail($id);
      $totalEquipment = EquipmentInventory::where('equipment_inventories.warehouse_id', $warehouse->id)
      ->join('equipment', 'equipment_inventories.equipment_id', '=', 'equipment.id')
      ->leftJoin('unit_measures', 'equipment.unit_id', '=', 'unit_measures.id')
      ->select(
          'equipment.item',
          'unit_measures.name as unit_name',
          'equipment.reorder_quantity',
          'equipment.min_quantity',
          'equipment.unit_id',
          'equipment_inventories.quantity'
      )
      ->get();
      // Filter, sort, and paginate the data as needed
      $filteredEquipment = $totalEquipment;
      if ($search) {
          $filteredEquipment = $totalEquipment->filter(function ($item) use ($search) {
              return stripos($item->item, $search) !== false || stripos($item->unit_name, $search) !== false;
          });
      }
  
      if ($sort && $order) {
          if ($sort == 'id') {
              $filteredEquipment = $filteredEquipment->sortBy($sort, SORT_REGULAR, $order === 'desc');
          } else {
              $filteredEquipment = $filteredEquipment->sortBy($sort, SORT_REGULAR, $order === 'desc');
          }
      }
  
      $totalItems = $filteredEquipment->count();
      $filteredEquipment = $filteredEquipment->slice($offset, $limit);
  
      return response()->json([
          'total' => $totalItems,
          'rows' => $filteredEquipment
      ]);
  }

  public function warehousesLabors(Request $request)
  {
    $id = 1;
      $search = $request->get('search');
      $limit = $request->get('limit');
      $offset = $request->get('offset');
  
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
     
      $totalEquipment = Labor::all();
      // Filter, sort, and paginate the data as needed
      $filteredEquipment = $totalEquipment;
      if ($search) {
          $filteredEquipment = $totalEquipment->filter(function ($item) use ($search) {
              return stripos($item->item, $search) !== false || stripos($item->unit_name, $search) !== false;
          });
      }
  
      if ($sort && $order) {
          if ($sort == 'id') {
              $filteredEquipment = $filteredEquipment->sortBy($sort, SORT_REGULAR, $order === 'desc');
          } else {
              $filteredEquipment = $filteredEquipment->sortBy($sort, SORT_REGULAR, $order === 'desc');
          }
      }
  
      $totalItems = $filteredEquipment->count();
      $filteredEquipment = $filteredEquipment->slice($offset, $limit);
  
      return response()->json([
          'total' => $totalItems,
          'rows' => $filteredEquipment
      ]);
  }
    /**
     * Show the form for creating a new warehouse.
     *
     * @return \Illuminate\View\View
     */

    public function create(Request $request)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'manager' => 1,
            'contact_info' => 'required|string|max:255',
            'created_by' => 1
        ]);

        // Create a new warehouse
        $warehouse = new Warehouse();
        $warehouse->name = $validatedData['name'];
        $warehouse->description = $validatedData['description'];
        $warehouse->location = $validatedData['location'];
        $warehouse->manager = $validatedData['manager'];
        $warehouse->contact_info = $validatedData['contact_info'];
        $warehouse->created_by = $validatedData['created_by'];
        $warehouse->save();

        // Return a response or redirect to the appropriate view
        return response()->json($warehouse, 201);
    }

    /**
     * Store a newly created warehouse in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'manager' => 1,
            'contact_info' => 'required|string|max:255',
            'created_by' => 1
        ]);

        // Create a new warehouse
        $warehouse = new Warehouse();
        $warehouse->name = $validatedData['name'];
        $warehouse->description = $validatedData['description'];
        $warehouse->location = $validatedData['location'];
        $warehouse->manager = $validatedData['manager'];
        $warehouse->contact_info = $validatedData['contact_info'];
        $warehouse->created_by = $validatedData['created_by'];
        $warehouse->save();

        // Return a response or redirect to the appropriate view
        return response()->json($warehouse, 201);
    }

    /**
     * Return the warehouse data for the table.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        $search = $request->get('search');
        $limit = $request->get('limit');
        $offset = $request->get('offset');
    
        $sort = $request->get('sort', 'id');
        $order = 'desc';
        
        switch ($sort) {
            case 'newest':
                $sort = 'created_at';
                break;
            case 'oldest':
                $sort = 'created_at';
                $order = 'asc';
                break;
            case 'recently-updated':
                $sort = 'updated_at';
                break;
            case 'earliest-updated':
                $sort = 'updated_at';
                $order = 'asc';
                break;
        }
    
        $warehouses = Warehouse::select(
            'warehouses.id',
            'warehouses.name',
            'warehouses.location',
            'warehouses.contact_info',
            \DB::raw('COUNT(equipment_inventories.id) AS total_equipment'),
            \DB::raw('SUM(equipment_inventories.quantity) AS equipments'),
            \DB::raw('(SELECT COUNT(*) FROM materials_inventories mi WHERE mi.warehouse_id = warehouses.id) AS total_materials'),
            \DB::raw('(SELECT SUM(quantity) FROM materials_inventories mi WHERE mi.warehouse_id = warehouses.id) AS materials'),
            'users.first_name',
            'users.last_name'
        )
        ->leftJoin('equipment_inventories', 'warehouses.id', '=', 'equipment_inventories.warehouse_id')
        ->leftJoin('users', 'warehouses.manager', '=', 'users.id') // Join with users table
        ->groupBy('warehouses.id', 'users.first_name', 'users.last_name')
        ->get();
    
        $totalWarehouses = $warehouses;
        if ($search) {
            $totalWarehouses = $warehouses->filter(function ($item) use ($search) {
                return stripos($item->name, $search) !== false || stripos($item->location, $search) !== false;
            });
        }
    
        // Sort the results
        if ($sort && $order) {
            $totalWarehouses = $totalWarehouses->sortBy($sort, SORT_REGULAR, $order === 'desc');
        }
    
        $totalItems = $totalWarehouses->count();
        $filteredWarehouses = $totalWarehouses->slice($offset, $limit);
    
        return response()->json([
            'total' => $totalItems,
            'rows' => $filteredWarehouses
        ]);
    }
    public function exportPdf()
{
    // Fetch the data to be exported
    $warehouses = Warehouse::all();

    // Generate the PDF file
    $pdf = PDF::loadView('warehouses.pdf', compact('warehouses'));

    // Return the PDF file for download
    return $pdf->download('warehouses.pdf');
}
public function exportXlsx()
{
    // Fetch the data to be exported
    $warehouses = Warehouse::all();

    // Generate the XLSX file
    return Excel::download(new WarehouseExport($warehouses), 'warehouses.xlsx');
}
public function exportCsv()
{
    // Fetch the data to be exported
    $warehouses = Warehouse::all();

    // Generate the CSV file
    return Excel::download(new WarehouseExport($warehouses), 'warehouses.csv');
}
public function showImportForm()
{
    return view('warehouses.import');
}
public function importWarehouses(Request $request)
{
    // Validate the uploaded file
    $request->validate([
        'file' => 'required|file|mimes:xlsx,csv'
    ]);

    // Process the uploaded file and import the data
    Excel::import(new WarehouseImport, $request->file('file'));

    // Redirect to the warehouses list or display a success message
    return redirect()->route('warehouses.index')->with('success', 'Warehouses imported successfully.');
}
}