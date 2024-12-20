<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetAllocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_allocations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('amount', 10, 2);
            $table->string('planned_bug')->nullable();
            $table->string('bug_type')->nullable();
            $table->string('currency')->nullable();
            $table->string('priority')->nullable();
            $table->date('release_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('billing_type')->nullable();
            $table->string('milestone')->nullable();
            $table->boolean('paid_for')->default(false);
            $table->string('status')->nullable();
            $table->timestamps();

            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budget_allocations');
    }
}