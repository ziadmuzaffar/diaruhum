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
        Schema::create('real_estate_properties', function (Blueprint $table) {
            $table->id();
            $table->string('property_code')->unique();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_agent_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('type', ['residential', 'commercial', 'industrial', 'land', 'mixed_use']);
            $table->enum('sub_type', ['apartment', 'villa', 'office', 'shop', 'warehouse', 'plot', 'building']);
            $table->enum('purpose', ['sale', 'rent', 'lease', 'investment']);
            $table->enum('status', ['available', 'under_offer', 'sold', 'rented', 'off_market'])->default('available');
            $table->string('title_ar');
            $table->string('title_en')->nullable();
            $table->text('description_ar');
            $table->text('description_en')->nullable();
            $table->string('address');
            $table->string('city');
            $table->string('district');
            $table->string('region');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('price', 12, 2);
            $table->string('price_currency', 3)->default('SAR');
            $table->enum('price_type', ['total', 'per_sqm', 'per_month', 'per_year']);
            $table->decimal('area_sqm', 10, 2)->nullable();
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('parking_spaces')->nullable();
            $table->integer('floors')->nullable();
            $table->year('built_year')->nullable();
            $table->json('features')->nullable(); // Pool, garden, elevator, etc.
            $table->json('amenities')->nullable(); // Nearby facilities
            $table->json('images')->nullable();
            $table->json('documents')->nullable(); // Deeds, permits, etc.
            $table->json('virtual_tour')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('views_count')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['type', 'purpose', 'status']);
            $table->index(['city', 'district']);
            $table->index(['price', 'area_sqm']);
            $table->index('owner_id');
            $table->index('assigned_agent_id');
            $table->index('property_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('real_estate_properties');
    }
};
