<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RealEstateProperty extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_code',
        'owner_id',
        'assigned_agent_id',
        'type',
        'sub_type',
        'purpose',
        'status',
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'address',
        'city',
        'district',
        'region',
        'latitude',
        'longitude',
        'price',
        'price_currency',
        'price_type',
        'area_sqm',
        'bedrooms',
        'bathrooms',
        'parking_spaces',
        'floors',
        'built_year',
        'features',
        'amenities',
        'images',
        'documents',
        'virtual_tour',
        'is_featured',
        'views_count',
        'metadata',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'price' => 'decimal:2',
        'area_sqm' => 'decimal:2',
        'features' => 'array',
        'amenities' => 'array',
        'images' => 'array',
        'documents' => 'array',
        'virtual_tour' => 'array',
        'is_featured' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Get the owner of the property.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the assigned agent for the property.
     */
    public function assignedAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_agent_id');
    }

    /**
     * Get the inquiries for the property.
     */
    public function inquiries(): HasMany
    {
        return $this->hasMany(PropertyInquiry::class, 'property_id');
    }
}
