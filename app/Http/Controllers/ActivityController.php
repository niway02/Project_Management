<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Subtask;
use App\Models\User;
use App\Models\Client;
use App\Models\Status;
use App\Models\Priority;
use App\Models\Project;
use App\Models\Workspace;
use App\Models\Activity;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\MasterSchedule;
use App\Models\UnitMeasure;
use Illuminate\Support\Facades\Log;

class ActivityController extends Controller
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
    public function show($id)
    {
        $tasks = Task::all();
        $task = Task::findOrFail($id);
        $units = UnitMeasure::all();
        $subtasks1 = Task::select('id', 'title')
            ->with(['subtasks' => function ($query) {
                $query->select('start_date', 'end_date', 'name', 'progress', 'task_id', 'status');
            }])
            ->get();
    
        $subtasks = Subtask::with('materialCosts', 'equipmentCosts', 'laborCosts')
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
    
        return view('activity.activity_information', [
            'task' => $task,
            'units' =>$units,
            'tasks' => $tasks,
            'subtasks1' => $subtasks1,
            'data' => $data,
            'totalCompleted' => $totalCompleted,
            'totalPending' => $totalPending,
            'totalnotstarted' => $totalnotstarted,
            'totalCancelled' => $totalCancelled,
        ]);
    }
  /*  public function list(Request $request, $id = '', $type = '')
    {
        $search = request('search');
        $sort = request('sort', 'id');
        $order = request('order', 'DESC');
        $status = request('status', '');
        $user_id = request('user_id', '');
        $client_id = request('client_id', '');
        $start_date_from = request('project_start_date_from', '');
        $start_date_to = request('project_start_date_to', '');
        $end_date_from = request('project_end_date_from', '');
        $end_date_to = request('project_end_date_to', '');
        $is_favorites = request('is_favorites', '');
        
        $where = [];
        if ($status != '') {
            $where['status_id'] = $status;
        }
        
        if ($is_favorites) {
            $where['is_favorite'] = 1;
        }
    
        $activities = Activity::query();
    
        if ($id) {
            // Handle different types of relationships
            $id = explode('_', $id);
            $belongs_to = $id[0];
            $belongs_to_id = $id[1];
    
            if ($belongs_to == 'user') {
                $belongs_to = User::find($belongs_to_id);
                $activities->where('user_id', $belongs_to_id); // Assuming a relationship
            }
    
            if ($belongs_to == 'client') {
                $belongs_to = Client::find($belongs_to_id);
                $activities->where('client_id', $belongs_to_id); // Assuming a relationship
            }
        } else {
            // General query if no specific ID is provided
            $activities = isAdminOrHasAllDataAccess() ? Activity::query() : $this->user->activities();
        }
    
        if ($user_id) {
            $activities->where('user_id', $user_id);
        }
    
        if ($client_id) {
            $activities->where('client_id', $client_id);
        }
    
        if ($start_date_from && $start_date_to) {
            $activities->whereBetween('activity_start', [$start_date_from, $start_date_to]);
        }
    
        if ($end_date_from && $end_date_to) {
            $activities->whereBetween('activity_end', [$end_date_from, $end_date_to]);
        }
    
        $activities->when($search, function ($query) use ($search) {
            return $query->where('activity_name', 'like', '%' . $search . '%');
        });
    
        $activities->where($where);
        $totalActivities = $activities->count();
    
        $activities = $activities->orderBy($sort, $order)
            ->paginate(request("limit"))
            ->through(function ($activity) {
                return [
                    'id' => $activity->id,
                    'project_name' => $activity->task->project->title??"N/A", // Assuming relationship
                    'task_name' => $activity->task->title, // Assuming relationship
                    'activity_name' => $activity->activity_name,
                    'activity_start' => format_date($activity->start_date),
                    'activity_end' => format_date($activity->end_date),
                    'status_id' => "<span class='badge bg-label-{}'>{$activity->status}</span>",
                    'priority_id' => $activity->priority ? "<span class='badge bg-label-'>{$activity->priority}</span>" : "<span class='badge bg-label-secondary'>No Priority</span>",
                    'created_at' => format_date($activity->created_at, true),
                    'updated_at' => format_date($activity->updated_at, true),
                ];
            });
    
        return response()->json([
            "rows" => $activities->items(),
            "total" => $totalActivities,
        ]);
    }*/
   /* public function list(Request $request, $id = '', $type = ''){
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
       
      
        return response()->json([
            "rows" => $data,
        ]);
    }*/
    public function list($id = '')
    {
        // Retrieve filtering and sorting parameters from the request
        $search = request('search');
        $sort = request('sort', 'id'); // Default sort by 'id'
        $order = request('order', 'DESC'); // Default order is 'DESC'
        $status = request('status', ''); // Default status is empty
        $user_id = request('user_id', '');
        $start_date_from = request('task_start_date_from', '');
        $start_date_to = request('task_start_date_to', '');
        $end_date_from = request('task_end_date_from', '');
        $end_date_to = request('task_end_date_to', '');

        // Initialize the tasks query
           $tasksQuery = Activity::with('unitMeasure')->where('task_id', $id);


        // Apply filters based on request parameters
        if ($status) {
            $tasksQuery->where('status', $status);
        }

        if ($id) {
            $idParts = explode('_', $id);
            $belongsTo = $idParts[0]; // e.g., 'user'
            $belongsToId = $idParts[1] ?? null;

            if ($belongsTo === 'user' && $belongsToId) {
                $user = User::find($belongsToId);
                if ($user) {
                    $tasksQuery->where('user_id', $user->id);
                }
            }
        }

        if ($user_id) {
            $user = User::find($user_id);
            if ($user) {
                $tasksQuery->where('user_id', $user->id);
            }
        }

        if ($start_date_from && $start_date_to) {
            $tasksQuery->whereBetween('start_date', [$start_date_from, $start_date_to]);
        }

        if ($end_date_from && $end_date_to) {
            $tasksQuery->whereBetween('end_date', [$end_date_from, $end_date_to]);
        }

        if ($search) {
            $tasksQuery->where('name', 'like', '%' . $search . '%');
        }

        // Apply sorting
        $tasksQuery->orderBy($sort, $order);

        // Paginate tasks
        $paginatedTasks = $tasksQuery->paginate(10); // Adjust items per page as needed

        // Map the tasks to the desired format
        $data = $paginatedTasks->map(function ($subtask) {
            $status = Status::find($subtask->status);
            $priority = Priority::find($subtask->priority);
            
            // Get the current date
            $currentDate = Carbon::now();
            
            // Calculate start and end dates
            $startDate = Carbon::parse($subtask->start_date);
            $endDate = Carbon::parse($subtask->end_date);
            
            // Calculate total duration
            $durationDays = $endDate->diffInDays($startDate);
            $durationMonths = floor($durationDays / 30);
            $durationRemainingDays = $durationDays % 30;
        
            // Calculate remaining duration
            $remainingDays = $endDate->diffInDays($currentDate);
            
            // Format remaining time for readability
            $remaining = '';
            if ($remainingDays < 0) {
                // If the end date is in the past, mark the remaining as "passed"
                $remaining = 'Passed';
            } else {
                // Calculate remaining months and days
                $remainingMonths = floor($remainingDays / 30);
                $remainingRemainingDays = $remainingDays % 30;
        
                if ($remainingMonths > 0) {
                    $remaining .= $remainingMonths . ' m' . ($remainingMonths > 1 ? 's' : '');
                }
                if ($remainingRemainingDays > 0) {
                    $remaining .= ($remaining ? ' ' : '') . $remainingRemainingDays . ' d' . ($remainingRemainingDays > 1 ? 's' : '');
                }
            }
            
            // Format duration for readability
            $duration = '';
            if ($durationMonths > 0) {
                $duration .= $durationMonths . ' m' . ($durationMonths > 1 ? 's' : '');
            }
            if ($durationRemainingDays > 0) {
                $duration .= ($duration ? ' ' : '') . $durationRemainingDays . ' d' . ($durationRemainingDays > 1 ? 's' : '');
            }
            
            return [
                'wbs' => $subtask->task->project->id . "." . $subtask->task->id . "." . $subtask->id,
                'id' => $subtask->id,
                'activity_name' => $subtask->name,
                'start_date' => format_date($subtask->start_date, false, app('php_date_format'), 'Y-m-d'),
                'end_date' => $subtask->end_date,
                'progress' => $subtask->progress,
                'unit' => $subtask->unitMeasure->name,
                'quantity' => $subtask->quantity,
                'duration' => trim($duration),
                'remaining' => trim($remaining),
                'aproval_status' => "<span class='badge bg-label-danger'>{$subtask->aproval_status}</span>",
                'priority' => $priority ? "<span class='badge bg-label-{$priority->color}'>{$priority->title}</span>" : "<span class='badge bg-label-secondary'>No Priority</span>",
                'status' => $status ? "<span class='badge bg-label-{$status->color}'>{$status->title}</span>" : 'Unknown',
            ];
        });

        // Return JSON response with formatted tasks and total count
        return response()->json([
            "rows" => $data,
            "total" => $paginatedTasks->total(),
        ]);
    }

    // Function to generate reports
    public function generateReport(Request $request)
    {
        $format = $request->get('format');
        $status = $request->get('status');

        // Query to get tasks based on status
        $tasksQuery = Activity::query();

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
            return Excel::download(new TasksExport($tasks), 'tasks_report.xlsx');
        }

        return redirect()->back()->with('error', 'Invalid report format');
    }
    /*public function store(Request $request)
    {
        // Log the start of the method
        \Log::info('Store method started');
    
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
    
        // Log the incoming request data
        \Log::info('Incoming request data:', $request->all());
    
        // Continue with your existing code...
    }*/
  /*  public function store(Request $request)
    {
        // Log the start of the method
        \Log::info('Store method started');
    
        // Validate the incoming request data
        $validatedData = $request->validate([
            'task_id' => 'required|integer',
            'priority' => 'required|integer',
            'status_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer', 
            'start_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:start_date',
            'unit_id' => 'required|integer',
        ]);
    
        // Hardcoded data to insert into the activities table
        $activityData = [
            'task_id' => (int) $validatedData['task_id'],
            'name' => $validatedData['name'],
            'progress' => 0, // Default progress
            'priority' => (int) $validatedData['priority'],
            'start_date' => $validatedData['start_date'],
            'status' => (int) $validatedData['status_id'],
            'assigned_to' => null,
            'remark' => 'This is a sample remark',
            'issue' => null,
            'end_date' => '2024-11-10', // Example end date
            'aproval_status' => 'pending',
            'unit_id' => (int) $validatedData['unit_id'],
            'quantity' => (int) $validatedData['quantity'],
        ];
    
        try {
            // Create the new activity
            $new_task = Activity::create($activityData);
    
            // Flash success message
            session()->flash('success', 'Task created successfully.');
    
            // Return a JSON response indicating success
            return response()->json([
                'error' => false,
                'id' => $new_task->id,
                'message' => 'Task created successfully.'
            ]);
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error creating task: ' . $e->getMessage());
    
            // Flash error message
            session()->flash('error', 'Failed to create task. Please try again.');
    
            // Return a JSON response indicating failure
            return response()->json(['error' => true, 'message' => 'Failed to create task.'], 500);
        }
    }*/
 /*   public function store(Request $request)
    {
        // Log the start of the method
        \Log::info('Store method started');
        \Log::info('Incoming request data:', $request->all());
    
        // Validate the incoming request data
        $formFields = $request->validate([
            'task_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'status_id' => 'required|integer', 
            'priority' => 'required|integer',  
            'start_date' => 'required|date',
            'quantity' => 'required|integer', 
            'due_date' => 'required|date|date_format:Y-m-d|after_or_equal:start_date',
        ]);
    
        // Log the validated request data
        \Log::info('Validated request data:', $formFields);
    
        try {
            // Create the new activity
            $new_task = Activity::create([
                'task_id' => $formFields['task_id'],
                'name' => $formFields['name'],
                'status' => $formFields['status_id'],
                'progress' => 0, // Default progress
                'priority' => $formFields['priority'],
                'start_date' => $formFields['start_date'],
                'end_date' => $formFields['due_date'], // Assuming due_date is the end_date
                'assigned_to' => null, // Set if applicable
                'quantity' => $formFields['quantity'],
                'assigned_to'=> 1,
                // Add other fields as necessary
            ]);
    
            // Return a JSON response indicating success
            return response()->json([
                'error' => false,
                'id' => $new_task->id,
                'message' => 'Task created successfully.'
            ]);
        } catch (\Exception $e) {
            // Log any errors
            \Log::error('Error creating task: ' . $e->getMessage());
            return response()->json(['error' => true, 'message' => 'Failed to create task.'], 500);
        }
    }*/
    public function store1(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'task_id' => 'required|integer',
            'priority' => 'required|integer',
            'status_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'start_date' => 'required|date_format:d-m-Y', // Validate the date format
            'due_date' => 'required|date_format:d-m-Y|after_or_equal:start_date', // Validate the date format
            'unit_id' => 'required|integer',
        ]);
    
        try {
         
            // Log the incoming request data
            Log::info('Creating activity', $request->all());
    
            // Create the Activity
            $activity = Activity::create([
                'task_id' => 1,
                'name' => $validatedData['name'],
                'progress' => 0, // Default progress
                'priority' => 0,
                'start_date' => \Carbon\Carbon::createFromFormat('d-m-Y', $validatedData['start_date'])->format('Y-m-d H:i:s'), // Format to datetime
                'status' =>1,
                'assigned_to' => null, // Nullable if not provided
                'remark' => 'This is a sample remark', // Hard-coded remark, can be adjusted based on your needs
                'issue' => null, // Optional; adjust as needed
                'end_date' => \Carbon\Carbon::createFromFormat('d-m-Y', $validatedData['due_date'])->format('Y-m-d H:i:s'), // Format to datetime
                'unit_id' => 1,
                'quantity' => 1,
            ]);
    
            // Log the activity creation
            Log::info('Activity created', ['activity_id' => $activity->id]);
    
            // Flash message for success
            Session::flash('message', 'Activity created successfully!');
    
            // Redirect back to the previous page
            return redirect()->back();
    
        } catch (\Exception $e) {
            // Log the error message with full context
            Log::error('Error creating activity', [
                'request_data' => $request->all(),
                'error' => $e->getMessage()
            ]);
    
            // Flash an error message
            Session::flash('error', 'An error occurred while creating the activity: ' . $e->getMessage());
    
            // Redirect back with an error message
            return redirect()->back()->withInput();
        }
    }
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'task_id' => 'required|exists:tasks,id|integer', // Ensure task_id exists in the tasks table
            'name' => 'required|string|max:255',
            'priority' => 'nullable|integer',
            'status_id' => 'nullable|integer',
            'unit_id' => 'required|integer',
            'quantity' => 'required|integer',
            'start_date' => 'required|date_format:d-m-Y',
            'end_date' => 'required|date_format:d-m-Y|after_or_equal:start_date',
        ]);
        
        // Log the incoming request data
        Log::info('Incoming request data:', $request->all());
    
        try {
            // Create the Activity with user-provided values
            $activity = Activity::create([
                'task_id' => $validatedData['task_id'], // Correctly assign task_id
                'name' => $validatedData['name'],
                'progress' => 0, // Default progress
                'priority' => $validatedData['priority'] ?? 0, // Default priority if not provided
                'status' => $validatedData['status_id'] ?? null, // Use null if not provided
                'assigned_to' => null, // Nullable if not provided
                'remark' => '', // Hardcoded remark
                'issue' => null, // Optional; adjust as needed
                'unit_id' => $validatedData['unit_id'],
                'quantity' => $validatedData['quantity'],
                'start_date' => Carbon::createFromFormat('d-m-Y', $validatedData['start_date']), // Ensure correct date format
                'end_date' => Carbon::createFromFormat('d-m-Y', $validatedData['end_date']), // Ensure correct date format
            ]);
    
            // Log the created activity
            Log::info('Activity created', ['activity_id' => $activity->id]);
    
            // Create a corresponding entry in the MasterSchedule table
            $parentId = MasterSchedule::where('project_or_task_id', $validatedData['task_id'])
                ->where('type', 'task')
                ->pluck('id')
                ->first();
            
            MasterSchedule::create([
                'text' => $validatedData['name'],
                'start_date' => $activity->start_date, // Use the same start date from Activity
                'duration' => 110, // Set duration as required
                'progress' => 0, // Initially set progress to 0
                'type' => 'activity', // Set type as 'activity'
                'parent' => $parentId, // Link to the parent task
            ]);
    
            // Flash message for success
            Session::flash('message', 'Activity created successfully!');
    
            // Redirect back to the previous page
            return redirect()->back();
    
        } catch (\Exception $e) {
            // Log the error message with full context
            Log::error('Error creating activity', [
                'request_data' => $request->all(),
                'error' => $e->getMessage()
            ]);
    
            // Flash an error message
            Session::flash('error', 'An error occurred while creating the activity: ' . $e->getMessage());
    
            // Redirect back with an error message
          //  return redirect()->back()->withInput();
        }
    }
    
   /* public function actvitySellection(Request $request)
    {
        // Retrieve the selected activity IDs
        $selectedActivities = $request->input('selected_activities', []);
        $selectedTasks = $request->input('selected_tasks', []);
        // Check if any activities were selected
        if (!empty($selectedActivities)) {
            // Process the selected activities as needed
            // Example: Save them to a checklist or perform other actions
            foreach ($selectedActivities as $activityId) {
                // Perform actions with each $activityId
            }
    
            return redirect()->back()->with('success', 'Selected activities have been processed.');
        }
    
        return redirect()->back()->with($selectedTasks);
    }*/
    /*public function actvitySellection(Request $request)
    {
        $selectedTasks = $request->input('selected_tasks', []);
    
        // Initialize an array to hold the tasks' data
        $selectedActivity = [];
    
        foreach ($selectedTasks as $taskId) {
            $selectedActivity[] = [
                'id' => $taskId,
                'activity_name' => $request->input("activity_name.$taskId"),
                'wbs' => $request->input("wbs.$taskId"),
                'status' => $request->input("status.$taskId"),
                'priority' => $request->input("priority.$taskId"),
                'start_date' => $request->input("start_date.$taskId"),
                'end_date' => $request->input("end_date.$taskId"),
                'progress' => $request->input("progress.$taskId"),
            ];
        }
        // Pass the selected activities to the view
        return view('activity.selelction', ['selectedActivity' => $selectedActivity]);
    }*/
    public function actvitySellection(Request $request)
    {
        $selectedActivity = Activity::where('id',1);
        //dd($request);
        // Pass the tasks to a view or process as needed
        return view('checklist/checklist',compact('selectedActivity'));
    }
    public function update(Request $request, $activityId)
    {
      
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'quantity' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Find the activity by ID
        $activity = Activity::findOrFail($activityId);

        // Update the activity with the validated data
        $activity->update([
            'name' => 'required|string|max:255',
            'priority' => 'required|exists:priorities,id',
            'status_id' => 'required|exists:statuses,id',
            'unit_id' => 'required|exists:units,id',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Redirect back to the activities list with a success message
        return redirect()->route('tasks.activities.index', $activity->task_id)
                         ->with('success', 'Activity updated successfully.');
    }

    public function destroy($activityId)
    {
    $activity = Activity::findOrFail($activityId);
    $activity->delete();
    Session::flash('message', 'Activity deleted successfully.');
    return response()->json(['success' => 'Activity deleted successfully.']);
   }
}
