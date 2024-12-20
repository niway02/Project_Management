<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
    use HasFactory;

    protected $fillable = [
        
        'name',
        // Add more fillable attributes as needed
    ];

    public function material_costs()
    {
        return $this->hasMany(MaterialCost::class);
    }
}