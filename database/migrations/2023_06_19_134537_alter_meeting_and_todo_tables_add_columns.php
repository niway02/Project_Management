<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('todos', function (Blueprint $table) {
            $table->unsignedBigInteger('workspace_id')->after('id');
        });

        Schema::table('meetings', function (Blueprint $table) {
            $table->unsignedBigInteger('workspace_id')->after('id');
            $table->unsignedBigInteger('created_by')->after('end_date_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->dropColumn('workspace_id');
        });

        Schema::table('meetings', function (Blueprint $table) {
            $table->dropColumn('workspace_id');
            $table->dropColumn('created_by');
        });
    }
};
