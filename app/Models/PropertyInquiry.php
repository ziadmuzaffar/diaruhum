<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyInquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'customer_id',
        'agent_id',
        'type',
        'status',
        'customer_name',
        'customer_phone',
        'customer_email',
        'message',
        'offered_price',
        'financing_details',
        'preferred_viewing_date',
        'scheduled_viewing_date',
        'contacted_at',
        'agent_notes',
        'priority',
        'follow_up_history',
    ];

    protected $casts = [
        'offered_price' => 'decimal:2',
        'financing_details' => 'array',
        'preferred_viewing_date' => 'datetime',
        'scheduled_viewing_date' => 'datetime',
        'contacted_at' => 'datetime',
        'follow_up_history' => 'array',
    ];

    /**
     * Get the property associated with the inquiry.
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(RealEstateProperty::class, 'property_id');
    }

    /**
     * Get the customer that made the inquiry.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the agent assigned to the inquiry.
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}
