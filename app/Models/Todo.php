<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'priority',
        'description',
        'creator_id',
        'creator_type',
        'completed',
        'workspace_id'
    ];
    public function creator()
    {
        return $this->morphTo();
    }
}
