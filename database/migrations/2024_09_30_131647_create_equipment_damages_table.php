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
        Schema::create('equipment_damages', function (Blueprint $table) {
            $table->id(); // Unique identifier
            $table->unsignedBigInteger('item_id'); // Foreign key for materials
            $table->unsignedBigInteger('warehouse_id'); // Foreign key for warehouses
            $table->unsignedBigInteger('approved_by'); // Foreign key for users
            $table->string('issue')->nullable(); // Description of the issue
            $table->timestamp('damage_date')->nullable(); // Date of damage report
            $table->string('status')->default('Under investigation'); // Damage status
            $table->integer('quantity_damaged')->unsigned(); // Quantity of damaged items
            $table->timestamps(); // Created at and updated at fields

            // Foreign key constraints
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('equipment')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_damages');
    }
};
