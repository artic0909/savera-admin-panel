<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingSetting extends Model
{
    protected $fillable = [
        'shiprocket_email',
        'shiprocket_password',
        'shiprocket_pickup_location',
        'shiprocket_token',
        'shiprocket_token_expiry',
        'is_shiprocket_enabled',
    ];

    protected $casts = [
        'is_shiprocket_enabled' => 'boolean',
        'shiprocket_token_expiry' => 'datetime',
    ];
}
