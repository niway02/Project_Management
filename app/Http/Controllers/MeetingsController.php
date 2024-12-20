<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Client;
use App\Models\Meeting;
use App\Models\Workspace;
use Illuminate\Http\Request;
use App\Services\DeletionService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MeetingsController extends Controller
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
        $meetings = isAdminOrHasAllDataAccess() ? $this->workspace->meetings : $this->user->meetings;
        $users = $this->workspace->users;
        $clients = $this->workspace->clients;
        return view('meetings.meetings', compact('meetings', 'users', 'clients'));
    }

    public function create()
    {
        $users = $this->workspace->users;
        $clients = $this->workspace->clients;
        $auth_user = $this->user;

        return view('meetings.create_meeting', compact('users', 'clients', 'auth_user'));
    }

    public function store(Request $request)
    {
        $formFields = $request->validate([
            'title' => ['required'],
            'start_date' => ['required', 'before_or_equal:end_date'],
            'end_date' => ['required', 'after_or_equal:start_date'],
            'start_time' => ['required'],
            'end_time' => ['required']

        ]);
        $start_date = $request->input('start_date');
        $start_time = $request->input('start_time');
        $end_date = $request->input('end_date');
        $end_time = $request->input('end_time');

        $formFields['start_date_time'] = format_date($start_date, false, app('php_date_format'), 'Y-m-d', false).' '.$start_time;
        $formFields['end_date_time'] = format_date($end_date, false, app('php_date_format'), 'Y-m-d', false).' '.$end_time;

        $formFields['workspace_id'] = $this->workspace->id;
        $formFields['user_id'] =  $this->user->id;
        $userIds = $request->input('user_ids') ?? [];
        $clientIds = $request->input('client_ids') ?? [];

        // Set creator as a participant automatically

        if (Auth::guard('client')->check() && !in_array($this->user->id, $clientIds)) {
            array_splice($clientIds, 0, 0, $this->user->id);
        } else if (Auth::guard('web')->check() && !in_array($this->user->id, $userIds)) {
            array_splice($userIds, 0, 0, $this->user->id);
        }

        $new_meeting = Meeting::create($formFields);
        $meeting_id = $new_meeting->id;
        $meeting = Meeting::find($meeting_id);
        $meeting->users()->attach($userIds);
        $meeting->clients()->attach($clientIds);

        // Prepare notification data
        $notification_data = [
            'type' => 'meeting',
            'type_id' => $meeting_id,
            'type_title' => $meeting->title,
            'action' => 'assigned',
            'title' => 'Added in a meeting',
            'message' => $this->user->first_name . ' ' . $this->user->last_name . ' added you in meeting: ' . $meeting->title . ', ID #' . $meeting_id . '.'

        ];

        // Combine user and client IDs for notification recipients
        $recipients = array_merge(
            array_map(function ($userId) {
                return 'u_' . $userId;
            }, $userIds),
            array_map(function ($clientId) {
                return 'c_' . $clientId;
            }, $clientIds)
        );

        // Process notifications
        processNotifications($notification_data, $recipients);

        Session::flash('message', 'Meeting created successfully.');
        return response()->json(['error' => false, 'id' => $meeting_id]);
    }

    public function list()
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $status = isset($_REQUEST['status']) && $_REQUEST['status'] !== '' ? $_REQUEST['status'] : "";
        $user_id = (request('user_id')) ? request('user_id') : "";
        $client_id = (request('client_id')) ? request('client_id') : "";
        $start_date_from = (request('start_date_from')) ? request('start_date_from') : "";
        $start_date_to = (request('start_date_to')) ? request('start_date_to') : "";
        $end_date_from = (request('end_date_from')) ? request('end_date_from') : "";
        $end_date_to = (request('end_date_to')) ? request('end_date_to') : "";
        $meetings = isAdminOrHasAllDataAccess() ? $this->workspace->meetings() : $this->user->meetings();
        if ($search) {
            $meetings = $meetings->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
            });
        }
        if ($user_id) {
            $user = User::find($user_id);
            $meetings = $user->meetings();
        }
        if ($client_id) {
            $client = Client::find($client_id);
            $meetings = $client->meetings();
        }
        if ($start_date_from && $start_date_to) {
            $start_date_from = $start_date_from . ' 00:00:00';
            $start_date_to = $start_date_to . ' 23:59:59';
            $meetings = $meetings->whereBetween('start_date_time', [$start_date_from, $start_date_to]);
        }
        if ($end_date_from && $end_date_to) {
            $end_date_from = $end_date_from . ' 00:00:00';
            $end_date_to = $end_date_to . ' 23:59:59';
            $meetings  = $meetings->whereBetween('end_date_time', [$end_date_from, $end_date_to]);
        }
        if ($status) {
            if ($status === 'ongoing') {
                $meetings = $meetings->where('start_date_time', '<=', Carbon::now(config('app.timezone')))
                    ->where('end_date_time', '>=', Carbon::now(config('app.timezone')));
            } elseif ($status === 'yet_to_start') {
                $meetings = $meetings->where('start_date_time', '>', Carbon::now(config('app.timezone')));
            } elseif ($status === 'ended') {
                $meetings = $meetings->where('end_date_time', '<', Carbon::now(config('app.timezone')));
            }
        }
        $totalmeetings = $meetings->count();
        $currentDateTime = Carbon::now(config('app.timezone'));
        $meetings = $meetings->orderBy($sort, $order)


            ->paginate(request("limit"))
            ->through(
                fn ($meeting) => [
                    'id' => $meeting->id,
                    'title' => $meeting->title,
                    'start_date_time' => format_date($meeting->start_date_time, true, null, null, false),
                    'end_date_time' => format_date($meeting->end_date_time, true, null, null, false),
                    'users' => $meeting->users,
                    'clients' => $meeting->clients,
                    'status' => (($currentDateTime < \Carbon\Carbon::parse($meeting->start_date_time, config('app.timezone'))) ? 'Will start in ' . $currentDateTime->diff(\Carbon\Carbon::parse($meeting->start_date_time, config('app.timezone')))->format('%a days %H hours %I minutes %S seconds') : (($currentDateTime > \Carbon\Carbon::parse($meeting->end_date_time, config('app.timezone')) ? 'Ended before ' . \Carbon\Carbon::parse($meeting->end_date_time, config('app.timezone'))->diff($currentDateTime)->format('%a days %H hours %I minutes %S seconds') : 'Ongoing'))),
                    'created_at' => format_date($meeting->created_at,  true),
                    'updated_at' => format_date($meeting->updated_at, true),
                ]
            );
        foreach ($meetings->items() as $meeting => $collection) {
            foreach ($collection['clients'] as $i => $client) {
                $collection['clients'][$i] = "<a href='/clients/profile/" . $client->id . "' target='_blank'><li class='avatar avatar-sm pull-up'  title='" . $client['first_name'] . " " . $client['last_name'] . "'>
                    <img src='" . ($client['photo'] ? asset('storage/' . $client['photo']) : asset('storage/photos/no-image.jpg')) . "' alt='Avatar' class='rounded-circle' />
                </li></a>";
            };
        }

        foreach ($meetings->items() as $meeting => $collection) {
            foreach ($collection['users'] as $i => $user) {
                $collection['users'][$i] = "<a href='/users/profile/" . $user->id . "' target='_blank'><li class='avatar avatar-sm pull-up'  title='" . $user['first_name'] . " " . $user['last_name'] . "'>
                    <img src='" . ($user['photo'] ? asset('storage/' . $user['photo']) : asset('storage/photos/no-image.jpg')) . "' class='rounded-circle' />
                </li></a>";
            };
        }


        return response()->json([
            "rows" => $meetings->items(),
            "total" => $totalmeetings,
        ]);
    }

    public function edit($id)
    {
        $meeting = Meeting::findOrFail($id);
        $users = $this->workspace->users;
        $clients = $this->workspace->clients;
        return view('meetings.update_meeting', compact('meeting', 'users', 'clients'));
    }

    public function update(Request $request, $id)
    {
        $formFields = $request->validate([
            'title' => ['required'],
            'start_date' => ['required', 'before_or_equal:end_date'],
            'end_date' => ['required'],
            'start_time' => ['required'],
            'end_time' => ['required']
        ]);
        $start_date = $request->input('start_date');
        $start_time = $request->input('start_time');
        $end_date = $request->input('end_date');
        $end_time = $request->input('end_time');
        
        $formFields['start_date_time'] = format_date($start_date, false, app('php_date_format'), 'Y-m-d', false).' '.$start_time;
        $formFields['end_date_time'] = format_date($end_date, false, app('php_date_format'), 'Y-m-d', false).' '.$end_time;


        $userIds = $request->input('user_ids') ?? [];
        // dd($userIds);
        $clientIds = $request->input('client_ids') ?? [];
        $meeting = Meeting::findOrFail($id);
        // Set creator as a participant automatically

        if (User::where('id', $meeting->user_id)->exists() && !in_array($meeting->user_id, $userIds)) {
            array_splice($userIds, 0, 0, $meeting->user_id);
        } elseif (Client::where('id', $meeting->user_id)->exists() && !in_array($meeting->user_id, $clientIds)) {
            array_splice($clientIds, 0, 0, $meeting->user_id);
        }

        // Get current list of users and clients associated with the workspace
        $existingUserIds = $meeting->users->pluck('id')->toArray();
        $existingClientIds = $meeting->clients->pluck('id')->toArray();

        $meeting->update($formFields);
        $meeting->users()->sync($userIds);
        $meeting->clients()->sync($clientIds);

        // Exclude old users and clients from receiving notification
        $userIds = array_diff($userIds, $existingUserIds);
        $clientIds = array_diff($clientIds, $existingClientIds);

        // Prepare notification data
        $notification_data = [
            'type' => 'meeting',
            'type_id' => $id,
            'type_title' => $meeting->title,
            'action' => 'assigned',
            'title' => 'Added in a meeting',
            'message' => $this->user->first_name . ' ' . $this->user->last_name . ' added you in meeting: ' . $meeting->title . ', ID #' . $id . '.'
        ];

        // Combine user and client IDs for notification recipients
        $recipients = array_merge(
            array_map(function ($userId) {
                return 'u_' . $userId;
            }, $userIds),
            array_map(function ($clientId) {
                return 'c_' . $clientId;
            }, $clientIds)
        );
        // Process notifications
        processNotifications($notification_data, $recipients);

        Session::flash('message', 'Meeting updated successfully.');
        return response()->json(['error' => false, 'id' => $id]);
    }


    public function destroy($id)
    {

        $response = DeletionService::delete(Meeting::class, $id, 'Meeting');
        return $response;
    }

    public function destroy_multiple(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'ids' => 'required|array', // Ensure 'ids' is present and an array
            'ids.*' => 'integer|exists:meetings,id' // Ensure each ID in 'ids' is an integer and exists in the table
        ]);

        $ids = $validatedData['ids'];
        $deletedMeetings = [];
        $deletedMeetingTitles = [];
        // Perform deletion using validated IDs
        foreach ($ids as $id) {
            $meeting = Meeting::find($id);
            if ($meeting) {
                $deletedMeetings[] = $id;
                $deletedMeetingTitles[] = $meeting->title;
                DeletionService::delete(Meeting::class, $id, 'Meeting');
            }
        }

        return response()->json(['error' => false, 'message' => 'Meetings(s) deleted successfully.', 'id' => $deletedMeetings, 'titles' => $deletedMeetingTitles]);
    }

    public function join($id)
    {

        $meeting = Meeting::findOrFail($id);
        $currentDateTime = Carbon::now(config('app.timezone'));
        if ($currentDateTime < $meeting->start_date_time) {
            return redirect('/meetings')->with('error', 'Meeting is yet to start');
        } elseif ($currentDateTime > $meeting->end_date_time) {
            return redirect('/meetings')->with('error', 'Meeting has been ended');
        } else {
            if ($meeting->users->contains($this->user->id) || isAdminOrHasAllDataAccess()) {
                $is_meeting_admin =  $this->user->id == $meeting['user_id'];
                $meeting_id = $meeting['id'];
                $room_name = $meeting['title'];
                $user_email =  $this->user->email;
                $user_display_name =  $this->user->first_name . ' ' .  $this->user->last_name;
                return view('meetings.join_meeting', compact('is_meeting_admin', 'meeting_id', 'room_name', 'user_email', 'user_display_name'));
            } else {
                return redirect('/meetings')->with('error', 'You are not authorized to join this meeting');
            }
        }
    }

    public function duplicate($id)
    {
        // Define the related tables for this meeting
        $relatedTables = ['users', 'clients']; // Include related tables as needed

        // Use the general duplicateRecord function
        $duplicateMeeting = duplicateRecord(Meeting::class, $id, $relatedTables);
        if (!$duplicateMeeting) {
            return response()->json(['error' => true, 'message' => 'Meeting duplication failed.']);
        }
        if (request()->has('reload') && request()->input('reload') === 'true') {
            Session::flash('message', 'Meeting duplicated successfully.');
        }
        return response()->json(['error' => false, 'message' => 'Meeting duplicated successfully.', 'id' => $id]);
    }
}
