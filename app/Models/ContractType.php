<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'workspace_id'
    ];

    public function contracts()
    {
        return $this->hasMany(Contract::class)->where('contracts.workspace_id', session()->get('workspace_id'));
    }
}
