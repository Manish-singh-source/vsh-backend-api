<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StaffLeaveRequest>
 */
class StaffLeaveRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $from = now()->addDays(fake()->numberBetween(1, 30));
        $to = (clone $from)->addDays(fake()->numberBetween(0, 5));
        return [
            'staff_id' => function () {
                return \App\Models\User::where('role', 'staff')->inRandomOrder()->value('id') ?? \App\Models\User::factory()->create()->id;
            },
            'leave_type' => fake()->randomElement(['sick', 'casual', 'paid', 'unpaid', 'emergency', 'annual', 'other']),
            'from_date' => $from,
            'to_date' => $to,
            'is_half_day' => fake()->boolean(10),
            'reason' => fake()->sentence(),
            'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
            'approved_by' => null,
        ];
    }
}
