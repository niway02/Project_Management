<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckList extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'status',
        'activity_id',
    ];

    // Define the relationship with the Activity model (if needed)
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
