<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'phone_number',
        'country',
        'ip',
    ];

    // Relationship: A user can have multiple subscriptions
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
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
