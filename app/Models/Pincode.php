<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pincode extends Model
{
    protected $fillable = ['code', 'status'];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Scope to get only active pincodes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
