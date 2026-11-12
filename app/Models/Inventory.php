<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'merchant_id',
        'warehouse_location',
        'quantity_available',
        'quantity_reserved',
        'quantity_ordered',
        'reorder_level',
        'max_stock_level',
        'cost_per_unit',
        'last_restocked',
        'expiry_date',
        'batch_number',
        'supplier',
        'storage_conditions',
        'notes',
    ];

    protected $casts = [
        'cost_per_unit' => 'decimal:2',
        'last_restocked' => 'date',
        'expiry_date' => 'date',
        'storage_conditions' => 'array',
    ];

    /**
     * Get the product that owns the inventory.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the merchant that owns the inventory.
     */
    public function merchant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }
}
