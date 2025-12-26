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
                'qr_code'   => $user->qr_code_image,
                // add counts / stats as needed
            ],
        ]);
    }
}
