<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payslip extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'workspace_id',
        'payment_method_id',
        'month',
        'working_days',
        'lop_days',
        'paid_days',
        'basic_salary',
        'leave_deduction',
        'ot_hours',
        'ot_rate',
        'ot_payment',
        'total_allowance',
        'incentives',
        'bonus',
        'total_earnings',
        'total_deductions',
        'net_pay',
        'payment_date',
        'status',
        'note',
        'created_by',
    ];


    public function allowances()
    {
        return $this->belongsToMany(Allowance::class);
    }


    public function deductions()
    {
        return $this->belongsToMany(Deduction::class);
    }
}
