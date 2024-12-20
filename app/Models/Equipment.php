<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'item',
        'quantity',
        'rate_with_vat',
        'amount',
        'remark',
        'status',
        'type',
        'reorder_quantity',
        'min_quantity',
        'unit_id',
        'warehouse_id',
        'created_by',
        'updated_by',
        'manufacturer',
    ];



    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function unitMeasure()
    {
        return $this->belongsTo(UnitMeasure::class, 'unit_id');
    }
}
