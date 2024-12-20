<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeTracker extends Model
{
    use HasFactory;
    protected $fillable = [
        'workspace_id',
        'user_id',
        'start_date_time',
        'end_date_time',
        'duration',
        'message'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

}
