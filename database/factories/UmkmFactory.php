<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Umkm>
 */
class UmkmFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'description' => fake()->sentence(3),
            'address' => fake()->address(),
            'city_id' => fake()->state(),
            'province_id' => fake()->city(),
            'owner_name' => fake()->name(),
            'contact' => fake()->phoneNumber(),
        ];
    }
}
