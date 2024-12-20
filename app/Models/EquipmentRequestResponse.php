<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentRequestResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_request_id',
        'approved_quantity',
        'response_message',
    ];

    public function equipmentRequest()
    {
        return $this->belongsTo(EquipmentRequest::class);
    }
}