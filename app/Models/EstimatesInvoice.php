<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimatesInvoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'workspace_id',
        'client_id',
        'name',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'phone',
        'type',
        'status',
        'note',
        'personal_note',
        'from_date',
        'to_date',
        'total',
        'tax_amount',
        'final_total',
        'created_by'
    ];

    public function items()
    {
        return $this->belongsToMany(Item::class, 'estimates_invoice_item')
            ->withPivot('qty', 'unit_id', 'rate', 'tax_id', 'amount');
    }
    public function payments()
    {
        return $this->hasMany(Payment::class, 'invoice_id');
    }
}
