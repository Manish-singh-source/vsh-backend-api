<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FamilyMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 30; $i++) {
            $member = \App\Models\FamilyMember::factory()->make();

            // ensure unique user_code (loop until available)
            do {
                $code = 'FM' . str_pad(fake()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT);
            } while (\App\Models\FamilyMember::where('user_code', $code)->exists());

            $member->user_code = $code;
            $member->save();
        }
    }
}
