<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $casts = [
        'start_date' => 'datetime', // Cast to Carbon instance
        // Other attributes...
    ];
    use HasFactory;
    protected $dates = ['start_date', 'end_date'];
    protected $fillable = [
        'task_id',
        'name',
        'id',
        'status',
        'progress',
        'priority',
        'assigned_to',
        'estimated_date',
        'start_date',
        'end_date',
        'quantity',
        'unit_id'
        

        
        // Add more fillable attributes as needed
    ];
public function task()
{
    return $this->belongsTo(Task::class,'task_id');
}
public function assignedTo()
{
    return $this->belongsTo(User::class, 'assigned_to'); // 'assigned_to' is the foreign key
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
    public function unitMeasure()
    {
        return $this->belongsTo(UnitMeasure::class, 'unit_id');
    }
}