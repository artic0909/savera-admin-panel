<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    protected $fillable = [
        'razorpay_key',
        'razorpay_secret',
        'is_cod_enabled',
    ];

    protected $casts = [
        'is_cod_enabled' => 'boolean',
    ];
}
