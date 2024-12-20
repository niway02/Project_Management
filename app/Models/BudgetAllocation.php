<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetAllocation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'amount',
        'planned_bug',
        'bug_type',
        'currency',
        'priority',
        'release_date',
        'payment_method',
        'billing_type',
        'milestone',
        'paid_for',
        'status',
        'project_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'release_date' => 'date',
        'paid_for' => 'boolean',
    ];

    /**
     * Get the project associated with the budget allocation.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}