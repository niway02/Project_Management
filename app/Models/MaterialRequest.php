<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'item_id',
        'item_quantity',
        'material_description',
        'status',
    ];

    // Relationship to the Request model
    public function resourceRequest()
    {
        return $this->belongsTo(ResourceRequest::class, 'resource_request_id');
    }
    // Relationship to the Material model
    public function material()
    {
        return $this->belongsTo(Material::class, 'item_id');
    }

    // Relationship to the MaterialRequestResponse model
    public function response()
    {
        return $this->hasOne(MaterialRequestResponse::class);
    }
}