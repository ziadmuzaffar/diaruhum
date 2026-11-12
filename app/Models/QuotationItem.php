<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'item_type',
        'service_id',
        'product_id',
        'item_name',
        'description',
        'unit_price',
        'quantity',
        'unit',
        'total_price',
        'specifications',
        'estimated_hours',
        'notes',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'specifications' => 'array',
    ];

    /**
     * Get the quotation that owns the quotation item.
     */
    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    /**
     * Get the service associated with the quotation item.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the product associated with the quotation item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
