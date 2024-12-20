<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasPermissions;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Client extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPermissions, CanResetPassword;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'country_code',
        'company',
        'address',
        'city',
        'state',
        'country',
        'zip',
        'photo',
        'dob',
        'doj',
        'status',
        'email_verified_at'
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

    protected $guard = 'client';

    public function projects()
    {
        return $this->belongsToMany(Project::class)->where('projects.workspace_id', session()->get('workspace_id'));
    }

    public function meetings()
    {
        return $this->belongsToMany(Meeting::class);
    }
    public function workspaces()
    {
        return $this->belongsToMany(Workspace::class);
    }

    public function getresult()
    {
        return str($this->first_name . " " . $this->last_name);
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

    public function status_projects($status_id)
    {
        return $this->belongsToMany(Project::class, 'client_project')
            ->where('projects.workspace_id', session()->get('workspace_id'))->where('projects.status_id', $status_id);
    }
    public function status_tasks($status_id)
    {
        return Task::whereHas('project.clients', function ($query) use ($status_id) {
            $query->where('clients.id', getAuthenticatedUser()->id)->where('tasks.workspace_id', session()->get('workspace_id'))->where('tasks.status_id', $status_id);
        })->get();
    }

    public function tasks()
    {
        return Task::whereIn('project_id', $this->projects->pluck('id'));
    }

    public function project_tasks($project_id)
    {
        return Task::whereHas('project.clients', function ($query) use ($project_id) {
            $query->where('clients.id', getAuthenticatedUser()->id)->where('tasks.workspace_id', session()->get('workspace_id'))->where('tasks.project_id', $project_id);
        })->get();
    }

    public function contracts()
    {
        return Contract::where(function ($query) {
            $query->where('created_by', 'c_' . $this->getKey())
                ->orWhere('client_id', $this->getKey());
        })->get();
    }

    public function notes($search = '')
    {
        $query = Note::where(function ($query) {
            $query->where('creator_id', 'c_' . $this->getKey())
                ->where('workspace_id', session()->get('workspace_id'));
        });

        if ($search !== '') {
            $query->where('title', 'like', '%' . $search . '%');
        }

        return $query->get();
    }

    public function estimates_invoices()
    {
        return $this->hasMany(EstimatesInvoice::class, 'client_id')
            ->orWhere(function ($query) {
                $query->where('created_by', 'c_' . $this->getKey());
            });
    }

    public function expenses()
    {
        return Expense::where(function ($query) {
            $query->where('created_by', 'c_' . $this->getKey());
        })->get();
    }

    public function payments()
    {
        return Payment::where(function ($query) {
            $query->where('created_by', 'c_' . $this->getKey());
        })->get();
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function profile()
    {
        return $this->morphOne(Profile::class, 'profileable');
    }

    public function getlink()
    {
        return str('/clients/profile/show/' . $this->id);
    }
    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'client_notifications')->where('notifications.workspace_id', session()->get('workspace_id'))->withPivot('read_at');
    }
}
