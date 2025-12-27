<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function usersList(Request $request)
    {
        $checkAdmin = User::where('user_id', auth('api')->user()->user_id)
            ->whereIn('role', ['admin', 'super admin'])
            ->first();

        if (!$checkAdmin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
            ], 403);
        }

        $query = User::with('roles')->where('role', '!=', 'super admin');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Users list retrieved',
            'data' => [
                'users' => UserResource::collection($users),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'total' => $users->total(),
                    'per_page' => $users->perPage(),
                    'last_page' => $users->lastPage(),
                ]
            ]
        ]);
    }

    public function approveUser(Request $request, User $user)
    {
        $validated = Validator::make($request->all(),([
            'status' => 'required|in:active'
        ]));

        if ($validated->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Status is required',
                'errors' => $validated->errors(),
            ], 422);
        }

        $user->update([
            'status' => $request->status,
            'is_verified' => 1,
            'approved_by' => auth('api')->user()->user_id,
            'approved_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User ' . strtolower($request->status) . 'd successfully',
            'data' => new UserResource($user->fresh()),
        ]);
    }
    
    public function rejectUser(Request $request, User $user)
    {
        $validated = Validator::make($request->all(),([
            'status' => 'required|in:reject'
        ]));

        if ($validated->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Status is required',
                'errors' => $validated->errors(),
            ], 422);
        }

        $user->update([
            'status' => $request->status == 'reject' ? 'blocked' : 'active',
            'approved_by' => auth('api')->user()->user_id,
            'approved_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User ' . strtolower($request->status) . 'd successfully',
            'data' => new UserResource($user->fresh()),
        ]);
    }
}
