<?php

namespace App\Http\Controllers;

use PDO;
use App\Models\Task;
use App\Models\Subtask;
use App\Models\User;
use App\Models\Client;
use App\Models\Status;
use App\Models\Priority;
use App\Models\Project;
use App\Models\Workspace;
use App\Models\Activity;
use App\Models\MasterSchedule;
use App\Models\UnitMeasure;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Services\DeletionService;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Exception;

class TasksController extends Controller
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
 public function index($id = '')
{
    $taskData = [
        'Started' => 6,
        'Not Started' => 10,
        'Completed' => 15,
        'Canceled' => 20,
    ];
    
    $project = (object)[];
    $tasks = [];
    $toSelectTaskUsers = [];
    
    if ($id) {
        $project = Project::findOrFail($id);
        $tasks = $this->user->project_tasks($id);
        $toSelectTaskUsers = $project->users;
    } else {
        $tasks = $this->user->tasks();
        $toSelectTaskUsers = $this->workspace->users;
    }
    
    $tasksCount = $tasks->count();
    $users = $this->workspace->users;
    $clients = $this->workspace->clients;
    $projects = $this->user->projects;
    $units= UnitMeasure::all();
    // Completed projects
    $statusId = Status::where('title', 'completed')->value('id');
    if ($statusId !== null) {
        $completedProjects = $projects->where('status_id', $statusId);
    }

    // In-progress projects
    $statusId = Status::where('title', 'inProgress')->value('id');
    if ($statusId !== null) {
        $inProgressProjects = $projects->where('status_id', $statusId);
    }

    // Not started projects
    $statusId = Status::where('title', 'notStarted')->value('id');
    if ($statusId !== null) {
        $notStartedProjects = $projects->where('status_id', $statusId);
    }

    // Cancelled projects
    $statusId = Status::where('title', 'cancelled')->value('id');
    if ($statusId !== null) {
        $cancelledProjects = $projects->where('status_id', $statusId);
    }

    return view('tasks.tasks', [
        'project' => $project,
        'taskData' => $taskData,
        'tasks' => $tasksCount,
        'units' =>$units,
        'users' => $users,
        'clients' => $clients,
        'projects' => $projects,
        'toSelectTaskUsers' => $toSelectTaskUsers
    ]);
}
    //user tasks 
    public function userTask($id = '')
    {
        $taskData = [
            'Started' => 6,
            'Not Started' => 10,
            'Completed' => 15,
            'Canceled' => 20,
        ];
        
        $project = (object)[];
        $tasks = [];
        $toSelectTaskUsers = [];
        
        if ($id) {
            $project = Project::findOrFail($id);
            $tasks = $this->user->project_tasks($id);
            $toSelectTaskUsers = $project->users;
        } else {
            $tasks = $this->user->tasks();
            $toSelectTaskUsers = $this->workspace->users;
        }
        
        $tasksCount = $tasks->count();
        $users = $this->workspace->users;
        $clients = $this->workspace->clients;
        $projects = $this->user->projects;
    
        // Completed projects
        $statusId = Status::where('title', 'completed')->value('id');
        if ($statusId !== null) {
            $completedProjects = $projects->where('status_id', $statusId);
        }
    
        // In-progress projects
        $statusId = Status::where('title', 'inProgress')->value('id');
        if ($statusId !== null) {
            $inProgressProjects = $projects->where('status_id', $statusId);
        }
    
        // Not started projects
        $statusId = Status::where('title', 'notStarted')->value('id');
        if ($statusId !== null) {
            $notStartedProjects = $projects->where('status_id', $statusId);
        }
    
        // Cancelled projects
        $statusId = Status::where('title', 'cancelled')->value('id');
        if ($statusId !== null) {
            $cancelledProjects = $projects->where('status_id', $statusId);
        }
    
        return view('tasks.user.task', [
            'project' => $project,
            'taskData' => $taskData,
            'tasks' => $tasksCount,
            'users' => $users,
            'clients' => $clients,
            'projects' => $projects,
            'toSelectTaskUsers' => $toSelectTaskUsers
        ]);
    }
    //userActivitylist
    public function userActivity($id = '')
    {
        $activity = Activity::where('id', $id)->first();
        $taskData = [
            'Started' => 6,
            'Not Started' => 10,
            'Completed' => 15,
            'Canceled' => 20,
        ];
        
        $project = (object)[];
        $tasks = [];
        $toSelectTaskUsers = [];
        
        if ($id) {
            $project = Project::findOrFail($id);
            $tasks = $this->user->project_tasks($id);
            $toSelectTaskUsers = $project->users;
        } else {
            $tasks = $this->user->tasks();
            $toSelectTaskUsers = $this->workspace->users;
        }
        
        $tasksCount = $tasks->count();
        $users = $this->workspace->users;
        $clients = $this->workspace->clients;
        $projects = $this->user->projects;
    
        // Completed projects
        $statusId = Status::where('title', 'completed')->value('id');
        if ($statusId !== null) {
            $completedProjects = $projects->where('status_id', $statusId);
        }
    
        // In-progress projects
        $statusId = Status::where('title', 'inProgress')->value('id');
        if ($statusId !== null) {
            $inProgressProjects = $projects->where('status_id', $statusId);
        }
    
        // Not started projects
        $statusId = Status::where('title', 'notStarted')->value('id');
        if ($statusId !== null) {
            $notStartedProjects = $projects->where('status_id', $statusId);
        }
    
        // Cancelled projects
        $statusId = Status::where('title', 'cancelled')->value('id');
        if ($statusId !== null) {
            $cancelledProjects = $projects->where('status_id', $statusId);
        }
    
        return view('activity.user.activities', [
            'project' => $project,
            'taskData' => $taskData,
            'tasks' => $tasksCount,
            'users' => $users,
            'clients' => $clients,
            'projects' => $projects,
            'toSelectTaskUsers' => $toSelectTaskUsers
        ]);
    }
    public function userTasklist($id = '')
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $status = isset($_REQUEST['status']) && $_REQUEST['status'] !== '' ? $_REQUEST['status'] : "";
        $user_id = (request('user_id')) ? request('user_id') : "";
        $client_id = (request('client_id')) ? request('client_id') : "";
        $project_id = (request('project_id')) ? request('project_id') : "";
        $start_date_from = (request('task_start_date_from')) ? request('task_start_date_from') : "";
        $start_date_to = (request('task_start_date_to')) ? request('task_start_date_to') : "";
        $end_date_from = (request('task_end_date_from')) ? request('task_end_date_from') : "";
        $end_date_to = (request('task_end_date_to')) ? request('task_end_date_to') : "";
        $where = [];
        if ($status != '') {
            $where['status_id'] = $status;
        }
        if ($id) {
            $id = explode('_', $id);
            $belongs_to = $id[0];
            $belongs_to_id = $id[1];
            if ($belongs_to == 'project') {
                $belongs_to = Project::find($belongs_to_id);
            }
            if ($belongs_to == 'user') {
                $belongs_to = User::find($belongs_to_id);
            }
            if ($belongs_to == 'client') {
                $belongs_to = Client::find($belongs_to_id);
            }
            $tasks = $belongs_to->tasks();
        } else {
            $tasks =$this->user->tasks();
        }
        if ($user_id) {
            $user = User::find($user_id);
            $tasks = $user->tasks();
        }
        if ($client_id) {
            $client = Client::find($client_id);
            $tasks = $client->tasks();
        }
        if ($project_id) {
            $where['project_id'] = $project_id;
        }
        if ($start_date_from && $start_date_to) {
            $tasks->whereBetween('start_date', [$start_date_from, $start_date_to]);
        }
        if ($end_date_from && $end_date_to) {
            $tasks->whereBetween('due_date', [$end_date_from, $end_date_to]);
        }
        if ($search) {
            $tasks = $tasks->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            });
        }
        // Apply where clause to $tasks
        $tasks = $tasks->where($where);
       
        // Count total tasks before pagination
        $totaltasks = $tasks->count();
        $statuses = Status::all();
        $priorities = Priority::all();
        // Paginate tasks and format them
        $tasks = $tasks->orderBy($sort, $order)->paginate(request('limit'))->through(function ($task) use ($statuses, $priorities) {
            $statusOptions = '';
            foreach ($statuses as $status) {
                $selected = $task->status_id == $status->id ? 'selected' : '';
                $statusOptions .= "<option value='{$status->id}' class='badge bg-label-{$status->color}' {$selected}>{$status->title}</option>";
            }

            $priorityOptions = '';
            foreach ($priorities as $priority) {
                $selectedPriority = $task->priority_id == $priority->id ? 'selected' : '';
                $priorityOptions .= "<option value='{$priority->id}' class='badge bg-label-{$priority->color}' {$selectedPriority}>{$priority->title}</option>";
            }
            $units = UnitMeasure::all();
            return [
                'id' => $task->id,
                'units' =>$units,
                'title' => "<a href='/tasks/information/{$task->id}' target='_blank' title='{$task->description}'><strong>{$task->title}</strong></a>",
                'project_id' => "<a href='/projects/information/{$task->project->id}' target='_blank' title='{$task->project->description}'><strong>{$task->project->title}</strong></a> <a href='javascript:void(0);' class='mx-2'><i class='bx " . ($task->project->is_favorite ? 'bxs' : 'bx') . "-star favorite-icon text-warning' data-favorite='{$task->project->is_favorite}' data-id='{$task->project->id}' title='" . ($task->project->is_favorite ? get_label('remove_favorite', 'Click to remove from favorite') : get_label('add_favorite', 'Click to mark as favorite')) . "'></i></a>",
                'users' => $task->users,
                'clients' => $task->project->clients,
                'start_date' => format_date($task->start_date),
                'end_date' => format_date($task->due_date),
                'status_id' => "<select class='form-select form-select-sm' id='statusSelect' data-id='{$task->id}' data-original-status-id='{$task->status->id}' data-type='task'>{$statusOptions}</select>",
               // 'priority_id' => $task->priority->title,
               'priority_id' => $activity->priority ?? 'Normal', // Priority (default value if not set)
                'status' => $task->status->title,
                'created_at' => format_date($task->created_at, true),
                'updated_at' => format_date($task->updated_at, true),
            ];
        });
        // Modify users and clients within the same loop
        foreach ($tasks->items() as $task => $collection) {
            foreach ($collection['users'] as $i => $user) {
                // Modify users...
                $collection['users'][$i] = "<a href='/users/profile/" . $user->id . "' target='_blank'><li class='avatar avatar-sm pull-up'  title='" . $user['first_name'] . " " . $user['last_name'] . "'>
        <img src='" . ($user['photo'] ? asset('storage/' . $user['photo']) : asset('storage/photos/no-image.jpg')) . "' class='rounded-circle' />
        </li></a>";
            }
            foreach ($collection['clients'] as $i => $client) {
                // Modify clients...
                $collection['clients'][$i] = "<a href='/clients/profile/" . $client->id . "' target='_blank'><li class='avatar avatar-sm pull-up'  title='" . $client['first_name'] . " " . $client['last_name'] . "'>
        <img src='" . ($client['photo'] ? asset('storage/' . $client['photo']) : asset('storage/photos/no-image.jpg')) . "' alt='Avatar' class='rounded-circle' />
        </li></a>";
            }
        }

        // Return JSON response with formatted tasks and total count
        return response()->json([
            "rows" => $tasks->items(),
            "total" => $totaltasks,
        ]);
    }
  //  public function userActivitylist($id = '')
  public function userActivitylist($id = '')
  {
      // Fetch the search and filter parameters
      $search = request('search');
      $sort = request('sort', 'activities.id'); // Specify table name
      $order = request('order', 'DESC');
      $user_id = request('user_id', '');
      $client_id = request('client_id', '');
      $project_id = request('project_id', '');
      $start_date_from = request('activity_start_date_from', '');
      $start_date_to = request('activity_start_date_to', '');
      $end_date_from = request('activity_end_date_from', '');
      $end_date_to = request('activity_end_date_to', '');
  
      $where = [];
      if ($user_id) {
          $user = User::find($user_id);
          $tasks = $user ? $user->tasks() : Task::query();
      } elseif ($client_id) {
          $client = Client::find($client_id);
          $tasks = $client ? $client->tasks() : Task::query();
      } elseif ($project_id) {
          $where['project_id'] = $project_id;
          $tasks = Project::find($project_id)?->tasks() ?? Task::query();
      } else {
          $tasks = auth()->user()->tasks(); // Assuming you are using authentication
      }
  
      // Apply filters
      if ($start_date_from && $start_date_to) {
          $tasks->whereBetween('start_date', [$start_date_from, $start_date_to]);
      }
      if ($end_date_from && $end_date_to) {
          $tasks->whereBetween('due_date', [$end_date_from, $end_date_to]);
      }
      if ($search) {
          $tasks->where('tasks.title', 'like', '%' . $search . '%'); // Specify table name
      }
      $tasks->where($where);
      // Fetch activities related to tasks
      $activities = Activity::whereIn('task_id', $tasks->pluck('tasks.id'))->get(); // Ensure to specify the correct column name
  
      // Format activities for the response
      $activityList = $activities->map(function ($activity) {
          $task = Task::find($activity->task_id); // Fetch task details
          $project = $task ? $task->project : null; // Get project details

      // Generate status options for the select dropdown
      $statuses = Status::all()->keyBy('id'); // Key statuses by ID for easy access

      $statusOptions = '';
      foreach ($statuses as $status) {
          $selected = $activity->status_id == $status->id ? 'selected' : '';
          $statusOptions .= "<option value='{$status->id}' class='badge bg-label-{$status->color}' {$selected}>{$status->title}</option>";
      }
          return [
              'id' => $activity->id, // Activity ID
              'project_name' => $project ? $project->title : 'N/A', // Project Name
              'task_name' => $task ? $task->title : 'N/A', // Task Name
              'activity_name' => $activity->name ?? 'N/A', // Activity Name
              'activity_start' => format_date($activity->start_date), // Activity Start Date
              'activity_end' => format_date($activity->end_date), // Activity End Date
              'priority' => $activity->priority ?? 'Normal', // Priority (default value if not set)
              'status' => $task->status->title,
              'status_id' => "<select class='form-select form-select-sm' id='statusSelect' data-id='{$activity->id}' data-original-status-id='{$task->status->id}' data-type='task'>{$statusOptions}</select>",
          ];
      });
  
      // Return JSON response with formatted activities
      return response()->json([
          "activities" => $activityList,
          "total_activities" => $activityList->count(),
      ]);
  }

  public function cancelled($id = '')
    {
        $project = (object)[];
        if ($id) {
            $project = Project::findOrFail($id);
            $tasks = isAdminOrHasAllDataAccess() ? $project->tasks : $this->user->project_tasks($id);
            $toSelectTaskUsers = $project->users;
        } else {
            $tasks = isAdminOrHasAllDataAccess() ? $this->workspace->tasks : $this->user->tasks();
            $toSelectTaskUsers = $this->workspace->users;
        }
        $tasks = $tasks->count();
        $users = $this->workspace->users;
        $clients = $this->workspace->clients;
        $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects : $this->user->projects;

        $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects : $this->user->projects;
        //completed
        $statusId = Status::where('title', 'completed')->value('id');
        if ($statusId != null) {
            $completedProjects = $projects->where('status_id', $statusId);
        }
        
        $statusId = Status::where('title', 'inProgress')->value('id');
        if ($statusId != null) {
            $inProgressProjects = $projects->where('status_id', $statusId);
        }
        $statusId = Status::where('title', 'notStarted')->value('id');
        if ($statusId != null) {
            $notStartedProjects = $projects->where('status_id', $statusId);
        }
        $statusId = Status::where('title', 'cancelled')->value('id');
        if ($statusId != null) {
            $cancelledProjects = $projects->where('status_id', $statusId);
        }
        
        return view('tasks.cancelled', ['project' => $project, 'tasks' => $tasks, 'users' => $users, 'clients' => $clients, 'projects' => $projects, 'toSelectTaskUsers' => $toSelectTaskUsers]);
    }
    public function inProgress($id = '')
    {
        $project = (object)[];
        if ($id) {
            $project = Project::findOrFail($id);
            $tasks = isAdminOrHasAllDataAccess() ? $project->tasks : $this->user->project_tasks($id);
            $toSelectTaskUsers = $project->users;
        } else {
            $tasks = isAdminOrHasAllDataAccess() ? $this->workspace->tasks : $this->user->tasks();
            $toSelectTaskUsers = $this->workspace->users;
        }
        $tasks = $tasks->count();
        $users = $this->workspace->users;
        $clients = $this->workspace->clients;
        $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects : $this->user->projects;

        $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects : $this->user->projects;
        //completed
        $statusId = Status::where('title', 'completed')->value('id');
        if ($statusId != null) {
            $completedProjects = $projects->where('status_id', $statusId);
        }
        
        $statusId = Status::where('title', 'inProgress')->value('id');
        if ($statusId != null) {
            $inProgressProjects = $projects->where('status_id', $statusId);
        }
        $statusId = Status::where('title', 'notStarted')->value('id');
        if ($statusId != null) {
            $notStartedProjects = $projects->where('status_id', $statusId);
        }
        $statusId = Status::where('title', 'cancelled')->value('id');
        if ($statusId != null) {
            $cancelledProjects = $projects->where('status_id', $statusId);
        }
        
        return view('tasks.inProgress', ['project' => $project, 'tasks' => $tasks, 'users' => $users, 'clients' => $clients, 'projects' => $projects, 'toSelectTaskUsers' => $toSelectTaskUsers]);
    }
    public function notStarted($id = '')
    {
        $project = (object)[];
        if ($id) {
            $project = Project::findOrFail($id);
            $tasks = isAdminOrHasAllDataAccess() ? $project->tasks : $this->user->project_tasks($id);
            $toSelectTaskUsers = $project->users;
        } else {
            $tasks = isAdminOrHasAllDataAccess() ? $this->workspace->tasks : $this->user->tasks();
            $toSelectTaskUsers = $this->workspace->users;
        }
        $tasks = $tasks->count();
        $users = $this->workspace->users;
        $clients = $this->workspace->clients;
        $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects : $this->user->projects;

        $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects : $this->user->projects;
        //completed
        $statusId = Status::where('title', 'completed')->value('id');
        if ($statusId != null) {
            $completedProjects = $projects->where('status_id', $statusId);
        }
        
        $statusId = Status::where('title', 'inProgress')->value('id');
        if ($statusId != null) {
            $inProgressProjects = $projects->where('status_id', $statusId);
        }
        $statusId = Status::where('title', 'notStarted')->value('id');
        if ($statusId != null) {
            $notStartedProjects = $projects->where('status_id', $statusId);
        }
        $statusId = Status::where('title', 'cancelled')->value('id');
        if ($statusId != null) {
            $cancelledProjects = $projects->where('status_id', $statusId);
        }
        
        return view('tasks.notStarted', ['project' => $project, 'tasks' => $tasks, 'users' => $users, 'clients' => $clients, 'projects' => $projects, 'toSelectTaskUsers' => $toSelectTaskUsers]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = '')
    {
        $project = (object)[];
        $projects = [];
        if ($id) {
            $project = Project::find($id);
            $users = $project->users;
        } else {
            $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects : $this->user->projects;
            $users = $this->workspace->users;
        }
        return view('tasks.create_task', ['project' => $project, 'projects' => $projects, 'users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'title' => ['required'],
            'status_id' => ['required'],
            'priority_id' => ['nullable'],
            'start_date' => ['required', 'before_or_equal:due_date'],
            'due_date' => ['required'],
            'description' => ['required'],
            'project' => ['required']
        ]);
        $project_id = $request->input('project');
        $project = Project::findOrFail($project_id);
        $start_date = $request->input('start_date');
        $due_date = $request->input('due_date');
        $formFields['start_date'] = format_date($start_date, false, app('php_date_format'), 'Y-m-d');
        $formFields['due_date'] = format_date($due_date, false, app('php_date_format'), 'Y-m-d');

        $formFields['workspace_id'] = $this->workspace->id;
        $formFields['created_by'] = $this->user->id;

        $formFields['project_id'] = $project_id;
        $userIds = $request->input('user_id', []);

        $new_task = Task::create($formFields);
        $task_id = $new_task->id;
        $task = Task::find($task_id);
        $task->users()->attach($userIds);
        $parentId = MasterSchedule::where('project_or_task_id', $project_id)
        ->where('type', 'project')
        ->pluck('id')
        ->first();
        MasterSchedule::create([
            'text' =>  $task->title,
            'duration' => 110, // Set duration as required
            'progress' => 0, // Initially set progress to 0
            'type' => 'task', // Set type as 'activity'
            'parent' =>$parentId, // Link to the parent task
            'project_or_task_id' => $task_id
        ]);

        $notification_data = [
            'type' => 'task',
            'type_id' => $task_id,
            'type_title' => $task->title,
            'access_url' => 'tasks/information/' . $task->id,
            'action' => 'assigned',
            'title' => 'New task assigned',
            'message' => $this->user->first_name . ' ' . $this->user->last_name . ' assigned you new task : ' . $task->title . ', ID #' . $task_id . '.'
        ];
        // $clientIds = $project->clients()->pluck('clients.id')->toArray();
        // $recipients = array_merge(
        //     array_map(function ($userId) {
        //         return 'u_' . $userId;
        //     }, $userIds),
        //     array_map(function ($clientId) {
        //         return 'c_' . $clientId;
        //     }, $clientIds)
        // );
        $recipients = array_map(function ($userId) {
            return 'u_' . $userId;
        }, $userIds);
        processNotifications($notification_data, $recipients);
        return response()->json(['error' => false, 'id' => $new_task->id, 'message' => 'Task created successfully.']);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   /* public function show($id)
    {
        $tasks = Task::all();
        $task = Task::findOrFail($id);
     ///   $tasks = Task::select('start_date', 'due_date', 'progress', 'title')->get();
        //return $tasks;
       // $subtasks = Task::select('id','start_date', 'due_date', 'progress', 'title')->with('subtasks')->get();
        $subtasks1 = Task::select('id', 'title')
        ->with(['subtasks' => function ($query) {
            $query->select('start_date','end_date','name','progress','task_id');
        }])
        ->get();
       // $subtasks = Subtask::with('materialCosts', 'equipmentCosts', 'laborCosts')->get();
        $subtasks = Subtask::with('materialCosts', 'equipmentCosts', 'laborCosts')->where('task_id', $id)->get();
        $data = $subtasks->map(function ($subtask) {
            $materialCosts = $subtask->materialCosts ?? collect([]); // Handle potential null materialCosts relationship
            $materialCostsData = $materialCosts->map(function ($materialCost) {
                return [
                    'name' => $materialCost->name,
                    'unit' => $materialCost->unit,
                    'planQty' => $materialCost->qty,
                    'ActualQty' => $materialCost->actualQty,
                    'plancost' => $materialCost->amount,
                    'Actualcost' => $materialCost->actualCost,
                ];
            });  $materialCosts = $subtask->materialCosts->map(function ($materialCost) {
                return [
                    'name' => $materialCost->unit,
                    'unit' => $materialCost->unit,
                    'planQty' => $materialCost->qty,
                    'ActualQty' => $materialCost->actualQty,
                    'plancost' => $materialCost->amount,
                    'Actualcost' => $materialCost->actualCost,
                ];
            });
            return [
                'id' => $subtask->id,
                'task_name' => $subtask->name,
                'status' =>  $subtask->status,
                'priority' =>  $subtask->priority,
                'planned' =>  $subtask->planned,
                'actual' =>  $subtask->actual,
                'start_date' =>  $subtask->start_date,
                'estimated_date' =>$subtask->estimated_date,
                'end_date' =>  $subtask->end_date,
                'progress' => $subtask->progress,
                'materialCosts' => $materialCostsData,
                'total_material_amount' => $subtask->materialCosts->sum('amount'),
                'total_equipment_amount' => $subtask->equipmentCosts->sum('amount'),
                'total_labor_amount' => $subtask->laborCosts->sum('amount'),
            ];
        });
        // Pass the data to the view
        //return view('subtasks.index', ['data' => $data]);
       return view('tasks.task_information', ['task' => $task,'tasks' =>$tasks,'subtasks1'=>$subtasks1, 'data' => $data]);
      // return $subtasks;
    }
*/
/*public function show($id)
{
    $tasks = Task::all();
    $task =  Task::findOrFail($id);
    $users = User::all();
    $status = Status::all();
    $priority =  Priority::all();
    

    $subtasks1 = Task::select('id', 'title')
        ->with(['subtasks' => function ($query) {
            $query->select('start_date', 'end_date', 'name', 'progress', 'task_id', 'status');
        }])
        ->get();

    $subtasks = Activity::with('materialCosts', 'equipmentCosts', 'laborCosts')
        ->where('task_id', $id)
        ->get();

    $totalCompleted = $subtasks->where('status', 'completed')->count();
    $totalPending = $subtasks->where('status', 'pending')->count();
    $totalnotstarted = $subtasks->where('status', 'not start')->count();
    $totalCancelled = $subtasks->where('status', 'cancel')->count();

    $data = $subtasks->map(function ($subtask) {
        $materialCosts = $subtask->materialCosts ?? collect([]); // Handle potential null materialCosts relationship
        $materialCostsData = $materialCosts->map(function ($materialCost) {
            return [
                'name' => $materialCost->name,
                'unit' => $materialCost->unit,
                'planQty' => $materialCost->qty,
                'ActualQty' => $materialCost->actualQty,
                'plancost' => $materialCost->amount,
                'Actualcost' => $materialCost->actualCost,
            ];
        });

        return [
            'wbs' $subtask->id 
            'id' => $subtask->id,
            'task_name' => $subtask->name,
            'status' => $subtask->status,
            'priority' => $subtask->priority,
            'planned' => $subtask->planned,
            'actual' => $subtask->actual,
            'start_date' => $subtask->start_date,
            'lead_time' => $subtask->lead_time,
            'estimated_date' => $subtask->estimated_date,
            'end_date' => $subtask->end_date,
            'progress' => $subtask->progress,
            'materialCosts' => $materialCostsData,
            'total_material_amount' => $subtask->materialCosts->sum('amount'),
            'total_equipment_amount' => $subtask->equipmentCosts->sum('amount'),
            'total_labor_amount' => $subtask->laborCosts->sum('amount'),
        ];
    });
   // return response()->json(['total'=>$tasks]);
    return view('tasks.task_information', [
        'task' => $task,
        'tasks' => $tasks,
        'subtasks1' => $subtasks1,
        'data' => $data,
        'totalCompleted' => $totalCompleted,
        'totalPending' => $totalPending,
        'totalnotstarted' => $totalnotstarted,
        'totalCancelled' => $totalCancelled,
        'users' =>$users,
        'status' =>$status,
        "priority" =>$priority,
    ]);
}*/
/*public function show($id)
{
    // Retrieve all tasks
    $tasks = Task::all();

    // Retrieve the specific task or fail if not found
    $task = Task::findOrFail($id);

    // Retrieve all users
    $users = User::all();
    $status = Status::all();
    $priority = Priority::all();
    // Retrieve subtasks associated with the task
    $subtasks = Activity::where('task_id', $id)->get();

    // Count total by status
    $totalCompleted = $subtasks->where('status', Status::where('id', 72)->first()->id)->count();
    $totalPending = $subtasks->where('status', Status::where('id', 73)->first()->id)->count();
    $totalNotStarted = $subtasks->where('status', Status::where('id', 0)->first()->id)->count();
    $totalCancelled = $subtasks->where('status', Status::where('id', 71)->first()->id)->count();

    // Prepare data for the view
    $data = $subtasks->map(function ($subtask) {
        // Fetch status title and priority title by ID
        $status = Status::find($subtask->status);
        $priority = Priority::find($subtask->priority);

        return [
            'id' => $subtask->id,
            'wbs' => $subtask->task->project->id . "." . $subtask->task->id . "." . $subtask->id, // Use . for concatenation
            'status' => $status  ? $status->title : 'Unknown',
            'priority' => $priority ? $priority->title: 'Unknown',
            'status_color' => $status  ? $status->color : 'Unknown',
            'priority_color' => $priority ? $priority->color: 'Unknown',
            'activity_name' => $subtask->name, 
            'start_date' => format_date($subtask->start_date, false, app('php_date_format'), 'Y-m-d'),
            'end_date' => $subtask->end_date,
            'progress' => $subtask->progress,  
            'duration' => $subtask->progress,  
        ];
    });
    $statusData = [
        'completed' => ['color' => '#71dd37', 'count' => $totalCompleted],
        'in_progress' => ['color' => '#696cff', 'count' => $totalPending], // Assuming totalPending for in_progress
        'not_started' => ['color' => '#ffab00', 'count' => $totalNotStarted],
        'cancelled' => ['color' => '#ff3e1d', 'count' => $totalCancelled]
    ];
    // Return the view with the data
    return view('tasks.task_information', [
        'task' => $task, // Include the main task
        'activites' => $data,
        'statusData' =>$statusData,
        'users' => $users,
        'priority' =>$priority,
        'status' =>$status
        
    ]);
}*/
public function show($id = '')
{
   
     // Retrieve all tasks
     $tasks = Task::all();

     // Retrieve the specific task or fail if not found
     $task = Task::findOrFail($id);
 
     // Retrieve all users
     $users = User::all();
     $status = Status::all();
     $priority = Priority::all();
     $units = UnitMeasure::all();
     // Retrieve subtasks associated with the task
     $subtasks = Activity::where('task_id', $id)->get();
     $totalCompleted = $subtasks->where('status', Status::where('id', 72)->first()->id)->count();
     $totalPending = $subtasks->where('status', Status::where('id', 73)->first()->id)->count();
     $totalNotStarted = $subtasks->where('status', Status::where('id', 0)->first()->id)->count();
     $totalCancelled = $subtasks->where('status', Status::where('id', 71)->first()->id)->count();
 $activity = count($subtasks);
     $statusData = [
        'completed' => ['color' => '#71dd37', 'count' => $totalCompleted],
        'in_progress' => ['color' => '#696cff', 'count' => $totalPending], // Assuming totalPending for in_progress
        'not_started' => ['color' => '#ffab00', 'count' => $totalNotStarted],
        'cancelled' => ['color' => '#ff3e1d', 'count' => $totalCancelled]
    ];
    /*return response()->json([
        "total" => $subtasks,
    ]);*/
    return view('tasks.task_information', [
        'taskData' => $tasks,
        'activities' => $activity,
        'clients' => $tasks,
        'projects' => $tasks,
        'toSelectTaskUsers' => $tasks,
        'id' =>$id,
        'statusData' =>$statusData,
        'users' => $users,
        'units' => $units,
        'priority' =>$priority,
        'status' =>$status,
        'subtasks' =>$subtasks   
    ]);
}
    public function get($id)
    {
        $task = Task::with('users')->findOrFail($id);
        $project = $task->project()->with('users')->firstOrFail();

        return response()->json(['error' => false, 'task' => $task, 'project' => $project]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        $project = $task->project;
        $users = $task->project->users;
        $task_users = $task->users;
        return view('tasks.update_task', ["project" => $project, "task" => $task, "users" => $users, "task_users" => $task_users]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  /*  public function update(Request $request)
    {
        $formFields = $request->validate([
            'id' => 'required|exists:tasks,id',
            'title' => ['required'],
            'status_id' => ['required'],
            'priority_id' => ['nullable'],
            'start_date' => ['required', 'before_or_equal:due_date'],
            'due_date' => ['required'],
            'description' => ['required'],
            'progress' =>[],
            'issue'=>[]
        ]);
        $id = $request->input('id');
        $start_date = $request->input('start_date');
        $due_date = $request->input('due_date');
        $formFields['start_date'] = format_date($start_date, false, app('php_date_format'), 'Y-m-d');
        $formFields['due_date'] = format_date($due_date, false, app('php_date_format'), 'Y-m-d');
        $userIds = $request->input('user_id', []);

        $task = Task::findOrFail($id);
        $task->update($formFields);

        // Get the current users associated with the task
        $currentUsers = $task->users->pluck('id')->toArray();

        // Sync the users for the task
        $task->users()->sync($userIds);

        // Get the new users associated with the task
        $newUsers = array_diff($userIds, $currentUsers);

        // Prepare notification data for new users
        $notification_data = [
            'type' => 'task',
            'type_id' => $id,
            'type_title' => $task->title,
            'access_url' => 'tasks/information/' . $task->id,
            'action' => 'assigned',
            'title' => 'Task updated',
            'message' => $this->user->first_name . ' ' . $this->user->last_name . ' assigned you new task : ' . $task->title . ', ID #' . $id . '.'
        ];

        // Notify only the new users
        $recipients = array_map(function ($userId) {
            return 'u_' . $userId;
        }, $newUsers);

        // Process notifications for new users
        processNotifications($notification_data, $recipients);
        return response()->json(['error' => false, 'id' => $id,  'message' => 'Task updated successfully.']);
    }
*/

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        DeletionService::delete(Task::class, $id, 'Task');
        return response()->json(['error' => false, 'message' => 'Task deleted successfully.', 'id' => $id, 'title' => $task->title, 'parent_id' => $task->project_id, 'parent_type' => 'project']);
    }

    public function destroy_multiple(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'ids' => 'required|array', // Ensure 'ids' is present and an array
            'ids.*' => 'integer|exists:tasks,id' // Ensure each ID in 'ids' is an integer and exists in the table
        ]);

        $ids = $validatedData['ids'];
        $deletedTasks = [];
        $deletedTaskTitles = [];
        $parentIds = [];
        // Perform deletion using validated IDs
        foreach ($ids as $id) {
            $task = Task::find($id);
            if ($task) {
                $deletedTaskTitles[] = $task->title;
                DeletionService::delete(Task::class, $id, 'Task');
                $deletedTasks[] = $id;
                $parentIds[] = $task->project_id;
            }
        }

        return response()->json(['error' => false, 'message' => 'Task(s) deleted successfully.', 'id' => $deletedTasks, 'titles' => $deletedTaskTitles, 'parent_id' => $parentIds, 'parent_type' => 'project']);
    }


    public function list($id = '')
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $status = isset($_REQUEST['status']) && $_REQUEST['status'] !== '' ? $_REQUEST['status'] : "";
        $user_id = (request('user_id')) ? request('user_id') : "";
        $client_id = (request('client_id')) ? request('client_id') : "";
        $project_id = (request('project_id')) ? request('project_id') : "";
        $start_date_from = (request('task_start_date_from')) ? request('task_start_date_from') : "";
        $start_date_to = (request('task_start_date_to')) ? request('task_start_date_to') : "";
        $end_date_from = (request('task_end_date_from')) ? request('task_end_date_from') : "";
        $end_date_to = (request('task_end_date_to')) ? request('task_end_date_to') : "";
        $where = [];
        if ($status != '') {
            $where['status_id'] = $status;
        }
        if ($id) {
            $id = explode('_', $id);
            $belongs_to = $id[0];
            $belongs_to_id = $id[1];
            if ($belongs_to == 'project') {
                $belongs_to = Project::find($belongs_to_id);
            }
            if ($belongs_to == 'user') {
                $belongs_to = User::find($belongs_to_id);
            }
            if ($belongs_to == 'client') {
                $belongs_to = Client::find($belongs_to_id);
            }
            $tasks = $belongs_to->tasks();
        } else {
            $tasks = isAdminOrHasAllDataAccess() ? $this->workspace->tasks() : $this->user->tasks();
        }
        if ($user_id) {
            $user = User::find($user_id);
            $tasks = $user->tasks();
        }
        if ($client_id) {
            $client = Client::find($client_id);
            $tasks = $client->tasks();
        }
        if ($project_id) {
            $where['project_id'] = $project_id;
        }
        if ($start_date_from && $start_date_to) {
            $tasks->whereBetween('start_date', [$start_date_from, $start_date_to]);
        }
        if ($end_date_from && $end_date_to) {
            $tasks->whereBetween('due_date', [$end_date_from, $end_date_to]);
        }
        if ($search) {
            $tasks = $tasks->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            });
        }
        // Apply where clause to $tasks
        $tasks = $tasks->where($where);

        // Count total tasks before pagination
        $totaltasks = $tasks->count();
        $statuses = Status::all();
        $priorities = Priority::all();
        // Paginate tasks and format them
        $tasks = $tasks->orderBy($sort, $order)->paginate(request('limit'))->through(function ($task) use ($statuses, $priorities) {
            $statusOptions = '';
            foreach ($statuses as $status) {
                $selected = $task->status_id == $status->id ? 'selected' : '';
                $statusOptions .= "<option value='{$status->id}' class='badge bg-label-{$status->color}' {$selected}>{$status->title}</option>";
            }

            $priorityOptions = '';
            foreach ($priorities as $priority) {
                $selectedPriority = $task->priority_id == $priority->id ? 'selected' : '';
                $priorityOptions .= "<option value='{$priority->id}' class='badge bg-label-{$priority->color}' {$selectedPriority}>{$priority->title}</option>";
            }

            return [
                'id' => $task->id,
                'title' => "<a href='/tasks/information/{$task->id}' target='_blank' title='{$task->description}'><strong>{$task->title}</strong></a>",
                'project_id' => "<a href='/projects/information/{$task->project->id}' target='_blank' title='{$task->project->description}'><strong>{$task->project->title}</strong></a> <a href='javascript:void(0);' class='mx-2'><i class='bx " . ($task->project->is_favorite ? 'bxs' : 'bx') . "-star favorite-icon text-warning' data-favorite='{$task->project->is_favorite}' data-id='{$task->project->id}' title='" . ($task->project->is_favorite ? get_label('remove_favorite', 'Click to remove from favorite') : get_label('add_favorite', 'Click to mark as favorite')) . "'></i></a>",
                'users' => $task->users,
                'clients' => $task->project->clients,
                'start_date' => format_date($task->start_date),
                'end_date' => format_date($task->due_date),
                'status_id' => "<span class='badge bg-label-{$task->status->color}'>{$task->status->title}</span>", // Fixed badge format"'>{$priorityOptions}</select>",
                'priority_id' => $priority ? "<span class='badge bg-label-{$priority->color}'>{$priority->title}</span>" : "<span class='badge bg-label-secondary'>No Priority</span>",
                'created_at' => format_date($task->created_at, true),
                'updated_at' => format_date($task->updated_at, true),
                'wbs' => $task->project->id . "." . $task->id, // Use . for concatenation
                'approval' =>'Not',
                //'duaration' =>  format_date(($task->due_date)- format_date($task->start_date))
                'duaration' => '350 days'
            ];
        });

        // Modify users and clients within the same loop
        foreach ($tasks->items() as $task => $collection) {
            foreach ($collection['users'] as $i => $user) {
                // Modify users...
                $collection['users'][$i] = "<a href='/users/profile/" . $user->id . "' target='_blank'><li class='avatar avatar-sm pull-up'  title='" . $user['first_name'] . " " . $user['last_name'] . "'>
        <img src='" . ($user['photo'] ? asset('storage/' . $user['photo']) : asset('storage/photos/no-image.jpg')) . "' class='rounded-circle' />
        </li></a>";
            }
            foreach ($collection['clients'] as $i => $client) {
                // Modify clients...
                $collection['clients'][$i] = "<a href='/clients/profile/" . $client->id . "' target='_blank'><li class='avatar avatar-sm pull-up'  title='" . $client['first_name'] . " " . $client['last_name'] . "'>
        <img src='" . ($client['photo'] ? asset('storage/' . $client['photo']) : asset('storage/photos/no-image.jpg')) . "' alt='Avatar' class='rounded-circle' />
        </li></a>";
            }
        }

        // Return JSON response with formatted tasks and total count
        return response()->json([
            "rows" => $tasks->items(),
            "total" => $totaltasks,
        ]);
    }

        public function filter(Request $request)
        {
            // Validate incoming request data
            $request->validate([
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'project_id' => 'nullable|exists:projects,id',
                'user_id' => 'nullable|exists:users,id',
                'status_id' => 'nullable|exists:statuses,id',
            ]);
    
            // Build the query to filter tasks
            $query = Task::query();
    
            if ($request->start_date) {
                $query->where('start_date', '>=', $request->start_date);
            }
    
            if ($request->end_date) {
                $query->where('end_date', '<=', $request->end_date);
            }
    
            if ($request->project_id) {
                $query->where('project_id', $request->project_id);
            }
    
            if ($request->user_id) {
                $query->where('user_id', $request->user_id);
            }
    
            if ($request->status_id) {
                $query->where('status_id', $request->status_id);
            }
    
            // Get the filtered tasks
            $tasks = $query->get();
    
            // Return the tasks as a partial view
            return view('tasks.partials.task_table', compact('tasks'));
        }

    public function dragula($id = '')
    {
        $project = (object)[];
        $projects = [];
        if ($id) {
            $project = Project::findOrFail($id);
            $tasks = isAdminOrHasAllDataAccess() ? $project->tasks : $this->user->project_tasks($id);
            $toSelectTaskUsers = $project->users;
        } else {
            $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects : $this->user->projects;
            $toSelectTaskUsers = $this->workspace->users;
            $tasks = isAdminOrHasAllDataAccess() ? $this->workspace->tasks : $this->user->tasks()->get();
        }
        $total_tasks = $tasks->count();
        $units= UnitMeasure::all();
        return view('tasks.board_view', ['units' =>$units,'project' => $project, 'tasks' => $tasks, 'total_tasks' => $total_tasks, 'projects' => $projects, 'toSelectTaskUsers' => $toSelectTaskUsers]);
    }

    public function updateStatus($id, $newStatus)
    {
        $task = Task::findOrFail($id);
        $current_status = $task->status->title;
        $task->status_id = $newStatus;
        if ($task->save()) {
            $task->refresh();
            $new_status = $task->status->title;
            return response()->json(['error' => false, 'message' => 'Task status updated successfully.', 'id' => $id, 'activity_message' => $this->user->first_name . ' ' . $this->user->last_name . ' updated task status from ' . $current_status . ' to ' . $new_status]);
        } else {
            return response()->json(['error' => true, 'message' => 'Task status couldn\'t updated.']);
        }
    }
    //For status change from dropdown
    public function update_status(Request $request)
    {
        $request->validate([
            'id' => ['required'],
            'statusId' => ['required']

        ]);
        $id = $request->id;
        $statusId = $request->statusId;
        $task = Task::findOrFail($id);
        $currentStatus = $task->status->title;
        $task->status_id = $statusId;
        $task->note = $request->note;
        if ($task->save()) {
            // Reload the project to get updated status information
            $task = $task->fresh();
            $newStatus = $task->status->title;
            return response()->json(['error' => false, 'message' => 'Status updated successfully.', 'id' => $id, 'type' => 'task', 'activity_message' => $this->user->first_name . ' ' . $this->user->last_name . ' updated task status from ' . $currentStatus . ' to ' . $newStatus]);
        } else {
            return response()->json(['error' => true, 'message' => 'Status couldn\'t updated.']);
        }
    }

    public function duplicate($id)
    {
        // Define the related tables for this meeting
        $relatedTables = ['users']; // Include related tables as needed

        // Use the general duplicateRecord function
        $duplicate = duplicateRecord(Task::class, $id, $relatedTables);

        if (!$duplicate) {
            return response()->json(['error' => true, 'message' => 'Task duplication failed.']);
        }
        if (request()->has('reload') && request()->input('reload') === 'true') {
            Session::flash('message', 'Task duplicated successfully.');
        }
        return response()->json(['error' => false, 'message' => 'Task duplicated successfully.', 'id' => $id]);
    }

    public function upload_media(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id' => 'integer|exists:tasks,id'
            ]);

            $mediaIds = [];

            if ($request->hasFile('media_files')) {
                $task = Task::find($validatedData['id']);
                $mediaFiles = $request->file('media_files');

                foreach ($mediaFiles as $mediaFile) {
                    $mediaItem = $task->addMedia($mediaFile)
                        ->sanitizingFileName(function ($fileName) use ($task) {
                            // Replace special characters and spaces with hyphens
                            $sanitizedFileName = strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));

                            // Generate a unique identifier based on timestamp and random component
                            $uniqueId = time() . '_' . mt_rand(1000, 9999);

                            $extension = pathinfo($sanitizedFileName, PATHINFO_EXTENSION);
                            $baseName = pathinfo($sanitizedFileName, PATHINFO_FILENAME);

                            return "{$baseName}-{$uniqueId}.{$extension}";
                        })
                        ->toMediaCollection('task-media');

                    $mediaIds[] = $mediaItem->id;
                }


                Session::flash('message', 'File(s) uploaded successfully.');
                return response()->json(['error' => false, 'message' => 'File(s) uploaded successfully.', 'id' => $mediaIds, 'type' => 'media', 'parent_type' => 'task']);
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
        $task = Task::findOrFail($id);
        $media = $task->getMedia('task-media');

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
                ? asset('storage/task-media/' . $mediaItem->file_name)
                : $mediaItem->getFullUrl();


            $fileExtension = pathinfo($fileUrl, PATHINFO_EXTENSION);

            // Check if file extension corresponds to an image type
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
            $isImage = in_array(strtolower($fileExtension), $imageExtensions);

            if ($isImage) {
                $html = '<a href="' . $fileUrl . '" data-lightbox="task-media">';
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
                    '<a href="' . $fileUrl . '" title="' . get_label('download', 'Download') . '" download>' .
                        '<i class="bx bx-download bx-sm"></i>' .
                        '</a>' .
                        '<button title="' . get_label('delete', 'Delete') . '" type="button" class="btn delete" data-id="' . $mediaItem->id . '" data-type="task-media">' .
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

        return response()->json(['error' => false, 'message' => 'File deleted successfully.', 'id' => $mediaId, 'title' => $mediaItem->file_name, 'parent_id' => $mediaItem->model_id,  'type' => 'media', 'parent_type' => 'task']);
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

        return response()->json(['error' => false, 'message' => 'Files(s) deleted successfully.', 'id' => $deletedIds, 'titles' => $deletedTitles, 'parent_id' => $parentIds, 'type' => 'media', 'parent_type' => 'task']);
    }

    public function update_priority(Request $request)
    {
        $request->validate([
            'id' => ['required'],
            'priorityId' => ['nullable']

        ]);
        $id = $request->id;
        $priorityId = $request->priorityId;
        $task = Task::findOrFail($id);
        $currentPriority = $task->priority ? $task->priority->title : 'Default';
        $task->priority_id = $priorityId;
        $task->note = $request->note;
        if ($task->save()) {
            // Reload the task to get updated priority information
            $task = $task->fresh();
            $newPriority = $task->priority ? $task->priority->title : 'Default';
            $message = $this->user->first_name . ' ' . $this->user->last_name . ' updated task priority from ' . $currentPriority . ' to ' . $newPriority;
            return response()->json(['error' => false, 'message' => 'Priority updated successfully.', 'id' => $id, 'type' => 'task', 'activity_message' => $message]);
        } else {
            return response()->json(['error' => true, 'message' => 'Priority couldn\'t updated.']);
        }
    }
}
