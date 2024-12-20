<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Client;
use App\Models\Status;
use App\Models\Priority;
use App\Models\Project;
use App\Models\MasterSchedule;
use App\Models\Workspace;
use App\Models\Milestone;
use App\Models\Tag;
use App\Models\ProjectUser;
use Illuminate\Http\Request;
use App\Models\ProjectClient;
use App\Models\Site;
use App\Models\UnitMeasure;
use App\Services\DeletionService;
use Dompdf\Dompdf;
use Dompdf\Options;
//use Barryvdh\DomPDF\PDF;
use Barryvdh\DomPDF\Facade as PDF; // Ensure this is the correct import

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

class ProjectsController extends Controller
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
public function index(Request $request, $type = null)
{
    $taskData = [
        'Started' => 6,
        'Not Started' => 10,
        'Completed' => 15,
        'Canceled' => 20,
    ];

       // $status = isset($_REQUEST['status']) && $_REQUEST['status'] !== '' ? $_REQUEST['status'] : "";
$projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects : $this->user->projects;
$statusId = Status::where('title', 'completed')->value('id');
if ($statusId != null) {
    $completedProjects = $projects->where('status_id', $statusId);
}

$statusId = Status::where('title', 'in Progress')->value('id');
if ($statusId != null) {
    $inProgressProjects = $projects->where('status_id', $statusId);
}
$statusId = Status::where('title', 'not Started')->value('id');
if ($statusId != null) {
    $notStartedProjects = $projects->where('status_id', $statusId);
}
$statusId = Status::where('title', 'Blocked')->value('id');
if ($statusId != null) {
    $cancelledProjects = $projects->where('status_id', $statusId);
}
        $selectedTags = (request('tags')) ? request('tags') : [];
        $where = [];
      
        $is_favorite = 0;
        if ($type === 'favorite') {
            $where['is_favorite'] = 1;
            $is_favorite = 1;
        }
        $sort = (request('sort')) ? request('sort') : "id";
        $order = 'desc';
        if ($sort == 'newest') {
            $sort = 'created_at';
            $order = 'desc';
        } elseif ($sort == 'oldest') {
            $sort = 'created_at';
            $order = 'asc';
        } elseif ($sort == 'recently-updated') {
            $sort = 'updated_at';
            $order = 'desc';
        } elseif ($sort == 'earliest-updated') {
            $sort = 'updated_at';
            $order = 'asc';
        }
        $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects() : $this->user->projects();
        $projects->where($where);
        if (!empty($selectedTags)) {
            $projects->whereHas('tags', function ($q) use ($selectedTags) {
                $q->whereIn('tags.id', $selectedTags);
            });
        }
        $toSelectProjectUsers = $this->workspace->users;
        $toSelectProjectClients = $this->workspace->clients;
        $projects = $projects->orderBy($sort, $order)->paginate(6);
        $units = UnitMeasure::all();
        $sites= Site::all();
        return view('projects.grid_view', ['projects' => $projects,'sites'=>$sites,'units'=>$units,'taskData' => $taskData,'completedProjects'=>$completedProjects,'inProgressProjects'=>$inProgressProjects,'notStartedProjects'=>$inProgressProjects,'cancelledProjects'=>$cancelledProjects, 'auth_user' => $this->user, 'toSelectProjectUsers' => $toSelectProjectUsers, 'toSelectProjectClients' => $toSelectProjectClients, 'selectedTags' => $selectedTags, 'is_favorite' => $is_favorite]);
    }

    public function list_view(Request $request, $type = null)
    {
        $taskData = [
            'Started' => 6,
            'Not Started' => 10,
            'Completed' => 15,
            'Canceled' => 20,
        ];
     $units = UnitMeasure::all();
        $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects : $this->user->projects;
//completed
$statusId = Status::where('title', 'completed')->value('id');
if ($statusId != null) {
    $completedProjects = $projects->where('status_id', $statusId);
}

$statusId = Status::where('title', 'in Progress')->value('id');
if ($statusId != null) {
    $inProgressProjects = $projects->where('status_id', $statusId);
}
$statusId = Status::where('title', 'not Started')->value('id');
if ($statusId != null) {
    $notStartedProjects = $projects->where('status_id', $statusId);
}
$statusId = Status::where('title', 'Blocked')->value('id');
if ($statusId != null) {
    $cancelledProjects = $projects->where('status_id', $statusId);
}
        $users = $this->workspace->users;
        $clients = $this->workspace->clients;

        $toSelectProjectUsers = $this->workspace->users;
        $toSelectProjectClients = $this->workspace->clients;

        $is_favorites = 0;
        if ($type === 'favorite') {
            $is_favorites = 1;
        }
        $units = UnitMeasure::all();
        $sites = Site::all();
        return view('projects.projects', ['sites' => $sites,'units'=>$units,'projects' => $projects,'taskData' => $taskData,'completedProjects'=>$completedProjects,'inProgressProjects'=>$inProgressProjects,'notStartedProjects'=>$inProgressProjects,'cancelledProjects'=>$cancelledProjects, 'users' => $users, 'clients' => $clients, 'toSelectProjectUsers' => $toSelectProjectUsers, 'toSelectProjectClients' => $toSelectProjectClients, 'is_favorites' => $is_favorites]);
    // return view('projects.projects', ['sites'=>$sites,'projects' => $projects,'taskData' => $taskData, 'users' => $users, 'clients' => $clients, 'toSelectProjectUsers' => $toSelectProjectUsers, 'toSelectProjectClients' => $toSelectProjectClients, 'is_favorites' => $is_favorites,'completedProjects'=>$completedProjects,'inProgressProjects'=>$inProgressProjects,'notStartedProjects'=>$inProgressProjects,'cancelledProjects'=>$cancelledProjects,]);

    }
    
 
    public function completed(Request $request, $type = null)
    {
        $status = 'completed';
    $selectedTags = (request('tags')) ? request('tags') : [];
    $where = [];
    if ($status != '') {
        // Assuming you have a 'statuses' table with a 'name' column
        $statusId = Status::where('title', $status)->value('id');
        if ($statusId != null) {
            $where['status_id'] = $statusId;
        }
    }
        $is_favorite = 0;
        if ($type === 'favorite') {
            $where['is_favorite'] = 1;
            $is_favorite = 1;
        }
        $sort = (request('sort')) ? request('sort') : "id";
        $order = 'desc';
        if ($sort == 'newest') {
            $sort = 'created_at';
            $order = 'desc';
        } elseif ($sort == 'oldest') {
            $sort = 'created_at';
            $order = 'asc';
        } elseif ($sort == 'recently-updated') {
            $sort = 'updated_at';
            $order = 'desc';
        } elseif ($sort == 'earliest-updated') {
            $sort = 'updated_at';
            $order = 'asc';
        }
        $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects() : $this->user->projects();
        $projects->where($where);
        if (!empty($selectedTags)) {
            $projects->whereHas('tags', function ($q) use ($selectedTags) {
                $q->whereIn('tags.id', $selectedTags);
            });
        }
        $toSelectProjectUsers = $this->workspace->users;
        $toSelectProjectClients = $this->workspace->clients;
        $projects = $projects->orderBy($sort, $order)->paginate(6);
       // return $statusId;
       $units = UnitMeasure::all();
        return view('projects.grid_view', ['projects' => $projects,'units'=>$units,'sites'=>$projects, 'auth_user' => $this->user, 'toSelectProjectUsers' => $toSelectProjectUsers, 'toSelectProjectClients' => $toSelectProjectClients, 'selectedTags' => $selectedTags, 'is_favorite' => $is_favorite]);
    }
       public function cancelled(Request $request, $type = null)
    {
        $status = 'cancelled';
    $selectedTags = (request('tags')) ? request('tags') : [];
    $where = [];
    if ($status != '') {
        // Assuming you have a 'statuses' table with a 'name' column
        $statusId = Status::where('title', $status)->value('id');
        if ($statusId != null) {
            $where['status_id'] = $statusId;
        }
    }
        $is_favorite = 0;
        if ($type === 'favorite') {
            $where['is_favorite'] = 1;
            $is_favorite = 1;
        }
        $sort = (request('sort')) ? request('sort') : "id";
        $order = 'desc';
        if ($sort == 'newest') {
            $sort = 'created_at';
            $order = 'desc';
        } elseif ($sort == 'oldest') {
            $sort = 'created_at';
            $order = 'asc';
        } elseif ($sort == 'recently-updated') {
            $sort = 'updated_at';
            $order = 'desc';
        } elseif ($sort == 'earliest-updated') {
            $sort = 'updated_at';
            $order = 'asc';
        }
        $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects() : $this->user->projects();
        $projects->where($where);
        if (!empty($selectedTags)) {
            $projects->whereHas('tags', function ($q) use ($selectedTags) {
                $q->whereIn('tags.id', $selectedTags);
            });
        }
        $toSelectProjectUsers = $this->workspace->users;
        $toSelectProjectClients = $this->workspace->clients;
        $projects = $projects->orderBy($sort, $order)->paginate(6);
       // return $statusId;
       $units = UnitMeasure::all();
        return view('projects.grid_view', ['projects' => $projects,'units'=>$units, 'auth_user' => $this->user, 'toSelectProjectUsers' => $toSelectProjectUsers, 'toSelectProjectClients' => $toSelectProjectClients, 'selectedTags' => $selectedTags, 'is_favorite' => $is_favorite]);
    }
       public function inProgress(Request $request, $type = null)
    {
        $status = 'inProgress';
    $selectedTags = (request('tags')) ? request('tags') : [];
    $where = [];
    if ($status != '') {
        // Assuming you have a 'statuses' table with a 'name' column
        $statusId = Status::where('title', $status)->value('id');
        if ($statusId != null) {
            $where['status_id'] = $statusId;
        }
    }
        $is_favorite = 0;
        if ($type === 'favorite') {
            $where['is_favorite'] = 1;
            $is_favorite = 1;
        }
        $sort = (request('sort')) ? request('sort') : "id";
        $order = 'desc';
        if ($sort == 'newest') {
            $sort = 'created_at';
            $order = 'desc';
        } elseif ($sort == 'oldest') {
            $sort = 'created_at';
            $order = 'asc';
        } elseif ($sort == 'recently-updated') {
            $sort = 'updated_at';
            $order = 'desc';
        } elseif ($sort == 'earliest-updated') {
            $sort = 'updated_at';
            $order = 'asc';
        }
        $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects() : $this->user->projects();
        $projects->where($where);
        if (!empty($selectedTags)) {
            $projects->whereHas('tags', function ($q) use ($selectedTags) {
                $q->whereIn('tags.id', $selectedTags);
            });
        }
        $toSelectProjectUsers = $this->workspace->users;
        $toSelectProjectClients = $this->workspace->clients;
        $projects = $projects->orderBy($sort, $order)->paginate(6);
       // return $statusId;
        return view('projects.grid_view', ['projects' => $projects, 'auth_user' => $this->user, 'toSelectProjectUsers' => $toSelectProjectUsers, 'toSelectProjectClients' => $toSelectProjectClients, 'selectedTags' => $selectedTags, 'is_favorite' => $is_favorite]);
    }
       public function notStarted(Request $request, $type = null)
    {
        $status = 'notStarted';
    $selectedTags = (request('tags')) ? request('tags') : [];
    $where = [];
    if ($status != '') {
        // Assuming you have a 'statuses' table with a 'name' column
        $statusId = Status::where('title', $status)->value('id');
        if ($statusId != null) {
            $where['status_id'] = $statusId;
        }
    }
        $is_favorite = 0;
        if ($type === 'favorite') {
            $where['is_favorite'] = 1;
            $is_favorite = 1;
        }
        $sort = (request('sort')) ? request('sort') : "id";
        $order = 'desc';
        if ($sort == 'newest') {
            $sort = 'created_at';
            $order = 'desc';
        } elseif ($sort == 'oldest') {
            $sort = 'created_at';
            $order = 'asc';
        } elseif ($sort == 'recently-updated') {
            $sort = 'updated_at';
            $order = 'desc';
        } elseif ($sort == 'earliest-updated') {
            $sort = 'updated_at';
            $order = 'asc';
        }
        $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects() : $this->user->projects();
        $projects->where($where);
        if (!empty($selectedTags)) {
            $projects->whereHas('tags', function ($q) use ($selectedTags) {
                $q->whereIn('tags.id', $selectedTags);
            });
        }
        $toSelectProjectUsers = $this->workspace->users;
        $toSelectProjectClients = $this->workspace->clients;
        $projects = $projects->orderBy($sort, $order)->paginate(6);
       // return $statusId;
        return view('projects.grid_view', ['projects' => $projects, 'auth_user' => $this->user, 'toSelectProjectUsers' => $toSelectProjectUsers, 'toSelectProjectClients' => $toSelectProjectClients, 'selectedTags' => $selectedTags, 'is_favorite' => $is_favorite]);
    }
    public function list_view_cancelled(Request $request, $type = null)
    {
        $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects : $this->user->projects;
        $users = $this->workspace->users;
        $clients = $this->workspace->clients;

        $toSelectProjectUsers = $this->workspace->users;
        $toSelectProjectClients = $this->workspace->clients;

        $is_favorites = 0;
        if ($type === 'favorite') {
            $is_favorites = 1;
        }
      
        return view('projects.cancelled_projects', ['projects' => $projects, 'users' => $users, 'clients' => $clients, 'toSelectProjectUsers' => $toSelectProjectUsers, 'toSelectProjectClients' => $toSelectProjectClients, 'is_favorites' => $is_favorites]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = $this->workspace->users;
        $clients = $this->workspace->clients;

        return view('projects.create_project', ['users' => $users, 'clients' => $clients, 'auth_user' => $this->user]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  /*  public function store(Request $request)
    {
        try {
            $formFields = $request->validate([
                'title' => ['required'],
                'status_id' => ['required'],
                'priority_id' => ['nullable'],
                'start_date' => ['required', 'date', 'before_or_equal:end_date'],
                'end_date' => ['required', 'date'],
                'budget' => ['nullable', 'regex:/^\d+(\.\d+)?$/'],
                'task_accessibility' => ['required'],
                'description' => ['required'],
            ]);
    
            // Format dates
            $formFields['start_date'] = format_date($request->input('start_date'), false, app('php_date_format'), 'Y-m-d');
            $formFields['end_date'] = format_date($request->input('end_date'), false, app('php_date_format'), 'Y-m-d');
    
            // Additional fields
            $formFields['workspace_id'] = $this->workspace->id;
            $formFields['created_by'] = $this->user->id;
    
            // Create the project
            $new_project = Project::create($formFields);
    
            // Retrieve user, client, and tag IDs
            $userIds = $request->input('user_id', []);
            $clientIds = $request->input('client_id', []);
            $tagIds = $request->input('tag_ids', []);
    
            // Set creator as a participant automatically
            if (Auth::guard('client')->check() && !in_array($this->user->id, $clientIds)) {
                array_unshift($clientIds, $this->user->id);
            } elseif (Auth::guard('web')->check() && !in_array($this->user->id, $userIds)) {
                array_unshift($userIds, $this->user->id);
            }
    
            // Attach users, clients, and tags
            $new_project->users()->attach($userIds);
            $new_project->clients()->attach($clientIds);
            $new_project->tags()->attach($tagIds);
    
            // Create a new MasterSchedule entry
            MasterSchedule::create([
                'text' => $request->title,  
                'start_date' => $formFields['start_date'], // Use formatted start date
                'duration' => 110, // Set duration as required
                'progress' => 0, // Initially set progress to 0
                'type' => 'task', // Set type as 'activity'
                'parent' => 0 // Link to the parent task
            ]);
    
            // Prepare and send notifications
            $notification_data = [
                'type' => 'project',
                'type_id' => $new_project->id,
                'type_title' => $new_project->title,
                'access_url' => 'projects/information/' . $new_project->id,
                'action' => 'assigned',
                'title' => 'New project assigned',
                'message' => "{$this->user->first_name} {$this->user->last_name} assigned you new project: {$new_project->title}, ID #{$new_project->id}."
            ];
    
            $recipients = array_merge(
                array_map(fn($userId) => 'u_' . $userId, $userIds),
                array_map(fn($clientId) => 'c_' . $clientIds, $clientIds)
            );
    
          //  processNotifications($notification_data, $recipients);
    
            return response()->json(['error' => false, 'id' => $new_project->id, 'message' => 'Project created successfully.']);
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Project creation failed: ' . $e->getMessage());
            
            return response()->json(['error' => true, 'message' => 'An error occurred while creating the project. Please try again.'], 500);
        }
    }*/
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'title' => ['required'],
            'status_id' => ['required'],
            'priority_id' => ['nullable'],
            'start_date' => ['required', 'before_or_equal:end_date'],
            'end_date' => ['required'],
            'budget' => ['nullable', 'regex:/^\d+(\.\d+)?$/'],
            'task_accessibility' => ['required'],
            'description' => ['required'],
            'site_id' => ['nullable'],
        ]);

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $formFields['start_date'] = format_date($start_date, false, app('php_date_format'), 'Y-m-d');
        $formFields['end_date'] = format_date($end_date, false, app('php_date_format'), 'Y-m-d');

        $formFields['workspace_id'] = $this->workspace->id;
        $formFields['created_by'] = $this->user->id;


        $new_project = Project::create($formFields);

        $userIds = $request->input('user_id') ?? [];
        $clientIds = $request->input('client_id') ?? [];
        $tagIds = $request->input('tag_ids') ?? [];
        // Set creator as a participant automatically
        if (Auth::guard('client')->check() && !in_array($this->user->id, $clientIds)) {
            array_splice($clientIds, 0, 0, $this->user->id);
        } else if (Auth::guard('web')->check() && !in_array($this->user->id, $userIds)) {
            array_splice($userIds, 0, 0, $this->user->id);
        }

        $project_id = $new_project->id;
        $project = Project::find($project_id);
        $project->users()->attach($userIds);
        $project->clients()->attach($clientIds);
        $project->tags()->attach($tagIds);
        MasterSchedule::create([
            'text' => $request->title,  
            'start_date' => $formFields['start_date'], // Use formatted start date
            'duration' => 110, // Set duration as required
            'progress' => 0, // Initially set progress to 0
            'type' => 'project', // Set type as 'activity'
            'parent' => 0, // Link to the parent task
            'project_or_task_id' => $project_id
        ]);
        $notification_data = [
            'type' => 'project',
            'type_id' => $project_id,
            'type_title' => $project->title,
            'access_url' => 'projects/information/' . $project_id,
            'action' => 'assigned',
            'title' => 'New project assigned',
            'message' => $this->user->first_name . ' ' . $this->user->last_name . ' assigned you new project : ' . $project->title . ', ID #' . $project_id . '.'
        ];
        $recipients = array_merge(
            array_map(function ($userId) {
                return 'u_' . $userId;
            }, $userIds),
            array_map(function ($clientId) {
                return 'c_' . $clientId;
            }, $clientIds)
        );
        processNotifications($notification_data, $recipients);
        return response()->json(['error' => false, 'id' => $new_project->id, 'message' => 'Project created successfully.']);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $units=   UnitMeasure::all();
        $project = Project::findOrFail($id);
        $projectTags = $project->tags;
        $users = $this->workspace->users;
        $clients = $this->workspace->clients;
        $types = getControllerNames();
        $toSelectTaskUsers = $project->users;
        $toSelectProjectUsers = $this->workspace->users;
        $toSelectProjectClients = $this->workspace->clients;
        return view('projects.project_information', ['project' => $project,'units'=>$units,'sites'=>$project, 'projectTags' => $projectTags, 'users' => $users, 'clients' => $clients, 'types' => $types, 'auth_user' => $this->user, 'toSelectTaskUsers' => $toSelectTaskUsers, 'toSelectProjectUsers' => $toSelectProjectUsers, 'toSelectProjectClients' => $toSelectProjectClients]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $users = $this->workspace->users;
        $clients = $this->workspace->clients;
        return view('projects.update_project', ["project" => $project, "users" => $users, "clients" => $clients]);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function get($projectId)
    {
        $project = Project::findOrFail($projectId);
        $users = $project->users()->get();
        $clients = $project->clients()->get();
        $tags = $project->tags()->get();

        $workspace_users = $this->workspace->users;
        $workspace_clients = $this->workspace->clients;

        return response()->json(['error' => false, 'project' => $project, 'users' => $users, 'clients' => $clients, 'workspace_users' => $workspace_users, 'workspace_clients' => $workspace_clients, 'tags' => $tags]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function update(Request $request)
    {
        $formFields = $request->validate([
            'id' => 'required|exists:projects,id',
            'title' => ['required'],
            'status_id' => ['required'],
            'priority_id' => ['nullable'],
            'budget' => ['nullable', 'regex:/^\d+(\.\d+)?$/'],
            'start_date' => ['required', 'before_or_equal:end_date'],
            'end_date' => ['required'],
            'task_accessibility' => ['required'],
            'description' => ['required'],
        ]);
        $id = $request->input('id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $formFields['start_date'] = format_date($start_date, false, app('php_date_format'), 'Y-m-d');
        $formFields['end_date'] = format_date($end_date, false, app('php_date_format'), 'Y-m-d');

        $userIds = $request->input('user_id') ?? [];
        $clientIds = $request->input('client_id') ?? [];
        $tagIds = $request->input('tag_ids') ?? [];

        // Set creator as a participant automatically
        if (Auth::guard('client')->check() && !in_array($this->user->id, $clientIds)) {
            array_splice($clientIds, 0, 0, $this->user->id);
        } else if (Auth::guard('web')->check() && !in_array($this->user->id, $userIds)) {
            array_splice($userIds, 0, 0, $this->user->id);
        }

        $project = Project::findOrFail($id);

        // Get current list of users and clients associated with the project
        $existingUserIds = $project->users->pluck('id')->toArray();
        $existingClientIds = $project->clients->pluck('id')->toArray();


        // Update project and its relationships
        $project->update($formFields);
        $project->users()->sync($userIds);
        $project->clients()->sync($clientIds);
        $project->tags()->sync($tagIds);

        // Exclude old users and clients from receiving notification
        $userIds = array_diff($userIds, $existingUserIds);
        $clientIds = array_diff($clientIds, $existingClientIds);

        // Prepare notification data
        $notificationData = [
            'type' => 'project',
            'type_id' => $project->id,
            'type_title' => $project->title,
            'access_url' => 'projects/information/' . $project->id,
            'action' => 'assigned',
            'title' => 'New project assigned',
            'message' => $this->user->first_name . ' ' . $this->user->last_name . ' assigned you new project : ' . $project->title . ', ID #' . $project->id . '.'
        ];

        // Determine recipients
        $recipients = array_merge(
            array_map(function ($userId) {
                return 'u_' . $userId;
            }, $userIds),
            array_map(function ($clientId) {
                return 'c_' . $clientId;
            }, $clientIds)
        );

        // Process notifications
        processNotifications($notificationData, $recipients);
        return response()->json(['error' => false, 'id' => $id, 'message' => 'Project updated successfully.']);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $response = DeletionService::delete(Project::class, $id, 'Project');
        return $response;
    }

    public function destroy_multiple(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'ids' => 'required|array', // Ensure 'ids' is present and an array
            'ids.*' => 'integer|exists:projects,id' // Ensure each ID in 'ids' is an integer and exists in the 'projects' table
        ]);

        $ids = $validatedData['ids'];
        $deletedProjects = [];
        $deletedProjectTitles = [];
        // Perform deletion using validated IDs
        foreach ($ids as $id) {
            $project = Project::find($id);
            if ($project) {
                $deletedProjectTitles[] = $project->title;
                DeletionService::delete(Project::class, $id, 'Project');
                $deletedProjects[] = $id;
            }
        }

        return response()->json(['error' => false, 'message' => 'Project(s) deleted successfully.', 'id' => $deletedProjects, 'titles' => $deletedProjectTitles]);
    }



    public function list(Request $request, $id = '', $type = '')
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $status = isset($_REQUEST['status']) && $_REQUEST['status'] !== '' ? $_REQUEST['status'] : "";
        $user_id = (request('user_id')) ? request('user_id') : "";
        $client_id = (request('client_id')) ? request('client_id') : "";
        $start_date_from = (request('project_start_date_from')) ? request('project_start_date_from') : "";
        $start_date_to = (request('project_start_date_to')) ? request('project_start_date_to') : "";
        $end_date_from = (request('project_end_date_from')) ? request('project_end_date_from') : "";
        $end_date_to = (request('project_end_date_to')) ? request('project_end_date_to') : "";
        $is_favorites = (request('is_favorites')) ? request('is_favorites') : "";
        $where = [];
        if ($status != '') {
            $where['status_id'] = $status;
        }

        if ($is_favorites) {
            $where['is_favorite'] = 1;
        }

        if ($id) {
            $id = explode('_', $id);
            $belongs_to = $id[0];
            $belongs_to_id = $id[1];
            if ($belongs_to == 'user') {
                $belongs_to = User::find($belongs_to_id);
            }
            if ($belongs_to == 'client') {
                $belongs_to = Client::find($belongs_to_id);
            }
            $projects = $belongs_to->projects();
        } else {
            $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects() : $this->user->projects();
        }
        if ($user_id) {
            $user = User::find($user_id);
            $projects = $user->projects();
        }
        if ($client_id) {
            $client = Client::find($client_id);
            $projects = $client->projects();
        }
        if ($start_date_from && $start_date_to) {
            $projects->whereBetween('start_date', [$start_date_from, $start_date_to]);
        }
        if ($end_date_from && $end_date_to) {
            $projects->whereBetween('end_date', [$end_date_from, $end_date_to]);
        }
        $projects->when($search, function ($query) use ($search) {
            return $query->where('title', 'like', '%' . $search . '%');
        });
        $projects->where($where);
        $totalprojects = $projects->count();
        $statuses = Status::all();
        $priorities = Priority::all();

        $projects = $projects->orderBy($sort, $order)
            ->paginate(request("limit"))
            ->through(
                function ($project) use ($statuses, $priorities) {
                    $statusOptions = '';
                    foreach ($statuses as $status) {
                        $selected = $project->status_id == $status->id ? 'selected' : '';
                        $statusOptions .= "<option value='{$status->id}' class='badge bg-label-$status->color' $selected>$status->title</option>";
                    }

                    $priorityOptions = "";
                    foreach ($priorities as $priority) {
                        $selected = $project->priority_id == $priority->id ? 'selected' : '';
                        $priorityOptions .= "<option value='{$priority->id}' class='badge bg-label-$priority->color' $selected>$priority->title</option>";
                    }
                    $priority = $project->priority; // Ensure that the relationship is defined in the model
                    return [
                        'id' => $project->id,
                        'title' => "<a href='/projects/information/{$project->id}' target='_blank' title='{$project->description}'><strong>{$project->title}</strong></a> <a href='javascript:void(0);' class='mx-2'><i class='bx " . ($project->is_favorite ? 'bxs' : 'bx') . "-star favorite-icon text-warning' data-favorite='{$project->is_favorite}' data-id='{$project->id}' title='" . ($project->is_favorite ? get_label('remove_favorite', 'Click to remove from favorite') : get_label('add_favorite', 'Click to mark as favorite')) . "'></i></a>",
                        'users' => $project->users,
                        'clients' => $project->clients,
                        'start_date' => format_date($project->start_date),
                        'end_date' => format_date($project->end_date),
                        'budget' => !empty($project->budget) && $project->budget !== null ? format_currency($project->budget) : '-',
                        'status_id' => "<span class='badge bg-label-{$project->status->color}'>{$project->status->title}</span>",
                        // Adjusted progress based on your conditions
                        'status' => "<select class='form-select form-select-sm' id='statusSelect' data-id='{$project->id}' data-original-status-id='{$project->status->id}'>{$statusOptions}</select>",
                        'progress' => 25,
                        'priority_id' => $priority ? "<span class='badge bg-label-{$priority->color}'>{$priority->title}</span>" : "<span class='badge bg-label-secondary'>No Priority</span>",
                        'task_accessibility' => get_label($project->task_accessibility, ucwords(str_replace("_", " ", $project->task_accessibility))),
                        'created_at' => format_date($project->created_at, true),
                        'updated_at' => format_date($project->updated_at, true),
                    ];
                }
            );

        foreach ($projects->items() as $project => $collection) {
            foreach ($collection['clients'] as $i => $client) {
                $collection['clients'][$i] = "<a href='/clients/profile/" . $client->id . "' target='_blank'><li class='avatar avatar-sm pull-up'  title='" . $client['first_name'] . " " . $client['last_name'] . "'>
                <img src='" . ($client['photo'] ? asset('storage/' . $client['photo']) : asset('storage/photos/no-image.jpg')) . "' alt='Avatar' class='rounded-circle' />
                </li></a>";
            };
        }

        foreach ($projects->items() as $project => $collection) {
            foreach ($collection['users'] as $i => $user) {
                $collection['users'][$i] = "<a href='/users/profile/" . $user->id . "' target='_blank'><li class='avatar avatar-sm pull-up'  title='" . $user['first_name'] . " " . $user['last_name'] . "'>
                <img src='" . ($user['photo'] ? asset('storage/' . $user['photo']) : asset('storage/photos/no-image.jpg')) . "' class='rounded-circle' />
                </li></a>";
            };
        }

        return response()->json([
            "rows" => $projects->items(),
            "total" => $totalprojects,
        ]);
    }

    public function update_favorite(Request $request, $id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['error' => true, 'message' => 'Project not found']);
        }

        $isFavorite = $request->input('is_favorite');

        // Update the project's favorite status
        $project->is_favorite = $isFavorite;
        $project->save();
        return response()->json(['error' => false]);
    }

    public function duplicate($id)
    {
        // Define the related tables for this meeting
        $relatedTables = ['users', 'clients', 'tasks', 'tags']; // Include related tables as needed

        // Use the general duplicateRecord function
        $duplicate = duplicateRecord(Project::class, $id, $relatedTables);

        if (!$duplicate) {
            return response()->json(['error' => true, 'message' => 'Project duplication failed.']);
        }

        if (request()->has('reload') && request()->input('reload') === 'true') {
            Session::flash('message', 'Project duplicated successfully.');
        }
        return response()->json(['error' => false, 'message' => 'Project duplicated successfully.', 'id' => $id]);
    }

    public function upload_media(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id' => 'integer|exists:projects,id'
            ]);

            $mediaIds = [];

            if ($request->hasFile('media_files')) {
                $project = Project::find($validatedData['id']);
                $mediaFiles = $request->file('media_files');

                foreach ($mediaFiles as $mediaFile) {
                    $mediaItem = $project->addMedia($mediaFile)
                        ->sanitizingFileName(function ($fileName) use ($project) {
                            // Replace special characters and spaces with hyphens
                            $sanitizedFileName = strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));

                            // Generate a unique identifier based on timestamp and random component
                            $uniqueId = time() . '_' . mt_rand(1000, 9999);

                            $extension = pathinfo($sanitizedFileName, PATHINFO_EXTENSION);
                            $baseName = pathinfo($sanitizedFileName, PATHINFO_FILENAME);

                            return "{$baseName}-{$uniqueId}.{$extension}";
                        })
                        ->toMediaCollection('project-media');

                    $mediaIds[] = $mediaItem->id;
                }


                Session::flash('message', 'File(s) uploaded successfully.');
                return response()->json(['error' => false, 'message' => 'File(s) uploaded successfully.', 'id' => $mediaIds, 'type' => 'media', 'parent_type' => 'project']);
            } else {
                Session::flash('error', 'No file(s) chosen.');
                return response()->json(['error' => true, 'message' => 'No file(s) chosen.']);
            }
        } catch (Exception $e) {
            // Handle the exception as needed
            Session::flash('error', 'An error occurred during file upload: ' . $e->getMessage());
            return response()->json(['error' => true, 'message' => 'An error occurred during file upload: ' . $e->getMessage()]);
        }
    }





    public function get_media($id)
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $project = Project::findOrFail($id);
        $media = $project->getMedia('project-media');

        if ($search) {
            $media = $media->filter(function ($mediaItem) use ($search) {
                return (
                    // Check if ID contains the search query
                    stripos($mediaItem->id, $search) !== false ||
                    // Check if file name contains the search query
                    stripos($mediaItem->file_name, $search) !== false ||
                    // Check if date created contains the search query
                    stripos($mediaItem->created_at->format('Y-m-d'), $search) !== false
                );
            });
        }
        $formattedMedia = $media->map(function ($mediaItem) {
            // Check if the disk is public
            $isPublicDisk = $mediaItem->disk == 'public' ? 1 : 0;

            // Generate file URL based on disk visibility
            $fileUrl = $isPublicDisk
                ? asset('storage/project-media/' . $mediaItem->file_name)
                : $mediaItem->getFullUrl();

            $fileExtension = pathinfo($fileUrl, PATHINFO_EXTENSION);

            // Check if file extension corresponds to an image type
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
            $isImage = in_array(strtolower($fileExtension), $imageExtensions);

            if ($isImage) {
                $html = '<a href="' . $fileUrl . '" data-lightbox="project-media">';
                $html .= '<img src="' . $fileUrl . '" alt="' . $mediaItem->file_name . '" width="50">';
                $html .= '</a>';
            } else {
                $html = '<a href="' . $fileUrl . '" title=' . get_label('download', 'Download') . '>' . $mediaItem->file_name . '</a>';
            }

            return [
                'id' => $mediaItem->id,
                'file' => $html,
                'file_name' => $mediaItem->file_name,
                'file_size' => formatSize($mediaItem->size),
                'created_at' => format_date($mediaItem->created_at, true),
                'updated_at' => format_date($mediaItem->updated_at, true),
                'actions' => [
                    '<a href="' . $fileUrl . '" title=' . get_label('download', 'Download') . ' download>' .
                        '<i class="bx bx-download bx-sm"></i>' .
                        '</a>' .
                        '<button title=' . get_label('delete', 'Delete') . ' type="button" class="btn delete" data-id="' . $mediaItem->id . '" data-type="project-media">' .
                        '<i class="bx bx-trash text-danger"></i>' .
                        '</button>'
                ],
            ];
        });


        if ($order == 'asc') {
            $formattedMedia = $formattedMedia->sortBy($sort);
        } else {
            $formattedMedia = $formattedMedia->sortByDesc($sort);
        }

        return response()->json([
            'rows' => $formattedMedia->values()->toArray(),
            'total' => $formattedMedia->count(),
        ]);
    }

    public function delete_media($mediaId)
    {
        $mediaItem = Media::find($mediaId);

        if (!$mediaItem) {
            // Handle case where media item is not found
            return response()->json(['error' => true, 'message' => 'File not found.']);
        }

        // Delete media item from the database and disk
        $mediaItem->delete();

        return response()->json(['error' => false, 'message' => 'File deleted successfully.', 'id' => $mediaId, 'title' => $mediaItem->file_name, 'parent_id' => $mediaItem->model_id,  'type' => 'media', 'parent_type' => 'project']);
    }

    public function delete_multiple_media(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'ids' => 'required|array', // Ensure 'ids' is present and an array
            'ids.*' => 'integer|exists:media,id' // Ensure each ID in 'ids' is an integer and exists in the table
        ]);

        $ids = $validatedData['ids'];
        $deletedIds = [];
        $deletedTitles = [];
        $parentIds = [];
        // Perform deletion using validated IDs
        foreach ($ids as $id) {
            $media = Media::find($id);
            if ($media) {
                $deletedIds[] = $id;
                $deletedTitles[] = $media->file_name;
                $parentIds[] = $media->model_id;
                $media->delete();
            }
        }

        return response()->json(['error' => false, 'message' => 'Files(s) deleted successfully.', 'id' => $deletedIds, 'titles' => $deletedTitles, 'parent_id' => $parentIds, 'type' => 'media', 'parent_type' => 'project']);
    }

    public function store_milestone(Request $request)
    {
        $formFields = $request->validate([
            'project_id' => ['required'],
            'title' => ['required'],
            'status' => ['required'],
            'start_date' => ['required', 'before_or_equal:end_date'],
            'end_date' => ['required'],
            'cost' => ['required', 'regex:/^\d+(\.\d+)?$/'],
            'description' => ['nullable'],
        ]);

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $formFields['start_date'] = format_date($start_date, false, app('php_date_format'), 'Y-m-d');
        $formFields['end_date'] = format_date($end_date, false, app('php_date_format'), 'Y-m-d');

        $formFields['workspace_id'] = $this->workspace->id;
        $formFields['created_by'] = isClient() ? 'c_' . $this->user->id : 'u_' . $this->user->id;


        $milestone = Milestone::create($formFields);

        return response()->json(['error' => false, 'message' => 'Milestone created successfully.', 'id' => $milestone->id, 'type' => 'milestone', 'parent_type' => 'project']);
    }

    public function get_milestones($id)
    {
        $project = Project::findOrFail($id);
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $status = isset($_REQUEST['status']) && $_REQUEST['status'] !== '' ? $_REQUEST['status'] : "";
        $start_date_from = (request('start_date_from')) ? request('start_date_from') : "";
        $start_date_to = (request('start_date_to')) ? request('start_date_to') : "";
        $end_date_from = (request('end_date_from')) ? request('end_date_from') : "";
        $end_date_to = (request('end_date_to')) ? request('end_date_to') : "";
        $milestones =  $project->milestones();
        if ($search) {
            $milestones = $milestones->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%')
                    ->orWhere('cost', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }
        if ($start_date_from && $start_date_to) {
            $milestones = $milestones->whereBetween('start_date', [$start_date_from, $start_date_to]);
        }
        if ($end_date_from && $end_date_to) {
            $milestones  = $milestones->whereBetween('to_date', [$end_date_from, $end_date_to]);
        }
        if ($status) {
            $milestones  = $milestones->where('status', $status);
        }
        $total = $milestones->count();
        $milestones = $milestones->orderBy($sort, $order)


            ->paginate(request("limit"))
            ->through(function ($milestone) {
                if (strpos($milestone->created_by, 'u_') === 0) {
                    // The ID corresponds to a user
                    $creator = User::find(substr($milestone->created_by, 2)); // Remove the 'u_' prefix
                } elseif (strpos($milestone->created_by, 'c_') === 0) {
                    // The ID corresponds to a client
                    $creator = Client::find(substr($milestone->created_by, 2)); // Remove the 'c_' prefix                    
                }
                if ($creator !== null) {
                    $creator = $creator->first_name . ' ' . $creator->last_name;
                } else {
                    $creator = '-';
                }

                $statusBadge = '';

                if ($milestone->status == 'incomplete') {
                    $statusBadge = '<span class="badge bg-danger">' . get_label('incomplete', 'Incomplete') . '</span>';
                } elseif ($milestone->status == 'complete') {
                    $statusBadge = '<span class="badge bg-success">' . get_label('complete', 'Complete') . '</span>';
                }
                $progress = '<div class="demo-vertical-spacing">
                <div class="progress">
                  <div class="progress-bar" role="progressbar" style="width: ' . $milestone->progress . '%" aria-valuenow="' . $milestone->progress . '" aria-valuemin="0" aria-valuemax="100">
                    
                  </div>
                </div>
              </div> <h6 class="mt-2">' . $milestone->progress . '%</h6>';

                return [
                    'id' => $milestone->id,
                    'title' => $milestone->title,
                    'status' => $statusBadge,
                    'progress' => $progress,
                    'cost' => format_currency($milestone->cost),
                    'start_date' => format_date($milestone->start_date),
                    'end_date' => format_date($milestone->end_date),
                    'created_by' => $creator,
                    'description' => $milestone->description,
                    'created_at' => format_date($milestone->created_at, true),
                    'updated_at' => format_date($milestone->updated_at, true),
                ];
            });



        return response()->json([
            "rows" => $milestones->items(),
            "total" => $total,
        ]);
    }

    public function get_milestone($id)
    {
        $ms = Milestone::findOrFail($id);
        return response()->json(['ms' => $ms]);
    }

    public function update_milestone(Request $request)
    {
        $formFields = $request->validate([
            'title' => ['required'],
            'status' => ['required'],
            'start_date' => ['required', 'before_or_equal:end_date'],
            'end_date' => ['required'],
            'cost' => ['required', 'regex:/^\d+(\.\d+)?$/'],
            'progress' => ['required'],
            'description' => ['nullable'],
        ]);

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $formFields['start_date'] = format_date($start_date, false, app('php_date_format'), 'Y-m-d');
        $formFields['end_date'] = format_date($end_date, false, app('php_date_format'), 'Y-m-d');

        $ms = Milestone::findOrFail($request->id);

        if ($ms->update($formFields)) {
            return response()->json(['error' => false, 'message' => 'Milestone updated successfully.', 'id' => $ms->id, 'type' => 'milestone', 'parent_type' => 'project']);
        } else {
            return response()->json(['error' => true, 'message' => 'Milestone couldn\'t updated.']);
        }
    }
    public function delete_milestone($id)
    {
        $ms = Milestone::findOrFail($id);
        DeletionService::delete(Milestone::class, $id, 'Milestone');
        return response()->json(['error' => false, 'message' => 'Milestone deleted successfully.', 'id' => $id, 'title' => $ms->title, 'type' => 'milestone']);
    }
    public function delete_multiple_milestones(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'ids' => 'required|array', // Ensure 'ids' is present and an array
            'ids.*' => 'integer|exists:milestones,id' // Ensure each ID in 'ids' is an integer and exists in the table
        ]);

        $ids = $validatedData['ids'];
        $deletedIds = [];
        $deletedTitles = [];
        // Perform deletion using validated IDs
        foreach ($ids as $id) {
            $ms = Milestone::findOrFail($id);
            $deletedIds[] = $id;
            $deletedTitles[] = $ms->title;
            DeletionService::delete(Milestone::class, $id, 'Milestone');
        }

        return response()->json(['error' => false, 'message' => 'Milestone(s) deleted successfully.', 'id' => $deletedIds, 'titles' => $deletedTitles, 'type' => 'milestone']);
    }

    public function update_status(Request $request)
    {
        $request->validate([
            'id' => ['required'],
            'statusId' => ['required']

        ]);
        $id = $request->id;
        $statusId = $request->statusId;
        $project = Project::findOrFail($id);
        $currentStatus = $project->status->title;
        $project->status_id = $statusId;
        $project->note = $request->note;
        if ($project->save()) {
            // Reload the project to get updated status information
            $project = $project->fresh();
            $newStatus = $project->status->title;
            return response()->json(['error' => false, 'message' => 'Status updated successfully.', 'id' => $id, 'type' => 'project', 'activity_message' => $this->user->first_name . ' ' . $this->user->last_name . ' updated project status from ' . $currentStatus . ' to ' . $newStatus]);
        } else {
            return response()->json(['error' => true, 'message' => 'Status couldn\'t updated.']);
        }
    }

    public function update_priority(Request $request)
    {
        $request->validate([
            'id' => ['required'],
            'priorityId' => ['nullable']

        ]);
        $id = $request->id;
        $priorityId = $request->priorityId;
        $project = Project::findOrFail($id);
        $currentPriority = $project->priority ? $project->priority->title : 'Default';
        $project->priority_id = $priorityId;
        $project->note = $request->note;
        if ($project->save()) {
            // Reload the project to get updated priority information
            $project = $project->fresh();
            $newPriority = $project->priority ? $project->priority->title : 'Default';
            $message = $this->user->first_name . ' ' . $this->user->last_name . ' updated project priority from ' . $currentPriority . ' to ' . $newPriority;            
            return response()->json(['error' => false, 'message' => 'Priority updated successfully.', 'id' => $id, 'type' => 'project', 'activity_message' => $message]);
        } else {
            return response()->json(['error' => true, 'message' => 'Priority couldn\'t updated.']);
        }
    }
    /*
    public function generateReport(Request $request)
    {
        $format = $request->get('format');
        $status = $request->get('status');

        // Query to get tasks based on status
        $tasksQuery = Project::query();

        if ($status) {
            $tasksQuery->where('status', $status);
        }

        $tasks = $tasksQuery->get(); // Retrieve tasks

        if ($format === 'pdf') {
            // Generate PDF report
            $pdf = PDF::loadView('reports.tasks', compact('tasks'));
            return $pdf->download('tasks_report.pdf');
        } elseif ($format === 'excel') {
            // Generate Excel report
            return Excel::download(new ProjectsExport($projects), 'projects.xlsx');
        }

        return redirect()->back()->with('error', 'Invalid report format');
    }*/
    public function exportPdf(Request $request)
    {
        // Fetch filters from the request
        $statusFilter = $request->input('status');
        $priorityFilter = $request->input('priority');
        $startDateFilter = $request->input('start_date');
        $endDateFilter = $request->input('end_date');
    
        // Start building the query
        $projectsQuery = Project::with(['site', 'status', 'priority']);
    
        // Apply filters
        if ($statusFilter) {
            $projectsQuery->where('status_id', $statusFilter);
        }
        if ($priorityFilter) {
            $projectsQuery->where('priority_id', $priorityFilter);
        }
        if ($startDateFilter) {
            $projectsQuery->where('start_date', '>=', $startDateFilter);
        }
        if ($endDateFilter) {
            $projectsQuery->where('end_date', '<=', $endDateFilter);
        }
    
        // Execute the query and get the results
        $projectsData = $projectsQuery->get(); // Fetch filtered data
    
        // Get general settings
        $general_settings = get_settings('general_settings');
        $logoPath = public_path('storage/' . ($general_settings['full_logo'] ?? 'logos/default_full_logo.png'));
        $logoData = base64_encode(file_get_contents($logoPath));
        $logoSrc = 'data:image/png;base64,' . $logoData;
        // Prepare data for the view
        $data = [
            'data' => $projectsData, // Use filtered data
            'company_title' => $general_settings['company_title'] ?? 'Raycon',
            'logo' => $logoSrc,
            'notes' => 'Your multiline<br>Additional notes<br>In regards to delivery or something else',
        ];
    
        // Load HTML view
        $html = view('reports.projects.pdf', $data)->render();
    
        // Instantiate Dompdf
        $pdf = new Dompdf(new Options(['defaultFont' => 'Arial']));
    
        // Load HTML content and set paper size
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
    
        // Render and output the PDF
        $pdf->render();
        return $pdf->stream('Projects_Report_' . now()->format('Y_m_d') . '.pdf');
    }
    public function Pdf(Request $request)
    {
        // Fetch filters from the request
        $statusFilter = $request->input('status');
        $priorityFilter = $request->input('priority');
        $startDateFilter = $request->input('start_date');
        $endDateFilter = $request->input('end_date');
    
        // Start building the query
        $projectsQuery = Project::query();
    
        // Apply filters
        if ($statusFilter) {
            $projectsQuery->where('status_id', $statusFilter);
        }
        if ($priorityFilter) {
            $projectsQuery->where('priority_id', $priorityFilter);
        }
        if ($startDateFilter) {
            $projectsQuery->where('start_date', '>=', $startDateFilter);
        }
        if ($endDateFilter) {
            $projectsQuery->where('end_date', '<=', $endDateFilter);
        }
    
        $projectsData = $projectsQuery->get();
    
        // Load the view and pass the data
        $pdf = PDF::loadView('reports.projects.pdf', compact('projectsData'));
    
        // Return PDF as a response
        return $pdf->download('projects_schedule.pdf');
    }
    
    public function exportCsv(Request $request)
    {
        // Fetch filters from the request
        $statusFilter = $request->input('status');
        $priorityFilter = $request->input('priority');
        $startDateFilter = $request->input('start_date');
        $endDateFilter = $request->input('end_date');
        
        // Start building the query
        $projectsQuery = Project::query();
        
        // Apply filters
        if ($statusFilter) {
            $projectsQuery->where('status_id', $statusFilter);
        }
        if ($priorityFilter) {
            $projectsQuery->where('priority_id', $priorityFilter);
        }
        if ($startDateFilter) {
            $projectsQuery->where('start_date', '>=', $startDateFilter);
        }
        if ($endDateFilter) {
            $projectsQuery->where('end_date', '<=', $endDateFilter);
        }
        
        $projectsData = $projectsQuery->get();
        
        // Create a CSV file
        $csvFileName = 'projects_schedule.csv';
        $handle = fopen('php://output', 'w');
        
        // Set headers for the CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $csvFileName . '"');
    
        // Add CSV column headers
        fputcsv($handle, [
            'ID',
            'WBS',
            'Title',
            'Site',
            'Priority',
            'Start Date',
            'End Date',
            'Duration',
            'Remaining',
            'Status',
            'Assigned To',
            'Created By',
            'Created Date'
        ]);
    
        // Add data rows
        foreach ($projectsData as $project) {
            fputcsv($handle, [
                $project->id,
                $project->wbs,
                $project->title,
                $project->site->name ?? '', // Assuming site relation gives name
                $project->priority->title ?? '',
                \Carbon\Carbon::parse($project->start_date)->format('Y-m-d'), // Format start date
                \Carbon\Carbon::parse($project->end_date)->format('Y-m-d'), // Format end date
                $project->duration,
                $project->remaining,
                $project->status->title ?? '',
                $project->assignedTo,
                $project->createdBy,
                \Carbon\Carbon::parse($project->created_at)->format('Y-m-d'), // Format created date
            ]);
        }
    
        fclose($handle);
        exit; // Ensure no further output is sent
    } 
}
