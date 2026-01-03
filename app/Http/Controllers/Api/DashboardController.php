<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $user = auth('api')->user();

        return response()->json([
            'success' => true,
            'message' => 'Dashboard data',
            'data'      => $user,
        ]);
    }

    public function profile()
    {
        $user = auth('api')->user();

        return response()->json([
            'success' => true,
            'message' => 'Profile data',
            'data'    => [
                'role'      => $user->role,
                'user_id'   => $user->user_id,
                'qr_code'   => $user->qr_code_image,
                'full name' => $user->full_name,
                'phone' => $user->phone_number,
                'email' => $user->email,
                'wing_name' => $user->wing_name,
                'flat_no' => $user->flat_no,
                'status' => $user->status,
                'is_verified' => $user->is_verified,
                'qr_code_image' => $user->qr_code_image ? asset($user->qr_code_image) : null,
                'profile_image' => $user->profile_image ? asset($user->profile_image) : null,
                'approved_by' => $user->approved_by,
                'approved_at' => $user->approved_at?->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
