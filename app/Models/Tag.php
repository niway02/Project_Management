<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'color',
        'slug'
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }
}
