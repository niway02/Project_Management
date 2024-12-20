<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'cost',
        'quantity',
        'depreciation',
        'maintenanceLog',
        'equipment_id',
        'warehouse_id',
        'created_by',
        'updated_by',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
  
    public function equipment()
    {
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }
}
