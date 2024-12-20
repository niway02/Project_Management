<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse2 extends Model
{
    use HasFactory;
    protected $fillabe = ["name", "description", "location", "manager", "contact_info", "updated_by"];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}
