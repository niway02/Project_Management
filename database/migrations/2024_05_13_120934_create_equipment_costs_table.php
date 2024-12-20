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
        Schema::create('equipment_costs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activity_id');
            $table->unsignedBigInteger('equipment_id');
            $table->integer('planned_quantity');
            $table->integer('actual_quantity');
            $table->decimal('planned_cost', 8, 2);
            $table->decimal('actual_cost', 8, 2);

            $table->text('remark')->nullable();

            $table->foreign('activity_id')
            ->references('id')
            ->on('activities')
            ->onDelete('cascade');

            $table->foreign('equipment_id')
            ->references('id')
            ->on('equipment')
            ->onDelete('cascade');
        });
     
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_costs');
    }
};
