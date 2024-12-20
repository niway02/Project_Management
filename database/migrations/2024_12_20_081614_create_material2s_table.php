<?php

use App\Models\User;
use App\Models\Warehouse2;
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
        Schema::create('material2s', function (Blueprint $table) {
            $table->id();
            $table->string('item');
            $table->integer('quantity');
            $table->decimal('rate_with_vat', 8, 2);
            $table->decimal('amount', 8, 2);
            $table->text('remark')->nullable();
            $table->string('status');
            $table->string('material_type');
            $table->decimal('reorder_quantity', 10, 2);
            $table->decimal('min_quantity', 10, 2);
            $table->foreignIdFor(Warehouse2::class);
            $table->foreignIdFor(User::class,'created_by')->nullable();
            $table->foreignIdFor(User::class,'updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material2s');
    }
};
