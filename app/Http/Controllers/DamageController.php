<?php

namespace App\Http\Controllers;
use App\Models\LeaveEditor;
use App\Models\LeaveRequest;
use App\Models\MaterialDamage; // Model for material damages
use App\Models\EquipmentDamage; // Model for equipment damages
use Illuminate\Http\Request;
use App\Models\Workspace;
class DamageController extends Controller
{
    /**
     * Display a listing of the damages.
     */
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
       /* $materialDamages = MaterialDamage::orderBy('created_at', 'desc')->get();
        $equipmentDamages = EquipmentDamage::orderBy('created_at', 'desc')->get();
        return view('damages.list', compact('materialDamages', 'equipmentDamages'));*/
        /*$materialDamages = MaterialDamage::orderBy('created_at', 'desc')->get(); */
        $leave_requests = is_admin_or_leave_editor() ? $this->workspace->leave_requests() : $this->user->leave_requests();
        $users = $this->workspace->users(true)->get();
        return view('damages.list', ['leave_requests' => $leave_requests->count(), 'users' => $users, 'auth_user' => $this->user]);
    }
    public function list()
    {
        $search = request('search');
        $sort = request('sort') ?: "id";
        $order = request('order') ?: "DESC";
        $status = request('status', "");
        $action_by_id = request('action_by_id', "");
        $start_date_from = request('start_date_from', "");
        $start_date_to = request('start_date_to', "");
        $end_date_from = request('end_date_from', "");
        $end_date_to = request('end_date_to', "");
    
        // Initialize the $where array
        $where = [];
    
        if ($status !== '') {
            $where['status'] = $status;
        }
    
        $material_damages = MaterialDamage::select('material_damages.*');
    
        if ($search) {
            $material_damages = $material_damages->where(function ($query) use ($search) {
                $query->where('description', 'like', '%' . $search . '%')
                      ->orWhere('material_damages.id', 'like', '%' . $search . '%');
            });
        }
    
        // Apply $where conditions
        $material_damages->where($where);
    
        $total = $material_damages->count();
    
        $material_damages = $material_damages->orderBy($sort, $order)
            ->paginate(request("limit"))
            ->through(function ($damage) {
                $statusBadges = [
                    'pending' => '<span class="badge bg-warning">' . get_label('pending', 'Pending') . '</span>',
                    'resolved' => '<span class="badge bg-success">' . get_label('resolved', 'Resolved') . '</span>',
                    'unresolved' => '<span class="badge bg-danger">' . get_label('unresolved', 'Unresolved') . '</span>',
                ];
                $statusBadge = $statusBadges[$damage->status] ?? '';
    
                return [
                    'id' => $damage->id,
                    'material' => $damage->item_id,
                    'warehouse' => $damage->warehouse_id,
                    'damage_date' => $damage->damage_date,
                    'quantity_damaged' => $damage->quantity_damaged,
                    'approved_by' => $damage->approved_by,
                    'issue' => $damage->issue,
                    'status' => $statusBadge,
                ];
            });
    
        return response()->json([
            "rows" => $material_damages->items(),
            "total" => $total,
        ]);
    }
    /**
     * Show the form for creating a new damage record.
     */
    public function create()
    {
        return view('damages.create'); // Return a form for creating a damage record
    }

    /**
     * Store a newly created damage record in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' =>1,
            'warehouse_id' => 1,
            'approved_by' => 1,
            'quantity_damaged' => 'required|integer|min:1',
            'issue' => 'nullable|string',
            'damage_date' => 'nullable|date',
        ]);
    
        MaterialDamage::create($request->only([
            'item_id',
            'warehouse_id',
            'approved_by',
            'quantity_damaged',
            'issue',
            'damage_date',
        ]));
    
        return redirect()->route('damages.index')->with('success', 'Damage record created successfully.');
    }
    /**
     * Display the specified damage record.
     */
    public function show($id)
    {
        // Logic to find either material or equipment damage
        $damage = MaterialDamage::find($id) ?? EquipmentDamage::findOrFail($id);
        return view('damages.show', compact('damage'));
    }

    /**
     * Show the form for editing the specified damage record.
     */
    public function edit($id)
    {
        // Logic to find either material or equipment damage
        $damage = MaterialDamage::find($id) ?? EquipmentDamage::findOrFail($id);
        return view('damages.edit', compact('damage'));
    }

    /**
     * Update the specified damage record in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:material,equipment',
            'item_id' => 'required_if:type,material|exists:materials,id',
            'equipment_id' => 'required_if:type,equipment|exists:equipment,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'approved_by' => 'required|exists:users,id',
            'quantity_damaged' => 'required|integer|min:1',
            'issue' => 'nullable|string',
            'damage_date' => 'nullable|date',
        ]);

        if ($request->type === 'material') {
            $damage = MaterialDamage::findOrFail($id);
            $damage->update($request->only([
                'item_id',
                'warehouse_id',
                'approved_by',
                'quantity_damaged',
                'issue',
                'damage_date',
            ]));
        } else {
            $damage = EquipmentDamage::findOrFail($id);
            $damage->update($request->only([
                'equipment_id',
                'warehouse_id',
                'approved_by',
                'quantity_damaged',
                'issue',
                'damage_date',
            ]));
        }

        return redirect()->route('damages.index')->with('success', 'Damage record updated successfully.');
    }

    /**
     * Remove the specified damage record from storage.
     */
    public function destroy($id)
    {
        $damage = MaterialDamage::find($id) ?? EquipmentDamage::findOrFail($id);
        $damage->delete(); // Delete damage record

        return redirect()->route('damages.index')->with('success', 'Damage record deleted successfully.');
    }
}