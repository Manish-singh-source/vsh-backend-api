<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GymEntry>
 */
class GymEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $checkIn = now()->subDays(fake()->numberBetween(0, 10))->setTime(fake()->numberBetween(6, 20), 0);
        $checkOut = (clone $checkIn)->addMinutes(fake()->numberBetween(20, 120));
        return [
            'user_id' => function () {
                return \App\Models\User::inRandomOrder()->value('id') ?? \App\Models\User::factory()->create()->id;
            },
            'check_in_at' => $checkIn,
            'check_out_at' => $checkOut,
            'duration_minutes' => $checkOut->diffInMinutes($checkIn),
        ];
    }
}
