<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaborCost extends Model
{
    protected $fillable = [
        'id',
        'activity_id',
        'labor_id',
        'qty',
        'remark',
    ];
   /**
     * Get the Labor associated with the material cost.
     */
    public function Labor()
    {
        return $this->belongsTo(Labor::class);
    }
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}

