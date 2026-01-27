<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Get all users (Admin only)
     */
    public function getAllUsers(Request $request)
    {
        // user is already attached by verify.jwt middleware
        $user = $request->user;

        if ($user->role !== 'admin') {
            return response()->json([
                'message' => 'Forbidden: Admin access only'
            ], 403);
        }

        $users = User::select(
            'id',
            'email',
            'full_name',
            'role',
            'created_at'
        )->get();

        return response()->json([
            'status' => 200,
            'data' => $users
        ]);
    }
}
