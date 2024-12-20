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
        Schema::create('materials_inventories', function (Blueprint $table) {
            $table->id();
            $table->decimal('cost', 10, 2)->required()->min(0.01); // Minimum cost of 0.01
            $table->decimal('quantity', 10, 2)->required()->min(0.01); // Minimum quantity of 0.01
            $table->decimal('depreciation', 10, 2)->required()->min(0); // Minimum depreciation of 0
            $table->text('maintenanceLog')->nullable(); // Maintenance log is optional
            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->timestamps();

            $table->foreign('material_id')->references('id')->on('materials');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials_inventories');
    }
};