<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceRequest extends Model
{
    use HasFactory;

    protected $table = 'resource_requests';

    protected $fillable = [
        'activity_id',
        'requested_by',
        'type',
        'status',
        'finance_status'
    ];

    // Define relationships
    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
    public function materialRequest()
    {
        return $this->hasOne(MaterialRequest::class);
    }
}

