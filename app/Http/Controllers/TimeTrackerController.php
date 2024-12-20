<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use App\Models\Workspace;
use App\Models\TimeTracker;
use Illuminate\Http\Request;
use App\Services\DeletionService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TimeTrackerController extends Controller
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
        $timesheet = isAdminOrHasAllDataAccess() ? $this->workspace->timesheets : $this->user->timesheets;
        $users = $this->workspace->users;
        return view('time_trackers.timesheet', compact('timesheet', 'users'));
    }


    public function store(Request $request)
    {

        $formFields['workspace_id'] = $this->workspace->id;
        $formFields['user_id'] =  $this->user->id;
        $formFields['start_date_time'] = date('Y-m-d H:i:s');
        $formattedDateTime = format_date($formFields['start_date_time'], true);

        if ($request->has('message') && !empty($request->input('message'))) {
            $formFields['message'] = $request->input('message');
        }

        $new_record = TimeTracker::create($formFields);
        $recorded_id = $new_record->id;
        return response()->json(['error' => false, 'message' => 'Timer has been started successfully.', 'id' => $recorded_id, 'activity_message' => $this->user->first_name . ' ' . $this->user->last_name . ' started time tracker ' . $formattedDateTime, 'type' => 'time_tracker', 'operation' => 'started']);
    }

    public function update(Request $request)
    {
        $formFields['end_date_time'] =  date('Y-m-d H:i:s');
        $formattedDateTime = format_date($formFields['end_date_time'], true);
        if ($request->has('message') && !empty($request->input('message'))) {
            $formFields['message'] = $request->input('message');
        }
        $time_tracker = TimeTracker::findOrFail($request->input('record_id'));
        $time_tracker->update($formFields);
        return response()->json(['error' => false, 'message' => 'Timer has been stopped successfully.', 'id' => $request->input('record_id'), 'activity_message' => $this->user->first_name . ' ' . $this->user->last_name . ' stopped time tracker ' . $formattedDateTime, 'type' => 'time_tracker', 'operation' => 'stopped']);
    }

    public function list()
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $user_id = (request('user_id')) ? request('user_id') : "";
        $start_date_from = (request('start_date_from')) ? request('start_date_from') : "";
        $start_date_to = (request('start_date_to')) ? request('start_date_to') : "";
        $end_date_from = (request('end_date_from')) ? request('end_date_from') : "";
        $end_date_to = (request('end_date_to')) ? request('end_date_to') : "";

        $timesheet = TimeTracker::select(
            'time_trackers.*',
            'users.photo as user_photo',
            DB::raw('CONCAT(users.first_name, " ", users.last_name) AS user_name')
        )
            ->leftJoin('users', 'time_trackers.user_id', '=', 'users.id');

        $timesheet  = $timesheet->where('workspace_id', $this->workspace->id);

        if (!isAdminOrHasAllDataAccess()) {
            $timesheet  = $timesheet->where('user_id', $this->user->id);
        }
        if ($start_date_from && $start_date_to) {
            $start_date_from = $start_date_from . ' 00:00:00';
            $start_date_to = $start_date_to . ' 23:59:59';
            $timesheet = $timesheet->whereBetween('start_date_time', [$start_date_from, $start_date_to]);
        }
        if ($end_date_from && $end_date_to) {
            $end_date_from = $end_date_from . ' 00:00:00';
            $end_date_to = $end_date_to . ' 23:59:59';
            $timesheet  = $timesheet->whereBetween('end_date_time', [$end_date_from, $end_date_to]);
        }
        if ($user_id) {
            $timesheet  = $timesheet->where('user_id', $user_id);
        }
        if ($search) {
            $timesheet = $timesheet->where(function ($query) use ($search) {
                $query->where('message', 'like', '%' . $search . '%');
            });
        }

        $total = $timesheet->count();

        $timesheet = $timesheet->orderBy($sort, $order)
            ->paginate(request("limit"))
            ->through(function ($timesheet) {
                $formattedDuration = '-';
                if ($timesheet->end_date_time) {
                    $startDateTime = Carbon::parse($timesheet->start_date_time);
                    $endDateTime = Carbon::parse($timesheet->end_date_time);

                    // Calculate the difference between start and end date times
                    $duration = $endDateTime->diff($startDateTime);

                    // Check if the duration spans multiple days
                    if ($duration->days > 0) {
                        // Format with days if the duration spans multiple days
                        $formattedDuration = $duration->format('%D days %H:%I:%S');
                    } else {
                        // Format as usual without days if the duration is within the same day
                        $formattedDuration = $duration->format('%H:%I:%S');
                    }
                }

                return [
                    'id' => $timesheet->id,
                    'user_name' => $timesheet->user_name,
                    'photo' => "<div class='avatar avatar-md pull-up' title='" . $timesheet->user_name . "'>
                    <a href='/users/profile/" . $timesheet->user_id . "' target='_blank'>
                    <img src='" . ($timesheet->user_photo ? asset('storage/' . $timesheet->user_photo) : asset('storage/photos/no-image.jpg')) . "' alt='Avatar' class='rounded-circle'>
                    </a>
                    </div>",
                    'start_date_time' => format_date($timesheet->start_date_time,  true),
                    'end_date_time' => $timesheet->end_date_time ? format_date($timesheet->end_date_time,  true) : '-',
                    'duration' => $formattedDuration,
                    'message' => $timesheet->message,
                    'created_at' => format_date($timesheet->created_at, true),
                    'updated_at' => format_date($timesheet->updated_at, true),
                ];
            });


        return response()->json([
            "rows" => $timesheet->items(),
            "total" => $total,
        ]);
    }


    public function destroy($id)
    {

        DeletionService::delete(TimeTracker::class, $id, 'Record');
        return response()->json(['error' => false, 'message' => 'Record deleted successfully.', 'id' => $id, 'activity_message' => $this->user->first_name . ' ' . $this->user->last_name . ' deleted time tracker record', 'type' => 'time_tracker']);
    }

    public function destroy_multiple(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'ids' => 'required|array', // Ensure 'ids' is present and an array
            'ids.*' => 'integer|exists:time_trackers,id' // Ensure each ID in 'ids' is an integer and exists in the table
        ]);

        $ids = $validatedData['ids'];
        $deletedIds = [];
        // Perform deletion using validated IDs
        foreach ($ids as $id) {
            $deletedIds[] = $id;
            DeletionService::delete(TimeTracker::class, $id, 'Record');
        }

        return response()->json(['error' => false, 'message' => 'Record(s) deleted successfully.', 'id' => $deletedIds, 'activity_message' => $this->user->first_name . ' ' . $this->user->last_name . ' deleted time tracker record', 'type' => 'time_tracker']);
    }
}
