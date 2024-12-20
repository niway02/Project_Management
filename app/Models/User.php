<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Carbon\Carbon;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'country_code',
        'profile',
        'address',
        'city',
        'state',
        'country',
        'zip',
        'photo',
        'dob',
        'doj',
        'status',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeFilter($query, array $filters)
    {
        if ($filters['search'] ?? false) {
            $query->where('first_name', 'like', '%' . request('search') . '%')
                ->orWhere('last_name', 'like', '%' . request('search') . '%')
                ->orWhere('role', 'like', '%' . request('search') . '%');
        }
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class)->where('projects.workspace_id', session()->get('workspace_id'));
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_user')
            ->where('tasks.workspace_id', session()->get('workspace_id'));
    }

    public function status_tasks($status_id)
    {
        return $this->belongsToMany(Task::class, 'task_user')
            ->where('tasks.workspace_id', session()->get('workspace_id'))->where('tasks.status_id', $status_id);
    }

    public function labor()
    {
        return $this->hasOne(Labor::class);
    }
    public function status_projects($status_id)
    {
        return $this->belongsToMany(Project::class, 'project_user')
            ->where('projects.workspace_id', session()->get('workspace_id'))->where('projects.status_id', $status_id);
    }

    public function project_tasks($project_id)
    {
        return $this->belongsToMany(Task::class, 'task_user')
            ->where('tasks.workspace_id', session()->get('workspace_id'))->where('tasks.project_id', $project_id)->get();
    }

    // public function meetings()
    // {
    //     return $this->belongsToMany(Meeting::class)->where('workspace_id', '=', session()->get('workspace_id'));
    // }
    public function meetings($status = null)
    {
        $meetings = $this->belongsToMany(Meeting::class)->where('workspace_id', '=', session()->get('workspace_id'));

        if ($status !== null && $status == 'ongoing') {
            $meetings->where('start_date_time', '<=', Carbon::now(config('app.timezone')))
                ->where('end_date_time', '>=', Carbon::now(config('app.timezone')));
        }

        return $meetings;
    }
    public function workspaces()
    {
        return $this->belongsToMany(Workspace::class);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function todos($status = null, $search = '')
    {
        $query = $this->morphMany(Todo::class, 'creator')->where('workspace_id', session()->get('workspace_id'));

        if ($status !== null) {
            $query->where('is_completed', $status);
        }
        if ($search !== '') {
            $query->where('title', 'like', '%' . $search . '%');
        }

        return $query;
    }

    public function payslips()
    {
        return $this->hasMany(Payslip::class, 'user_id')
            ->orWhere(function ($query) {
                $query->where('created_by', 'u_' . $this->getKey());
            });
    }


    public function contracts()
    {
        return Contract::where(function ($query) {
            $query->where('created_by', 'u_' . $this->getKey());
        })->get();
    }

    public function profile()
    {
        return $this->morphOne(Profile::class, 'profileable');
    }

    public function getresult()
    {
        return str($this->first_name . " " . $this->last_name);
    }

    public function leave_requests()
    {
        return $this->hasMany(LeaveRequest::class)->where('workspace_id', session()->get('workspace_id'));
    }

    public function leaveEditors()
    {
        return $this->hasMany(LeaveEditor::class, 'user_id');
    }
    public function notes($search = '')
    {
        $query = Note::where(function ($query) {
            $query->where('creator_id', 'u_' . $this->getKey())
                ->where('workspace_id', session()->get('workspace_id'));
        });

        if ($search !== '') {
            $query->where('title', 'like', '%' . $search . '%');
        }

        return $query->get();
    }

    public function timesheets()
    {
        return $this->hasMany(TimeTracker::class, 'user_id', 'id')
            ->where('workspace_id', session()->get('workspace_id'));
    }

    public function estimates_invoices()
    {
        return EstimatesInvoice::where(function ($query) {
            $query->where('created_by', 'u_' . $this->getKey());
        })->get();
    }

    public function expenses()
    {
        $userId = $this->getKey(); // Get the current user's ID

        return $this->hasMany(Expense::class, 'user_id')
            ->orWhere(function ($query) use ($userId) {
                $query->where('created_by', 'u_' . $userId);
            });
    }

    public function payments()
    {
        $userId = $this->getKey(); // Get the current user's ID

        return $this->hasMany(Payment::class, 'user_id')
            ->orWhere(function ($query) use ($userId) {
                $query->where('created_by', 'u_' . $userId);
            });
    }

    public function can($ability, $arguments = [])
    {
        $isAdmin = $this->hasRole('admin'); // Check if the user has the 'admin' role

        // Check if the user is an admin or has the specific permission
        if ($isAdmin || $this->hasPermissionTo($ability)) {
            return true;
        }

        // For other cases, use the original can() method
        return parent::can($ability, $arguments);
    }


    public function getlink()
    {
        return str('/users/profile/show/' . $this->id);
    }
    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'notification_user')->where('notifications.workspace_id', session()->get('workspace_id'))->withPivot('read_at');
    }
}
