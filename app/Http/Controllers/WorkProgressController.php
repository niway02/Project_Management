<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\Subtask;
use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Status;
use App\Models\Priority;
use App\Models\UnitMeasure;

class WorkProgressController extends Controller
{
    /**
     * Display the work progress dashboard.
     *
     * @return \Illuminate\View\View
     */
 
    public function index() {
        // Retrieve all status records
        $status = Status::all();
        $priority = Priority::all();
        // Count activities based on their status
        $inProgress = Activity::where('status', 73)->count();
        $completed = Activity::where('status', 72)->count();
        $notStarted = Activity::where('status', 74)->count(); // Fixed missing assignment operator
        $blocked = Activity::where('status', 71)->count();
        // Calculate total activities
        $total = $inProgress + $completed + $notStarted + $blocked; // Changed total calculation to sum up counts
        // Retrieve all activities (if needed)
        $activities = Activity::all();
        $data = Activity::with('assignedTo')->get(); // Eager load assigned user
        $units= UnitMeasure::all();

        $activities = $data->map(function ($subtask) {
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
                'issue'  =>  $subtask->issue,  
                'kpi'  =>  $subtask->kpi,  
                'remark' => $subtask->remark,  
                'assignedTo' => $subtask->assignedTo->first_name?? 'N/A',
            ];
        });
       /* return response()->json([
            $activities
        ]);  */
        // Return the view with the necessary data
        return view('work-progress.index', compact('completed','units', 'notStarted', 'inProgress', 'blocked', 'total', 'status', 'activities'));
    }
 /*   public function index()
    {
        $users = [
            (object)[
                'name' => 'Alemayehu assa',
                'status' => 'not started',
                'remark' => "hury",
                'bage' => 'bad',
                'issue' => 'The weather is not good ',
            ],
            (object)[
                'name' => 'Wondimu Waijira',
                'status' => 'not started',
                'remark' => "hury",
                'bage' => 'bad',
                'issue' => 'labour issue',
            ],
            (object)[
                'name' => 'Abreham Mesifin',
                'status' => 'not started',
                'remark' => "hury",
                'bage' => 'bad',
                'issue' => 'Material Issue ',
            ],
            (object)[
                'name' => 'Tigist Gizachew',
                'status' => 'not started',
                'remark' => "hury",
                'bage' => 'bad',
                'issue' => 'No ',
            ],
            (object)[
                'name' => 'Melkamu TAmagne',
                'status' => 'started',
                'remark' => "good",
                'bage' => 'good',
                'issue' => 'No ',
            ],
            (object)[
                'name' => 'Robson Abera',
                'status' => 'in progress',
                'remark' => "good",
                'bage' => 'Good',
                'issue' => 'No ',
            ],
            (object)[
                'name' => 'Askale Mamo',
                'status' => 'in progress',
                'remark' => "good",
                'bage' => 'Good',
                'issue' => 'No ',
            ],
            (object)[
                'name' => 'Girum Erimiyas',
                'status' => 'completed',
                'remark' => "good",
                'bage' => 'Excelelnt',
                'issue' => 'No ',
            ],
            (object)[
                'name' => 'Helen Tibebu',
                'status' => 'completed',
                'remark' => "good",
                'bage' => 'Excelelnt',
                'issue' => 'No ',
            ],
            
            // Add 8 more users with similar data
        ];
    
        $started = 0;
        $completed = 0;
        $notStarted = 0;
        $inProgress = 0;
        $total = count($users);
    
        foreach ($users as $user) {
            if ($user->status == 'not started') {
                $notStarted++;
            } elseif ($user->status == 'started') {
                $started++;
            } elseif ($user->status == 'completed') {
                $completed++;
            }
            elseif ($user->status == 'in progress') {
                $inProgress++;
            }
        }
    
        return view('work-progress.index', compact('users','started' ,'completed', 'notStarted', 'inProgress', 'total'));
    }*/
    public function showWorkProgress()
    {
        // Hardcoded data for a construction company
        $employee = [
            'id' => 1,
            'name' => 'John Smith',
            'department' => 'Project Management',
        ];
    
        $projects = [
            [
                'id' => 1,
                'name' => 'Building A',
                'tasks' => [
                    [
                        'id' => 1,
                        'name' => 'Excavation and Foundation',
                        'subtasks' => [
                            [
                                'id' => 1,
                                'name' => 'Site Preparation',
                                'status' => 'started',
                                'materials' => ['Excavators', 'Concrete', 'Rebar'],
                                'equipment' => ['Bulldozers', 'Backhoes', 'Cranes'],
                                'labor' => ['John', 'Jane', 'Bob'],
                            ],
                            [
                                'id' => 2,
                                'name' => 'Pouring Concrete',
                                'status' => 'in progress',
                                'materials' => ['Concrete', 'Rebar', 'Formwork'],
                                'equipment' => ['Concrete Mixers', 'Vibrators', 'Trowels'],
                                'labor' => ['Charlie', 'David', 'Eve'],
                            ],
                            [
                                'id' => 3,
                                'name' => 'Curing Concrete',
                                'status' => 'not started',
                                'materials' => ['Curing Compound', 'Tarps', 'Water'],
                                'equipment' => ['Sprayers', 'Covers'],
                                'labor' => ['Frank', 'Alice', 'George'],
                            ],
                        ],
                    ],
                    [
                        'id' => 2,
                        'name' => 'Framing and Drywall',
                        'subtasks' => [
                            [
                                'id' => 4,
                                'name' => 'Wall Framing',
                                'status' => 'started',
                                'materials' => ['Lumber', 'Nails', 'Screws'],
                                'equipment' => ['Nail Guns', 'Saws', 'Drills'],
                                'labor' => ['Henry', 'Ivy', 'Jack'],
                            ],
                            [
                                'id' => 5,
                                'name' => 'Drywall Installation',
                                'status' => 'in progress',
                                'materials' => ['Drywall Panels', 'Drywall Compound', 'Tape'],
                                'equipment' => ['Drywall Lifts', 'Sanding Tools'],
                                'labor' => ['Kate', 'Liam', 'Mia'],
                            ],
                        ],
                    ],
                ],
            ],
      
        ];
    
        return view('work-progress.detail', compact('employee', 'projects'));
    }
    /**
     * Display the work progress for a specific user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        $user->load('tasks.subtasks');

        return view('work-progress.show', compact('user'));
    }
}