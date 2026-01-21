<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Http\Request;

class ResumeController extends Controller
{
    /**
     * CREATE RESUME
     */
    public function store(Request $request)
    {
        $user = $request->user; // from VerifyJWT middleware

        if (
            !$request->resume_name ||
            !$request->template_id ||
            !$request->form_data
        ) {
            return response()->json([
                'message' => 'Data is missing.'
            ], 400);
        }

        $resume = Resume::create([
            'user_id'     => $user->id,
            'resume_name' => $request->resume_name,
            'template_id' => $request->template_id,
            'form_data'   => $request->form_data,
        ]);

        return response()->json([
            'status' => 201,
            'data' => $resume,
            'message' => 'Resume saved successfully'
        ], 201);
    }

    /**
     * GET ALL RESUMES OF LOGGED-IN USER
     */
    public function index(Request $request)
    {
        $user = $request->user;

        $resumes = Resume::where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json([
            'status' => 200,
            'data' => $resumes,
            'message' => 'User resumes fetched successfully'
        ]);
    }

    /**
     * GET RESUME BY ID (OWNERSHIP CHECK)
     */
    public function show(Request $request, $id)
    {
        $user = $request->user;

        $resume = Resume::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$resume) {
            return response()->json([
                'message' => 'Resume not found for the ID or does not belong to you'
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'data' => $resume,
            'message' => 'Resume fetched successfully'
        ]);
    }

    /**
     * UPDATE RESUME (OWNERSHIP CHECK)
     */
    public function update(Request $request, $id)
    {
        $user = $request->user;

        $resume = Resume::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$resume) {
            return response()->json([
                'message' => 'Resume not found or not authorized'
            ], 404);
        }

        $resume->update($request->all());

        return response()->json([
            'status' => 200,
            'data' => $resume,
            'message' => 'Resume updated successfully'
        ]);
    }

    /**
     * DELETE RESUME (OWNERSHIP CHECK)
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user;

        $resume = Resume::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$resume) {
            return response()->json([
                'message' => 'Resume not found or not authorized'
            ], 404);
        }

        $resume->delete();

        return response()->json([
            'status' => 200,
            'data' => [],
            'message' => 'Resume deleted successfully'
        ]);
    }
}
