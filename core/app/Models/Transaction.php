<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'subscription_id',
        'external_order_id',
        'request_id',
        'transaction_number',
        'payment_id',
        'senypro_transaction_id',
        'senypro_order_id',
        'amount',
        'currency',
        'status',
        'payment_status',
        'customer_email',
        'customer_name',
        'customer_phone',
        'billing_address',
        'products',
        'payment_url',
        'completed_at',
    ];

    protected $casts = [
        'amount' => 'float',
        'billing_address' => 'array',
        'products' => 'array',
        'completed_at' => 'datetime',
    ];

    // Relationship: A transaction belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: A transaction is linked to a plan
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    // Relationship: A transaction may be linked to a subscription
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    // Check if transaction is completed
    public function isCompleted(): bool
    {
        return $this->payment_status === 'Paid' && $this->status === 'completed';
    }

    // Check if transaction is pending
    public function isPending(): bool
    {
        return $this->payment_status === 'Pending' || $this->status === 'pending';
    }

    // Scope: Filter by status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope: Filter by payment status
    public function scopeByPaymentStatus($query, $paymentStatus)
    {
        return $query->where('payment_status', $paymentStatus);
    }

    // Scope: Get completed transactions
    public function scopeCompleted($query)
    {
        return $query->where('payment_status', 'Paid')->where('status', 'completed');
    }
}

