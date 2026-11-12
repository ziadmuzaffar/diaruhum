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
        Schema::create('property_inquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('real_estate_properties')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('agent_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('type', ['viewing', 'information', 'offer', 'negotiation']);
            $table->enum('status', ['new', 'contacted', 'scheduled', 'completed', 'closed'])->default('new');
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->text('message')->nullable();
            $table->decimal('offered_price', 12, 2)->nullable();
            $table->json('financing_details')->nullable();
            $table->datetime('preferred_viewing_date')->nullable();
            $table->datetime('scheduled_viewing_date')->nullable();
            $table->datetime('contacted_at')->nullable();
            $table->text('agent_notes')->nullable();
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->json('follow_up_history')->nullable();
            $table->timestamps();

            $table->index(['property_id', 'status']);
            $table->index('customer_id');
            $table->index('agent_id');
            $table->index(['status', 'priority']);
            $table->index('scheduled_viewing_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_inquiries');
    }
};
