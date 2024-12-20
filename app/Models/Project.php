<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Project extends Model implements HasMedia
{
    protected $casts = [
        'start_date' => 'datetime', // Cast to Carbon instance
        // Other attributes...
    ];
    use InteractsWithMedia;
    use HasFactory;

    protected $fillable = [
        'title',
        'status_id',
        'priority_id',
        'budget',
        'start_date',
        'end_date',
        'description',
        'note',
        'user_id',
        'client_id',
        'workspace_id',
        'task_accessibility',
        'created_by',
    ];

    public function registerMediaCollections(): void
    {
        $media_storage_settings = get_settings('media_storage_settings');
        $mediaStorageType = $media_storage_settings['media_storage_type'] ?? 'local';
        if ($mediaStorageType === 's3') {
            $this->addMediaCollection('project-media')->useDisk('s3');
        } else {
            $this->addMediaCollection('project-media')->useDisk('public');
        }
    }

    public function scopeFilter($query, array $filters)
    {
        if ($filters['search_projects'] ?? false) {
            $query->where('title', 'like', '%' . request('search_projects') . '%')
                ->orWhere('status', 'like', '%' . request('search_projects') . '%');
        }
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
    public function tasks()
    {
        return $this->hasMany(Task::class)->where('tasks.workspace_id', session()->get('workspace_id'));
    }
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class);
    }

    public function getresult()
    {

        return substr($this->title, 0, 100);
    }

    public function getlink()
    {
        return str('/projects/information/' . $this->id);
    }
    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
    public function milestones()
    {
        return $this->hasMany(Milestone::class)->where('milestones.workspace_id', session()->get('workspace_id'));
    }
}
