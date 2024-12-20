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
        Schema::create('master_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('text'); // Project or task title
            $table->date('start_date')->nullable(); // Start date
            $table->integer('duration')->default(0); // Duration in days
            $table->integer('progress')->default(0); // Progress percentage
            $table->string('type'); // 'project' or 'task'
            $table->integer('parent_id'); // Link to parent project/task
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_schedules');
    }
};
