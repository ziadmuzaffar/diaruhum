<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_type',
        'company_name',
        'commercial_register',
        'tax_number',
        'national_id',
        'birth_date',
        'gender',
        'address',
        'city',
        'region',
        'postal_code',
        'latitude',
        'longitude',
        'emergency_contact',
        'preferred_language',
        'preferred_communication',
        'credit_limit',
        'current_balance',
        'loyalty_points',
        'registration_date',
        'preferences',
        'documents',
        'metadata',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'emergency_contact' => 'array',
        'credit_limit' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'registration_date' => 'date',
        'preferences' => 'array',
        'documents' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Get the user that owns the customer profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the orders for the customer.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the projects for the customer.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
