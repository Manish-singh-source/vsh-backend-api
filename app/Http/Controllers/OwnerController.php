<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\QrCodeService;

class OwnerController extends Controller
{
    //
    public function profile()
    {
        $checkOwner = User::where('user_id', auth('api')->user()->user_id)
            ->whereIn('role', ['owner', 'super admin'])
            ->first();

        if (!$checkOwner) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
            ], 403);
        }

        $user = auth('api')->user();

        return response()->json([
            'success' => true,
            'message' => 'Owner profile',
            'data' => [
                'user' => $user,
                'qr_code_url' => $user->qr_code_image ? asset($user->qr_code_image) : null,
            ]
        ]);
    }

    public function refreshQrCode()
    {
        $checkOwner = User::where('user_id', auth('api')->user()->user_id)
            ->whereIn('role', ['owner', 'super admin'])
            ->first();

        if (!$checkOwner) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
            ], 403);
        }

        $user = auth('api')->user();

        // Generate fresh QR with current timestamp for security
        $membership = [
            'from_date' => now()->toDateString(),
            'to_date' => now()->addYear()->toDateString(),
            'title' => 'Standard Membership',
            'timestamp' => now()->timestamp, // refreshes every 5 min
        ];

        $qrPayload = [
            'user_id' => $user->user_id,
            'role' => $user->role,
            'membership' => $membership,
        ];

        $qrFileName = $user->user_id . '_' . now()->timestamp . '.png';
        $qrPath = QrCodeService::generateQrForUser($qrPayload, $qrFileName);

        $user->qr_code_image = $qrPath;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'QR Code refreshed',
            'data' => [
                'qr_code_url' => asset($qrPath),
            ]
        ]);
    }
}
