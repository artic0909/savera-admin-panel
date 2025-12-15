<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StyleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $styles = [
            'Office',
            'Daily',
            'Wedding',
            'Party',
            'Traditional',
        ];

        foreach ($styles as $style) {
            DB::table('styles')->updateOrInsert(
                ['slug' => Str::slug($style)],
                [
                    'style_name' => $style,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
