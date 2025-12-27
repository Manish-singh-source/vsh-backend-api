<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Services\UserIdGenerator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin
        User::create([
            'role' => 'super admin',
            'user_id' => 'vsh@sa',
            'full_name' => 'Super Admin',
            'phone' => '1234567890',
            'email' => 'superadmin@gmail.com',
            'wing_name' => 'A',
            'flat_no' => '1304',
            'password' => Hash::make('Superadmin@123'),
            'status' => 'active',
            'is_verified' => true,
        ]);

        // Admin
        User::create([
            'role' => 'admin',
            'user_id' => 'AD0001',
            'full_name' => 'Admin User',
            'phone' => '1234567891',
            'email' => 'admin@gmail.com',
            'wing_name' => 'A',
            'flat_no' => '101',
            'password' => Hash::make('Admin@123'),
            'status' => 'active',
            'is_verified' => true,
        ]);

        // 5 Owners
        $owners = [
            ['Gaurav Sharma', '9876543210', 'owner1@gmail.com', 'A', '101'],
            ['Priya Patel', '9876543211', 'owner2@gmail.com', 'A', '102'],
            ['Ramesh Kumar', '9876543212', 'owner3@gmail.com', 'B', '201'],
            ['Sita Devi', '9876543213', 'owner4@gmail.com', 'B', '202'],
            ['Amit Singh', '9876543214', 'owner5@gmail.com', 'C', '301'],
        ];

        foreach ($owners as $owner) {
            $userId = UserIdGenerator::generate('owner', $owner[3]);
            User::create([
                'role' => 'owner',
                'user_id' => $userId,
                'full_name' => $owner[0],
                'phone' => $owner[1],
                'email' => $owner[2],
                'wing_name' => $owner[3],
                'flat_no' => $owner[4],
                'password' => Hash::make('Owner@123'),
                'status' => 'inactive',
            ]);
        }

        // 10 Staff
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'role' => 'staff',
                'user_id' => 'ST' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'full_name' => 'Staff Member ' . $i,
                'phone' => '98888888' . $i,
                'email' => 'staff' . $i . '@society.com',
                'password' => Hash::make('Staff@123'),
                'status' => 'inactive',
            ]);
        }
    }
}
