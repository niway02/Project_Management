<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up()
    {
        Schema::create('estimates_invoice_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('estimates_invoice_id');
            $table->double('qty');
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->double('rate');
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->double('amount');

            // Define foreign keys
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('estimates_invoice_id')->references('id')->on('estimates_invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimates_invoice_item');
    }
};
