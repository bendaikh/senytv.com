<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'price',
        'status',
        'payment_method',
        'transaction_id', // Now links to transactions table
        'expires_at',
    ];

    protected $casts = [
        'price' => 'float',
        'expires_at' => 'datetime',
    ];

    // Relationship: A subscription belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: A subscription is linked to a plan
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    // Check if subscription is active
    public function isActive(): bool
    {
        return $this->status === 'active' && now()->lt($this->expires_at);
    }

    // Relationship: A subscription belongs to a payment method
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method');
    }

    // Relationship: A subscription may have a transaction
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
