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
            'user_id' => 'SA001',
            'full_name' => 'Super Admin',
            'phone_number' => '9999999991',
            'email' => 'superadmin@society.com',
            'wing_name' => 'A',
            'flat_no' => 'ADMIN',
            'password' => Hash::make('Admin@123'),
            'status' => 'active',
            'is_verified' => true,
        ]);

        // Admin
        User::create([
            'role' => 'admin',
            'user_id' => 'AD001',
            'full_name' => 'Admin User',
            'phone_number' => '9999999992',
            'email' => 'admin@society.com',
            'wing_name' => 'A',
            'flat_no' => 'ADMIN2',
            'password' => Hash::make('Admin@123'),
            'status' => 'active',
            'is_verified' => true,
        ]);

        // 5 Owners
        $owners = [
            ['Gaurav Sharma', '9876543210', 'owner1@society.com', 'A', '101'],
            ['Priya Patel', '9876543211', 'owner2@society.com', 'A', '102'],
            ['Ramesh Kumar', '9876543212', 'owner3@society.com', 'B', '201'],
            ['Sita Devi', '9876543213', 'owner4@society.com', 'B', '202'],
            ['Amit Singh', '9876543214', 'owner5@society.com', 'C', '301'],
        ];

        foreach ($owners as $owner) {
            $userId = UserIdGenerator::generate('owner', $owner[3]);
            User::create([
                'role' => 'owner',
                'user_id' => $userId,
                'full_name' => $owner[0],
                'phone_number' => $owner[1],
                'email' => $owner[2],
                'wing_name' => $owner[3],
                'flat_no' => $owner[4],
                'password' => Hash::make('Owner@123'),
                'status' => 'active',
                'is_verified' => true,
            ]);
        }

        // 10 Staff
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'role' => 'staff',
                'user_id' => 'ST' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'full_name' => 'Staff Member ' . $i,
                'phone_number' => '98888888' . $i,
                'email' => 'staff' . $i . '@society.com',
                'wing_name' => 'STAFF',
                'flat_no' => 'S' . $i,
                'password' => Hash::make('Staff@123'),
                'status' => 'active',
                'is_verified' => true,
            ]);
        }
    }
}
