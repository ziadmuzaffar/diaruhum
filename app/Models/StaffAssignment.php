<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'assignment_type',
        'project_id',
        'order_id',
        'order_item_id',
        'delivery_id',
        'status',
        'assigned_at',
        'started_at',
        'completed_at',
        'due_date',
        'estimated_hours',
        'actual_hours',
        'instructions',
        'completion_notes',
        'quality_rating',
        'checklist',
        'attachments',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'due_date' => 'datetime',
        'checklist' => 'array',
        'attachments' => 'array',
    ];

    /**
     * Get the staff member assigned.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    /**
     * Get the project associated with the assignment.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the order associated with the assignment.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the order item associated with the assignment.
     */
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    /**
     * Get the delivery associated with the assignment.
     */
    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Delivery::class);
    }
}
