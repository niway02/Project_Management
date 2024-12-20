<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('labor_site', function (Blueprint $table) {
            $table->id(); // Unique identifier for the pivot entry
            $table->foreignId('labor_id')
                  ->constrained('labors')
                  ->onDelete('cascade'); // Foreign key to labors table
            $table->foreignId('site_id')
                  ->constrained('sites')
                  ->onDelete('cascade'); // Foreign key to sites table
            $table->foreignId('project_id')->nullable()
                  ->constrained('projects')
                  ->onDelete('set null'); // Foreign key to projects table, nullable
            $table->timestamp('started_at')->nullable(); // When the laborer started at the site
            $table->timestamp('ended_at')->nullable(); // When the laborer left the site
            $table->timestamps(); // CreatedAt and UpdatedAt timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labor_site');
    }
};