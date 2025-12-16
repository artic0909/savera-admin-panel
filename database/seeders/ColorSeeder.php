<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            'Rose Gold',
            'Silver',
            'Gold',
        ];

        foreach ($colors as $color) {
            DB::table('colors')->updateOrInsert(
                ['color_name' => $color],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
