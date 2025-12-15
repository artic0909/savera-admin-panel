<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ShapeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shapes = [
            'Round Brilliant',
            'Emerald',
            'Marquise',
            'Heart',
            'Pear',
            'Cushion',
            'Radiant',
            'Oval',
            'Princess',
        ];

        foreach ($shapes as $shape) {
            DB::table('shapes')->updateOrInsert(
                ['slug' => Str::slug($shape)],
                [
                    'shape_name' => $shape,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
