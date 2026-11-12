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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_en')->nullable();
            $table->string('slug')->unique();
            $table->text('description_ar');
            $table->text('description_en')->nullable();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['design', 'labor', 'materials', 'logistics', 'realestate']);
            $table->decimal('base_price', 10, 2)->nullable();
            $table->string('price_unit')->nullable(); // per hour, per m2, per item, etc.
            $table->json('pricing_tiers')->nullable(); // Different pricing based on quantity/complexity
            $table->integer('estimated_duration')->nullable(); // in hours
            $table->json('requirements')->nullable(); // What's needed from customer
            $table->json('deliverables')->nullable(); // What customer gets
            $table->json('images')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_quote')->default(false);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['type', 'is_active']);
            $table->index('category_id');
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
