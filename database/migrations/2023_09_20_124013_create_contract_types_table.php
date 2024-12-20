<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractTypesTable extends Migration
{
    public function up()
    {
       Schema::create('contract_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workspace_id')->nullable();
            $table->string('type'); // Add a column for the contract type name
            $table->timestamps();

            // Define foreign key constraints            
            $table->foreign('workspace_id')->references('id')->on('workspaces')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('contract_types');
    }
}
