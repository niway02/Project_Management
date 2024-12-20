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
    { {
        Schema::create('milestones', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('workspace_id');
                $table->unsignedBigInteger('project_id');
                $table->string('title');
                $table->string('status');
                $table->date('start_date');
                $table->date('end_date');
                $table->double('cost');
                $table->double('progress')->default(0);
                $table->longText('description')->nullable();
                $table->string('created_by', 56);
                $table->timestamps();

                // Define foreign key constraints            
                $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
                $table->foreign('workspace_id')->references('id')->on('workspaces')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milestones');
    }
};
