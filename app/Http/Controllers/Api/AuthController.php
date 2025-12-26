<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Services\QrCodeService;
use App\Services\UserIdGenerator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name'   => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'phone'       => 'required|string|max:20|unique:users,phone',
            'wing_name'   => 'required|string|max:10',
            'flat_no'     => 'required|string|max:50|unique:users,flat_no,NULL,id,wing_name,' . $request->wing_name,
            'password'    => 'required|string|min:6|confirmed',
            'role'        => 'required|string|in:owner,staff,admin,super admin,owner family member,owner rental person,owner rental family member',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // generate user_id
        $userId = UserIdGenerator::generate($request->role, $request->wing_name);

        // create user
        $user = User::create([
            'role'         => $request->role,
            'user_id'      => $userId,
            'full_name'    => $request->full_name,
            'name'         => $request->full_name,
            'phone' => $request->phone,
            'email'        => $request->email,
            'wing_name'    => $request->wing_name,
            'flat_no'      => $request->flat_no,
            'password'     => Hash::make($request->password),
            'status'       => 'inactive',
            'is_verified'  => false,
        ]);

        // assign role (spatie)
        $user->assignRole($request->role);

        // generate static membership details
        $membership = [
            'from_date' => now()->toDateString(),
            'to_date'   => now()->addYear()->toDateString(),
            'title'     => 'Standard Membership',
        ];

        // generate QR code
        $qrPayload = [
            'user_id'   => $user->user_id,
            'role'      => $user->role,
            'membership' => $membership,
        ];

        $qrFileName = $user->user_id . '.png';
        // $qrPath = QrCodeService::generateQrForUser($qrPayload, $qrFileName);

        // $user->qr_code_image = $qrPath;
        $user->qr_code_image = $qrFileName;
        $user->save();

        // generate token
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data'    => [
                'user'  => $user,
                'token' => $token,
            ],
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_id' => 'required|string', // user_id
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = User::where('user_id', $request->login_id)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        if ($user->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Account is not active',
            ], 403);
        }

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token'   => $token,
            'user'    => $user,
        ]);
    }

    // OTP: send (generate and send via SMS/email in real implementation)
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|exists:users,phone',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = User::where('phone', $request->phone)->first();

        $otp = rand(100000, 999999);
        $user->otp = (string) $otp;
        $user->otp_expiry = now()->addMinutes(10);
        $user->save();

        // TODO: integrate SMS provider here. DO NOT return OTP in production.
        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully',
            'debug_otp' => $otp, // remove in production
        ]);
    }

    // OTP: verify and login
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|exists:users,phone',
            'otp'          => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = User::where('phone', $request->phone)->first();

        if (! $user || $user->otp !== $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP',
            ], 400);
        }

        if (Carbon::parse($user->otp_expiry)->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'OTP expired',
            ], 400);
        }

        $user->is_verified = true;
        $user->otp = null;
        $user->otp_expiry = null;
        $user->status = 'active'; // optionally activate on first OTP login
        $user->save();

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully',
            'token'   => $token,
            'user'    => $user,
        ]);
    }

    public function refresh()
    {
        try {
            $newToken = JWTAuth::parseToken()->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Token refreshed successfully',
                'token'   => $newToken,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not refresh token',
                'error'   => $e->getMessage(),
            ], 401);
        }
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to logout',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $token = Str::random(64);

        \DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // TODO: send email with token link
        return response()->json([
            'success' => true,
            'message' => 'Password reset link sent',
            'debug_token' => $token, // remove in production
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'                 => 'required|email|exists:users,email',
            'token'                 => 'required|string',
            'password'              => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $record = \DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (! $record || ! Hash::check($request->token, $record->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid password reset token',
            ], 400);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $user->password = Hash::make($request->password);
        $user->save();

        \DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully',
        ]);
    }

    public function changePassword(Request $request)
    {
        $user = auth('api')->user();

        $validator = Validator::make($request->all(), [
            'current_password'      => 'required|string',
            'new_password'          => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect',
            ], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully',
        ]);
    }
}
