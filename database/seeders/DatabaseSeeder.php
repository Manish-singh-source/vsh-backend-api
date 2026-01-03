<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


        $roles = [
            'owner',
            'staff',
            'admin',
            'super admin',
            'owner family member',
            'owner rental person',
            'owner rental family member',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'api']);
        }

        $this->call([
            UsersTableSeeder::class,

            // Data seeders
            VisitorSeeder::class,
            AdvertisementSeeder::class,
            NoticeSeeder::class,
            EquipmentSeeder::class,
            ServicesSeeder::class,
            FamilyMemberSeeder::class,
            EventSeeder::class,
            GymEntrySeeder::class,
            GymVisitBookingSeeder::class,
            GateEntrySeeder::class,
            BookingSeeder::class,
            ComplaintSeeder::class,
            StaffTaskSeeder::class,
            StaffLeaveRequestSeeder::class,
        ]);
    }
}
