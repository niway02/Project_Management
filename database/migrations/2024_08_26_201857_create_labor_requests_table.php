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
        Schema::create('labor_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('labor_type_id');
            $table->unsignedBigInteger('resource_request_id'); // Foreign key to requests table
            $table->integer('quantity_requested');
            $table->string('status')->default('pending'); // Status of the request
            $table->timestamps();
            // Foreign key constraints
            $table->foreign('labor_type_id')->references('id')->on('labor_types');
            $table->foreign('resource_request_id')->references('id')->on('resource_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labor_requests');
    }
};


