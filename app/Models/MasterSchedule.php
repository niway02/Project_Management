<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSchedule extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'master_schedules';
    
    // Indicate that the primary key is not auto-incrementing
    public $incrementing = false;

    // Specify the primary key
    protected $primaryKey = 'id';

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'id', // Include 'id' if this is the primary identifier
        'text',
        'start_date',
        'duration',
        'progress',
        'type',
        'parent', // This will link tasks to their parent project
        'project_or_task_id'
    ];

    // Optionally, you can define date casting if needed
    protected $dates = [
        'start_date', // Automatically cast to Carbon instances
        'due_date',   // Add any additional date fields here
    ];
}