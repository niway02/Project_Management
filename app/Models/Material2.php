<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material2 extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'item',
        'unit',
        'quantity',
        'rate_with_vat',
        'amount',
        'remark',
        'status',
        'material_type',
        'reorder_quantity',
        'min_quantity',
        'warehouse2_id',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse2::class);
    }
}
