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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('delivery_number')->unique();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['pending', 'assigned', 'picked_up', 'in_transit', 'delivered', 'failed', 'cancelled'])->default('pending');
            $table->string('pickup_address');
            $table->decimal('pickup_lat', 10, 8)->nullable();
            $table->decimal('pickup_lng', 11, 8)->nullable();
            $table->string('delivery_address');
            $table->decimal('delivery_lat', 10, 8)->nullable();
            $table->decimal('delivery_lng', 11, 8)->nullable();
            $table->datetime('scheduled_pickup')->nullable();
            $table->datetime('scheduled_delivery')->nullable();
            $table->datetime('actual_pickup')->nullable();
            $table->datetime('actual_delivery')->nullable();
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->decimal('delivery_cost', 8, 2);
            $table->string('vehicle_type')->nullable();
            $table->text('special_instructions')->nullable();
            $table->string('recipient_name')->nullable();
            $table->string('recipient_phone')->nullable();
            $table->json('tracking_updates')->nullable();
            $table->json('proof_of_delivery')->nullable(); // Photos, signatures
            $table->text('delivery_notes')->nullable();
            $table->integer('rating')->nullable();
            $table->text('feedback')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['driver_id', 'status']);
            $table->index('order_id');
            $table->index('delivery_number');
            $table->index(['status', 'scheduled_delivery']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
