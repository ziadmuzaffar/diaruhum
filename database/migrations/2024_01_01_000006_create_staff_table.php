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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('employee_id')->unique();
            $table->enum('staff_type', ['engineer', 'technician', 'supervisor', 'coordinator', 'specialist']);
            $table->string('department'); // design, labor, materials, logistics, realestate
            $table->string('position');
            $table->json('specializations'); // Areas of expertise
            $table->json('certifications')->nullable();
            $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'consultant']);
            $table->date('hire_date');
            $table->date('contract_end_date')->nullable();
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->decimal('monthly_salary', 10, 2)->nullable();
            $table->string('national_id');
            $table->date('birth_date');
            $table->string('address');
            $table->string('city');
            $table->string('region');
            $table->json('emergency_contact');
            $table->json('bank_details')->nullable();
            $table->enum('education_level', ['high_school', 'diploma', 'bachelor', 'master', 'phd']);
            $table->string('university')->nullable();
            $table->string('major')->nullable();
            $table->integer('years_experience')->default(0);
            $table->json('skills')->nullable(); // Technical skills
            $table->json('languages')->nullable(); // Spoken languages
            $table->boolean('can_travel')->default(true);
            $table->boolean('has_vehicle')->default(false);
            $table->boolean('available_for_assignments')->default(true);
            $table->enum('current_status', ['available', 'assigned', 'on_leave', 'busy'])->default('available');
            $table->integer('current_projects_count')->default(0);
            $table->integer('max_concurrent_projects')->default(3);
            $table->decimal('performance_rating', 3, 2)->default(0);
            $table->integer('completed_projects')->default(0);
            $table->json('working_hours')->nullable();
            $table->json('documents')->nullable(); // CV, certificates, etc.
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique('user_id');
            $table->unique('national_id');
            $table->index(['staff_type', 'department']);
            $table->index(['current_status', 'available_for_assignments']);
            $table->index(['specializations', 'city']);
            $table->index('performance_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
