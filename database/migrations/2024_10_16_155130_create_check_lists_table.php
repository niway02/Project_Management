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
        Schema::create('check_lists', function (Blueprint $table) {
            $table->id(); // This will create an auto-incrementing primary key
            $table->date('date'); // Date field
            $table->string('status'); // Status field (you can change this to enum if required)
            $table->foreignId('activity_id')->constrained()->onDelete('cascade'); // Foreign key to activities table
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_lists');
    }
};
