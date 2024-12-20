<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Unit;
use Illuminate\Support\Facades\Session;
use App\Services\DeletionService;
use Illuminate\Support\Facades\DB;

class UnitsController extends Controller
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

    public function index(Request $request)
    {
        $units = $this->workspace->units();
        $units = $units->count();
        return view('units.list', ['units' => $units]);
    }

    public function store(Request $request)
    {
        // Validate the request data
        $formFields = $request->validate([
            'title' => 'required|unique:units,title',
            'description' => 'nullable',
        ]);

        $formFields['workspace_id'] = $this->workspace->id;

        if ($res = Unit::create($formFields)) {
            Session::flash('message', 'Unit created successfully.');
            return response()->json(['error' => false, 'message' => 'Unit created successfully.', 'id' => $res->id]);
        } else {
            return response()->json(['error' => true, 'message' => 'Unit couldn\'t created.']);
        }
    }

    public function list()
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $units = $this->workspace->units();
        if ($search) {
            $units = $units->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
            });
        }
        $total = $units->count();
        $units = $units->orderBy($sort, $order)
            ->paginate(request("limit"))
            ->through(
                fn ($unit) => [
                    'id' => $unit->id,
                    'title' => $unit->title,
                    'description' => $unit->description,
                    'created_at' => format_date($unit->created_at, true),
                    'updated_at' => format_date($unit->updated_at, true),
                ]
            );

        return response()->json([
            "rows" => $units->items(),
            "total" => $total,
        ]);
    }



    public function get($id)
    {
        $unit = Unit::findOrFail($id);
        return response()->json(['unit' => $unit]);
    }

    public function update(Request $request)
    {
        // Validate the request data
        $formFields = $request->validate([
            'title' => 'required|unique:units,title,' . $request->id,
            'description' => 'nullable',
        ]);

        $formFields['workspace_id'] = $this->workspace->id;

        $unit = Unit::findOrFail($request->id);

        if ($unit->update($formFields)) {
            return response()->json(['error' => false, 'message' => 'Unit updated successfully.', 'id' => $unit->id]);
        } else {
            return response()->json(['error' => true, 'message' => 'Unit couldn\'t updated.']);
        }
    }

    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        DB::table('estimates_invoice_item')
            ->where('unit_id', $unit->id)
            ->update(['unit_id' => null]);
        DB::table('items')
            ->where('unit_id', $unit->id)
            ->update(['unit_id' => null]);
        $response = DeletionService::delete(Unit::class, $id, 'Unit');
        return $response;
    }
    public function destroy_multiple(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'ids' => 'required|array', // Ensure 'ids' is present and an array
            'ids.*' => 'integer|exists:units,id' // Ensure each ID in 'ids' is an integer and exists in the table
        ]);

        $ids = $validatedData['ids'];
        $deletedIds = [];
        $deletedTitles = [];
        // Perform deletion using validated IDs
        foreach ($ids as $id) {
            $unit = Unit::findOrFail($id);
            DB::table('estimates_invoice_item')
                ->where('unit_id', $unit->id)
                ->update(['unit_id' => null]);
            DB::table('items')
                ->where('unit_id', $unit->id)
                ->update(['unit_id' => null]);
            $deletedIds[] = $id;
            $deletedTitles[] = $unit->title;
            DeletionService::delete(Unit::class, $id, 'Unit');
        }

        return response()->json(['error' => false, 'message' => 'Unit(s) deleted successfully.', 'id' => $deletedIds, 'titles' => $deletedTitles]);
    }
}
