<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\EstimatesInvoice;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Services\DeletionService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;




class EstimatesInvoicesController extends Controller
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
        $estimates_invoices = isAdminOrHasAllDataAccess() ? $this->workspace->estimates_invoices() : $this->user->estimates_invoices();
        $estimates_invoices = $estimates_invoices->count();
        $users = $this->workspace->users;
        $clients = $this->workspace->clients;
        return view('estimates-invoices.list', ['estimates_invoices' => $estimates_invoices, 'users' => $users, 'clients' => $clients]);
    }

    public function create(Request $request)
    {
        $clients = $this->workspace->clients;
        $items = $this->workspace->items;
        $units = $this->workspace->units;
        $taxes = $this->workspace->taxes;
        return view('estimates-invoices.create', ['clients' => $clients, 'items' => $items, 'units' => $units, 'taxes' => $taxes]);
    }

    public function store(Request $request)
    {
        $rules = [
            'type' => 'required|in:estimate,invoice',
            'client_id' => 'required',
            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'zip_code' => 'required',
            'phone' => 'required',
            'note' => 'nullable',
            'from_date' => ['required', 'before_or_equal:to_date'],
            'to_date' => 'required',
            'personal_note' => 'nullable',
            'total' => ['required', 'regex:/^\d+(\.\d+)?$/'],
            'tax_amount' => ['nullable', 'regex:/^\d+(\.\d+)?$/'],
            'final_total' => ['required', 'regex:/^\d+(\.\d+)?$/'],
        ];

        // Handle 'status' field before validation
        $status = $request->input('status', ''); // Get status from request, default to empty string if not present
        if ($status == '') {
            // If status is empty, set it to 'na'
            $status = 'not_specified';
        }

        // Add conditional validation for the 'status' field based on the 'type'
        if ($request->type === 'invoice') {
            // If the type is 'invoice', restrict status to specific values
            $rules['status'] = 'in:not_specified,partially_paid,fully_paid,draft,cancelled,due';
        } elseif ($request->type === 'estimate') {
            // If the type is 'estimate', restrict status to specific values
            $rules['status'] = 'in:not_specified,sent,accepted,draft,declined,expired';
        }

        // Assign modified 'status' back to request
        $request->merge(['status' => $status]);

        $formFields = $request->validate($rules);

        // Define custom attribute names
        $customAttributes = [
            'rate.*' => 'rate',
            'amount.*' => 'amount',
        ];

        // Validate 'rate' and 'amount' fields with custom attribute names
        $validator = Validator::make($request->all(), [
            'quantity.*' => 'required|numeric',
            'unit.*' => 'nullable',
            'rate.*' => ['required', 'regex:/^\d+(\.\d+)?$/'],
            'tax.*' => 'nullable',
            'amount.*' => ['required', 'regex:/^\d+(\.\d+)?$/'],
        ], [], $customAttributes);


        $item_ids = $request->input('item_ids') ?? [];
        if (!empty($item_ids)) {
            $type = $request->input('type');
            $from_date = $request->input('from_date');
            $to_date = $request->input('to_date');
            $formFields['from_date'] = format_date($from_date, false, app('php_date_format'), 'Y-m-d');
            $formFields['to_date'] = format_date($to_date, false, app('php_date_format'), 'Y-m-d');

            $formFields['workspace_id'] = $this->workspace->id;
            $formFields['created_by'] = isClient() ? 'c_' . $this->user->id : 'u_' . $this->user->id;
            if ($res = EstimatesInvoice::create($formFields)) {
                foreach ($request->input('item') as $key => $item_id) {
                    $quantity = $request->input('quantity')[$key];
                    $unit_id = $request->input('unit')[$key];
                    $rate = $request->input('rate')[$key];
                    $tax_id = $request->input('tax')[$key];
                    $amount = $request->input('amount')[$key];

                    // Attach the item with its respective values
                    $res->items()->attach($item_id, ['qty' => $quantity, 'unit_id' => $unit_id, 'rate' => $rate, 'tax_id' => $tax_id, 'amount' => $amount]);
                }
                Session::flash('message', ucfirst($type) . ' created successfully.');
                return response()->json(['error' => false, 'id' => $res->id, 'type' => $type]);
            } else {
                return response()->json(['error' => true, 'message' => ucfirst($type) . ' couldn\'t created.']);
            }
        } else {
            return response()->json(['error' => true, 'message' => 'Please add at least one item.']);
        }
    }

    public function list()
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $status = (request('status')) ? request('status') : "";
        $type = (request('type')) ? request('type') : "";
        $client_id = (request('client_id')) ? request('client_id') : "";
        $start_date_from = (request('start_date_from')) ? request('start_date_from') : "";
        $start_date_to = (request('start_date_to')) ? request('start_date_to') : "";
        $end_date_from = (request('end_date_from')) ? request('end_date_from') : "";
        $end_date_to = (request('end_date_to')) ? request('end_date_to') : "";
        $where = ['estimates_invoices.workspace_id' => $this->workspace->id];

        if ($status != '') {
            $where['estimates_invoices.status'] = $status;
        }

        if ($type != '') {
            $where['estimates_invoices.type'] = $type;
        }

        $estimates_invoices = EstimatesInvoice::select(
            'estimates_invoices.*',
            DB::raw('CONCAT(clients.first_name, " ", clients.last_name) AS client_name')
        )
            ->leftJoin('clients', 'estimates_invoices.client_id', '=', 'clients.id');


        if (!isAdminOrHasAllDataAccess()) {
            $estimates_invoices = $estimates_invoices->where(function ($query) {
                $query->where('estimates_invoices.created_by', isClient() ? 'c_' . $this->user->id : 'u_' . $this->user->id)
                    ->orWhere('estimates_invoices.client_id', $this->user->id);
            });
        }

        if ($client_id) {
            $where['client_id'] = $client_id;
        }

        if ($start_date_from && $start_date_to) {
            $estimates_invoices = $estimates_invoices->whereBetween('estimates_invoices.from_date', [$start_date_from, $start_date_to]);
        }
        if ($end_date_from && $end_date_to) {
            $estimates_invoices  = $estimates_invoices->whereBetween('estimates_invoices.to_date', [$end_date_from, $end_date_to]);
        }
        if ($search) {
            $estimates_invoices = $estimates_invoices->where(function ($query) use ($search) {
                $query->where('estimates_invoices.id', 'like', '%' . $search . '%')
                    ->orWhere('estimates_invoices.name', 'like', '%' . $search . '%');
            });
        }

        $estimates_invoices->where($where);
        $total = $estimates_invoices->count();

        $estimates_invoices = $estimates_invoices->orderBy($sort, $order)
            ->paginate(request("limit"))
            ->through(function ($estimates_invoice) {
                $creator = User::find(substr($estimates_invoice->created_by, 2)); // Remove the 'u_' prefix
                if ($creator !== null) {
                    $creator = $creator->first_name . ' ' . $creator->last_name;
                } else {
                    $creator = '-';
                }

                $statusBadge = '';

                if ($estimates_invoice->status == 'sent') {
                    $statusBadge = '<span class="badge bg-primary">' . get_label('sent', 'Sent') . '</span>';
                } elseif ($estimates_invoice->status == 'accepted') {
                    $statusBadge = '<span class="badge bg-success">' . get_label('accepted', 'Accepted') . '</span>';
                } elseif ($estimates_invoice->status == 'partially_paid') {
                    $statusBadge = '<span class="badge bg-warning">' . get_label('partially_paid', 'Partially paid') . '</span>';
                } elseif ($estimates_invoice->status == 'fully_paid') {
                    $statusBadge = '<span class="badge bg-success">' . get_label('fully_paid', 'Fully paid') . '</span>';
                } elseif ($estimates_invoice->status == 'draft') {
                    $statusBadge = '<span class="badge bg-secondary">' . get_label('draft', 'Draft') . '</span>';
                } elseif ($estimates_invoice->status == 'declined') {
                    $statusBadge = '<span class="badge bg-danger">' . get_label('declined', 'Declined') . '</span>';
                } elseif ($estimates_invoice->status == 'expired') {
                    $statusBadge = '<span class="badge bg-warning">' . get_label('expired', 'Expired') . '</span>';
                } elseif ($estimates_invoice->status == 'not_specified') {
                    $statusBadge = '<span class="badge bg-secondary">' . get_label('not_specified', 'Not specified') . '</span>';
                } elseif ($estimates_invoice->status == 'due') {
                    $statusBadge = '<span class="badge bg-danger">' . get_label('due', 'Due') . '</span>';
                }

                return [
                    'id' => $estimates_invoice->id,
                    'type' => ucfirst($estimates_invoice->type),
                    'client' => $estimates_invoice->client_name,
                    'total' => format_currency($estimates_invoice->total),
                    'tax_amount' => format_currency($estimates_invoice->tax_amount),
                    'final_total' => format_currency($estimates_invoice->final_total),
                    'from_date' => format_date($estimates_invoice->from_date),
                    'to_date' => format_date($estimates_invoice->to_date),
                    'status' => $statusBadge,
                    'created_by' => $creator,
                    'created_at' => format_date($estimates_invoice->created_at, true),
                    'updated_at' => format_date($estimates_invoice->updated_at, true),
                ];
            });


        return response()->json([
            "rows" => $estimates_invoices->items(),
            "total" => $total,
        ]);
    }

    public function edit(Request $request, $id)
    {
        $estimate_invoice = EstimatesInvoice::findOrFail($id);
        $clients = $this->workspace->clients;
        $items = $this->workspace->items;
        $units = $this->workspace->units;
        $taxes = $this->workspace->taxes;
        return view('estimates-invoices.update', ['estimate_invoice' => $estimate_invoice, 'clients' => $clients, 'items' => $items, 'units' => $units, 'taxes' => $taxes]);
    }

    public function update(Request $request)
    {
        $rules = [
            'id' => 'required|exists:estimates_invoices,id',
            'type' => 'required|in:estimate,invoice',
            'client_id' => 'required',
            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'zip_code' => 'required',
            'phone' => 'required',
            'note' => 'nullable',
            'from_date' => ['required', 'before_or_equal:to_date'],
            'to_date' => 'required',
            'personal_note' => 'nullable',
            'total' => ['required', 'regex:/^\d+(\.\d+)?$/'],
            'tax_amount' => ['nullable', 'regex:/^\d+(\.\d+)?$/'],
            'final_total' => ['required', 'regex:/^\d+(\.\d+)?$/'],
        ];

        // Handle 'status' field before validation
        $status = $request->input('status', ''); // Get status from request, default to empty string if not present
        if ($status == '') {
            // If status is empty, set it to 'na'
            $status = 'not_specified';
        }

        // Add conditional validation for the 'status' field based on the 'type'
        if ($request->type === 'invoice') {
            // If the type is 'invoice', restrict status to specific values
            $rules['status'] = 'in:not_specified,partially_paid,fully_paid,draft,cancelled,due';
        } elseif ($request->type === 'estimate') {
            // If the type is 'estimate', restrict status to specific values
            $rules['status'] = 'in:not_specified,sent,accepted,draft,declined,expired';
        }

        // Assign modified 'status' back to request
        $request->merge(['status' => $status]);

        $formFields = $request->validate($rules);

        // Define custom attribute names
        $customAttributes = [
            'rate.*' => 'rate',
            'amount.*' => 'amount',
        ];

        // Validate 'rate' and 'amount' fields with custom attribute names
        $validator = Validator::make($request->all(), [
            'quantity.*' => 'required|numeric',
            'unit.*' => 'nullable',
            'rate.*' => ['required', 'regex:/^\d+(\.\d+)?$/'],
            'tax.*' => 'nullable',
            'amount.*' => ['required', 'regex:/^\d+(\.\d+)?$/'],
        ], [], $customAttributes);

        // Check if validation fails
        if ($validator->fails()) {
            // Return validation error response
            return response()->json(['error' => true, 'message' => $validator->errors()->first()]);
        }


        $estimate_invoice = EstimatesInvoice::findOrFail($request->input('id'));
        $itemPivotData = [];
        $items = $request->input('item') ?? [];
        if (!empty($items)) {
            $type = $request->input('type');
            $from_date = $request->input('from_date');
            $to_date = $request->input('to_date');
            
            $formFields['from_date'] = format_date($from_date, false, app('php_date_format'), 'Y-m-d');
            $formFields['to_date'] = format_date($to_date, false, app('php_date_format'), 'Y-m-d');

            if ($estimate_invoice->update($formFields)) {
                for ($i = 0; $i < count($items); $i++) {
                    $itemPivotData[$items[$i]] = [
                        'qty' => $request->input('quantity')[$i],
                        'unit_id' => $request->input('unit')[$i],
                        'rate' => $request->input('rate')[$i],
                        'tax_id' => $request->input('tax')[$i],
                        'amount' => $request->input('amount')[$i],
                    ];
                }
                $estimate_invoice->items()->sync($itemPivotData);

                Session::flash('message', ucfirst($type) . ' updated successfully.');
                return response()->json(['error' => false, 'id' => $request->input('id'), 'type' => $type]);
            } else {
                return response()->json(['error' => true, 'message' => ucfirst($type) . ' couldn\'t updated.']);
            }
        } else {
            return response()->json(['error' => true, 'message' => 'Please add at least one item.']);
        }
    }

    public function view(Request $request, $id)
    {
        $estimate_invoice = EstimatesInvoice::findOrFail($id);


        // The ID corresponds to a user
        $creator = User::find(substr($estimate_invoice->created_by, 2)); // Remove the 'u_' prefix
        if ($creator !== null) {
            $estimate_invoice->creator = $creator->first_name . ' ' . $creator->last_name;
        } else {
            $estimate_invoice->creator = ' -';
        }
        $estimate_invoice->from_date = $estimate_invoice->from_date !== null ? format_date($estimate_invoice->from_date) : '';
        $estimate_invoice->to_date = $estimate_invoice->to_date !== null ? format_date($estimate_invoice->to_date) : '';

        $statusBadge = '';

        if ($estimate_invoice->status == 'sent') {
            $statusBadge = '<span class="badge bg-primary">' . get_label('sent', 'Sent') . '</span>';
        } elseif ($estimate_invoice->status == 'accepted') {
            $statusBadge = '<span class="badge bg-success">' . get_label('accepted', 'Accepted') . '</span>';
        } elseif ($estimate_invoice->status == 'partially_paid') {
            $statusBadge = '<span class="badge bg-warning">' . get_label('partially_paid', 'Partially paid') . '</span>';
        } elseif ($estimate_invoice->status == 'fully_paid') {
            $statusBadge = '<span class="badge bg-success">' . get_label('fully_paid', 'Fully paid') . '</span>';
        } elseif ($estimate_invoice->status == 'draft') {
            $statusBadge = '<span class="badge bg-secondary">' . get_label('draft', 'Draft') . '</span>';
        } elseif ($estimate_invoice->status == 'declined') {
            $statusBadge = '<span class="badge bg-danger">' . get_label('declined', 'Declined') . '</span>';
        } elseif ($estimate_invoice->status == 'expired') {
            $statusBadge = '<span class="badge bg-warning">' . get_label('expired', 'Expired') . '</span>';
        } elseif ($estimate_invoice->status == 'not_specified') {
            $statusBadge = '<span class="badge bg-secondary">' . get_label('not_specified', 'Not specified') . '</span>';
        } elseif ($estimate_invoice->status == 'due') {
            $statusBadge = '<span class="badge bg-danger">' . get_label('due', 'Due') . '</span>';
        }



        $estimate_invoice->status = $statusBadge;
        return view('estimates-invoices.view', compact('estimate_invoice'));
    }

    public function pdf(Request $request, $id)
    {
        $estimate_invoice = EstimatesInvoice::findOrFail($id);
        $general_settings = get_settings('general_settings');

        $logo = !isset($general_settings['full_logo']) || empty($general_settings['full_logo']) ? 'storage/logos/default_full_logo.png' : 'storage/' . $general_settings['full_logo'];
        $company_title = $general_settings['company_title'] ?? 'Taskify';
        $client = new Party([
            'name'          => $estimate_invoice->name,
            'address'          => $estimate_invoice->address,
            'city_state_country_zip'         => $estimate_invoice->city . ',' .  $estimate_invoice->state . ',' . $estimate_invoice->country . ',' . $estimate_invoice->zip_code,
            'phone'         => $estimate_invoice->phone,
        ]);

        $customer = new Party([
            'name'          => 'Ashley Medina',
            'address'       => 'The Green Street 12',
            'code'          => '#22663214',
            'custom_fields' => [
                'order number' => '> 654321 <',
            ],
        ]);

        $items = [
            InvoiceItem::make('Service 1')
                ->description('Your product or service description')
                ->pricePerUnit(47.79)
                ->quantity(2)
                ->discount(10),
            InvoiceItem::make('Service 2')->pricePerUnit(71.96)->quantity(2),
            InvoiceItem::make('Service 3')->pricePerUnit(4.56),
            InvoiceItem::make('Service 4')->pricePerUnit(87.51)->quantity(7)->discount(4)->units('kg'),
            InvoiceItem::make('Service 5')->pricePerUnit(71.09)->quantity(7)->discountByPercent(9),
            InvoiceItem::make('Service 6')->pricePerUnit(76.32)->quantity(9),
            InvoiceItem::make('Service 7')->pricePerUnit(58.18)->quantity(3)->discount(3),
            InvoiceItem::make('Service 8')->pricePerUnit(42.99)->quantity(4)->discountByPercent(3),
            InvoiceItem::make('Service 9')->pricePerUnit(33.24)->quantity(6)->units('m2'),
            InvoiceItem::make('Service 11')->pricePerUnit(97.45)->quantity(2),
            InvoiceItem::make('Service 12')->pricePerUnit(92.82),
            InvoiceItem::make('Service 13')->pricePerUnit(12.98),
            InvoiceItem::make('Service 14')->pricePerUnit(160)->units('hours'),
            InvoiceItem::make('Service 15')->pricePerUnit(62.21)->discountByPercent(5),
            InvoiceItem::make('Service 16')->pricePerUnit(2.80),
            InvoiceItem::make('Service 17')->pricePerUnit(56.21),
            InvoiceItem::make('Service 18')->pricePerUnit(66.81)->discountByPercent(8),
            InvoiceItem::make('Service 19')->pricePerUnit(76.37),
            InvoiceItem::make('Service 20')->pricePerUnit(55.80),
        ];

        $notes = [
            'your multiline',
            'additional notes',
            'in regards of delivery or something else',
        ];
        $notes = implode("<br>", $notes);


        $invoice = Invoice::make(($estimate_invoice->type == 'estimate' ? get_label('estimate_id_prefix', 'ESTMT-') : get_label('invoice_id_prefix', 'INVC-')) . $estimate_invoice->id . ' - ' . $company_title)
            ->series('BIG')
            ->status(get_label($estimate_invoice->status, ucfirst(str_replace('_', ' ', $estimate_invoice->status))))
            ->sequence(667)
            ->serialNumberFormat('{SEQUENCE}/{SERIES}')
            ->seller($client)
            ->buyer($customer)
            ->date(now()->subWeeks(3))
            ->dateFormat('m/d/Y')
            ->payUntilDays(14)
            ->currencySymbol('$')
            ->currencyCode('USD')
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator('.')
            ->currencyDecimalPoint(',')
            ->filename(($estimate_invoice->type == 'estimate' ? get_label('estimate_id_prefix', 'ESTMT-') : get_label('invoice_id_prefix', 'INVC-')) . $estimate_invoice->id . ' - ' . $company_title)
            ->addItems($items)
            ->notes($notes)
            ->logo($logo)
            // You can additionally save generated invoice to configured disk
            ->setCustomData(['estimate_invoice' => $estimate_invoice])
            ->save('public');

        $link = $invoice->url();
        // Then send email to party with link

        // And return invoice itself to browser or have a different view
        return $invoice->stream();
    }


    public function destroy($id)
    {
        $estimate_invoice = EstimatesInvoice::findOrFail($id);
        $type = ucfirst($estimate_invoice->type);
        $estimate_invoice->items()->detach();
        DeletionService::delete(EstimatesInvoice::class, $id, $type);
        return response()->json(['error' => false, 'message' => $type . ' deleted successfully.', 'id' => $id, 'title' => $estimate_invoice->type == 'estimate' ? get_label('estimate_id_prefix', 'ESTMT-') . $id : get_label('invoice_id_prefix', 'INVC-') . $id, 'type' => $estimate_invoice->type]);
    }

    public function destroy_multiple(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'ids' => 'required|array', // Ensure 'ids' is present and an array
            'ids.*' => 'integer|exists:estimates_invoices,id' // Ensure each ID in 'ids' is an integer and exists in the table
        ]);

        $ids = $validatedData['ids'];
        $deletedIds = [];
        $deletedTitles = [];

        // Perform deletion using validated IDs
        foreach ($ids as $id) {
            $res = EstimatesInvoice::findOrFail($id);
            if ($res) {
                $deletedIds[] = $id;
                $deletedTitles[] = ($res->type == 'estimate' ? get_label('estimate_id_prefix', 'ESTMT-') : get_label('invoice_id_prefix', 'INVC-')) . $id;
                $res->items()->detach();
                DeletionService::delete(EstimatesInvoice::class, $id, ucfirst($res->type));
            }
        }
        return response()->json(['error' => false, 'message' => 'Records deleted successfully.', 'id' => $deletedIds, 'titles' => $deletedTitles, 'type' => $res->type]);
    }

    public function duplicate($id)
    {
        $relatedTables = ['items']; // Include related tables as needed
        $res = EstimatesInvoice::findOrFail($id);
        // Use the general duplicateRecord function
        $duplicate = duplicateRecord(EstimatesInvoice::class, $id, $relatedTables);

        if (!$duplicate) {
            return response()->json(['error' => true, 'message' => ucfirst($res->type) . ' duplication failed.']);
        }
        if (request()->has('reload') && request()->input('reload') === 'true') {
            Session::flash('message', ucfirst($res->type) . ' duplicated successfully.');
        }
        return response()->json(['error' => false, 'message' => ucfirst($res->type) . ' duplicated successfully.', 'id' => $id, 'type' => $res->type]);
    }
}
