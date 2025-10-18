<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['name', 'price', 'best_plan', 'language', 'description', 'duration'];

    protected $casts = [
        'price' => 'float',
        'duration' => 'integer',
    ];

    // Relationship: A plan can have multiple subscriptions
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

}
