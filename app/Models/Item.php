<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [
        'workspace_id',
        'unit_id',
        'title',
        'price',
        'description'
    ];

    public function estimatesInvoices()
    {
        return $this->belongsToMany(EstimatesInvoice::class)
            ->withPivot('quantity'); // Include 'quantity' in the pivot table
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }
}
