<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Task;
use App\Models\Todo;
use App\Models\User;
use App\Models\Client;
use App\Models\Meeting;
use App\Models\Project;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;

class SearchController extends Controller
{

    public function search(Request $request)
    {
        $query = $request->input('q');
        $workspace_id = session('workspace_id');
        $authUser = getAuthenticatedUser();
        // Search in Projects
        if ($authUser->can('manage_projects')) {
            $projects = Project::where('title', 'like', '%' . $query . '%')
                ->where('workspace_id', $workspace_id)
                ->get();
            foreach ($projects as $project) {
                if (isAdminOrHasAllDataAccess() || $this->hasAccess($authUser, 'projects', Project::class, $project->id)) {
                    $results['projects'][] = [
                        'id' => $project->id,
                        'title' => $project->title
                    ];
                }
            }
        }

        // Search in Tasks
        if ($authUser->can('manage_tasks')) {
            $tasks = Task::where('title', 'like', '%' . $query . '%')
                ->where('workspace_id', $workspace_id)
                ->get();
            foreach ($tasks as $task) {
                if (isAdminOrHasAllDataAccess() || $this->hasAccess($authUser, 'tasks', Task::class, $task->id)) {
                    $results['tasks'][] = [
                        'id' => $task->id,
                        'title' => $task->title
                    ];
                }
            }
        }

        // Search in Meetings
        if ($authUser->can('manage_meetings')) {
            $meetings = Meeting::where('title', 'like', '%' . $query . '%')
                ->where('workspace_id', $workspace_id)
                ->get();
            foreach ($meetings as $meeting) {
                if (isAdminOrHasAllDataAccess() || $this->hasAccess($authUser, 'meetings', Meeting::class, $meeting->id)) {
                    $results['meetings'][] = [
                        'id' => $meeting->id,
                        'title' => $meeting->title
                    ];
                }
            }
        }

        // Search in Workspace
        if ($authUser->can('manage_workspaces')) {
            $workspaces = Workspace::where('title', 'like', '%' . $query . '%')
                ->get();
            foreach ($workspaces as $workspace) {
                if (isAdminOrHasAllDataAccess() || $this->hasAccess($authUser, 'workspaces', Workspace::class, $workspace->id)) {
                    $results['workspaces'][] = [
                        'id' => $workspace->id,
                        'title' => $workspace->title
                    ];
                }
            }
        }

        // Search for users by first name and last name using pivot table
        if ($authUser->can('manage_users')) {
            $users = User::whereHas('workspaces', function ($queryBuilder) use ($workspace_id, $query) {
                $queryBuilder->where('workspace_id', $workspace_id)
                    ->where(function ($subQuery) use ($query) {
                        $subQuery->where('first_name', 'like', '%' . $query . '%')
                            ->orWhere('last_name', 'like', '%' . $query . '%')
                            // Add condition to match combination of first name and last name
                            ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%' . $query . '%');
                    });
            })
                ->get();

            foreach ($users as $user) {
                $results['users'][] = [
                    'id' => $user->id,
                    'title' => $user->first_name . ' ' . $user->last_name
                ];
            }
        }


        // Search for clients by first name and last name using pivot table
        if ($authUser->can('manage_clients')) {
            $clients = Client::whereHas('workspaces', function ($queryBuilder) use ($workspace_id, $query) {
                $queryBuilder->where('workspace_id', $workspace_id)
                    ->where(function ($subQuery) use ($query) {
                        $subQuery->where('first_name', 'like', '%' . $query . '%')
                            ->orWhere('last_name', 'like', '%' . $query . '%')
                            // Add condition to match combination of first name and last name
                            ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%' . $query . '%');
                    });
            })
                ->get();
            foreach ($clients as $client) {
                $results['clients'][] = [
                    'id' => $client->id,
                    'title' => $client->first_name . ' ' . $client->last_name
                ];
            }
        }

        // Search in Notes
        $notes = $authUser->notes($query);
        $results['notes'] = $notes->map(function ($note) {
            return [
                'id' => $note->id,
                'title' => $note->title
            ];
        })->toArray();


        // Search in Todos
        $todos = $authUser->todos(null, $query)->get();

        $results['todos'] = $todos->map(function ($todo) {
            return [
                'id' => $todo->id,
                'title' => $todo->title
            ];
        })->toArray();


        return response()->json(['results' => $results]);
    }

    private function hasAccess($user, $typeKey, $typeModel, $itemId)
    {
        // Check if $user->$typeKey is a relationship or a collection
        if ($user->$typeKey() instanceof Illuminate\Database\Eloquent\Relations\Relation) {
            return $user->$typeKey->contains($typeModel::find($itemId));
        } else {
            return $user->$typeKey()->get()->contains($typeModel::find($itemId));
        }
    }
}
