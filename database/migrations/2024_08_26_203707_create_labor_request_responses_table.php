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
        Schema::create('labor_request_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('labor_request_id');
            $table->integer('approved_quantity');
            $table->unsignedBigInteger('approved_by');
            $table->text('response_message');
            $table->timestamps();
            $table->foreign('labor_request_id')->references('id')->on('labor_requests');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labor_request_responses');
    }
};
