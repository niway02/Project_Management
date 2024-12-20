<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', // Example field
        'location', // Example field
        // Add other relevant fields here
    ];

    /**
     * Get the projects associated with the site.
     */
    public function labors()
    {
        return $this->belongsToMany(Labor::class, 'labor_site')  // Specify the pivot table
                    ->withPivot('started_at', 'ended_at') // Include pivot fields
                    ->withTimestamps(); // Automatically manage timestamps
    }
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}