<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermsOfServiceManagements extends Model
{
    protected $fillable = [
        'privacy_policy',
        'terms_of_use',
        'refund_policy',
        'privacy_policy_updated_at',
        'terms_of_use_updated_at',
        'refund_policy_updated_at'
    ];

    public $timestamps = false;
    protected $casts = [
        'privacy_policy_updated_at' => 'datetime',
        'terms_of_use_updated_at' => 'datetime',
        'refund_policy_updated_at' => 'datetime',
    ];
}
