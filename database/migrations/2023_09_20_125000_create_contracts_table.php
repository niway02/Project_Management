<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    public function up()
    {
        // Schema::create('contracts', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('workspace_id');
        //     $table->unsignedBigInteger('project_id');
        //     $table->unsignedBigInteger('client_id');
        //     $table->string('title'); // Add title column
        //     $table->decimal('value', 10, 2); // Add value column as decimal
        //     $table->unsignedBigInteger('contract_type_id'); // Add contract_type_id
        //     $table->text('description');
        //     $table->date('start_date');
        //     $table->date('end_date');
        //     $table->timestamps();

        //     // Define foreign key constraints            
        //     $table->foreign('workspace_id')->references('id')->on('workspaces')->onDelete('cascade');
        //     $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        //     $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');

        //     // Add foreign key for contract_type_id referencing the master table
        //     $table->foreign('contract_type_id')->references('id')->on('contract_types');
        // });
    }

    public function down()
    {
        Schema::dropIfExists('contracts');
    }
}
