<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('product_name');
            $table->string('main_image');
            $table->json('additional_images')->nullable();
            $table->string('delivery_time');
            $table->json('colors')->nullable();
            $table->json('metal_configurations')->nullable();
            $table->boolean('is_diamond_used')->default(false);
            $table->json('diamond_gemstone_info')->nullable();
            $table->json('available_pincodes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
