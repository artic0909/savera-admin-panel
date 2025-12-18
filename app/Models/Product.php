<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'product_name',
        'slug',
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

    public function getDisplayPriceAttribute()
    {
        // 1. Normalize Configurations
        $allConfigs = [];
        $configs = $this->metal_configurations;

        if (is_array($configs)) {
            $firstKey = array_key_first($configs);
            if (!is_null($firstKey) && isset($configs[$firstKey]['material_id'])) {
                $allConfigs = $configs;
            } else {
                foreach ($configs as $group) {
                    if (is_array($group)) {
                        if (isset($group['material_id'])) {
                            $allConfigs[] = $group;
                        } else {
                            foreach ($group as $c) {
                                if (is_array($c) && isset($c['material_id'])) {
                                    $allConfigs[] = $c;
                                }
                            }
                        }
                    }
                }
            }
        }

        if (empty($allConfigs)) {
            return '--';
        }

        // 2. Get the LAST configuration
        $config = end($allConfigs);

        // 3. Fetch Materials (Optimize by fetching all needed once or just simple find)
        // Since this is per-product, we'll just fetch what we need.
        $materialPrice = 0;
        if (isset($config['material_id'])) {
            $material = Material::find($config['material_id']);
            if ($material) {
                $materialPrice = $material->price;
            }
        }

        // Diamond Price
        $diamondPricePerCarat = 0;
        $diamondMaterial = Material::where('name', 'Diamond')->first();
        if ($diamondMaterial) {
            $diamondPricePerCarat = $diamondMaterial->price;
        }

        // 4. Calculate
        $netWt = floatval($config['net_weight_gold'] ?? 0);
        $materialCost = $netWt * $materialPrice;

        $diamondTotalWt = 0;
        if (isset($config['diamond_info']) && is_array($config['diamond_info'])) {
            foreach ($config['diamond_info'] as $dInfo) {
                $diamondTotalWt += floatval($dInfo['total_weight'] ?? 0);
            }
        }
        $diamondCost = $diamondTotalWt * $diamondPricePerCarat;

        $makingCharge = floatval($config['making_charge'] ?? 0);

        $basePrice = $materialCost + $diamondCost + $makingCharge;
        $gstPercentage = floatval($config['gst_percentage'] ?? 0);
        $gstAmount = ($basePrice * $gstPercentage) / 100;
        $finalPrice = $basePrice + $gstAmount;

        return number_format($finalPrice, 2);
    }

    protected static function booted()
    {
        static::creating(function ($product) {
            $product->slug = \Illuminate\Support\Str::slug($product->product_name) . '-' . uniqid();
        });

        static::updating(function ($product) {
            if ($product->isDirty('product_name') && !$product->slug) {
                $product->slug = \Illuminate\Support\Str::slug($product->product_name) . '-' . uniqid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
