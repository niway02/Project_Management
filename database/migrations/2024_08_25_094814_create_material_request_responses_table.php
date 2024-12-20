<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('material_request_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_request_id');
            $table->unsignedBigInteger('approved_by');
            $table->integer('approved_quantity');
            $table->string('status')->nullable();
            $table->timestamps();

            $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('material_request_id')->references('id')->on('material_requests');
        });
    }

    public function down()
    {
        Schema::dropIfExists('material_request_responses');
    }
};