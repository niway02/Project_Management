<?php

namespace App\Http\Controllers;

use App\Models\Allowance;
use App\Models\Workspace;

use Illuminate\Http\Request;
use App\Services\DeletionService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class AllowancesController extends Controller
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
        $allowances = $this->workspace->allowances();
        $allowances = $allowances->count();
        return view('allowances.list', ['allowances' => $allowances]);
    }
    public function store(Request $request)
    {
        // Validate the request data
        $formFields = $request->validate([
            'title' => 'required|unique:allowances,title', // Validate the title
            'amount' => ['required', 'regex:/^\d+(\.\d+)?$/'],
        ]);
        $formFields['workspace_id'] = $this->workspace->id;

        if ($allowance = Allowance::create($formFields)) {
            Session::flash('message', 'Allowance created successfully.');
            return response()->json(['error' => false, 'message' => 'Allowance created successfully.', 'id' => $allowance->id]);
        } else {
            return response()->json(['error' => true, 'message' => 'Allowance couldn\'t created.']);
        }
    }

    public function list()
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $allowances = $this->workspace->allowances();
        if ($search) {
            $allowances = $allowances->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('amount', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
            });
        }
        $total = $allowances->count();
        $allowances = $allowances->orderBy($sort, $order)
            ->paginate(request("limit"))
            ->through(
                fn ($allowance) => [
                    'id' => $allowance->id,
                    'title' => $allowance->title,
                    'amount' => format_currency($allowance->amount),
                    'created_at' => format_date($allowance->created_at, true),
                    'updated_at' => format_date($allowance->updated_at, 'H:i:s')
                ]
            );

        return response()->json([
            "rows" => $allowances->items(),
            "total" => $total,
        ]);
    }

    public function get($id)
    {
        $allowance = Allowance::findOrFail($id);
        return response()->json(['allowance' => $allowance]);
    }

    public function update(Request $request)
    {
        $formFields = $request->validate([
            'id' => 'required',
            'title' => 'required|unique:allowances,title,' . $request->id,
            'amount' => ['required', 'regex:/^\d+(\.\d+)?$/'],
        ]);
        $allowance = Allowance::findOrFail($request->id);

        if ($allowance->update($formFields)) {
            return response()->json(['error' => false, 'message' => 'Allowance updated successfully.', 'id' => $allowance->id]);
        } else {
            return response()->json(['error' => true, 'message' => 'Allowance couldn\'t updated.']);
        }
    }

    public function destroy($id)
    {
        $allowance = Allowance::findOrFail($id);
        $allowance->payslips()->detach();
        $response = DeletionService::delete(Allowance::class, $id, 'Allowance');
        return $response;
    }

    public function destroy_multiple(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'ids' => 'required|array', // Ensure 'ids' is present and an array
            'ids.*' => 'integer|exists:allowances,id' // Ensure each ID in 'ids' is an integer and exists in the table
        ]);

        $ids = $validatedData['ids'];
        $deletedIds = [];
        $deletedTitles = [];
        // Perform deletion using validated IDs
        foreach ($ids as $id) {
            $allowance = Allowance::findOrFail($id);
            $deletedIds[] = $id;
            $deletedTitles[] = $allowance->title;
            $allowance->payslips()->detach();
            DeletionService::delete(Allowance::class, $id, 'Allowance');
        }

        return response()->json(['error' => false, 'message' => 'Allowance(s) deleted successfully.', 'id' => $deletedIds, 'titles' => $deletedTitles]);
    }
}
