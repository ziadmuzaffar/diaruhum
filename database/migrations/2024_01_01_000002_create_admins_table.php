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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('admin_level', ['super_admin', 'admin', 'moderator'])->default('admin');
            $table->json('permissions')->nullable(); // Specific permissions
            $table->json('accessible_modules')->nullable(); // Which modules can access
            $table->string('department')->nullable();
            $table->string('employee_id')->nullable();
            $table->date('hire_date')->nullable();
            $table->boolean('can_manage_users')->default(false);
            $table->boolean('can_manage_orders')->default(true);
            $table->boolean('can_manage_products')->default(true);
            $table->boolean('can_manage_finances')->default(false);
            $table->boolean('can_view_reports')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique('user_id');
            $table->index(['admin_level', 'department']);
            $table->index('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
