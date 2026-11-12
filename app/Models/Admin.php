<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admin_level',
        'permissions',
        'accessible_modules',
        'department',
        'employee_id',
        'hire_date',
        'can_manage_users',
        'can_manage_orders',
        'can_manage_products',
        'can_manage_finances',
        'can_view_reports',
        'metadata',
    ];

    protected $casts = [
        'permissions' => 'array',
        'accessible_modules' => 'array',
        'hire_date' => 'date',
        'can_manage_users' => 'boolean',
        'can_manage_orders' => 'boolean',
        'can_manage_products' => 'boolean',
        'can_manage_finances' => 'boolean',
        'can_view_reports' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Get the user that owns the admin profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
