<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialCost extends Model
{
    protected $fillable = [
        'id',
        'activity_id',
        'material_id',
        'planned_quantity',
        'actual_quantity',
        'planned_cost',
        'actual_cost',
        'remark',
    ];

    /**
     * Get the subtask associated with the material cost.
     */
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * Get the material associated with the material cost.
     */
    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}