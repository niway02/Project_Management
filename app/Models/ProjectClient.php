<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectClient extends Model
{
    public $table = 'project_client';

    protected $fillable = [
        'project_id', 'client_id'
    ];
}
