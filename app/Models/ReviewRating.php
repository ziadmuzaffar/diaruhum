<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ReviewRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'reviewable_type',
        'reviewable_id',
        'order_id',
        'rating',
        'title',
        'comment',
        'rating_breakdown',
        'is_verified',
        'is_published',
        'images',
        'merchant_response',
        'merchant_responded_at',
        'helpful_votes',
        'metadata',
    ];

    protected $casts = [
        'rating_breakdown' => 'array',
        'is_verified' => 'boolean',
        'is_published' => 'boolean',
        'images' => 'array',
        'merchant_responded_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Get the customer that made the review.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the order associated with the review.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the reviewable model (service, product, etc.).
     */
    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }
}
