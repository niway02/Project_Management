<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Client;
use App\Models\Status;
use App\Models\Priority;
use App\Models\Project;
use App\Models\Workspace;
use App\Models\Milestone;
use App\Models\Tag;
use App\Models\ProjectUser;
use Illuminate\Http\Request;
use App\Models\ProjectClient;
use App\Services\DeletionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Exception;

class ProjectReportController extends Controller
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
    public function generateReport(Request $request, $type = null)
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
        return view('projects.report', ['projects' => $projects,'taskData'=>$taskData, 'auth_user' => $this->user, 'toSelectProjectUsers' => $toSelectProjectUsers, 'toSelectProjectClients' => $toSelectProjectClients, 'selectedTags' => $selectedTags, 'is_favorite' => $is_favorite]);
    }
}