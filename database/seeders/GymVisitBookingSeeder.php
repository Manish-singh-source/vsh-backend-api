<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GymVisitBookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create bookings with retry on unique slot conflicts
        $target = 25;

        for ($i = 0; $i < $target; $i++) {
            $attempts = 0;
            while ($attempts < 6) {
                try {
                    \App\Models\GymVisitBooking::factory()->create();
                    break;
                } catch (\Illuminate\Database\QueryException $e) {
                    // 23000 = SQLSTATE for integrity constraint violation
                    if ((string) $e->getCode() === '23000') {
                        $attempts++;
                        // try again with a different random slot
                        continue;
                    }

                    // unexpected DB error — rethrow
                    throw $e;
                }
            }

            if ($attempts >= 6) {
                // skip this slot after several failed attempts
                logger()->warning("GymVisitBookingSeeder: skipped creating a booking after {$attempts} duplicate attempts");
            }
        }
    }
}
