<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Advertisement>
 */
class AdvertisementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'image' => null,
            'redirect_url' => fake()->url(),
            'start_date' => now()->subDays(fake()->numberBetween(0, 5)),
            'end_date' => now()->addDays(fake()->numberBetween(3, 30)),
            'is_important' => fake()->boolean(15),
            'status' => fake()->randomElement(['active', 'inactive']),
            'added_by' => function () {
                return \App\Models\User::inRandomOrder()->value('id') ?? \App\Models\User::factory()->create()->id;
            },
            'added_at' => now(),
        ];
    }
}
