<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'full_name',
        'email',
        'password',
        'phone_number',
        'country',
        'ip',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationship: A user can have multiple subscriptions
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    // Relationship: A user can have multiple transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Relationship: A user can have multiple tickets
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // Relationship: A user is subscribed to one plan (if any)
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    // Get user's active subscription
    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)->where('status', 'active')->latest();
    }

    // Check if user has an active subscription
    public function hasActiveSubscription(): bool
    {
        return $this->subscriptions()->where('status', 'active')->exists();
    }

    // Get formatted phone number (Example: +1 123-456-7890)
    public function getFormattedPhoneAttribute()
    {
        return preg_replace('/(\d{1})(\d{3})(\d{3})(\d{4})/', '+$1 $2-$3-$4', $this->phone_number);
    }
}
