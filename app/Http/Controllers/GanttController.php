<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Project;
use App\Models\MasterSchedule;
use Illuminate\Http\Request;
use App\Models\Task;

class GanttController extends Controller
{
    public function index()
    {
        // Fetch projects with their associated tasks and activities
        $projects = MasterSchedule::all();
    
        // Prepare the data for the Gantt chart
        $data = $projects->map(function ($project) {
            return [
                "id" => $project->id,  // Assuming MasterSchedule has an 'id' field
                "text" => $project->text,
                "parent" => $project->parent,
                "duration" => $project->duration,
                "start_date" => $project->start_date,  // Format as needed
                "status" => $project->status,  // Assuming MasterSchedule has a 'status' field
            ];
        });
        return response()->json($data);  
    }
    /*public function data(Request $request)
    {
        $tasks = Task::with('activity')->get();
        $data = [
            "data" => [],
            "links" => []
        ];
        
    
        foreach ($tasks as $task) {
            $taskData = [
                "id" => $task->id,
                "text" => $task->title,
                "start_date" => Carbon::parse($task->start_date)->toISOString(),
                "end_date" => Carbon::parse($task->due_date)->toISOString(),
                "progress" => 0 // You can calculate the progress based on your application's logic
            ];
    
            $taskData["activity"] = [];
    
            // Add child tasks
            foreach ($task->activity as $child) {
                $childData = [
                    "id" => $child->id,
                    "text" => $child->name,
                    "start_date" => Carbon::parse($child->start_date)->toISOString(),
                    "end_date" => Carbon::parse($child->due_date)->toISOString(),
                    "progress" => 0, // You can calculate the progress based on your application's logic
                    "parent" => $task->id
                ];
                $taskData["activity"][] = $childData;
            }
    
            $data["data"][] = $taskData;
    
            // Add links between tasks and child activities
            foreach ($taskData["activity"] as $child) {
                $data["links"][] = [
                    "id" => $child["id"],
                    "source" => $taskData["id"],
                    "target" => $child["id"],
                    "type" => "finish-start"
                ];
            }
        }
    
        return response()->json($data);
    }*/
    public function data()  
    {  
        // Hard-coded Gantt chart data  
        $data = [  
            ['id' => 1, 'text' => 'Project 1', 'start_date' => '2023-04-01', 'end_date' => '2023-04-10', 'duration' => 10],  
            ['id' => 2, 'text' => 'Project 2', 'start_date' => '2023-04-05', 'end_date' => '2023-04-15', 'duration' => 10],  
            ['id' => 3, 'text' => 'Project 3', 'start_date' => '2023-04-12', 'end_date' => '2023-04-20', 'duration' => 9],  
        ];  
    
        return response()->json($data);  
    }

}