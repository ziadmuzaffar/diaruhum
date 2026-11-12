<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the admin profile associated with the user.
     */
    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class);
    }

    /**
     * Get the customer profile associated with the user.
     */
    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }

    /**
     * Get the merchant profile associated with the user.
     */
    public function merchant(): HasOne
    {
        return $this->hasOne(Merchant::class);
    }

    /**
     * Get the driver profile associated with the user.
     */
    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class);
    }

    /**
     * Get the staff profile associated with the user.
     */
    public function staff(): HasOne
    {
        return $this->hasOne(Staff::class);
    }

    /**
     * Get the orders for the user (as customer).
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    /**
     * Get the projects for the user (as customer).
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'customer_id');
    }

    /**
     * Get the assigned projects for the user (as staff).
     */
    public function assignedProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'assigned_staff_id');
    }

    /**
     * Get the quotations for the user (as customer).
     */
    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class, 'customer_id');
    }

    /**
     * Get the created quotations for the user (as staff).
     */
    public function createdQuotations(): HasMany
    {
        return $this->hasMany(Quotation::class, 'created_by');
    }

    /**
     * Get the payments for the user (as customer).
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'customer_id');
    }

    /**
     * Get the deliveries for the user (as driver).
     */
    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class, 'driver_id');
    }

    /**
     * Get the invoices for the user (as customer).
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'customer_id');
    }

    /**
     * Get the inventory for the user (as merchant).
     */
    public function inventory(): HasMany
    {
        return $this->hasMany(Inventory::class, 'merchant_id');
    }

    /**
     * Get the staff assignments for the user.
     */
    public function staffAssignments(): HasMany
    {
        return $this->hasMany(StaffAssignment::class, 'staff_id');
    }

    /**
     * Get the real estate properties for the user (as owner).
     */
    public function ownedProperties(): HasMany
    {
        return $this->hasMany(RealEstateProperty::class, 'owner_id');
    }

    /**
     * Get the assigned properties for the user (as agent).
     */
    public function assignedProperties(): HasMany
    {
        return $this->hasMany(RealEstateProperty::class, 'assigned_agent_id');
    }

    /**
     * Get the property inquiries for the user (as customer).
     */
    public function propertyInquiries(): HasMany
    {
        return $this->hasMany(PropertyInquiry::class, 'customer_id');
    }

    /**
     * Get the assigned property inquiries for the user (as agent).
     */
    public function assignedPropertyInquiries(): HasMany
    {
        return $this->hasMany(PropertyInquiry::class, 'agent_id');
    }

    /**
     * Get the reviews for the user (as customer).
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(ReviewRating::class, 'customer_id');
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the activity logs for the user.
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Get the support tickets for the user (as customer).
     */
    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class, 'customer_id');
    }

    /**
     * Get the assigned support tickets for the user (as staff).
     */
    public function assignedSupportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class, 'assigned_to');
    }

    /**
     * Get the support messages for the user.
     */
    public function supportMessages(): HasMany
    {
        return $this->hasMany(SupportMessage::class);
    }

    /**
     * Get the coupon usages for the user (as customer).
     */
    public function couponUsages(): HasMany
    {
        return $this->hasMany(CouponUsage::class, 'customer_id');
    }

    /**
     * Get the file uploads for the user.
     */
    public function fileUploads(): HasMany
    {
        return $this->hasMany(FileUpload::class, 'uploaded_by');
    }

    /**
     * Get the GPS tracking records for the user (as driver).
     */
    public function gpsTracking(): HasMany
    {
        return $this->hasMany(GpsTracking::class, 'driver_id');
    }
}
