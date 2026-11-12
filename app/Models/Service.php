<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'slug',
        'description_ar',
        'description_en',
        'category_id',
        'type',
        'base_price',
        'price_unit',
        'pricing_tiers',
        'estimated_duration',
        'requirements',
        'deliverables',
        'images',
        'is_active',
        'requires_quote',
        'metadata',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'pricing_tiers' => 'array',
        'requirements' => 'array',
        'deliverables' => 'array',
        'images' => 'array',
        'is_active' => 'boolean',
        'requires_quote' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Get the category that owns the service.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
