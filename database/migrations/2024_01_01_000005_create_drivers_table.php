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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('driver_license_number');
            $table->enum('license_type', ['private', 'commercial', 'heavy_duty', 'motorcycle']);
            $table->date('license_expiry');
            $table->string('national_id');
            $table->date('birth_date');
            $table->string('address');
            $table->string('city');
            $table->string('region');
            $table->json('emergency_contact');
            $table->enum('employment_type', ['full_time', 'part_time', 'freelance'])->default('full_time');
            $table->json('vehicle_info'); // Make, model, year, plate number, etc.
            $table->string('vehicle_registration');
            $table->date('vehicle_insurance_expiry');
            $table->json('allowed_vehicle_types'); // truck, van, motorcycle, etc.
            $table->decimal('max_load_capacity', 8, 2)->nullable(); // in kg
            $table->integer('service_radius_km')->default(100);
            $table->decimal('base_rate_per_km', 8, 2)->default(2.00);
            $table->boolean('has_gps_device')->default(true);
            $table->boolean('available_for_work')->default(true);
            $table->enum('current_status', ['available', 'busy', 'offline', 'on_break'])->default('offline');
            $table->decimal('current_latitude', 10, 8)->nullable();
            $table->decimal('current_longitude', 11, 8)->nullable();
            $table->datetime('last_location_update')->nullable();
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_deliveries')->default(0);
            $table->integer('completed_deliveries')->default(0);
            $table->decimal('total_distance_km', 10, 2)->default(0);
            $table->json('working_hours')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->datetime('verified_at')->nullable();
            $table->json('documents')->nullable(); // License, insurance, etc.
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique('user_id');
            $table->unique('driver_license_number');
            $table->unique('national_id');
            $table->index(['current_status', 'available_for_work']);
            $table->index(['city', 'service_radius_km']);
            $table->index(['is_verified', 'rating']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
