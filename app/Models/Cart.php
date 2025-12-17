<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $fillable = [
        'customer_id',
        'product_id',
        'quantity',
        'metal_configuration',
        'price_at_addition',
    ];

    protected $casts = [
        'metal_configuration' => 'array',
        'price_at_addition' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Calculate subtotal for this cart item
    public function getSubtotalAttribute()
    {
        return $this->price_at_addition * $this->quantity;
    }
}
