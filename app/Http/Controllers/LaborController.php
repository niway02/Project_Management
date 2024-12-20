<?php

namespace App\Http\Controllers;

use App\Models\Labor;
use Illuminate\Http\Request;
use App\Models\Workspace;
use App\Models\LaborType;
class LaborController extends Controller
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
        $labors = Labor::all();
        return view('labors.index', compact('labors'));
    }
    public function laborList()
    {
        $search = request('search');
        $sort = request('sort') ?: "id";
        $order = request('order') ?: "DESC";
        $status = request('status') ?: "";
        // Start the query without any joins
        $leave_requests = Labor::select('labors.*'); // Adjust to include necessary fields

    
        if ($status != '') {
            $leave_requests = $leave_requests->where('status', $status);
        }

        if ($search) {
            $leave_requests = $leave_requests->where(function ($query) use ($search) {
                $query->where('reason', 'like', '%' . $search . '%')
                      ->orWhere('id', 'like', '%' . $search . '%');
            });
        }
    
        $total = $leave_requests->count();
    
        $leave_requests = $leave_requests->orderBy($sort, $order)
            ->paginate(request("limit"))
            ->through(function ($leave_request) {
                $statusBadges = [
                    'allocate' => '<span class="badge bg-warning">' . get_label('allocate', 'Allocate') . '</span>',
                    'approved' => '<span class="badge bg-success">' . get_label('approved', 'Approved') . '</span>',
                    'rejected' => '<span class="badge bg-danger">' . get_label('rejected', 'Rejected') . '</span>',
                ];
                $statusBadge = $statusBadges[$leave_request->status] ?? '';
                return [
                    'id' => $leave_request->id,
                    'skills' => $leave_request->skills,
                    'created_at' => format_date($leave_request->created_at, true),
                    'updated_at' => format_date($leave_request->updated_at, true),
                    'status' => $leave_request->status,
                ];
            });
    
        return response()->json([
            "rows" => $leave_requests->items(),
            "total" => $total,
        ]);
    }
    public function laborPossition()
    {
        $labors = Labor::all();
        return view('labors.laborPossition', compact('labors'));
    }
    public function laborPossitionList()
    {
        $search = request('search');
        $sort = request('sort') ?: "id";
        $order = request('order') ?: "DESC";
        $status = request('status') ?: "";
        // Start the query without any joins
        $leave_requests = LaborType::select('labor_types.*'); // Adjust to include necessary fields

    
        if ($status != '') {
            $leave_requests = $leave_requests->where('status', $status);
        }

        if ($search) {
            $leave_requests = $leave_requests->where(function ($query) use ($search) {
                $query->where('reason', 'like', '%' . $search . '%')
                      ->orWhere('id', 'like', '%' . $search . '%');
            });
        }
        $total = $leave_requests->count();
        $leave_requests = $leave_requests->orderBy($sort, $order)
            ->paginate(request("limit"))
            ->through(function ($leave_request) {
                $statusBadges = [
                    'allocate' => '<span class="badge bg-warning">' . get_label('allocate', 'Allocate') . '</span>',
                    'approved' => '<span class="badge bg-success">' . get_label('approved', 'Approved') . '</span>',
                    'rejected' => '<span class="badge bg-danger">' . get_label('rejected', 'Rejected') . '</span>',
                ];
                $statusBadge = $statusBadges[$leave_request->status] ?? '';
                return [
                    'id' => $leave_request->id,
                    'labor_type_name' => $leave_request->labor_type_name,
                    'hourly_rate' =>$leave_request->hourly_rate,
                    'created_at' => format_date($leave_request->created_at, true),
                    'updated_at' => format_date($leave_request->updated_at, true),
                    'status' => $leave_request->status,
                ];
            });
    
        return response()->json([
            "rows" => $leave_requests->items(),
            "total" => $total,
        ]);
    }
    public function create()
    {
        // Show the form to create a new material
        return view('materials.create');
    }

    public function store(Request $request)
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

        // Create a new material
        $material = Material::create($validatedData);

        // Redirect to the index page with success message
        $materials = Material::all();
       return view('materials.index', compact('materials'));
        //return redirect()->route('materials.index', compact('materials'));
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