<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialRequestResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_request_id',
        'approved_quantity',
        'status',
        'remark',
        'approved_by'
    ];
    public function materialRequest()
    {
        return $this->belongsTo(MaterialRequest::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}