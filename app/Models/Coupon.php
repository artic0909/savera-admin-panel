<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'max_discount_amount',
        'valid_from',
        'valid_until',
        'usage_limit',
        'used_count',
        'status',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
    ];

    /**
     * Scope to get only active and valid coupons
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('valid_from')->orWhere('valid_from', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('valid_until')->orWhere('valid_until', '>=', now());
            });
    }

    /**
     * Check if coupon is valid for usage
     */
    public function isValid($orderAmount = 0)
    {
        // 1. Check Status
        if ($this->status !== 'active') return false;

        // 2. Check Dates
        if ($this->valid_from && $this->valid_from->isFuture()) return false;
        if ($this->valid_until && $this->valid_until->isPast()) return false;

        // 3. Check Usage Limit
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;

        // 4. Check Minimum Order Amount
        if ($this->min_order_amount && $orderAmount < $this->min_order_amount) return false;

        return true;
    }

    /**
     * Calculate discount amount
     */
    public function calculateDiscount($orderTotal)
    {
        if ($this->type === 'fixed') {
            return min($this->value, $orderTotal); // Can't discount more than total
        }

        // Percent calculation
        $discount = ($orderTotal * $this->value) / 100;

        // Cap at max discount if set
        if ($this->max_discount_amount) {
            $discount = min($discount, $this->max_discount_amount);
        }

        return $discount;
    }
}
