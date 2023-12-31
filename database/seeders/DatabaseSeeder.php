<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Product;
use App\Models\ProductPhoto;
use App\Models\Umkm;
use App\Models\UmkmPhoto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            ProvinceSeeder::class
        ]);

        // \App\Models\User::factory()->create([
        //     'name' => 'Syarip Mas`ud',
        //     'email' => 'admin@admin.com',
        //     'email_verified_at' => now(),
        //     'password' => Hash::make('password'),
        //     'remember_token' => Str::random(10),
        // ]);

        // Umkm::factory()->count(10)
        //     ->has(UmkmPhoto::factory()->count(3), 'photos')
        //     ->has(Product::factory()->count(5)->has(ProductPhoto::factory()->count(3), 'photos'), 'products')
        //     ->create();
    }
}
