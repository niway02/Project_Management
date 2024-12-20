<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Project;
use App\Models\MasterSchedule;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Status; // Importing the Status model
use App\Models\Priority; // Importing the Priority model
use App\Models\Activity;
use App\Models\Workspace;
use App\Models\User;
use App\Models\Site;
use App\Models\UnitMeasure;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MasterScheduleController extends Controller
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
        // Fetch users and clients associated with the workspace
        $toSelectProjectUsers = $this->workspace->users;
        $toSelectProjectClients = $this->workspace->clients;
    
        // Fetch all sites
        $sites = Site::all();
    
        // Fetch filters from the request (if necessary)
        $statusFilter = $request->input('status');
        $priorityFilter = $request->input('priority');
    
        // Start building the query with eager loading for projects
        $projectsQuery = Project::with(['tasks.activities', 'site']);
    
        // Apply filters if provided
        if ($statusFilter) {
            $projectsQuery->where('status_id', $statusFilter);
        }
        if ($priorityFilter) {
            $projectsQuery->where('priority_id', $priorityFilter);
        }
    
        // Fetch the projects and apply pagination
        $projectsData = $projectsQuery->paginate(10);
    
        // Fetch report data for the current week
       /* $reportData22 = Task::with('project')
            ->whereBetween('start_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->get();*/
            $reportData = Activity::with(['task.project'])->get(); 
        //   return response()->json($reportData);
        // Transform the projects data to include additional calculations
        $projectsData->getCollection()->transform(function ($project) {
            // Fetch related data
            $status = Status::find($project->status_id);
            $priority = Priority::find($project->priority_id);
            $creator = User::find($project->created_by);
    
            // Calculate duration and remaining time
            $startDate = \Carbon\Carbon::parse($project->start_date);
            $endDate = \Carbon\Carbon::parse($project->end_date);
            $now = \Carbon\Carbon::now();
    
            // Duration and remaining time calculations
            $duration = $startDate->diff($endDate);
            $durationFormatted = $this->formatDuration($duration);
            $remaining = $now->diff($endDate);
            $remainingFormatted = $this->formatRemainingTime($now, $endDate, $remaining);
    
            // Determine color based on remaining days
            $colorClass = $this->determineColorClass($now, $endDate, $remaining);
    
            // Build status and priority options HTML
            $statusOptions = $this->buildOptionsHtml($status, 'status');
            $priorityOptions = $this->buildOptionsHtml($priority, 'priority');
    
            // Prepare creator information
            $createdBy = $creator ? "<p>{$creator->first_name}</p>" : '';
    
            // Retrieve users assigned to the project
            $toSelectTaskUsers = $project->users; // Ensure this returns a collection
         
            return [
                "id" => $project->id,
                "wbs" => $project->id,
                "title" => $project->title,
                "site" => $project->site ? $project->site->name : null,
                "priority" => $priorityOptions,
                "startDate" => $startDate->format('d-m-Y'),
                "endDate" => $endDate->format('d-m-Y'),
                "duration" => $durationFormatted,
                "remaining" => $remainingFormatted,
                "remainingColor" => $colorClass,
                "status" => $statusOptions,
                "assignedTo" => $project->assigned_to,
                "createdBy" => $createdBy,
                "createdDate" => $project->created_at->toDateString(),
                "tasks" => $this->mapTasks($project->tasks),
                "toSelectTaskUsers" => $toSelectTaskUsers,
            ];
        });
    
        // Fetch other necessary data
        $priorities = Priority::all();
        $statuses = Status::all();
        $activities = 2; // Adjust as necessary
        $id = 1; // Adjust as necessary
        $units = UnitMeasure::all();
        $users = $toSelectProjectUsers; // Already fetched earlier
    
        // Fetch user's projects
        $projects = $this->user->projects; // Fetch projects associated with the user
        $tasks = Task::all(); // Fetch all tasks


        // Pass all data to the view, including report data
        return view('master-schedule.index', compact(
            'sites', 
            'priorities', 
            'users', 
            'toSelectProjectClients', 
            'toSelectProjectUsers', 
            'activities', 
            'id', 
            'statuses', 
            'projectsData', 
            'units',
            'tasks',
            'projects', // Include the user's projects
            'reportData' // Pass the report data to the view
        ));
    }
   // Helper methods
   public function generateReport(Request $request)
   {
       $period = $request->input('report_period');
       $projectId = $request->input('project_id');
       $taskId = $request->input('project_task_id');

       // Build your query based on the selected period, project, and task
       $query = Task::query()
           ->with('project') // Assuming you have a relationship set up
           ->when($period, function ($query) use ($period) {
               if ($period === 'this_week') {
                   $query->whereBetween('start_date', [now()->startOfWeek(), now()->endOfWeek()]);
               } elseif ($period === 'this_month') {
                   $query->whereMonth('start_date', now()->month);
               } elseif ($period === 'this_year') {
                   $query->whereYear('start_date', now()->year);
               } elseif ($period === 'year') {
                   $query->whereYear('start_date', $request->input('year'));
               }
           })
           ->when($projectId, function ($query) use ($projectId) {
               $query->where('project_id', $projectId);
           })
           ->when($taskId, function ($query) use ($taskId) {
               $query->where('id', $taskId);
           });

       $reportData = $query->get();

       return view('your-report-view-name', compact('reportData'));
   }
   public function projectReports(Request $request)
   {
       $period = $request->input('period');

       // Logic to fetch projects based on the selected period
       switch ($period) {
           case 'week':
               $projects = Project::where('created_at', '>=', now()->startOfWeek())
                                  ->where('created_at', '<=', now()->endOfWeek())
                                  ->get();
               break;
           case 'month':
               $projects = Project::where('created_at', '>=', now()->startOfMonth())
                                  ->where('created_at', '<=', now()->endOfMonth())
                                  ->get();
               break;
           case 'year':
               $projects = Project::where('created_at', '>=', now()->startOfYear())
                                  ->where('created_at', '<=', now()->endOfYear())
                                  ->get();
               break;
           default:
               $projects = Project::all(); // Fallback to all projects
               break;
       }

       // Return a view with the filtered projects or any other response
       return view('master-schedule.index', compact('projects')); // Adjust the view name accordingly
   }
private function formatDuration($duration)
{
    $durationParts = [];
    if ($duration->y > 0) {
        $durationParts[] = "{$duration->y} Y" . ($duration->y > 1 ? 's' : '');
    }
    if ($duration->m > 0) {
        $durationParts[] = "{$duration->m} M" . ($duration->m > 1 ? 's' : '');
    }
    if ($duration->d > 0) {
        $durationParts[] = "{$duration->d} D" . ($duration->d > 1 ? 's' : '');
    }
    return implode(', ', $durationParts) ?: '0 D'; // Fallback if all are 0
}

private function formatRemainingTime($now, $endDate, $remaining)
{
    // Similar logic as in your original code...
    // Calculate and format remaining time
}

private function determineColorClass($now, $endDate, $remaining)
{
    // Determine color based on remaining days
}

private function buildOptionsHtml($model, $type)
{
    if ($model) {
        return "<option value='{$model->id}' class='badge bg-label-{$model->color}' selected>{$model->title}</option>";
    }
    return '';
}

protected function mapTasks($tasks)
{
    return $tasks->map(function ($task) {
        // Ensure task is not null before trying to access its properties
        $statuses = Status::all();
        $priorities = Priority::all();
   // Calculate duration and remaining time
   $startDate = \Carbon\Carbon::parse($task->start_date);
   $endDate = \Carbon\Carbon::parse($task->end_date);
   $now = \Carbon\Carbon::now();
 // Duration calculation
 $duration = $startDate->diff($endDate);
 $durationFormatted = $this->formatDuration($duration);

 // Remaining time calculation
 $remaining = $now->diff($endDate);
 $remainingFormatted = $this->formatRemainingTime($now, $endDate, $remaining);
 
        return $task ? [
            'id' => $task->id,
            "wbs" => $task->id,
            "priority" => $task->priority_id,
            'title' => $task->title,
            'status' => $task->status_id, // Assuming you have a status property
            'assignedTo' => $task->assigned_to, // Assuming you have an assigned_to property
            "activities" => $this->mapActivities($task->activities),
            "startDate" => $startDate->format('d-m-Y'),
            "endDate" => $endDate->format('d-m-Y'),
            "duration" => $durationFormatted,
            "remaining" => $remainingFormatted,
            
            // Add any other relevant fields you want to return
        ] : null; // Return null if the task is not available
    })->filter(); // Remove any null values from the collection
}
protected function mapActivities($activities)
{
    // Fetch statuses and priorities once, outside the loop
    $statuses = Status::all();
    $priorities = Priority::all();
 
    return $activities->map(function ($activity) use ($statuses, $priorities) {

        $status = Status::find($activity->status);
        $priority = Priority::find($activity->priority);
        // Calculate duration and remaining time
        $startDate = \Carbon\Carbon::parse($activity->start_date);
        $endDate = \Carbon\Carbon::parse($activity->end_date);
        $now = \Carbon\Carbon::now();

        // Duration calculation
        $duration = $startDate->diff($endDate);
        $durationFormatted = $this->formatDuration($duration);

        // Remaining time calculation
        $remaining = $now->diff($endDate);
        $remainingFormatted = $this->formatRemainingTime($now, $endDate, $remaining);

        // Determine color based on remaining days
        $colorClass = $this->determineColorClass($now, $endDate, $remaining);

        // Build status options
     
        $statusOptions = $this->buildOptionsHtml($status, 'status');
        $priorityOptions = $this->buildOptionsHtml($priority, 'priority');
     

        return [
            'id' => $activity->id,
            'wbs' => $activity->id, // Assuming WBS uses the activity ID
            'name' => $activity->name,
            'status' => $activity->status_id, // Use status_id for consistency
            'assignedTo' => $activity->assigned_to, // Assuming you have an assigned_to property
            'priority' => $priorityOptions,
            'unit' => $activity->unitMeasure ? $activity->unitMeasure->name : null, // Get unit measure name
            'quantity' => $activity->quantity,
            'startDate' => $startDate->format('d-m-Y'),
            'endDate' => $endDate->format('d-m-Y'),
            'duration' => $durationFormatted,
            'remaining' => $remainingFormatted,
            'remainingColor' => $colorClass,
            'statusOptions' => $statusOptions, // Use statusOptions instead of status

            // Add any other relevant fields you want to return
        ];
    })->filter(); // Remove any null values from the collection
}
   /* public function index()
    {
        $projects = MasterSchedule::all();
        $project = Project::all();
        $task = Task::all();
        $priority = Priority::all();
        $activities = 2;
        $id =1;
        $users = $projects;
        $clients = $projects;
        $priority = $priority;
        $project = $project;
        $task = $task;
        
        return view('master-schedule.index', compact('projects','activities','id','users','clients','priority','project','task'));
    }*/
    public function data(Request $request) {
        $activities = Activity::with('task.project');
    
        // Input sanitization and defaults
        $search = $request->input('search', '');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'DESC');
        $status = $request->input('status', '');
        $user_id = $request->input('user_id', '');
        $client_id = $request->input('client_id', '');
        $start_date_from = $request->input('activity_start_date_from', '');
        $start_date_to = $request->input('activity_start_date_to', '');
        $end_date_from = $request->input('activity_end_date_from', '');
        $end_date_to = $request->input('activity_end_date_to', '');
        $is_favorites = $request->input('is_favorites', '');
    
        // Build where clauses
        $where = [];
        if ($status) {
            $where['status_id'] = $status;
        }
    
        if ($is_favorites) {
            $where['is_favorite'] = 1;
        }
    
        // Date filtering
        if ($start_date_from && $start_date_to) {
            $activities->whereBetween('start_date', [$start_date_from, $start_date_to]);
        }
        if ($end_date_from && $end_date_to) {
            $activities->whereBetween('end_date', [$end_date_from, $end_date_to]);
        }
    
        // Search filtering
        if ($search) {
            $activities->where('name', 'like', '%' . $search . '%');
        }
    
        // Apply other filters
        $activities->where($where);
        $totalActivities = $activities->count();
    
        // Fetch statuses and priorities
        $statuses = Status::all();
        $priorities = Priority::all();
    
        // Set default limit to 10 if not provided
        $limit = $request->input("limit", 10);
    
        // Paginate and format response
        $activities = $activities->orderBy($sort, $order)
            ->paginate($limit)
            ->through(function ($activity) use ($statuses, $priorities) {
                return $this->formatActivity($activity, $statuses, $priorities);
            });
    
        return response()->json([
            "rows" => $activities->items(),
            "total" => $totalActivities,
            "current_page" => $activities->currentPage(),
            "last_page" => $activities->lastPage(),
            "per_page" => $activities->perPage(),
        ]);
    }

    private function formatActivity($activity, $statuses, $priorities) {
        // Ensure status and priority are loaded
    $status = $activity->status; // This should be a Status model instance
    $priority = $activity->priority; // This should be a Priority model instance
    $statusOptions = '';
    foreach ($statuses as $status) {
        $selected = $activity->status == $status->id ? 'selected' : '';
        $statusOptions .= "<option value='{$status->id}' class='badge bg-label-$status->color' $selected>$status->title</option>";
    }
    $priorityOptions = "";
    foreach ($priorities as $priority) {
        $selected = $activity->priority == $priority->id ? 'selected' : '';
        $priorityOptions .= "<option value='{$priority->id}' class='badge bg-label-$priority->color' $selected>$priority->title</option>";
    }
        return [
            'id' => $activity->id,
            'activiity' => $activity->name,
            'progress' => $activity->progress,
            'project'=>$activity->task->project->title,
            'task'=>$activity->task->title,
           // 'priority_id' => $priorityBadge,
            'start_date' => format_date($activity->start_date),
            'end_date' => format_date($activity->end_date),
            'status' =>  $statusBadge = $status ? "<span class='badge bg-label-{$status->color}'>{$status->title}</span>" : "<span class='badge bg-label-secondary'>No Status</span>",
            'priority' =>  $priorityBadge = $priority ? "<span class='badge bg-label-{$priority->color}'>{$priority->title}</span>" : "<span class='badge bg-label-secondary'>No Priority</span>",
            'assigned_to' => $activity->assigned_to,
            'progress' => $activity->progress,
            'created_at' => format_date($activity->created_at, true),
            'updated_at' => format_date($activity->updated_at, true),
        ];
    }
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'task_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'status_id' => 'required|integer', 
            'priority' => 'required|integer',  
            'start_date' => 'nullable|date', 
            'quantity'=> 'required|integer', 
            'due_date' => 'required|date|date_format:Y-m-d|after_or_equal:start_date',
        ]);
    
        // Check for a debug query parameter
        if ($request->has('debug')) {
            return response()->json($request->all(), 200);
        }
    
        try {
            // Create the Activity
            $activity = Activity::create([  
                'task_id' => $request->task_id,  
                'name' => $request->name,  
                'status' => $request->status_id,
                'progress' => 0,  
                'assigned_to' => 1, // Ensure this field is correct
                'priority' => $request->priority,    
                'start_date' => $request->start_date,  
                'end_date' => $request->due_date,  
            ]);
    
            // Flash message for success
            Session::flash('message', 'Activity created successfully!');
    
            // Redirect back to the previous page
            return redirect()->back();
    
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error creating activity: ' . $e->getMessage(), [
                'request_data' => $request->all(),
            ]);
    
            // Flash an error message
            Session::flash('error', 'An error occurred while creating the activity: ' . $e->getMessage());
    
            // Redirect back with an error message
            return redirect()->back()->withInput();
        }
    }
   /* public function store(Request $request)
{
    // Start a database transaction
    DB::beginTransaction();

    try {
        // Create a new MasterSchedule instance
        $task = new MasterSchedule();
        
        // Set the parent ID from the request
        $task->parent = $request->parent;

        // Find the parent task by ID
        $parentTask = MasterSchedule::find($request->parent);

        // Check if the parent task exists
        if ($parentTask) {
            // Set the type based on the parentTask type
            if ($parentTask->type === 'project') {
                $task->type = 'task';

                // Insert into Task model
                $newTask = new Task();
                $newTask->text = $request->text;
                $newTask->start_date = $request->start_date;
                $newTask->duration = $request->duration;
                $newTask->progress = $request->has("progress") ? $request->progress : 0;
                $newTask->project_id = 1;
                $newTask->due_date  = 1;
                $newTask->$request->text;;
                $newTask->description = 1;
                $newTask->status_id = 1;
                $newTask->priority_id = 1;
                // Save the new Task
                $newTask->save();
            } elseif ($parentTask->type === 'task') {
                $task->type = 'activity';

                // Insert into Activity model
                $newActivity = new Activity();
                $newActivity->text = $request->text;
                $newActivity->start_date = $request->start_date;
                $newActivity->duration = $request->duration;
                $newActivity->progress = $request->has("progress") ? $request->progress : 0;

                // Save the new Activity
                $newActivity->save();
            } else {
                // Handle other types if necessary
                $task->type = 'new';
            }
        } else {
            // Handle the case where the parent task does not exist
            return response()->json([
                "action" => "error",
                "message" => "Parent task not found."
            ], 404);
        }

        // Save the MasterSchedule task
        $task->text = $request->text; // Additional fields if needed
        $task->save();

        // Commit the transaction
        DB::commit();

        // Set flash message for success
        Session::flash('message', 'Schedule created successfully.');

        // Redirect or return a response
        return response()->json([
            "action" => "inserted",
            "tid" => $task->id
        ]);

    } catch (\Exception $e) {
        // Rollback the transaction on error
        DB::rollBack();

        // Handle the exception
        return response()->json([
            "action" => "error",
            "message" => "Error occurred: " . $e->getMessage()
        ], 500);
    }
}*/
   /* public function data(Request $request)
    {
        $tasks = Task::with('activity')->get();
        $data = [  
            [  
                "id" => 1,  
                "text" => "Project 1",  
                "start_date" => "2023-10-01",  
                "duration" => 30,  
                "progress" => 0.4,  
                "children" => [  
                    [  
                        "id" => 2,  
                        "text" => "Task 1.1",  
                        "start_date" => "2023-10-01",  
                        "duration" => 15,  
                        "progress" => 0.6,  
                    ],  
                    [  
                        "id" => 3,  
                        "text" => "Task 1.2",  
                        "start_date" => "2023-10-10",  
                        "duration" => 15,  
                        "progress" => 0.2,  
                    ],  
                ],  
            ],  
            // More projects, tasks...  
        ];  
    
        return response()->json($data);
    }*/
}