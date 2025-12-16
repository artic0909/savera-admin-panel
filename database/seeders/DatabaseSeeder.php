<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            AdminSeeder::class,
        ]);

        $this->call([
            MetalSeeder::class,
        ]);

        $this->call([
            ShapeSeeder::class,
        ]);

        $this->call([
            StyleSeeder::class,
        ]);

        $this->call([
            ColorSeeder::class,
        ]);

        $this->call([
            SizeSeeder::class,
        ]);

        $this->call([
            PincodeSeeder::class,
        ]);
    }
}
