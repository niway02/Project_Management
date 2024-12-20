<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Tax;
use Illuminate\Support\Facades\Session;
use App\Services\DeletionService;
use Illuminate\Support\Facades\DB;

class TaxesController extends Controller
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
        $taxes = $this->workspace->taxes();
        $taxes = $taxes->count();
        return view('taxes.list', ['taxes' => $taxes]);
    }

    public function store(Request $request)
    {
        // Validate the request data
        $formFields = $request->validate([
            'title' => 'required|unique:taxes,title',
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

        if ($res = Tax::create($formFields)) {
            Session::flash('message', 'Tax created successfully.');
            return response()->json(['error' => false, 'message' => 'Tax created successfully.', 'id' => $res->id]);
        } else {
            return response()->json(['error' => true, 'message' => 'Tax couldn\'t created.']);
        }
    }

    public function list()
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $taxes = $this->workspace->taxes();
        if ($search) {
            $taxes = $taxes->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('amount', 'like', '%' . $search . '%')
                    ->orWhere('percentage', 'like', '%' . $search . '%')
                    ->orWhere('type', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
            });
        }
        $total = $taxes->count();
        $taxes = $taxes->orderBy($sort, $order)
            ->paginate(request("limit"))
            ->through(
                fn ($tax) => [
                    'id' => $tax->id,
                    'title' => $tax->title,
                    'type' => ucfirst($tax->type),
                    'percentage' => $tax->percentage,
                    'amount' => format_currency($tax->amount),
                    'created_at' => format_date($tax->created_at, true),
                    'updated_at' => format_date($tax->updated_at, true),
                ]
            );

        return response()->json([
            "rows" => $taxes->items(),
            "total" => $total,
        ]);
    }



    public function get($id)
    {
        $tax = Tax::findOrFail($id);
        return response()->json(['tax' => $tax]);
    }

    public function update(Request $request)
    {
        // Validate the request data
        $formFields = $request->validate([
            'title' => 'required|unique:taxes,title,' . $request->id,
            // 'type' => [
            //     'required',
            //     Rule::in(['amount', 'percentage']),
            // ],
        ]);

        $formFields['workspace_id'] = $this->workspace->id;

        // if ($request->type === 'amount') {
        //     $validatedAmount = $request->validate([
        //         'amount' => ['nullable', 'regex:/^\d+(\.\d+)?$/'],
        //     ]);
        //     $formFields['amount'] = $validatedAmount['amount'];
        //     $formFields['percentage'] = null;
        // } elseif ($request->type === 'percentage') {
        //     $validatedPercentage = $request->validate([
        //         'percentage' => 'required|numeric',
        //     ]);
        //     $formFields['percentage'] = $validatedPercentage['percentage'];
        //     $formFields['amount'] = null;
        // }

        $tax = Tax::findOrFail($request->id);

        if ($tax->update($formFields)) {
            return response()->json(['error' => false, 'message' => 'Tax updated successfully.', 'id' => $tax->id]);
        } else {
            return response()->json(['error' => true, 'message' => 'Tax couldn\'t updated.']);
        }
    }

    public function destroy($id)
    {
        $tax = Tax::findOrFail($id);
        DB::table('estimates_invoice_item')
            ->where('tax_id', $tax->id)
            ->update(['tax_id' => null]);
        $response = DeletionService::delete(Tax::class, $id, 'Tax');
        return $response;
    }
    public function destroy_multiple(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'ids' => 'required|array', // Ensure 'ids' is present and an array
            'ids.*' => 'integer|exists:taxes,id' // Ensure each ID in 'ids' is an integer and exists in the table
        ]);

        $ids = $validatedData['ids'];
        $deletedIds = [];
        $deletedTitles = [];
        // Perform deletion using validated IDs
        foreach ($ids as $id) {
            $tax = Tax::findOrFail($id);
            DB::table('estimates_invoice_item')
            ->where('tax_id', $tax->id)
            ->update(['tax_id' => null]);
            $deletedIds[] = $id;
            $deletedTitles[] = $tax->title;
            DeletionService::delete(Tax::class, $id, 'Tax');
        }

        return response()->json(['error' => false, 'message' => 'Tax(es) deleted successfully.', 'id' => $deletedIds, 'titles' => $deletedTitles]);
    }
}
