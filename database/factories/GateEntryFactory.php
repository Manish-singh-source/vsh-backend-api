<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GateEntry>
 */
class GateEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => function () {
                return \App\Models\User::inRandomOrder()->value('id') ?? \App\Models\User::factory()->create()->id;
            },
            'staff_id' => null,
            'vehicle_number' => fake()->optional()->bothify('??-####'),
            'entry_type' => fake()->randomElement(['entry', 'exit']),
            'entry_at' => now()->subMinutes(fake()->numberBetween(0, 1000)),
            'purpose' => fake()->optional()->sentence(),
        ];
    }
}
