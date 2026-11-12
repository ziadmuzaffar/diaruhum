<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'driver_license_number',
        'license_type',
        'license_expiry',
        'national_id',
        'birth_date',
        'address',
        'city',
        'region',
        'emergency_contact',
        'employment_type',
        'vehicle_info',
        'vehicle_registration',
        'vehicle_insurance_expiry',
        'allowed_vehicle_types',
        'max_load_capacity',
        'service_radius_km',
        'base_rate_per_km',
        'has_gps_device',
        'available_for_work',
        'current_status',
        'current_latitude',
        'current_longitude',
        'last_location_update',
        'rating',
        'total_deliveries',
        'completed_deliveries',
        'total_distance_km',
        'working_hours',
        'is_verified',
        'verified_at',
        'documents',
        'metadata',
    ];

    protected $casts = [
        'license_expiry' => 'date',
        'birth_date' => 'date',
        'emergency_contact' => 'array',
        'vehicle_info' => 'array',
        'vehicle_insurance_expiry' => 'date',
        'allowed_vehicle_types' => 'array',
        'max_load_capacity' => 'decimal:2',
        'base_rate_per_km' => 'decimal:2',
        'has_gps_device' => 'boolean',
        'available_for_work' => 'boolean',
        'current_latitude' => 'decimal:8',
        'current_longitude' => 'decimal:8',
        'last_location_update' => 'datetime',
        'rating' => 'decimal:2',
        'total_distance_km' => 'decimal:2',
        'working_hours' => 'array',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'documents' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Get the user that owns the driver profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the deliveries for the driver.
     */
    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class);
    }
}
