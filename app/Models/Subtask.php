<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'name',
        'id',
        'status',
        'progress',
        'priority',
        'estimated_date',
        'start_date',
        'end-date'
        // Add more fillable attributes as needed
    ];
public function task()
{
    return $this->belongsTo(Task::class);
}
    public function materialCosts()
    {
        return $this->hasMany(MaterialCost::class);
    }

    public function equipmentCosts()
    {
        return $this->hasMany(EquipmentCost::class);
    }

    public function laborCosts()
    {
        return $this->hasMany(LaborCost::class);
    }
}