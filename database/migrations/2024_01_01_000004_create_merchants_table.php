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
        Schema::create('merchants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('business_name');
            $table->string('commercial_register');
            $table->string('tax_number');
            $table->enum('business_type', ['supplier', 'manufacturer', 'distributor', 'retailer', 'service_provider']);
            $table->json('specializations'); // What they specialize in
            $table->string('license_number')->nullable();
            $table->date('license_expiry')->nullable();
            $table->string('business_address');
            $table->string('warehouse_address')->nullable();
            $table->string('city');
            $table->string('region');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('contact_person');
            $table->string('contact_phone');
            $table->string('contact_email');
            $table->json('bank_details')->nullable();
            $table->decimal('commission_rate', 5, 2)->default(5.00); // Platform commission
            $table->enum('payment_terms', ['cash', 'net_15', 'net_30', 'net_60'])->default('net_30');
            $table->integer('delivery_radius_km')->default(50);
            $table->decimal('minimum_order_amount', 10, 2)->default(0);
            $table->boolean('offers_delivery')->default(true);
            $table->decimal('delivery_fee', 8, 2)->default(0);
            $table->json('operating_hours')->nullable();
            $table->json('certifications')->nullable();
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_orders')->default(0);
            $table->boolean('is_verified')->default(false);
            $table->datetime('verified_at')->nullable();
            $table->enum('status', ['pending', 'active', 'suspended', 'rejected'])->default('pending');
            $table->json('documents')->nullable(); // License, certificates, etc.
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique('user_id');
            $table->unique('commercial_register');
            $table->index(['business_type', 'city']);
            $table->index(['is_verified', 'status']);
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchants');
    }
};
