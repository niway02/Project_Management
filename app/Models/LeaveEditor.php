<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveEditor extends Model
{
    use HasFactory;
    protected $table = 'leave_editors';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
