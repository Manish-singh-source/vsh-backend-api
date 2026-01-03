<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FamilyMember>
 */
class FamilyMemberFactory extends Factory
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
            'user_code' => function () {
                $last = \App\Models\FamilyMember::orderBy('user_code', 'desc')->value('user_code');
                if ($last && preg_match('/FM(\d{3})$/', $last, $m)) {
                    $num = (int) $m[1] + 1;
                } else {
                    $num = 1;
                }
                return 'FM' . str_pad($num, 3, '0', STR_PAD_LEFT);
            },
            'full_name' => fake()->name(),
            'email' => fake()->optional()->safeEmail(),
            'phone' => fake()->optional()->phoneNumber(),
            'relation_with_user' => fake()->randomElement(['spouse', 'child', 'parent', 'other']),
            'profile_image' => null,
            'qr_code_image' => null,
            'status' => fake()->randomElement(['active', 'inactive']),
            'approved_by' => null,
            'approved_at' => null,
        ];
    }
}
