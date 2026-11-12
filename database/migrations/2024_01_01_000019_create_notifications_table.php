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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['order', 'payment', 'delivery', 'project', 'system', 'promotion', 'reminder']);
            $table->string('title_ar');
            $table->string('title_en')->nullable();
            $table->text('message_ar');
            $table->text('message_en')->nullable();
            $table->json('data')->nullable(); // Additional notification data
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->boolean('is_read')->default(false);
            $table->datetime('read_at')->nullable();
            $table->enum('channel', ['in_app', 'email', 'sms', 'push'])->default('in_app');
            $table->boolean('sent')->default(false);
            $table->datetime('sent_at')->nullable();
            $table->string('action_url')->nullable();
            $table->datetime('expires_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_read']);
            $table->index(['type', 'priority']);
            $table->index(['sent', 'channel']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
