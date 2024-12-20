<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\Workspace;
use Illuminate\Http\Request;
use App\Services\DeletionService;
use App\Http\Controllers\Controller;
use App\Models\UnitMeasure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class WorkspacesController extends Controller
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
        $workspaces = Workspace::all();
        $users = User::all();
        $clients = Client::all();
        $units= UnitMeasure::all();
        return view('workspaces.workspaces', compact('workspaces','units', 'users', 'clients'));
    }
    public function create()
    {

        $users = User::all();
        $clients = Client::all();
        $auth_user = $this->user;

        return view('workspaces.create_workspace', compact('users', 'clients', 'auth_user'));
    }
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'title' => ['required']
        ]);

        // Add the authenticated user as a participant
        $formFields['user_id'] = $this->user->id;

        // Get user_ids and client_ids from the request
        $userIds = $request->input('user_ids') ?? [];
        $clientIds = $request->input('client_ids') ?? [];

        // Set creator as a participant automatically
        if (Auth::guard('client')->check() && !in_array($this->user->id, $clientIds)) {
            array_splice($clientIds, 0, 0, $this->user->id);
        } else if (Auth::guard('web')->check() && !in_array($this->user->id, $userIds)) {
            array_splice($userIds, 0, 0, $this->user->id);
        }

        // Create the new workspace
        $new_workspace = Workspace::create($formFields);
        $workspace_id = $new_workspace->id;
        $workspace = Workspace::find($workspace_id);

        // Attach users and clients to the workspace
        $workspace->users()->attach($userIds);
        $workspace->clients()->attach($clientIds);

        // Prepare notification data
        $notification_data = [
            'type' => 'workspace',
            'type_id' => $workspace_id,
            'type_title' => $workspace->title,
            'action' => 'assigned',
            'title' => 'Added in a workspace',
            'message' => $this->user->first_name . ' ' . $this->user->last_name . ' added you in workspace: ' . $workspace->title . ', ID #' . $workspace_id.'.'
            
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

        // Flash success message
        Session::flash('message', 'Workspace created successfully.');

        // Return JSON response with workspace ID
        return response()->json(['error' => false, 'id' => $workspace_id]);
    }

    public function list()
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $user_id = (request('user_id')) ? request('user_id') : "";
        $client_id = (request('client_id')) ? request('client_id') : "";

        $workspaces = isAdminOrHasAllDataAccess() ? $this->workspace : $this->user->workspaces();

        if ($user_id) {
            $user = User::find($user_id);
            $workspaces = $user->workspaces();
        }
        if ($client_id) {
            $client = Client::find($client_id);
            $workspaces = $client->workspaces();
        }
        $workspaces = $workspaces->when($search, function ($query) use ($search) {
            return $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%');
        });
        $totalworkspaces = $workspaces->count();

        $workspaces = $workspaces->orderBy($sort, $order)
            ->paginate(request("limit"))
            ->through(
                fn ($workspace) => [
                    'id' => $workspace->id,
                    'title' => '<a href="workspaces/switch/' . $workspace->id . '">' . $workspace->title . '</a>',
                    'users' => $workspace->users,
                    'clients' => $workspace->clients,
                    'created_at' => format_date($workspace->created_at, true),
                    'updated_at' => format_date($workspace->updated_at, true),
                ]
            );
        foreach ($workspaces->items() as $workspace => $collection) {
            foreach ($collection['clients'] as $i => $client) {
                $collection['clients'][$i] = "<a href='/clients/profile/" . $client->id . "' target='_blank'><li class='avatar avatar-sm pull-up'  title='" . $client['first_name'] . " " . $client['last_name'] . "'>
                <img src='" . ($client['photo'] ? asset('storage/' . $client['photo']) : asset('storage/photos/no-image.jpg')) . "' alt='Avatar' class='rounded-circle' />
                </li></a>";
            };
        }

        foreach ($workspaces->items() as $workspace => $collection) {
            foreach ($collection['users'] as $i => $user) {
                $collection['users'][$i] = "<a href='/users/profile/" . $user->id . "' target='_blank'><li class='avatar avatar-sm pull-up'  title='" . $user['first_name'] . " " . $user['last_name'] . "'>
                <img src='" . ($user['photo'] ? asset('storage/' . $user['photo']) : asset('storage/photos/no-image.jpg')) . "' class='rounded-circle' />
                </li></a>";
            };
        }

        return response()->json([
            "rows" => $workspaces->items(),
            "total" => $totalworkspaces,
        ]);
    }

    public function edit($id)
    {
        $workspace = Workspace::findOrFail($id);
        $users = User::all();
        $clients = Client::all();
        return view('workspaces.update_workspace', compact('workspace', 'users', 'clients'));
    }

    public function update(Request $request, $id)
    {
        $formFields = $request->validate([
            'title' => ['required']
        ]);

        $userIds = $request->input('user_ids') ?? [];
        $clientIds = $request->input('client_ids') ?? [];
        $workspace = Workspace::findOrFail($id);

        // Set creator as a participant automatically
        if (User::where('id', $workspace->user_id)->exists() && !in_array($workspace->user_id, $userIds)) {
            array_splice($userIds, 0, 0, $workspace->user_id);
        } elseif (Client::where('id', $workspace->user_id)->exists() && !in_array($workspace->user_id, $clientIds)) {
            array_splice($clientIds, 0, 0, $workspace->user_id);
        }

        // Get current list of users and clients associated with the workspace
        $existingUserIds = $workspace->users->pluck('id')->toArray();
        $existingClientIds = $workspace->clients->pluck('id')->toArray();

        $workspace->update($formFields);
        $workspace->users()->sync($userIds);
        $workspace->clients()->sync($clientIds);

        // Exclude old users and clients from receiving notification
        $userIds = array_diff($userIds, $existingUserIds);
        $clientIds = array_diff($clientIds, $existingClientIds);

         // Prepare notification data
         $notification_data = [
            'type' => 'workspace',
            'type_id' => $id,
            'type_title' => $workspace->title,
            'action' => 'assigned',
            'title' => 'Added in a workspace',
            'message' => $this->user->first_name . ' ' . $this->user->last_name . ' added you in workspace: ' . $workspace->title . ', ID #' . $id.'.'
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

        Session::flash('message', 'Workspace updated successfully.');
        return response()->json(['error' => false, 'id' => $id]);
    }

    public function destroy($id)
    {
        if ($this->workspace->id != $id) {
            $response = DeletionService::delete(Workspace::class, $id, 'Workspace');
            return $response;
        } else {
            return response()->json(['error' => true, 'message' => 'Current workspace couldn\'t deleted.']);
        }
    }

    public function destroy_multiple(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'ids' => 'required|array', // Ensure 'ids' is present and an array
            'ids.*' => 'integer|exists:workspaces,id' // Ensure each ID in 'ids' is an integer and exists in the table
        ]);

        $ids = $validatedData['ids'];
        $deletedWorkspaces = [];
        $deletedWorkspaceTitles = [];
        // Perform deletion using validated IDs
        foreach ($ids as $id) {
            $workspace = Workspace::find($id);
            if ($workspace) {
                $deletedWorkspaces[] = $id;
                $deletedWorkspaceTitles[] = $workspace->title;
                DeletionService::delete(Workspace::class, $id, 'Workspace');
            }
        }

        return response()->json(['error' => false, 'message' => 'Workspace(s) deleted successfully.', 'id' => $deletedWorkspaces, 'titles' => $deletedWorkspaceTitles]);
    }

    public function switch($id)
    {
        if (Workspace::findOrFail($id)) {
            session()->put('workspace_id', $id);
            return back()->with('message', 'Workspace changed successfully.');
        } else {
            return back()->with('error', 'Workspace not found.');
        }
    }

    public function remove_participant()
    {
        $workspace = Workspace::findOrFail(session()->get('workspace_id'));
        if (isClient()) {
            $workspace->clients()->detach($this->user->id);
        } else {
            $workspace->users()->detach($this->user->id);
        }
        $workspace_id = isset($this->user->workspaces[0]['id']) && !empty($this->user->workspaces[0]['id']) ? $this->user->workspaces[0]['id'] : 0;
        $data = ['workspace_id' => $workspace_id];
        session()->put($data);
        Session::flash('message', 'Removed from workspace successfully.');
        return response()->json(['error' => false]);
    }

    public function duplicate($id)
    {
        // Define the related tables for this meeting
        $relatedTables = ['users', 'clients']; // Include related tables as needed

        // Use the general duplicateRecord function
        $duplicate = duplicateRecord(Workspace::class, $id, $relatedTables);

        if (!$duplicate) {
            return response()->json(['error' => true, 'message' => 'Workspace duplication failed.']);
        }
        if (request()->has('reload') && request()->input('reload') === 'true') {
            Session::flash('message', 'Workspace duplicated successfully.');
        }
        return response()->json(['error' => false, 'message' => 'Workspace duplicated successfully.', 'id' => $id]);
    }
}
