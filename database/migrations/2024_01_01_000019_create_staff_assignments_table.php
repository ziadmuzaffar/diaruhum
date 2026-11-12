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
        Schema::create('staff_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->enum('assignment_type', ['project', 'order', 'service', 'delivery']);
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('order_item_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('delivery_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('status', ['assigned', 'in_progress', 'completed', 'cancelled', 'on_hold'])->default('assigned');
            $table->datetime('assigned_at');
            $table->datetime('started_at')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->datetime('due_date')->nullable();
            $table->integer('estimated_hours')->nullable();
            $table->integer('actual_hours')->nullable();
            $table->text('instructions')->nullable();
            $table->text('completion_notes')->nullable();
            $table->integer('quality_rating')->nullable();
            $table->json('checklist')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();

            $table->index(['staff_id', 'status']);
            $table->index(['assignment_type', 'status']);
            $table->index(['project_id', 'order_id']);
            $table->index(['assigned_at', 'due_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_assignments');
    }
};
