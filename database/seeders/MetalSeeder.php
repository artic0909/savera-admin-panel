<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MetalSeeder extends Seeder
{
    public function run(): void
    {
        $metals = [
            ['metal_name' => 'Gold', 'metal_purity' => '14KT'],
            ['metal_name' => 'Gold', 'metal_purity' => '18KT'],
            ['metal_name' => 'Gold', 'metal_purity' => '22KT'],

            ['metal_name' => 'Silver', 'metal_purity' => null],

            ['metal_name' => 'Diamond', 'metal_purity' => null],

            ['metal_name' => 'Platinum', 'metal_purity' => null],
        ];

        foreach ($metals as $metal) {
            $slug = Str::slug(
                $metal['metal_name'] .
                ($metal['metal_purity'] ? '-' . $metal['metal_purity'] : '')
            );

            DB::table('metals')->updateOrInsert(
                ['slug' => $slug],
                [
                    'metal_name'   => $metal['metal_name'],
                    'metal_purity' => $metal['metal_purity'],
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]
            );
        }
    }
}
