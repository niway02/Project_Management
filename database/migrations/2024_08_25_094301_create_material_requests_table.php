<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('material_requests', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('resource_request_id'); // Foreign key to requests table
            $table->unsignedBigInteger('item_id'); // Foreign key to materials table
            $table->integer('item_quantity'); // Quantity of the item
            $table->string('material_description')->nullable();; // Description of the material
            $table->string('status')->default('Pending'); // Status of the request
            $table->timestamps(); // Created at and updated at timestamps

            // Foreign key constraints
            $table->foreign('resource_request_id')->references('id')->on('resource_requests')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('materials')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('material_requests');
    }
};