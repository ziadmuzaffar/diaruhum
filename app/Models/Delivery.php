<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_number',
        'order_id',
        'driver_id',
        'status',
        'pickup_address',
        'pickup_lat',
        'pickup_lng',
        'delivery_address',
        'delivery_lat',
        'delivery_lng',
        'scheduled_pickup',
        'scheduled_delivery',
        'actual_pickup',
        'actual_delivery',
        'distance_km',
        'delivery_cost',
        'vehicle_type',
        'special_instructions',
        'recipient_name',
        'recipient_phone',
        'tracking_updates',
        'proof_of_delivery',
        'delivery_notes',
        'rating',
        'feedback',
        'metadata',
    ];

    protected $casts = [
        'pickup_lat' => 'decimal:8',
        'pickup_lng' => 'decimal:8',
        'delivery_lat' => 'decimal:8',
        'delivery_lng' => 'decimal:8',
        'scheduled_pickup' => 'datetime',
        'scheduled_delivery' => 'datetime',
        'actual_pickup' => 'datetime',
        'actual_delivery' => 'datetime',
        'distance_km' => 'decimal:2',
        'delivery_cost' => 'decimal:2',
        'tracking_updates' => 'array',
        'proof_of_delivery' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Get the order that owns the delivery.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the driver assigned to the delivery.
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}
