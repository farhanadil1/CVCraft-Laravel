<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Http\Request;

class ResumeController extends Controller
{
    //Create resume
    public function store(Request $request)
    {
        $user = $request->user;

        $resumeName = $request->resumeName;
        $templateId = $request->templateId;
        $formData   = $request->formData;

        if (!$resumeName || !$templateId || !$formData) {
            return response()->json([
                'message' => 'Data is missing.'
            ], 400);
        }

        $resume = Resume::create([
            'user_id'     => $user->id,
            'resume_name' => $resumeName,
            'template_id' => $templateId,
            'form_data'   => $formData,
        ]);

        return response()->json([
            'status' => 201,
            'data' => [
                'id' => $resume->id,
                'resumeName' => $resume->resume_name,
                'templateId' => $resume->template_id,
                'formData' => $resume->form_data,
                'createdAt' => $resume->created_at,
                'updatedAt' => $resume->updated_at,
            ],
            'message' => 'Resume saved successfully'
        ], 201);
    }

    //get all resume of logged in user
    public function index(Request $request)
    {
        $user = $request->user;

        $resumes = Resume::where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($resume) {
                return [
                    'id' => $resume->id,
                    'resumeName' => $resume->resume_name,
                    'templateId' => $resume->template_id,
                    'formData' => $resume->form_data,
                    'createdAt' => $resume->created_at,
                    'updatedAt' => $resume->updated_at,
                ];
            });

        return response()->json([
            'status' => 200,
            'data' => $resumes,
            'message' => 'User resumes fetched successfully'
        ]);
    }

    //get resume by id
    public function show(Request $request, $id)
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

        return response()->json([
            'status' => 200,
            'data' => [
                'id' => $resume->id,
                'resumeName' => $resume->resume_name,
                'templateId' => $resume->template_id,
                'formData' => $resume->form_data,
                'createdAt' => $resume->created_at,
                'updatedAt' => $resume->updated_at,
            ],
            'message' => 'Resume fetched successfully'
        ]);
    }

    //update resume
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

        $resume->update([
            'resume_name' => $request->resumeName ?? $resume->resume_name,
            'template_id' => $request->templateId ?? $resume->template_id,
            'form_data'   => $request->formData ?? $resume->form_data,
        ]);

        return response()->json([
            'status' => 200,
            'data' => [
                'id' => $resume->id,
                'resumeName' => $resume->resume_name,
                'templateId' => $resume->template_id,
                'formData' => $resume->form_data,
                'createdAt' => $resume->created_at,
                'updatedAt' => $resume->updated_at,
            ],
            'message' => 'Resume updated successfully'
        ]);
    }

    //delete resume
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
