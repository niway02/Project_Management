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
        Schema::create('labor_types', function (Blueprint $table) {
            $table->id(); // Unique identifier for the labor type
            $table->string('labor_type_name'); // Name of the labor category
            $table->text('description')->nullable(); // Description of the labor type
            $table->decimal('hourly_rate', 8, 2); // Cost per hour for the labor type
            $table->string('skill_level'); // Required skill level
            $table->string('certification_requirements')->nullable(); // Required certifications
            $table->enum('availability', ['full-time', 'part-time', 'contract']); // Type of availability
            $table->string('location')->nullable(); // Geographic area for the labor type
            $table->enum('status', ['active', 'inactive']); // Current status of the labor type
            $table->timestamps(); // CreatedAt and UpdatedAt timestamps
        });
    }

    /** 
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labor_types');
    }
};
