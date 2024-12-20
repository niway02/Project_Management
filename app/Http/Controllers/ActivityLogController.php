<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Client;
use App\Models\ActivityLog;
use App\Models\Workspace;
use Illuminate\Http\Request;
use App\Services\DeletionService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
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
        $users = $this->workspace->users;
        $clients = $this->workspace->clients;
        $types = getControllerNames();
        return view('activity_log.list', ['users' => $users, 'clients' => $clients, 'types' => $types]);
    }

    public function list()
    {
        $search = request('search');
        $sort = request('sort', 'id');
        $order = request('order', 'DESC');
        $date_from = (request('date_from')) ? request('date_from') : "";
        $date_to = (request('date_to')) ? request('date_to') : "";
        $user_id = (request('user_id')) ? request('user_id') : "";
        $client_id = (request('client_id')) ? request('client_id') : "";
        $activity = (request('activity')) ? request('activity') : "";
        $type = (request('type')) ? request('type') : "";
        $type_id = (request('type_id')) ? request('type_id') : "";
        $where = ['activity_logs.workspace_id' => $this->workspace->id];
        $date_from = $date_from ? date('Y-m-d H:i:s', strtotime($date_from . ' 00:00:00')) : null;
        $date_to = $date_to ? date('Y-m-d H:i:s', strtotime($date_to . ' 23:59:59')) : null;
        $activity_log_query = ActivityLog::select(
            'activity_logs.*',
            DB::raw(
                '
            CASE 
                WHEN activity_logs.actor_type = "user" THEN CONCAT(users.first_name, " ", users.last_name)
                WHEN activity_logs.actor_type = "client" THEN CONCAT(clients.first_name, " ", clients.last_name)
            END AS actor_name'
            ),
            DB::raw(
                "
    CASE 
        WHEN activity_logs.type = 'allowance' THEN allowances.title
        WHEN activity_logs.type = 'user' THEN 
            (
                SELECT CONCAT(first_name, ' ', last_name) 
                FROM users 
                WHERE id = activity_logs.type_id
            )
        WHEN activity_logs.type = 'client' THEN 
            (
                SELECT CONCAT(first_name, ' ', last_name) 
                FROM clients 
                WHERE id = activity_logs.type_id
            )
        WHEN activity_logs.type = 'contract' THEN contracts.title
        WHEN activity_logs.type = 'contract_type' THEN contract_types.type
        WHEN activity_logs.type = 'deduction' THEN deductions.title
        WHEN activity_logs.type = 'note' THEN notes.title
        WHEN activity_logs.type = 'payment_method' THEN payment_methods.title
        WHEN activity_logs.type = 'project' THEN projects.title
        WHEN activity_logs.type = 'task' THEN tasks.title
        WHEN activity_logs.type = 'meeting' THEN meetings.title
        WHEN activity_logs.type = 'status' THEN statuses.title
        WHEN activity_logs.type = 'priority' THEN priorities.title
        WHEN activity_logs.type = 'tag' THEN tags.title
        WHEN activity_logs.type = 'todo' THEN todos.title
        WHEN activity_logs.type = 'workspace' THEN workspaces.title
        WHEN activity_logs.type = 'media' THEN media.file_name
        WHEN activity_logs.type = 'tax' THEN taxes.title
        WHEN activity_logs.type = 'unit' THEN units.title
        WHEN activity_logs.type = 'item' THEN items.title
        WHEN activity_logs.type = 'expense_type' THEN expense_types.title
        WHEN activity_logs.type = 'expense' THEN expenses.title
        WHEN activity_logs.type = 'milestone' THEN milestones.title
        ELSE '-'
    END AS type_title,
    CASE
    WHEN activity_logs.type = 'task' THEN 'Project'
    WHEN activity_logs.type = 'media' THEN activity_logs.parent_type
    WHEN activity_logs.type = 'milestone' THEN activity_logs.parent_type
    ELSE '-'
END AS parent_type,
CASE 
WHEN activity_logs.type = 'task' THEN 
    CASE 
        WHEN activity_logs.parent_type_id IS NOT NULL THEN 
            (SELECT title FROM projects WHERE id = activity_logs.parent_type_id)
        ELSE 
            (SELECT title FROM projects WHERE id = tasks.project_id)
    END

    WHEN activity_logs.type = 'milestone' THEN 
    (SELECT title FROM projects WHERE id = milestones.project_id)

    
WHEN activity_logs.type = 'media' THEN 
    CASE 
        WHEN activity_logs.parent_type = 'project' THEN 
            CASE 
                WHEN activity_logs.parent_type_id IS NOT NULL THEN 
                    (SELECT title FROM projects WHERE id = activity_logs.parent_type_id)
                ELSE 
                    (SELECT title FROM projects WHERE id = media.model_id)
            END
        WHEN activity_logs.parent_type = 'task' THEN 
            CASE 
                WHEN activity_logs.parent_type_id IS NOT NULL THEN 
                    (SELECT title FROM tasks WHERE id = activity_logs.parent_type_id)
                ELSE 
                    (SELECT title FROM tasks WHERE id = media.model_id)
            END 
        ELSE '-'
    END
ELSE '-'
END AS parent_type_title,


        CASE 
            WHEN activity_logs.type = 'task' THEN 
                CASE 
                    WHEN activity_logs.parent_type_id IS NOT NULL THEN 
                    activity_logs.parent_type_id
                    ELSE 
                    tasks.project_id
                END
                WHEN activity_logs.type = 'media' THEN 
                CASE 
                    WHEN activity_logs.parent_type_id IS NOT NULL THEN 
                        activity_logs.parent_type_id
                    ELSE 
                        media.model_id
                END
                WHEN activity_logs.type = 'milestone' THEN 
                CASE 
                    WHEN activity_logs.parent_type_id IS NOT NULL THEN 
                        activity_logs.parent_type_id
                    ELSE 
                        milestones.project_id
                END
            ELSE '-'
        END AS parent_type_id
    "
            )
        )->leftJoin('allowances', function ($join) {
            $join->on('activity_logs.type_id', '=', 'allowances.id')
                ->where('activity_logs.type', '=', 'allowance');
        })
            ->leftJoin('contract_types', function ($join) {
                $join->on('activity_logs.type_id', '=', 'contract_types.id')
                    ->where('activity_logs.type', '=', 'contract_type');
            })
            ->leftJoin('deductions', function ($join) {
                $join->on('activity_logs.type_id', '=', 'deductions.id')
                    ->where('activity_logs.type', '=', 'deduction');
            })
            ->leftJoin('notes', function ($join) {
                $join->on('activity_logs.type_id', '=', 'notes.id')
                    ->where('activity_logs.type', '=', 'note');
            })
            ->leftJoin('payment_methods', function ($join) {
                $join->on('activity_logs.type_id', '=', 'payment_methods.id')
                    ->where('activity_logs.type', '=', 'payment_method');
            })
            ->leftJoin('projects', function ($join) {
                $join->on('activity_logs.type_id', '=', 'projects.id')
                    ->where('activity_logs.type', '=', 'project');
            })
            ->leftJoin('tasks', function ($join) {
                $join->on('activity_logs.type_id', '=', 'tasks.id')
                    ->where('activity_logs.type', '=', 'task');
            })
            ->leftJoin('meetings', function ($join) {
                $join->on('activity_logs.type_id', '=', 'meetings.id')
                    ->where('activity_logs.type', '=', 'meeting');
            })
            ->leftJoin('statuses', function ($join) {
                $join->on('activity_logs.type_id', '=', 'statuses.id')
                    ->where('activity_logs.type', '=', 'status');
            })
            ->leftJoin('priorities', function ($join) {
                $join->on('activity_logs.type_id', '=', 'priorities.id')
                    ->where('activity_logs.type', '=', 'priority');
            })
            ->leftJoin('tags', function ($join) {
                $join->on('activity_logs.type_id', '=', 'tags.id')
                    ->where('activity_logs.type', '=', 'tag');
            })
            ->leftJoin('users', function ($join) {
                $join->on('activity_logs.actor_id', '=', 'users.id')
                    ->where('activity_logs.actor_type', '=', 'user');
            })
            ->leftJoin('clients', function ($join) {
                $join->on('activity_logs.actor_id', '=', 'clients.id')
                    ->where('activity_logs.actor_type', '=', 'client');
            })
            ->leftJoin('contracts', function ($join) {
                $join->on('activity_logs.type_id', '=', 'contracts.id')
                    ->where('activity_logs.type', '=', 'contract');
            })
            ->leftJoin('todos', function ($join) {
                $join->on('activity_logs.type_id', '=', 'todos.id')
                    ->where('activity_logs.type', '=', 'todo');
            })
            ->leftJoin('workspaces', function ($join) {
                $join->on('activity_logs.type_id', '=', 'workspaces.id')
                    ->where('activity_logs.type', '=', 'workspace');
            })
            ->leftJoin('media', function ($join) {
                $join->on('activity_logs.type_id', '=', 'media.id')
                    ->where('activity_logs.type', '=', 'media');
            })
            ->leftJoin('taxes', function ($join) {
                $join->on('activity_logs.type_id', '=', 'taxes.id')
                    ->where('activity_logs.type', '=', 'tax');
            })
            ->leftJoin('units', function ($join) {
                $join->on('activity_logs.type_id', '=', 'units.id')
                    ->where('activity_logs.type', '=', 'unit');
            })
            ->leftJoin('items', function ($join) {
                $join->on('activity_logs.type_id', '=', 'items.id')
                    ->where('activity_logs.type', '=', 'item');
            })
            ->leftJoin('expense_types', function ($join) {
                $join->on('activity_logs.type_id', '=', 'expense_types.id')
                    ->where('activity_logs.type', '=', 'expense_type');
            })
            ->leftJoin('milestones', function ($join) {
                $join->on('activity_logs.type_id', '=', 'milestones.id')
                    ->where('activity_logs.type', '=', 'milestone');
            })
            ->leftJoin('expenses', function ($join) {
                $join->on('activity_logs.type_id', '=', 'expenses.id')
                    ->where('activity_logs.type', '=', 'expense');
            });

        if (Auth::guard('client')->check()) {
            $where['activity_logs.actor_id'] = $this->user->id;
            $where['activity_logs.actor_type'] = 'client';
        } elseif (!isAdminOrHasAllDataAccess()) {
            $where['activity_logs.actor_id'] = $this->user->id;
            $where['activity_logs.actor_type'] = 'user';
        }

        if ($activity) {
            $where['activity_logs.activity'] = $activity;
        }
        if ($type) {
            $where['activity_logs.type'] = $type;
        }

        if ($user_id) {
            $where['activity_logs.actor_id'] = $user_id;
            $where['activity_logs.actor_type'] = 'user';
        }
        if ($client_id) {
            $where['activity_logs.actor_id'] = $client_id;
            $where['activity_logs.actor_type'] = 'client';
        }
        if ($type && $type_id) {
            $where['activity_logs.type'] = $type;
            $where['activity_logs.type_id'] = $type_id;
        }



        if ($date_from && $date_to) {
            $activity_log_query->whereBetween('activity_logs.created_at', [$date_from, $date_to]);
        }

        if ($search) {
            $activity_log_query->where(function ($query) use ($search) {
                $query->where('activity_logs.id', 'like', '%' . $search . '%')
                    ->orWhere('activity_logs.workspace_id', 'like', '%' . $search . '%')
                    ->orWhere('activity_logs.actor_id', 'like', '%' . $search . '%')
                    ->orWhere('activity_logs.actor_type', 'like', '%' . $search . '%')
                    ->orWhere('activity_logs.type_id', 'like', '%' . $search . '%')
                    ->orWhere('activity_logs.activity', 'like', '%' . $search . '%')
                    ->orWhere('activity_logs.message', 'like', '%' . $search . '%')
                    ->orWhere('activity_logs.type', 'like', '%' . $search . '%');
            });
        }

        $activity_log_query = $activity_log_query->where($where);
        $total = $activity_log_query->count();

        $activity_log = $activity_log_query->orderBy($sort, $order)
            ->paginate(request("limit"))
            ->through(function ($activity_log) {
                if ($activity_log->type == 'payslip') {
                    $activity_log->type_title = get_label("contract_id_prefix", "CTR-") . $activity_log->type_id;
                }
                if ($activity_log->type == 'estimate') {
                    $activity_log->type_title = get_label("estimate_id_prefix", "ESTMT-") . $activity_log->type_id;
                }
                if ($activity_log->type == 'invoice') {
                    $activity_log->type_title = get_label("invoice_id_prefix", "INVC-") . $activity_log->type_id;
                }
                if ($activity_log->type == 'payment') {
                    $activity_log->type_title = get_label("payment_id", "Payment ID") . ' ' . $activity_log->type_id;
                }
                return [
                    'id' => $activity_log->id,
                    'actor_id' => $activity_log->actor_id,
                    'actor_name' => $activity_log->actor_name,
                    'actor_type' => ucfirst($activity_log->actor_type),
                    'type_id' => $activity_log->type_id,
                    'parent_type_id' => $activity_log->parent_type_id,
                    'type' => ucfirst(str_replace('_', ' ', $activity_log->type)),
                    'parent_type' => ucfirst(str_replace('_', ' ', $activity_log->parent_type)),
                    'type_title' => $activity_log->type_title,
                    'parent_type_title' => $activity_log->parent_type_title,
                    'activity' => ucfirst(str_replace('_', ' ', $activity_log->activity)),
                    'message' => $activity_log->message,
                    'created_at' => format_date($activity_log->created_at, true),
                    'updated_at' => format_date($activity_log->updated_at, true),
                ];
            });

        return response()->json([
            "rows" => $activity_log->items(),
            "total" => $total,
        ]);
    }

    public function destroy($id)
    {
        $response = DeletionService::delete(ActivityLog::class, $id, 'Record');
        return $response;
    }

    public function destroy_multiple(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'ids' => 'required|array', // Ensure 'ids' is present and an array
            'ids.*' => 'integer|exists:activity_logs,id' // Ensure each ID in 'ids' is an integer and exists in the table
        ]);

        $ids = $validatedData['ids'];
        // Perform deletion using validated IDs
        foreach ($ids as $id) {
            DeletionService::delete(ActivityLog::class, $id, 'Record');
        }

        return response()->json(['error' => false, 'message' => 'Record(s) deleted successfully.']);
    }
}
