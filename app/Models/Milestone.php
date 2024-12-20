<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'workspace_id',
        'project_id',
        'title',
        'status',
        'start_date',
        'end_date',
        'cost',
        'progress',
        'description',
        'created_by'
    ];
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
