<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pincode;

class PincodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pincodes = [
            '110001',
            '400001',
            '560001',
            '600001',
            '700001',
        ];

        foreach ($pincodes as $code) {
            Pincode::create(['code' => $code]);
        }
    }
}
