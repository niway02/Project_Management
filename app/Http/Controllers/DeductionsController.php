<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Deduction;
use Illuminate\Support\Facades\Session;
use App\Services\DeletionService;

class DeductionsController extends Controller
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
        $deductions = $this->workspace->deductions();
        $deductions = $deductions->count();
        return view('deductions.list', ['deductions' => $deductions]);
    }

    public function store(Request $request)
    {
        // Validate the request data
        $formFields = $request->validate([
            'title' => 'required|unique:deductions,title',
            'type' => [
                'required',
                Rule::in(['amount', 'percentage']),
            ],
        ]);

        $formFields['workspace_id'] = $this->workspace->id;

        if ($request->type === 'amount') {
            $validatedAmount = $request->validate([
                'amount' => ['required', 'regex:/^\d+(\.\d+)?$/'],
            ]);
            $formFields['amount'] = $validatedAmount['amount'];
        } elseif ($request->type === 'percentage') {
            $validatedPercentage = $request->validate([
                'percentage' => 'required|numeric',
            ]);
            $formFields['percentage'] = $validatedPercentage['percentage'];
        }

        if ($deduction = Deduction::create($formFields)) {
            Session::flash('message', 'Deduction created successfully.');
            return response()->json(['error' => false, 'message' => 'Deduction created successfully.', 'id' => $deduction->id]);
        } else {
            return response()->json(['error' => true, 'message' => 'Deduction couldn\'t created.']);
        }
    }

    public function list()
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $deductions = $this->workspace->deductions();
        if ($search) {
            $deductions = $deductions->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('amount', 'like', '%' . $search . '%')
                    ->orWhere('percentage', 'like', '%' . $search . '%')
                    ->orWhere('type', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
            });
        }
        $total = $deductions->count();
        $deductions = $deductions->orderBy($sort, $order)
            ->paginate(request("limit"))
            ->through(
                fn ($deduction) => [
                    'id' => $deduction->id,
                    'title' => $deduction->title,
                    'type' => ucfirst($deduction->type),
                    'percentage' => $deduction->percentage,
                    'amount' => format_currency($deduction->amount),
                    'created_at' => format_date($deduction->created_at, true),
                    'updated_at' => format_date($deduction->updated_at, true),
                ]
            );

        return response()->json([
            "rows" => $deductions->items(),
            "total" => $total,
        ]);
    }



    public function get($id)
    {
        $deduction = Deduction::findOrFail($id);
        return response()->json(['deduction' => $deduction]);
    }

    public function update(Request $request)
    {
        // Validate the request data
        $formFields = $request->validate([
            'title' => 'required|unique:deductions,title,' . $request->id,
            'type' => [
                'required',
                Rule::in(['amount', 'percentage']),
            ],
        ]);

        $formFields['workspace_id'] = $this->workspace->id;

        if ($request->type === 'amount') {
            $validatedAmount = $request->validate([
                'amount' => ['required', 'regex:/^\d+(\.\d+)?$/'],
            ]);
            $formFields['amount'] = $validatedAmount['amount'];
            $formFields['percentage'] = null;
        } elseif ($request->type === 'percentage') {
            $validatedPercentage = $request->validate([
                'percentage' => 'required|numeric',
            ]);
            $formFields['percentage'] = $validatedPercentage['percentage'];
            $formFields['amount'] = null;
        }

        $deduction = Deduction::findOrFail($request->id);

        if ($deduction->update($formFields)) {
            return response()->json(['error' => false, 'message' => 'Deduction updated successfully.', 'id' => $deduction->id]);
        } else {
            return response()->json(['error' => true, 'message' => 'Deduction couldn\'t updated.']);
        }
    }

    public function destroy($id)
    {
        $deduction = Deduction::findOrFail($id);
        $deduction->payslips()->detach();
        $response = DeletionService::delete(Deduction::class, $id, 'Deduction');
        return $response;
    }
    public function destroy_multiple(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'ids' => 'required|array', // Ensure 'ids' is present and an array
            'ids.*' => 'integer|exists:deductions,id' // Ensure each ID in 'ids' is an integer and exists in the table
        ]);

        $ids = $validatedData['ids'];
        $deletedIds = [];
        $deletedTitles = [];
        // Perform deletion using validated IDs
        foreach ($ids as $id) {
            $deduction = Deduction::findOrFail($id);
            $deletedIds[] = $id;
            $deletedTitles[] = $deduction->title;
            $deduction->payslips()->detach();
            DeletionService::delete(Deduction::class, $id, 'Deduction');
        }

        return response()->json(['error' => false, 'message' => 'Deduction(s) deleted successfully.', 'id' => $deletedIds, 'titles' => $deletedTitles]);
    }
}
