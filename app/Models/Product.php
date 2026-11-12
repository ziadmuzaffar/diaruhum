<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'sku',
        'description_ar',
        'description_en',
        'category_id',
        'merchant_id',
        'price',
        'cost_price',
        'unit',
        'stock_quantity',
        'min_stock_level',
        'weight',
        'dimensions',
        'brand',
        'model',
        'specifications',
        'images',
        'is_active',
        'requires_delivery',
        'delivery_cost',
        'metadata',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'weight' => 'decimal:3',
        'dimensions' => 'array',
        'specifications' => 'array',
        'images' => 'array',
        'is_active' => 'boolean',
        'requires_delivery' => 'boolean',
        'delivery_cost' => 'decimal:2',
        'metadata' => 'array',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the merchant that owns the product.
     */
    public function merchant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the inventory records for the product.
     */
    public function inventory(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }
}
