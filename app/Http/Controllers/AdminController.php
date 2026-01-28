<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Resume;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * ADMIN DASHBOARD STATS
     * GET /api/admin/stats
     */
    public function stats(Request $request)
    {
        return response()->json([
            'status' => 200,
            'data' => [
                'totalUsers'   => User::count(),
                'totalAdmins'  => User::where('role', 'admin')->count(),
                'totalResumes' => Resume::count(),
            ],
            'message' => 'Admin stats fetched successfully'
        ]);
    }

    /**
     * GET ALL USERS (ADMIN)
     * GET /api/admin/users
     */
    public function users(Request $request)
    {
        $users = User::withCount('resumes')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($user) => [
                'id' => $user->id,
                'fullName' => $user->full_name,
                'email' => $user->email,
                'role' => $user->role,
                'resumeCount' => $user->resumes_count,
                'joinedAt' => $user->created_at,
            ]);

        return response()->json([
            'status' => 200,
            'data' => $users,
            'message' => 'All users fetched successfully'
        ]);
    }

    /**
     * GET ALL RESUMES (ADMIN)
     * GET /api/admin/resumes
     */
    public function resumes(Request $request)
    {
        $resumes = Resume::with('user:id,full_name')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(fn($resume) => [
                'id' => $resume->id,
                'resumeName' => $resume->resume_name,
                'templateId' => $resume->template_id,
                'username' => $resume->user->full_name ?? 'Unknown',
                'updatedAt' => $resume->updated_at,
            ]);

        return response()->json([
            'status' => 200,
            'data' => $resumes,
            'message' => 'All resumes fetched successfully'
        ]);
    }
}
