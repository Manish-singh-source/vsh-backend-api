<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Visitor>
 */
class VisitorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $visitDate = now()->subDays(fake()->numberBetween(0, 10));
        return [
            'user_id' => function () {
                return \App\Models\User::inRandomOrder()->value('id') ?? \App\Models\User::factory()->create()->id;
            },
            'staff_id' => null,
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'vehicle_number' => fake()->optional()->bothify('??-####'),
            'purpose' => fake()->optional()->sentence(),
            'visit_date' => $visitDate,
            'check_in_at' => $visitDate->copy()->setTime(fake()->numberBetween(8, 18), 0),
            'check_out_at' => null,
            'status' => fake()->randomElement(['expected', 'checked_in', 'checked_out', 'denied']),
        ];
    }
}
