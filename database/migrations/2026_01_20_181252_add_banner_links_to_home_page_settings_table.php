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
        $links = [
            ['key' => 'banner_1_link', 'value' => '#', 'type' => 'text'],
            ['key' => 'banner_2_link', 'value' => '#', 'type' => 'text'],
            ['key' => 'banner_3_link', 'value' => '#', 'type' => 'text'],
            ['key' => 'banner_4_link', 'value' => '#', 'type' => 'text'],
        ];

        foreach ($links as $link) {
            \Illuminate\Support\Facades\DB::table('home_page_settings')->insert(array_merge($link, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::table('home_page_settings')
            ->whereIn('key', ['banner_1_link', 'banner_2_link', 'banner_3_link', 'banner_4_link'])
            ->delete();
    }
};
