<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'customer_id',
        'assigned_staff_id',
        'type',
        'status',
        'start_date',
        'end_date',
        'expected_completion',
        'budget',
        'actual_cost',
        'location_address',
        'location_lat',
        'location_lng',
        'requirements',
        'deliverables',
        'images',
        'documents',
        'progress_percentage',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'expected_completion' => 'date',
        'budget' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'location_lat' => 'decimal:8',
        'location_lng' => 'decimal:8',
        'requirements' => 'array',
        'deliverables' => 'array',
        'images' => 'array',
        'documents' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Get the customer that owns the project.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the assigned staff member.
     */
    public function assignedStaff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }

    /**
     * Get the orders for the project.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the staff assignments for the project.
     */
    public function staffAssignments(): HasMany
    {
        return $this->hasMany(StaffAssignment::class);
    }
}
