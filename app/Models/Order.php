<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'order_number',
        'status',
        'subtotal',
        'tax',
        'shipping',
        'total',
        'shipping_address',
        'billing_address',
        'payment_method',
        'payment_status',
        'transaction_id',
        'payment_info',
        'coupon_code',
        'discount_amount',
        'notes',
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'payment_info' => 'array',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping' => 'decimal:2',
        'total' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Generate unique order number
    public static function generateOrderNumber()
    {
        $prefix = 'ORD';
        $timestamp = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
        return $prefix . $timestamp . $random;
    }

    // Get status badge color
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'warning',
            'processing' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }
}
