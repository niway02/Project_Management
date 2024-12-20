<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('estimates_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workspace_id');
            $table->unsignedBigInteger('client_id');
            $table->string('name');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->integer('zip_code');
            $table->bigInteger('phone');
            $table->string('type');
            $table->string('status');
            $table->longText('note')->nullable();
            $table->longText('personal_note')->nullable();
            $table->date('from_date');
            $table->date('to_date');
            $table->double('total');
            $table->double('tax_amount')->nullable();
            $table->double('final_total');
            $table->string('created_by', 56);
            $table->timestamps();

            // Define foreign key constraints            
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('workspace_id')->references('id')->on('workspaces')->onDelete('cascade');
        });  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estimates_invoices');
    }
};
