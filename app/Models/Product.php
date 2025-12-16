<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'product_name',
        'main_image',
        'additional_images',
        'delivery_time',
        'colors',
        'metal_configurations',
        'available_pincodes',
    ];

    protected $casts = [
        'additional_images' => 'array',
        'colors' => 'array',
        'metal_configurations' => 'array',
        'available_pincodes' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
