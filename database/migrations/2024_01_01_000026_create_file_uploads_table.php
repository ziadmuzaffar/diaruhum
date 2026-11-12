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
        Schema::create('file_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->string('original_name');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('mime_type');
            $table->unsignedBigInteger('file_size'); // in bytes
            $table->string('disk')->default('public');
            $table->enum('type', ['image', 'document', 'video', 'audio', 'other']);
            $table->string('uploadable_type')->nullable(); // Model that owns this file
            $table->unsignedBigInteger('uploadable_id')->nullable();
            $table->string('collection')->nullable(); // Group files by collection
            $table->json('metadata')->nullable(); // Image dimensions, etc.
            $table->boolean('is_public')->default(true);
            $table->datetime('expires_at')->nullable();
            $table->timestamps();

            $table->index(['uploadable_type', 'uploadable_id']);
            $table->index(['uploaded_by', 'type']);
            $table->index(['collection', 'created_at']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_uploads');
    }
};
