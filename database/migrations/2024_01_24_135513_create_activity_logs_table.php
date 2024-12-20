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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workspace_id')->nullable();
            $table->bigInteger('actor_id');
            $table->string('actor_type', 56);
            $table->bigInteger('type_id');
            $table->string('type', 56);
            $table->bigInteger('parent_type_id')->nullable();
            $table->string('parent_type', 56)->nullable();
            $table->string('activity', 56);
            $table->string('message', 512)->nullable();
            $table->timestamps();

            $table->index('workspace_id');
            $table->foreign('workspace_id')->references('id')->on('workspaces')->onDelete('cascade');
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
