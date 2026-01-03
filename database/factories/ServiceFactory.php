<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
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
            'service_category' => fake()->randomElement(['parcel', 'vehicle', 'supermarket', 'grocery', 'garage', 'doctor', 'medical', 'other']),
            'phone' => fake()->optional()->phoneNumber(),
            'opening_hours' => '9:00 AM - 6:00 PM',
            'address' => fake()->address(),
            'status' => fake()->randomElement(['active', 'inactive']),
            'added_by' => function () {
                return \App\Models\User::inRandomOrder()->value('id') ?? \App\Models\User::factory()->create()->id;
            },
            'added_at' => now(),
        ];
    }
}
