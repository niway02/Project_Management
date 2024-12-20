<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaborSite extends Model
{
    use HasFactory; // Include the HasFactory trait for model factories

    protected $table = 'labor_site'; // Specify the table name

    protected $fillable = [
        'labor_id',
        'site_id',
        'project_id',
        'started_at',
        'ended_at',
    ];

    /**
     * Get the laborer associated with this labor site record.
     */
    public function labor()
    {
        return $this->belongsTo(Labor::class);
    }

    /**
     * Get the site associated with this labor site record.
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Get the project associated with this labor site record.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}