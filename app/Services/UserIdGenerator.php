<?php
// app/Services/UserIdGenerator.php

namespace App\Services;

use App\Models\User;

class UserIdGenerator
{
    public static function generate(string $role, string $wingName): string
    {
        $role = strtolower($role);
        $prefix = match ($role) {
            'owner' => 'OW',
            'staff' => 'ST',
            'admin' => 'AD',
            'super admin' => 'SA',
            'super_admin' => 'SA',
            'owner family member' => 'OWAF',
            'owner rental person' => 'OWAR',
            'owner rental family member' => 'OWARF',
            default => 'US',
        };

        $wing = strtoupper(substr($wingName, 0, 1));

        // find last user with same prefix+wing and increment (user_code column)
        $basePrefix = $prefix . $wing;
        $last = User::where('user_code', 'like', $basePrefix . '%')
            ->orderBy('user_code', 'desc')
            ->first();

        if ($last) {
            $number = (int) substr($last->user_code, strlen($basePrefix));
            $number++;
        } else {
            $number = 1;
        }

        return $basePrefix . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}
