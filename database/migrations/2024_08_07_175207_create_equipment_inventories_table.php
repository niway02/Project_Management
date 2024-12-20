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
        Schema::create('equipment_inventories', function (Blueprint $table) {
            $table->id();
            $table->decimal('cost', 10, 2)->required()->min(0.01); // Minimum cost of 0.01
            $table->decimal('quantity', 10, 2)->required()->min(0.01); // Minimum quantity of 0.01
            $table->decimal('depreciation', 10, 2)->required()->min(0); // Minimum depreciation of 0
            $table->text('maintenanceLog')->nullable(); // Maintenance log is optional
            $table->unsignedBigInteger('warehouse_id')->required(); // Make sure the data type matches the 'id' column in the 'warehouses' table
            $table->unsignedBigInteger('equipment_id')->required();
            $table->timestamps();
            $table->foreign('equipment_id')->references('id')->on('equipment')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            //$table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_inventories');
    }
};