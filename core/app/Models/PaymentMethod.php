<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $primaryKey = 'id'; // or whatever the actual PK column is

    protected $fillable = [
        'name',
        'img',
        'status',
    ];
}
