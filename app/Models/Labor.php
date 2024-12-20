<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Labor extends Model
{
    protected $fillable = [
        'labor_type_id',
        'user_id', // Reference to the User model
        'hire_date',
        'status',
        'skills',
        'assigned_project',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function laborType()
    {
        return $this->belongsTo(LaborType::class);
    }
    public function laborSites()
    {
        return $this->belongsToMany(Site::class, 'labor_site')
                    ->withPivot('project_id', 'started_at', 'ended_at')
                    ->withTimestamps();
    }
}
