<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class TeacherProfileController extends Controller
{
    /**
     * GET /api/teacher/profile
     * Returns editable + read-only fields for the authenticated teacher
     */
    public function getProfile()
    {
        $teacher = Auth::user();

        // Ensure user is a teacher
        if ($teacher->role !== 'teacher') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            // Editable fields
            'editable' => [
                'display_name' => $teacher->display_name,
                'avatar_url' => $teacher->avatar_url,
                'bio' => $teacher->bio,
                'pronouns' => $teacher->pronouns,
            ],
            
            // Read-only fields (from registration/approval flow)
            'readonly' => [
                'faculty_id_path' => $teacher->faculty_id_path,
                'faculty_id_url' => $teacher->faculty_id_path 
                    ? Storage::url($teacher->faculty_id_path) 
                    : null,
                'is_approved' => $teacher->is_approved,
                'approved_at' => $teacher->approved_at?->format('F j, Y'),
                'email' => $teacher->email,
                'created_at' => $teacher->created_at->format('F j, Y'),
            ],
        ]);
    }

    /**
     * GET /teacher/profile/show
     * Renders the teacher profile page
     */
    public function showProfile()
    {
        $teacher = Auth::user();

        // Ensure user is a teacher
        if ($teacher->role !== 'teacher') {
            abort(403, 'Unauthorized');
        }

        return view('teacher.profile', compact('teacher'));
    }

    /**
     * PATCH /api/teacher/profile
     * Updates ONLY whitelisted fields: display_name, bio, pronouns
     * 
     * SECURITY: Rejects any non-whitelisted field with 403
     */
    public function updateProfile(Request $request)
    {
        $teacher = Auth::user();

        // Ensure user is a teacher
        if ($teacher->role !== 'teacher') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // SECURITY WHITELIST: Only these fields are allowed
        $allowedFields = ['display_name', 'bio', 'pronouns'];
        
        // Check if request contains ANY field not in the whitelist
        $requestFields = array_keys($request->all());
        $unauthorizedFields = array_diff($requestFields, $allowedFields);
        
        if (!empty($unauthorizedFields)) {
            return response()->json([
                'error' => 'Forbidden: Attempted to modify restricted fields',
                'restricted_fields' => $unauthorizedFields,
            ], 403);
        }

        // Validate whitelisted fields
        $validated = $request->validate([
            'display_name' => ['required', 'string', 'min:1', 'max:40'],
            'bio' => ['nullable', 'string', 'max:200'],
            'pronouns' => ['nullable', 'string', 'max:30'],
        ]);

        // Update only the whitelisted fields
        $teacher->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'display_name' => $teacher->display_name,
                'bio' => $teacher->bio,
                'pronouns' => $teacher->pronouns,
            ],
        ]);
    }

    /**
     * POST /api/teacher/avatar
     * Uploads and validates teacher avatar
     * 
     * SECURITY: 
     * - Validates MIME type (jpeg/png only)
     * - Validates extension
     * - Max size: 1MB
     */
    public function uploadAvatar(Request $request)
    {
        $teacher = Auth::user();

        // Ensure user is a teacher
        if ($teacher->role !== 'teacher') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Validate avatar upload
        $request->validate([
            'avatar' => [
                'required',
                'file',
                'mimes:jpeg,jpg,png',
                'max:1024', // 1MB max
            ],
        ]);

        $file = $request->file('avatar');
        
        // Additional security: verify MIME type from file content
        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, ['image/jpeg', 'image/png'])) {
            throw ValidationException::withMessages([
                'avatar' => ['Invalid image type. Only JPEG and PNG are allowed.'],
            ]);
        }

        // Delete old avatar if exists
        if ($teacher->avatar_url && Storage::disk('public')->exists($teacher->avatar_url)) {
            Storage::disk('public')->delete($teacher->avatar_url);
        }

        // Store new avatar
        $path = $file->store('avatars/teachers', 'public');
        
        // Update teacher record
        $teacher->update(['avatar_url' => $path]);

        return response()->json([
            'success' => true,
            'message' => 'Avatar uploaded successfully',
            'data' => [
                'avatar_url' => $path,
                'avatar_full_url' => Storage::url($path),
            ],
        ]);
    }

    /**
     * POST /api/teacher/avatar/preset
     * Sets avatar to a preset option (alternative to upload)
     */
    public function setPresetAvatar(Request $request)
    {
        $teacher = Auth::user();

        if ($teacher->role !== 'teacher') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Preset avatar options
        $presets = [
            'avatar1.png',
            'avatar2.png',
            'avatar3.png',
            'avatar4.png',
            'avatar5.png',
            'avatar6.png',
            'avatar7.png',
            'avatar8.png',
        ];

        $validated = $request->validate([
            'preset' => ['required', 'string', 'in:' . implode(',', $presets)],
        ]);

        // Delete old uploaded avatar if exists (but not preset)
        if ($teacher->avatar_url && 
            !str_contains($teacher->avatar_url, 'avatars/avatar') &&
            Storage::disk('public')->exists($teacher->avatar_url)) {
            Storage::disk('public')->delete($teacher->avatar_url);
        }

        // Set preset avatar path
        $presetPath = 'images/avatars/' . $validated['preset'];
        
        $teacher->update(['avatar_url' => $presetPath]);

        return response()->json([
            'success' => true,
            'message' => 'Avatar updated successfully',
            'data' => [
                'avatar_url' => $presetPath,
            ],
        ]);
    }
}
