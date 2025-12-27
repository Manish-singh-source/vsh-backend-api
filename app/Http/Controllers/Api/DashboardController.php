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
            'data'    => [
                'user'      => $user,
                'role'      => $user->role,
                'user_id'   => $user->user_id,
                'full_name' => $user->full_name,
                'phone' => $user->phone_number,
                'email' => $user->email,
                'wing_name' => $user->wing_name,
                'flat_no' => $user->flat_no,
                'status' => $user->status,
                'is_verified' => $user->is_verified,
                'qr_code_image' => $user->qr_code_image ? asset($user->qr_code_image) : null,
            ],
        ]);
    }
    
    public function profile()
    {
        $user = auth('api')->user();

        return response()->json([
            'success' => true,
            'message' => 'Dashboard data',
            'data'    => [
                'user'      => $user,
                'role'      => $user->role,
                'user_id'   => $user->user_id,
                'qr_code'   => $user->qr_code_image,
                'full name' => $user->full_name,
                
                // add counts / stats as needed
            ],
        ]);
    }
}
