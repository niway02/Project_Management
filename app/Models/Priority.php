<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Priority extends Model
{
    use HasFactory;


    protected $fillable = [
        'title',
        'color',
        'slug'
    ];

    public static function all($columns = ['*'])
    {
        // Create a default priority object
        $defaultPriority = new Priority();
        $defaultPriority->id = 0;
        $defaultPriority->title = get_label('default', 'Default');
        $defaultPriority->color = 'secondary';

        // Get the original priorities
        $priorities = parent::all($columns);

        // Create a new collection with the default priority at the beginning
        $prioritiesCollection = new Collection([$defaultPriority]);
        $prioritiesCollection = $prioritiesCollection->merge($priorities);

        // Return the priorities collection
        return $prioritiesCollection;
    }

    public function projects($considerWorkspace = true)
    {
        $query = $this->hasMany(Project::class);

        if ($considerWorkspace) {
            $query->where('projects.workspace_id', session()->get('workspace_id'));
        }

        return $query;
    }

    public function tasks($considerWorkspace = true)
    {
        $query = $this->hasMany(Task::class);

        if ($considerWorkspace) {
            $query->where('tasks.workspace_id', session()->get('workspace_id'));
        }

        return $query;
    }
}
