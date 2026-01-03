<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Entry;
use Illuminate\Http\Request;
use App\Http\Resources\EntryResource;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    //
    public function scanQr(Request $request)
    {
        $checkStaff = User::where('user_code', $request->user_id)
            ->whereIn('role', ['staff', 'super admin'])
            ->first();

        if (!$checkStaff) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'qr_data' => 'required',
            'entry_mode' => 'required|in:gym,vehicle',
            'entry_type' => 'required|in:in,out',
            'vehicle_number' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // qr_data may be a JSON string or an array
        $qrData = $request->input('qr_data');

        if (is_string($qrData)) {
            $data = json_decode($qrData, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid QR code format',
                    'error' => json_last_error_msg(),
                ], 400);
            }
        } elseif (is_array($qrData)) {
            $data = $qrData;
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR code payload',
            ], 400);
        }

        if (! isset($data['user_id']) || ($data['role'] ?? null) !== 'owner') {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR code or not an owner',
            ], 400);
        }

        $owner = User::where('user_id', $data['user_id'])
            ->where('status', 'active')
            ->first();

        if (! $owner) {
            return response()->json([
                'success' => false,
                'message' => 'Owner not found or account inactive',
            ], 404);
        }

        // Check if QR is fresh (within 5 minutes)
        if (
            isset($data['membership']['timestamp']) &&
            (time() - $data['membership']['timestamp']) > 300
        ) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code expired. Please refresh.',
            ], 400);
        }

        $staff = auth('api')->user();

        $entry = Entry::create([
            'owner_id' => $owner->id,
            'staff_id' => $staff->id,
            'entry_mode' => $request->entry_mode,
            'entry_type' => $request->entry_type,
            'vehicle_number' => $request->vehicle_number,
            'notes' => $request->notes,
            'entry_date' => now()->format('Y-m-d'),
            'entry_time' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Entry recorded successfully',
            'data' => new EntryResource($entry->load(['owner', 'staff'])),
        ]);
    }

    public function entriesList(Request $request)
    {
        $checkStaff = User::where('user_id', auth('api')->user()->user_id)
            ->whereIn('role', ['staff', 'super admin'])
            ->first();
        if (!$checkStaff) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
            ], 403);
        }

        $query = Entry::with(['owner', 'staff'])
            ->whereHas('staff', function ($q) {
                $q->where('id', auth('api')->id());
            });

        if ($request->has('entry_mode')) {
            $query->where('entry_mode', $request->entry_mode);
        }

        if ($request->has('date_from') && $request->has('date_to')) {
            $query->whereBetween('entry_date', [
                $request->date_from,
                $request->date_to,
            ]);
        }

        $entries = $query->orderBy('entry_time', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'message' => 'Entries retrieved',
            'data' => [
                'entries' => EntryResource::collection($entries),
                'pagination' => [
                    'current_page' => $entries->currentPage(),
                    'total' => $entries->total(),
                ]
            ]
        ]);
    }
}
