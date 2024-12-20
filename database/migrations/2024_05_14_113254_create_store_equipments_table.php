<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreEquipmentsTable extends Migration
{
    public function up()
    {
        Schema::create('store_equipmentsh', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('equipment_id');
            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('equipment_id')->references('id')->on('equipments')->onDelete('cascade');
            // Add more columns or indexes as needed
        });
    }

    public function down()
    {
        Schema::dropIfExists('store_equipments');
    }
}