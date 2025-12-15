<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes = [
            '6',
            '7',
            '8',
            '9',
            '10',
            '11',
            '12',
        ];

        foreach ($sizes as $size) {
            DB::table('sizes')->updateOrInsert(
                ['size_name' => $size],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
