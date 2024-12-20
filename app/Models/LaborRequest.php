<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaborRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'task_id',
        'activity_id',
        'labor_type',
        'quantity_requested',
        'required_date',
        'justification',
        'approved',
    ];

  

    public function laborType()
    {
        return $this->belongsTo(LaborType::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function response()
    {
        return $this->hasOne(LaborRequestResponse::class);
    }
}