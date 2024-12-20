<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialsInventory extends Model
{
    use HasFactory;
    protected $fillable = [
        'cost',
        'quantity',
        'depreciation',
        'maintenanceLog',
        'material_id',
        'warehouse_id',
        'created_by',
        'updated_by',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
  
    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}
