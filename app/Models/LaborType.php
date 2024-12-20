<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaborType extends Model
{
    use HasFactory;
    // Define the fillable fields for mass assignment

    protected $fillable = [
        'labor_type_name',
        'description',
        'hourly_rate',
        'skill_level',
        'certification_requirements',
        'availability',
        'location',
        'status',
    ];
    public function labors()
    {
        return $this->hasMany(Labor::class);
    }
}
