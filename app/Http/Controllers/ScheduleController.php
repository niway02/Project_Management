<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Activity;
use App\Models\User;
use App\Models\Workspace;

class ScheduleController extends Controller
{
    protected $workspace;
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // Fetch session and use it in the entire class with constructor
            $this->workspace = Workspace::find(session()->get('workspace_id'));
            $this->user = auth()->user(); // Get the authenticated user directly
            return $next($request);
        });
    }
  public function index()
    {
        $tasks = Task::with(['activities:id,task_id,name']) // Select specific fields
        ->select('id', 'start_date', 'due_date', 'title') // Include 'id' for the relationship
        ->get();

/*return response()->json([
    'total' => $tasks,
]);*/
        return view('schedule.index', [
            'tasks' => $tasks
        ]);
    }
   
    public function memberSchedule()
    {
        // Fetch all tasks related to the authenticated user
        $tasks = $this->user->tasks() // Use the authenticated user
            ->with(['activities:id,task_id,name']) // Eager load activities
            ->select('tasks.id', 'tasks.start_date', 'tasks.due_date', 'tasks.title') // Specify table name for id
            ->get();

        return view('schedule.index', [
            'tasks' => $tasks
        ]);
    }
}