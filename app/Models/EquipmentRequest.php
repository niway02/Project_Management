<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'task_id',
        'activity_id',
        'equipment_name',
        'quantity_requested',
        'required_date',
        'justification',
        'approved',
    ];
    public function equipment()
    {
        return $this->belongsTo(Equipment::class, 'item_id');
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function response()
    {
        return $this->hasOne(EquipmentRequestResponse::class);
    }
}