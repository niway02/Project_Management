<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    use HasFactory;
    protected $fillable = [
        'workspace_id',
        'title',
        'type',
        'amount',
        'percentage'
    ];

    public function payslips()
    {
        return $this->belongsToMany(Payslip::class)->where('payslips.workspace_id', session()->get('workspace_id'));
    }
}
