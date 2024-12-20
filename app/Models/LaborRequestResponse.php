<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaborRequestResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'labor_request_id',
        'approved_quantity',
        'response_message',
        'approved_by'
    ];

    public function laborRequest()
    {
        return $this->belongsTo(LaborRequest::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    public function laborType()
{
    return $this->belongsTo(LaborType::class);
}
}



