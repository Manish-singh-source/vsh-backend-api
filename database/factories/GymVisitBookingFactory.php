<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GymVisitBooking>
 */
class GymVisitBookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = now()->addDays(fake()->numberBetween(0, 14));
        $start = $date->copy()->setTime(fake()->numberBetween(6, 20), 0);
        $end = (clone $start)->addMinutes(fake()->numberBetween(30, 120));
        return [
            'user_id' => function () {
                return \App\Models\User::inRandomOrder()->value('id') ?? \App\Models\User::factory()->create()->id;
            },
            'visit_date' => $date,
            'start_time' => $start,
            'end_time' => $end,
            'status' => fake()->randomElement(['pending', 'approved', 'cancelled']),
            'approved_by' => null,
            'purpose' => fake()->optional()->sentence(),
            'duration_minutes' => ($end->diffInMinutes($start)),
        ];
    }
}
