<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'workspace_id',
        'from_date',
        'to_date',
        'reason',
        'status',
        'action_by'        
    ];
}
