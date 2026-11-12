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
        Schema::create('reviews_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->enum('reviewable_type', ['service', 'product', 'project', 'delivery', 'staff', 'merchant']);
            $table->unsignedBigInteger('reviewable_id');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('rating'); // 1-5 stars
            $table->string('title')->nullable();
            $table->text('comment')->nullable();
            $table->json('rating_breakdown')->nullable(); // Quality, delivery, service, etc.
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_published')->default(true);
            $table->json('images')->nullable();
            $table->text('merchant_response')->nullable();
            $table->datetime('merchant_responded_at')->nullable();
            $table->integer('helpful_votes')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['reviewable_type', 'reviewable_id']);
            $table->index(['customer_id', 'rating']);
            $table->index(['is_published', 'rating']);
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews_ratings');
    }
};
