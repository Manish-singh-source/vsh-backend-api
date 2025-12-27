<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function usersList(Request $request)
    {
        $query = User::with('roles');

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
        $request->validate([
            'status' => 'required|in:active,blocked'
        ]);

        $user->update([
            'status' => $request->status,
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
