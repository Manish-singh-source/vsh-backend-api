<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Equipment>
 */
class EquipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word() . ' Equipment',
            'image' => null,
            'description' => fake()->optional()->sentence(),
            'wing_name' => fake()->randomElement(['A', 'B', 'C']),
            'is_bookable' => fake()->boolean(70),
            'status' => fake()->randomElement(['active', 'inactive', 'unavailable', 'damaged', 'under_maintenance']),
            'added_by' => function () {
                return \App\Models\User::inRandomOrder()->value('id') ?? \App\Models\User::factory()->create()->id;
            },
            'added_at' => now(),
        ];
    }
}
