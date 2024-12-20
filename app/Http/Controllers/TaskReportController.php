<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Client;
use App\Models\Status;
use App\Models\Priority;
use App\Models\Project;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TaskReportController extends Controller
{
    protected $workspace;
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // fetch session and use it in the entire class with constructor
            $this->workspace = Workspace::find(session()->get('workspace_id'));
            $this->user = getAuthenticatedUser();
            return $next($request);
        });
    }

    /**
     * Show the report form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showReportForm()
    {
        $users = $this->workspace->users;
        $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects : $this->user->projects;

        return view('tasks.report-form', [
            'users' => $users,
            'projects' => $projects,
        ]);
    }

    /**
     * Generate the report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
        public function generateReport($id = '')
        {
            $taskData = [
                'Started' => 6,
                'Not Started' => 10,
                'Completed' => 15,
                'Canceled' => 20,
            ];
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
            
            return view('tasks.tasks', ['project' => $project,'taskData' => $taskData,'tasks' => $tasks, 'users' => $users, 'clients' => $clients, 'projects' => $projects, 'toSelectTaskUsers' => $toSelectTaskUsers]);
        }
   
}