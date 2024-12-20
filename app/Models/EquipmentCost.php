<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentCost extends Model
{
    protected $fillable = [
        'id',
        'activity_id',
        'equipment_id',
        'planned_quantity',
        'actual_quantity',
        'planned_cost',
        'actual_cost',
        'remark',
    ];

    /**
     * Get the subtask associated with the equipment cost.
     */
    public function subtask()
    {
        return $this->belongsTo(Subtask::class);
    }

    /**
     * Get the equipment associated with the equipment cost.
     */
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
