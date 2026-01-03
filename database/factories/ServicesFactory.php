<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Services>
 */
class ServicesFactory extends Factory
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
            'service_category' => fake()->randomElement(['plumber', 'electrician', 'cleaning', 'security']),
            'phone' => fake()->optional()->phoneNumber(),
            'opening_hours' => '9:00 AM - 6:00 PM',
            'address' => fake()->address(),
            'status' => fake()->randomElement(['active', 'inactive']),
            'added_by' => \App\Models\User::factory(),
            'added_at' => now(),
        ];
    }
}
