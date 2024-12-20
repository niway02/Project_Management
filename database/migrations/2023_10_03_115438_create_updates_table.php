<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdatesTable extends Migration
{
    public function up()
    {
        Schema::create('updates', function (Blueprint $table) {
            $table->id();
            $table->string('version');
            $table->timestamps(); // Adds 'created_at' and 'updated_at' columns.
        });

        // Insert an initial record with version '1.0.0'
        DB::table('updates')->insert([
            'version' => '1.0.0',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('updates');
    }
}
