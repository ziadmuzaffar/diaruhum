<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FileUpload extends Model
{
    use HasFactory;

    protected $fillable = [
        'uploaded_by',
        'original_name',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'disk',
        'type',
        'uploadable_type',
        'uploadable_id',
        'collection',
        'metadata',
        'is_public',
        'expires_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_public' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that uploaded the file.
     */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the owning uploadable model.
     */
    public function uploadable(): MorphTo
    {
        return $this->morphTo();
    }
}
