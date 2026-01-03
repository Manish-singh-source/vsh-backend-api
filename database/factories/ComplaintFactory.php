<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Complaint>
 */
class ComplaintFactory extends Factory
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
            'complaint_type' => fake()->randomElement(['maintenance', 'security', 'electrical', 'plumbing', 'common_area', 'amenities', 'parking', 'other']),
            'title' => fake()->sentence(6),
            'description' => fake()->optional()->paragraph(),
            'flat_no' => fake()->optional()->bothify('A-###'),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'image' => null,
            'status' => fake()->randomElement(['pending', 'in_progress', 'resolved', 'reopened', 'cancelled']),
            'resolved_by' => null,
            'resolved_at' => null,
            'resolution_notes' => null,
        ];
    }
}
