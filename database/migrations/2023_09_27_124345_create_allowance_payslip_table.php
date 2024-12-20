<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllowancePayslipTable extends Migration
{
    public function up()
    {
        Schema::create('allowance_payslip', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('allowance_id');
            $table->unsignedBigInteger('payslip_id');            
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('allowance_id')->references('id')->on('allowances');
            $table->foreign('payslip_id')->references('id')->on('payslips');
        });
    }

    public function down()
    {
        Schema::dropIfExists('allowance_payslip');
    }
}
