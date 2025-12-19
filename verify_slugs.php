<?php

use App\Models\Product;
use Illuminate\Support\Str;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

function testSlugGeneration()
{
    $name = "Test Product " . rand(1000, 9999);
    echo "Testing with name: $name\n";

    // Create first product
    $p1 = new Product();
    $p1->product_name = $name;
    // Mocking the creation logic since we might not want to hit DB for just testing the method
    // But since it's a booted event, we should ideally use the model

    // Let's actually test the static method directly first if possible, 
    // but it's protected. Let's use Reflection or just test via model creation if DB is available.

    try {
        // We'll use a transaction to avoid cluttering the DB
        DB::beginTransaction();

        $commonFields = [
            'category_id' => 1,
            'main_image' => 'test.jpg',
            'delivery_time' => '3-5 days',
            'colors' => [],
            'metal_configurations' => [],
        ];

        $prod1 = Product::create(array_merge($commonFields, [
            'product_name' => $name,
        ]));
        echo "Product 1 Slug: " . $prod1->slug . "\n";

        $prod2 = Product::create(array_merge($commonFields, [
            'product_name' => $name,
        ]));
        echo "Product 2 Slug: " . $prod2->slug . "\n";

        $prod3 = Product::create(array_merge($commonFields, [
            'product_name' => $name,
        ]));
        echo "Product 3 Slug: " . $prod3->slug . "\n";

        DB::rollBack();
        echo "Test completed and rolled back.\n";
    } catch (\Exception $e) {
        DB::rollBack();
        echo "Error: " . $e->getMessage() . "\n";
    }
}

testSlugGeneration();
