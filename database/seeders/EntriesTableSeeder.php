<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Entry;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EntriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Entry::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $owners = User::where('role', 'owner')->pluck('id')->toArray();
        $staffs = User::where('role', 'staff')->pluck('id')->toArray();

        $modes = ['gym', 'vehicle'];
        $types = ['in', 'out'];
        $vehicles = ['MH04AB1234', 'MH04AB1235', 'MH04AB1236', null];

        for ($i = 0; $i < 20; $i++) {
            Entry::create([
                'owner_id' => $owners[array_rand($owners)],
                'staff_id' => $staffs[array_rand($staffs)],
                'entry_mode' => $modes[array_rand($modes)],
                'entry_type' => $types[array_rand($types)],
                'vehicle_number' => $vehicles[array_rand($vehicles)],
                'notes' => fake()->optional(0.3)->sentence(),
                'entry_date' => Carbon::now()->subDays(rand(1, 30))->format('Y-m-d'),
                'entry_time' => Carbon::now()->subDays(rand(1, 30))->subMinutes(rand(0, 1439))->format('Y-m-d H:i:s'),
            ]);
        }
    }
}
