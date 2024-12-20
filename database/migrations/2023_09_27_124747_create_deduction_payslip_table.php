<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeductionPayslipTable extends Migration
{
    public function up()
    {
       /* Schema::create('deduction_payslip', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('deduction_id');
            $table->unsignedBigInteger('payslip_id');            
            $table->timestamps();

            // Define foreign keys
            $table->foreign('deduction_id')->references('id')->on('deductions')->onDelete('cascade');
            $table->foreign('payslip_id')->references('id')->on('payslips')->onDelete('cascade');
        });*/
    }

    public function down()
    {
        Schema::dropIfExists('deduction_payslip');
    }
}
