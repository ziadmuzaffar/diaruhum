<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Merchant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_name',
        'commercial_register',
        'tax_number',
        'business_type',
        'specializations',
        'license_number',
        'license_expiry',
        'business_address',
        'warehouse_address',
        'city',
        'region',
        'latitude',
        'longitude',
        'contact_person',
        'contact_phone',
        'contact_email',
        'bank_details',
        'commission_rate',
        'payment_terms',
        'delivery_radius_km',
        'minimum_order_amount',
        'offers_delivery',
        'delivery_fee',
        'operating_hours',
        'certifications',
        'rating',
        'total_orders',
        'is_verified',
        'verified_at',
        'status',
        'documents',
        'metadata',
    ];

    protected $casts = [
        'specializations' => 'array',
        'license_expiry' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'bank_details' => 'array',
        'commission_rate' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
        'offers_delivery' => 'boolean',
        'delivery_fee' => 'decimal:2',
        'operating_hours' => 'array',
        'certifications' => 'array',
        'rating' => 'decimal:2',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'documents' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Get the user that owns the merchant profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the products for the merchant.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
