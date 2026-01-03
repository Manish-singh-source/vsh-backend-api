<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = now()->addDays(fake()->numberBetween(1, 60));
        $endDate = (clone $startDate)->addDays(fake()->numberBetween(0, 2));
        $startTime = $startDate->copy()->setTime(fake()->numberBetween(9, 20), 0);
        $endTime = (clone $startTime)->addHours(fake()->numberBetween(1, 6));
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'venue' => fake()->company(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'image' => null,
            'event_type' => fake()->randomElement(['festival', 'meeting', 'activity', 'sport', 'other']),
            'status' => fake()->randomElement(['active', 'inactive']),
            'added_by' => function () {
                return \App\Models\User::inRandomOrder()->value('id') ?? \App\Models\User::factory()->create()->id;
            },
            'added_at' => now(),
        ];
    }
}
