<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use App\Models\Workspace;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\UnitMeasure;

class HomeController extends Controller
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
        $projects = isAdminOrHasAllDataAccess() ? $this->workspace->projects ?? [] : $this->user->projects ?? [];
        $units = UnitMeasure::all();
        $tasks = isAdminOrHasAllDataAccess() ? $this->workspace->tasks ?? [] : $this->user->tasks() ?? [];
        $tasks = $tasks ? $tasks->count() : 0;
        $users = $this->workspace->users ?? [];
        $clients = $this->workspace->clients ?? [];
        $todos = $this->user->todos()
        ->orderBy('is_completed', 'asc')
        ->orderBy('created_at', 'desc')
        ->paginate(5);
        $total_todos = $this->user->todos;
        $meetings = isAdminOrHasAllDataAccess() ? $this->workspace->meetings ?? [] : $this->user->meetings ?? [];
        return view('dashboard', ['users' => $users, 'clients' => $units,'units' => $clients, 'projects' => $projects, 'tasks' => $tasks, 'todos' => $todos, 'total_todos' => $total_todos, 'meetings' => $meetings, 'auth_user' => $this->user]);
    }

    public function upcoming_birthdays()
    {
        $search = request('search');
        $sort = request('sort', 'dob');
        $order = request('order', 'ASC');
        $upcoming_days = (int)request('upcoming_days', 30); // Cast to integer, default to 30 if not provided
        $user_id = request('user_id');

        $users = $this->workspace->users();

        // Calculate the current date
        $currentDate = today();

        // Calculate the range for upcoming birthdays (e.g., 365 days from today)
        $upcomingDate = $currentDate->copy()->addDays($upcoming_days);

        $currentDateString = $currentDate->format('Y-m-d');
        $upcomingDateString = $upcomingDate->format('Y-m-d');

        $users = $users->whereRaw("DATE_ADD(DATE_FORMAT(dob, '%Y-%m-%d'), INTERVAL YEAR(CURRENT_DATE()) - YEAR(dob) + IF(DATE_FORMAT(CURRENT_DATE(), '%m-%d') > DATE_FORMAT(dob, '%m-%d'), 1, 0) YEAR) BETWEEN ? AND ? AND DATEDIFF(DATE_ADD(DATE_FORMAT(dob, '%Y-%m-%d'), INTERVAL YEAR(CURRENT_DATE()) - YEAR(dob) + IF(DATE_FORMAT(CURRENT_DATE(), '%m-%d') > DATE_FORMAT(dob, '%m-%d'), 1, 0) YEAR), CURRENT_DATE()) <= ?", [$currentDateString, $upcomingDateString, $upcoming_days])
            ->orderByRaw("DATEDIFF(DATE_ADD(DATE_FORMAT(dob, '%Y-%m-%d'), INTERVAL YEAR(CURRENT_DATE()) - YEAR(dob) + IF(DATE_FORMAT(CURRENT_DATE(), '%m-%d') > DATE_FORMAT(dob, '%m-%d'), 1, 0) YEAR), CURRENT_DATE()) " . $order);
        // Search by full name (first name + last name)
        if ($search) {
            $users->where(function ($query) use ($search) {
                $query->where('first_name', 'LIKE', "%$search%")
                    ->orWhere('last_name', 'LIKE', "%$search%")
                    ->orWhere('dob', 'LIKE', "%$search%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%$search%"]);
            });
        }

        if ($user_id) {
            $users->where('users.id', $user_id);
        }

        $total = $users->count();

        // $users = $users->orderBy($sort, $order)
            $users = $users->paginate(request("limit"))
            ->through(function ($user) use ($currentDate) {
                // Convert the 'dob' field to a DateTime object
                $birthdayDate = \Carbon\Carbon::createFromFormat('Y-m-d', $user->dob);

                // Set the year to the current year
                $birthdayDate->year = $currentDate->year;

                if ($birthdayDate->lt($currentDate)) {
                    // If the birthday has already passed this year, calculate for next year
                    $birthdayDate->year = $currentDate->year + 1;
                }

                // Calculate days left until the user's birthday
                $daysLeft = $currentDate->diffInDays($birthdayDate);

                $emoji = '';
                $label = '';

                if ($daysLeft === 0) {
                    $emoji = ' 🥳';
                    $label = ' <span class="badge bg-success">' . get_label('today', 'Today') . '</span>';
                } elseif ($daysLeft === 1) {
                    $label = ' <span class="badge bg-primary">' . get_label('tomorow', 'Tomorrow') . '</span>';
                } elseif ($daysLeft === 2) {
                    $label = ' <span class="badge bg-warning">' . get_label('day_after_tomorow', 'Day after tomorrow') . '</span>';
                }

                // Only return the user data if the days left is less than or equal to the upcoming days
                // if ($daysLeft <= $upcoming_days) {
                return [
                    'id' => $user->id,
                    'member' => $user->first_name . ' ' . $user->last_name . $emoji . "<ul class='list-unstyled users-list m-0 avatar-group d-flex align-items-center'><a href='/users/profile/" . $user->id . "' target='_blank'><li class='avatar avatar-sm pull-up'  title='" . $user->first_name . " " . $user->last_name . "'><img src='" . ($user->photo ? asset('storage/' . $user->photo) : asset('storage/photos/no-image.jpg')) . "' alt='Avatar' class='rounded-circle'>",
                    'age' => $currentDate->diffInYears($birthdayDate),
                    'days_left' => $daysLeft,
                    'dob' => format_date($birthdayDate) . $label, // Format as needed
                ];
                // }
            });

        return response()->json([
            "rows" => $users->items(),
            "total" => $total,
        ]);
    }




    public function upcoming_work_anniversaries()
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "doj";
        $order = (request('order')) ? request('order') : "ASC";
        $upcoming_days = (request('upcoming_days')) ? request('upcoming_days') : 30;
        $user_id = (request('user_id')) ? request('user_id') : "";
        $users = $this->workspace->users();

        $currentDate = today();

        // Calculate the range for upcoming birthdays (e.g., 365 days from today)
        $upcomingDate = $currentDate->copy()->addDays($upcoming_days);

        $currentDateString = $currentDate->format('Y-m-d');
        $upcomingDateString = $upcomingDate->format('Y-m-d');

        $users = $users->whereRaw("DATE_ADD(DATE_FORMAT(doj, '%Y-%m-%d'), INTERVAL YEAR(CURRENT_DATE()) - YEAR(doj) + IF(DATE_FORMAT(CURRENT_DATE(), '%m-%d') > DATE_FORMAT(doj, '%m-%d'), 1, 0) YEAR) BETWEEN ? AND ? AND DATEDIFF(DATE_ADD(DATE_FORMAT(doj, '%Y-%m-%d'), INTERVAL YEAR(CURRENT_DATE()) - YEAR(doj) + IF(DATE_FORMAT(CURRENT_DATE(), '%m-%d') > DATE_FORMAT(doj, '%m-%d'), 1, 0) YEAR), CURRENT_DATE()) <= ?", [$currentDateString, $upcomingDateString, $upcoming_days])
            ->orderByRaw("DATEDIFF(DATE_ADD(DATE_FORMAT(doj, '%Y-%m-%d'), INTERVAL YEAR(CURRENT_DATE()) - YEAR(doj) + IF(DATE_FORMAT(CURRENT_DATE(), '%m-%d') > DATE_FORMAT(doj, '%m-%d'), 1, 0) YEAR), CURRENT_DATE()) " . $order);

        // Search by full name (first name + last name)
        if (!empty($search)) {
            $users->where(function ($query) use ($search) {
                $query->where('first_name', 'LIKE', "%$search%")
                    ->orWhere('last_name', 'LIKE', "%$search%")
                    ->orWhere('doj', 'LIKE', "%$search%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%$search%"]);
            });
        }
        if (!empty($user_id)) {
            $users->where('users.id', $user_id);
        }
        $total = $users->count();

        // $users = $users->orderBy($sort, $order)
            $users = $users->paginate(request("limit"))
            ->through(function ($user) use ($currentDate) {
                // Convert the 'dob' field to a DateTime object
                $doj = \Carbon\Carbon::createFromFormat('Y-m-d', $user->doj);

                // Set the year to the current year
                $doj->year = $currentDate->year;

                if ($doj->lt($currentDate)) {
                    // If the birthday has already passed this year, calculate for next year
                    $doj->year = $currentDate->year + 1;
                }

                // Calculate days left until the user's birthday
                $daysLeft = $currentDate->diffInDays($doj);
                $label = '';
                $emoji = '';
                if ($daysLeft === 0) {
                    $emoji = ' 🥳';
                    $label = ' <span class="badge bg-success">' . get_label('today', 'Today') . '</span>';
                } elseif ($daysLeft === 1) {
                    $label = ' <span class="badge bg-primary">' . get_label('tomorow', 'Tomorrow') . '</span>';
                } elseif ($daysLeft === 2) {
                    $label = ' <span class="badge bg-warning">' . get_label('day_after_tomorow', 'Day after tomorrow') . '</span>';
                }


                return [
                    'id' => $user->id,
                    'member' => $user->first_name . ' ' . $user->last_name . $emoji . "<ul class='list-unstyled users-list m-0 avatar-group d-flex align-items-center'><a href='/users/profile/" . $user->id . "' target='_blank'><li class='avatar avatar-sm pull-up'  title='" . $user->first_name . " " . $user->last_name . "'>
                    <img src='" . ($user->photo ? asset('storage/' . $user->photo) : asset('storage/photos/no-image.jpg')) . "' alt='Avatar' class='rounded-circle'>",
                    'wa_date' => format_date($doj) . $label, // Format as needed
                    'days_left' => $daysLeft,
                ];
            });

        return response()->json([
            "rows" => $users->items(),
            "total" => $total,
        ]);
    }



    public function members_on_leave()
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "from_date";
        $order = (request('order')) ? request('order') : "ASC";
        $upcoming_days = (request('upcoming_days')) ? request('upcoming_days') : 30;
        $user_id = (request('user_id')) ? request('user_id') : "";

        // Calculate the current date
        $currentDate = today();

        // Calculate the range for upcoming work anniversaries (e.g., 30 days from today)
        $upcomingDate = $currentDate->copy()->addDays($upcoming_days);
        // Query members on leave based on 'start_date' in the 'leave_requests' table
        $leaveUsers = DB::table('leave_requests')
            ->join('users', 'leave_requests.user_id', '=', 'users.id')
            ->where(function ($query) use ($currentDate) {
                $query->where('from_date', '<=', $currentDate)
                    ->where('to_date', '>', $currentDate);
            })
            ->orWhere(function ($query) use ($currentDate, $upcomingDate) {
                $query->where(function ($query) use ($currentDate) {
                    $query->where('from_date', '>=', $currentDate);
                })
                    ->orWhere(function ($query) use ($currentDate, $upcomingDate) {
                        $query->where('from_date', '<=', $upcomingDate)
                            ->where('to_date', '>', $currentDate);
                    });
            })
            ->where('leave_requests.status', '=', 'approved')
            ->where('workspace_id', '=', $this->workspace->id);

        // Search by full name (first name + last name)
        if (!empty($search)) {
            $leaveUsers->where(function ($query) use ($search) {
                $query->where('first_name', 'LIKE', "%$search%")
                    ->orWhere('last_name', 'LIKE', "%$search%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%$search%"]);
            });
        }
        if (!empty($user_id)) {
            $leaveUsers->where('user_id', $user_id);
        }
        $total = $leaveUsers->count();

        $leaveUsers = $leaveUsers->orderBy($sort, $order)
            ->paginate(request("limit"))
            ->through(function ($user) use ($currentDate) {

                $fromDate = \Carbon\Carbon::createFromFormat('Y-m-d', $user->from_date);

                // Set the year to the current year
                $fromDate->year = $currentDate->year;

                // Calculate days left until the user's return from leave
                $daysLeft = $currentDate->diffInDays($fromDate);
                if ($fromDate->lt($currentDate)) {
                    $daysLeft = 0;
                }
                $label = '';

                if ($daysLeft === 0) {
                    $label = ' <span class="badge bg-success">' . get_label('on_leave', 'On leave') . '</span>';
                } elseif ($daysLeft === 1) {
                    $label = ' <span class="badge bg-primary">' . get_label('on_leave_tomorrow', 'On leave from tomorrow') . '</span>';
                } elseif ($daysLeft === 2) {
                    $label = ' <span class="badge bg-warning">' . get_label('on_leave_day_after_tomorow', 'On leave from day after tomorrow') . '</span>';
                }

                $fromDate = Carbon::parse($user->from_date);
                $toDate = Carbon::parse($user->to_date);

                $duration = $fromDate->diffInDays($toDate) + 1;
                return [
                    'id' => $user->id,
                    'member' => $user->first_name . ' ' . $user->last_name . ' ' . $label . "<ul class='list-unstyled users-list m-0 avatar-group d-flex align-items-center'><a href='/users/profile/" . $user->id . "' target='_blank'><li class='avatar avatar-sm pull-up'  title='" . $user->first_name . " " . $user->last_name . "'>
            <img src='" . ($user->photo ? asset('storage/' . $user->photo) : asset('storage/photos/no-image.jpg')) . "' alt='Avatar' class='rounded-circle'>",
                    'from_date' => format_date($user->from_date),
                    'to_date' => format_date($user->to_date),
                    'duration' => $duration . ' day' . ($duration > 1 ? 's' : ''),
                    'days_left' => $daysLeft,
                ];
            });

        return response()->json([
            "rows" => $leaveUsers->items(),
            "total" => $total,
        ]);
    }
}
