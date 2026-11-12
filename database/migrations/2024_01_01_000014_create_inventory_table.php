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
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('merchant_id')->constrained('users')->onDelete('cascade');
            $table->string('warehouse_location')->nullable();
            $table->integer('quantity_available');
            $table->integer('quantity_reserved')->default(0);
            $table->integer('quantity_ordered')->default(0);
            $table->integer('reorder_level')->default(0);
            $table->integer('max_stock_level')->nullable();
            $table->decimal('cost_per_unit', 10, 2)->nullable();
            $table->date('last_restocked')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('batch_number')->nullable();
            $table->string('supplier')->nullable();
            $table->json('storage_conditions')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'merchant_id']);
            $table->index(['quantity_available', 'reorder_level']);
            $table->index('warehouse_location');
            $table->index('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
