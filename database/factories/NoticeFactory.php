<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notice>
 */
class NoticeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(6),
            'description' => fake()->paragraph(),
            'notice_category' => fake()->randomElement(['general', 'maintenance', 'event']),
            'image' => null,
            'start_date' => now()->subDays(fake()->numberBetween(0, 5)),
            'end_date' => now()->addDays(fake()->numberBetween(1, 30)),
            'is_important' => fake()->boolean(10),
            'status' => fake()->randomElement(['active', 'inactive']),
            'added_by' => function () {
                return \App\Models\User::inRandomOrder()->value('id') ?? \App\Models\User::factory()->create()->id;
            },
            'added_at' => now(),
        ];
    }
}
