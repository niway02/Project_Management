<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_equipment_types_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentTypesTable extends Migration
{
    public function up()
    {
        Schema::create('equipment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Unique name for equipment type
            $table->text('description')->nullable(); // Optional description
            $table->timestamps(); // Created at and updated at columns
        });
    }

    public function down()
    {
        Schema::dropIfExists('equipment_types');
    }
}