<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'staff_type',
        'department',
        'position',
        'specializations',
        'certifications',
        'employment_type',
        'hire_date',
        'contract_end_date',
        'hourly_rate',
        'monthly_salary',
        'national_id',
        'birth_date',
        'address',
        'city',
        'region',
        'emergency_contact',
        'bank_details',
        'education_level',
        'university',
        'major',
        'years_experience',
        'skills',
        'languages',
        'can_travel',
        'has_vehicle',
        'available_for_assignments',
        'current_status',
        'current_projects_count',
        'max_concurrent_projects',
        'performance_rating',
        'completed_projects',
        'working_hours',
        'documents',
        'metadata',
    ];

    protected $casts = [
        'specializations' => 'array',
        'certifications' => 'array',
        'hire_date' => 'date',
        'contract_end_date' => 'date',
        'hourly_rate' => 'decimal:2',
        'monthly_salary' => 'decimal:2',
        'birth_date' => 'date',
        'emergency_contact' => 'array',
        'bank_details' => 'array',
        'skills' => 'array',
        'languages' => 'array',
        'can_travel' => 'boolean',
        'has_vehicle' => 'boolean',
        'available_for_assignments' => 'boolean',
        'performance_rating' => 'decimal:2',
        'working_hours' => 'array',
        'documents' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Get the user that owns the staff profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the projects assigned to the staff member.
     */
    public function assignedProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'assigned_staff_id');
    }

    /**
     * Get the staff assignments.
     */
    public function staffAssignments(): HasMany
    {
        return $this->hasMany(StaffAssignment::class);
    }
}
