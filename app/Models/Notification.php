<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'workspace_id',
        'from_id',
        'to_ids',
        'type',
        'type_id',
        'action',
        'title',
        'message'
    ];

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_notifications')->withPivot('read_at');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_user')->withPivot('read_at');
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
}
