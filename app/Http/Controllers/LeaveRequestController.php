<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Workspace;
use App\Models\LeaveEditor;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use App\Services\DeletionService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class LeaveRequestController extends Controller
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
        $leave_requests = is_admin_or_leave_editor() ? $this->workspace->leave_requests() : $this->user->leave_requests();
        $users = $this->workspace->users(true)->get();
        return view('leave_requests.list', ['leave_requests' => $leave_requests->count(), 'users' => $users, 'auth_user' => $this->user]);
    }

    public function store(Request $request)
    {
        if (is_admin_or_leave_editor()) {
            $formFields = $request->validate([
                'user_id' => ['required'],
                'reason' => ['required'],
                'from_date' => ['required', 'before_or_equal:to_date'],
                'to_date' => ['required'],
                'status' => ['nullable']
            ]);
        } else {
            $formFields = $request->validate([
                'reason' => ['required'],
                'from_date' => ['required', 'before_or_equal:to_date'],
                'to_date' => ['required']
            ]);
        }
        if (!$this->user->hasRole('admin') && $request->input('status') && $request->filled('status') && $request->input('status') == 'approved') {
            return response()->json(['error' => true, 'message' => 'You can not approve own leave request.']);
        }

        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $formFields['from_date'] = format_date($from_date, false, app('php_date_format'), 'Y-m-d');
        $formFields['to_date'] = format_date($to_date, false, app('php_date_format'), 'Y-m-d');
        if (is_admin_or_leave_editor() && $request->input('status') && $request->filled('status') && $request->input('status') != 'pending') {
            $formFields['action_by'] = $this->user->id;
        }

        $formFields['workspace_id'] = $this->workspace->id;
        $formFields['user_id'] = is_admin_or_leave_editor() && $request->filled('user_id') ? $request->input('user_id') : $this->user->id;
        if ($lr = LeaveRequest::create($formFields)) {
            Session::flash('message', 'Leave request created successfully.');
            return response()->json(['error' => false, 'message' => 'Leave request created successfully.', 'id' => $lr->id, 'type' => 'leave_request']);
        } else {
            return response()->json(['error' => true, 'message' => 'Leave request couldn\'t created.']);
        }
    }

    public function list()
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $status = isset($_REQUEST['status']) && $_REQUEST['status'] !== '' ? $_REQUEST['status'] : "";
        $user_id = (request('user_id')) ? request('user_id') : "";
        $action_by_id = (request('action_by_id')) ? request('action_by_id') : "";
        $start_date_from = (request('start_date_from')) ? request('start_date_from') : "";
        $start_date_to = (request('start_date_to')) ? request('start_date_to') : "";
        $end_date_from = (request('end_date_from')) ? request('end_date_from') : "";
        $end_date_to = (request('end_date_to')) ? request('end_date_to') : "";
        $where = ['workspace_id' => $this->workspace->id];

        if (!is_admin_or_leave_editor()) {
            // If the user is not an admin or leave editor, filter by user_id
            $where['user_id'] = $this->user->id;
        }

        if ($status != '') {
            $where['status'] = $status;
        }

        $leave_requests = LeaveRequest::select(
            'leave_requests.*',
            'users.photo AS user_photo',
            DB::raw('CONCAT(users.first_name, " ", users.last_name) AS user_name'),
            DB::raw('CONCAT(action_users.first_name, " ", action_users.last_name) AS action_by_name')
        )
            ->leftJoin('users', 'leave_requests.user_id', '=', 'users.id')
            ->leftJoin('users AS action_users', 'leave_requests.action_by', '=', 'action_users.id');

        if ($user_id) {
            $where['user_id'] = $user_id;
        }
        if ($action_by_id) {
            $where['action_by'] = $action_by_id;
        }
        if ($start_date_from && $start_date_to) {
            $leave_requests = $leave_requests->whereBetween('start_date', [$start_date_from, $start_date_to]);
        }
        if ($end_date_from && $end_date_to) {
            $leave_requests  = $leave_requests->whereBetween('to_date', [$end_date_from, $end_date_to]);
        }
        if ($search) {
            $leave_requests = $leave_requests->where(function ($query) use ($search) {
                $query->where('reason', 'like', '%' . $search . '%')
                    ->orWhere('leave_requests.id', 'like', '%' . $search . '%');
            });
        }

        $leave_requests->where($where);
        $total = $leave_requests->count();

        $leave_requests = $leave_requests->orderBy($sort, $order)
            ->paginate(request("limit"))
            ->through(function ($leave_request) {
                // Parse the "from_date" and "to_date"
                $fromDate = Carbon::parse($leave_request->from_date);
                $toDate = Carbon::parse($leave_request->to_date);

                // Calculate the inclusive duration in days
                $duration = $fromDate->diffInDays($toDate) + 1;

                // Format "from_date" and "to_date" with labels
                $formattedDates = $duration > 1 ? format_date($leave_request->from_date) . ' ' . get_label('to', 'To') . ' ' . format_date($leave_request->to_date) : format_date($leave_request->from_date);
                $statusBadges = [
                    'pending' => '<span class="badge bg-warning">' . get_label('pending', 'Pending') . '</span>',
                    'approved' => '<span class="badge bg-success">' . get_label('approved', 'Approved') . '</span>',
                    'rejected' => '<span class="badge bg-danger">' . get_label('rejected', 'Rejected') . '</span>',
                ];
                $statusBadge = $statusBadges[$leave_request->status] ?? '';
                return [
                    'id' => $leave_request->id,
                    'user_name' => $leave_request->user_name . "<ul class='list-unstyled users-list m-0 avatar-group d-flex align-items-center'><a href='/users/profile/" . $leave_request->user_id . "' target='_blank'><li class='avatar avatar-sm pull-up'  title='" . $leave_request->user_name . "'>
                    <img src='" . ($leave_request->user_photo ? asset('storage/' . $leave_request->user_photo) : asset('storage/photos/no-image.jpg')) . "' alt='Avatar' class='rounded-circle'>",
                    'action_by' => $leave_request->action_by_name,
                    'from_date' => format_date($leave_request->from_date),
                    'to_date' => format_date($leave_request->to_date),
                    'duration' => $duration . ' day' . ($duration > 1 ? 's' : ''),
                    'reason' => $leave_request->reason,
                    'created_at' => format_date($leave_request->created_at, true),
                    'updated_at' => format_date($leave_request->updated_at, true),
                    'status' => $statusBadge,
                ];
            });


        return response()->json([
            "rows" => $leave_requests->items(),
            "total" => $total,
        ]);
    }

    public function get($id)
    {
        $lr = LeaveRequest::findOrFail($id);
        return response()->json(['lr' => $lr]);
    }

    public function update(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'id' => 'required|exists:leave_requests,id', // Ensure the leave request exists
                'status' => 'required|in:pending,approved,rejected', // Validate the status
            ]);

            // Find the leave request by its ID
            $leaveRequest = LeaveRequest::findOrFail($validatedData['id']);

            if (!is_null($leaveRequest->action_by) && !$this->user->hasRole('admin')) {
                return response()->json([
                    'error' => true,
                    'message' => 'Once actioned only admin can update leave request.',
                ]);
            }

            if ($leaveRequest->user_id == $this->user->id && !$this->user->hasRole('admin') && $request->input('status') && $request->filled('status') && $request->input('status') == 'approved') {
                return response()->json([
                    'error' => true,
                    'message' => 'You can not approve own leave request.',
                ]);
            }

            // Update the status of the leave request
            if ($leaveRequest->update([
                'status' => $validatedData['status'],
                'action_by' => $this->user->id,
            ])) {
                return response()->json([
                    'error' => false,
                    'message' => 'Leave request updated successfully.',
                    'id' => $leaveRequest->id,
                    'type' => 'leave_request'
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => 'Leave request couldn\'t updated.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Error updating status: ' . $e->getMessage(),
            ], 500); // You can customize the error response as needed
        }
    }

    public function update_editors(Request $request)
    {

        $userIds = $request->input('user_ids') ?? [];
        $currentLeaveEditorUserIds = LeaveEditor::pluck('user_id')->toArray();
        $usersToDetach = array_diff($currentLeaveEditorUserIds, $userIds);
        LeaveEditor::whereIn('user_id', $usersToDetach)->delete();
        foreach ($userIds as $assignedUserId) {
            // Check if a leave editor with the same user_id already exists
            $existingLeaveEditor = LeaveEditor::where('user_id', $assignedUserId)->first();

            if (!$existingLeaveEditor) {
                // Create a new LeaveEditor only if it doesn't exist
                $leaveEditor = new LeaveEditor();
                $leaveEditor->user_id = $assignedUserId;
                $leaveEditor->save();
            }
        }

        return response()->json(['error' => false, 'message' => 'Leave editors updated successfully.']);
    }

    public function destroy($id)
    {
        DeletionService::delete(LeaveRequest::class, $id, 'Leave request');
        return response()->json(['error' => false, 'message' => 'Leave request deleted successfully.', 'id' => $id, 'type' => 'leave_request']);
    }

    public function destroy_multiple(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'ids' => 'required|array', // Ensure 'ids' is present and an array
            'ids.*' => 'integer|exists:leave_requests,id' // Ensure each ID in 'ids' is an integer and exists in the table
        ]);

        $ids = $validatedData['ids'];
        $deletedIds = [];
        // Perform deletion using validated IDs
        foreach ($ids as $id) {
            $deletedIds[] = $id;
            DeletionService::delete(LeaveRequest::class, $id, 'Leave request');
        }

        return response()->json(['error' => false, 'message' => 'Leave request(s) deleted successfully.', 'id' => $deletedIds, 'type' => 'leave_request']);
    }
}
