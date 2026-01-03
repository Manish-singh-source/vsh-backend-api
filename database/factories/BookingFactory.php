<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = now()->addDays(fake()->numberBetween(0, 30));
        $endDate = (clone $startDate)->addDays(fake()->numberBetween(0, 2));
        $startTime = $startDate->copy()->setTime(fake()->numberBetween(8, 20), 0);
        $endTime = (clone $startTime)->addMinutes(fake()->numberBetween(30, 180));
        return [
            'user_id' => function () {
                return \App\Models\User::inRandomOrder()->value('id') ?? \App\Models\User::factory()->create()->id;
            },
            'equipment_id' => \App\Models\Equipment::factory(),
            'booking_type' => fake()->randomElement(['equipment', 'gym_visit']),
            'purpose' => fake()->optional()->sentence(),
            'duration_minutes' => $endTime->diffInMinutes($startTime),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => fake()->randomElement(['pending', 'approved', 'rejected', 'cancelled']),
            'approved_by' => null,
            'approved_at' => null,
        ];
    }
}
