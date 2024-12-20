<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreMaterial extends Model
{
    protected $fillable = [
        'material_id',
        'quantity',
        // Add more fillable attributes as needed
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}