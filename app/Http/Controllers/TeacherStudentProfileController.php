<?php

namespace App\Http\Controllers;

use App\Models\ProfileChangeLog;
use App\Models\TeacherNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherStudentProfileController extends Controller
{
    /**
     * Show student profile details (teacher view)
     */
    public function show($studentId)
    {
        $teacher = Auth::user();
        
        // Verify teacher has access to this student
        $student = User::where('id', $studentId)
            ->where('role', 'student')
            ->whereHas('classrooms', function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })
            ->firstOrFail();

        // Get recent change logs
        $changeLogs = ProfileChangeLog::where('user_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('teacher.student-profile', compact('student', 'changeLogs'));
    }

    /**
     * View full change log for a student
     */
    public function changeLog($studentId)
    {
        $teacher = Auth::user();
        
        // Verify teacher has access to this student
        $student = User::where('id', $studentId)
            ->where('role', 'student')
            ->whereHas('classrooms', function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })
            ->firstOrFail();

        $logs = ProfileChangeLog::where('user_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('teacher.student-change-log', compact('student', 'logs'));
    }

    /**
     * Revert username to original (requires teacher override)
     */
    public function revertUsername(Request $request, $studentId)
    {
        $teacher = Auth::user();
        
        // Verify teacher has access to this student
        $student = User::where('id', $studentId)
            ->where('role', 'student')
            ->whereHas('classrooms', function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })
            ->firstOrFail();

        $request->validate([
            'reason' => ['required', 'string', 'max:255'],
        ]);

        if (!$student->original_username) {
            return back()->with('error', 'No original username found for this student.');
        }

        $oldUsername = $student->username;
        $student->username = $student->original_username;
        $student->username_changes_count = 0; // Reset counter
        $student->last_username_change = now();
        $student->save();

        // Log the reversion
        ProfileChangeLog::create([
            'user_id' => $student->id,
            'field_name' => 'username',
            'old_value' => $oldUsername,
            'new_value' => $student->original_username,
            'changed_by_role' => 'teacher',
            'changed_by_user_id' => $teacher->id,
        ]);

        return back()->with('success', 'Username reverted to original successfully.');
    }

    /**
     * Clear/reset a specific profile field (teacher override for inappropriate content)
     */
    public function clearField(Request $request, $studentId)
    {
        $teacher = Auth::user();
        
        // Verify teacher has access to this student
        $student = User::where('id', $studentId)
            ->where('role', 'student')
            ->whereHas('classrooms', function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })
            ->firstOrFail();

        $validated = $request->validate([
            'field_name' => ['required', 'string', 'in:nickname,bio,hobbies,display_badge,avatar_character,avatar_upload_path'],
            'reason' => ['required', 'string', 'max:255'],
        ]);

        $fieldName = $validated['field_name'];
        $oldValue = $student->$fieldName;

        // Clear the field
        if ($fieldName === 'hobbies') {
            $student->$fieldName = [];
        } else {
            $student->$fieldName = null;
        }
        $student->save();

        // Log the clearing
        ProfileChangeLog::create([
            'user_id' => $student->id,
            'field_name' => $fieldName,
            'old_value' => is_array($oldValue) ? json_encode($oldValue) : $oldValue,
            'new_value' => null,
            'changed_by_role' => 'teacher',
            'changed_by_user_id' => $teacher->id,
        ]);

        return back()->with('success', ucfirst(str_replace('_', ' ', $fieldName)) . ' cleared successfully.');
    }

    /**
     * Edit student username directly (emergency override)
     */
    public function editUsername(Request $request, $studentId)
    {
        $teacher = Auth::user();
        
        // Verify teacher has access to this student
        $student = User::where('id', $studentId)
            ->where('role', 'student')
            ->whereHas('classrooms', function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })
            ->firstOrFail();

        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $student->id],
            'reason' => ['required', 'string', 'max:255'],
        ]);

        $oldUsername = $student->username;
        $student->username = $validated['username'];
        $student->username_changes_count = 0; // Reset counter on teacher edit
        $student->last_username_change = now();
        $student->save();

        // Log the change
        ProfileChangeLog::create([
            'user_id' => $student->id,
            'field_name' => 'username',
            'old_value' => $oldUsername,
            'new_value' => $validated['username'],
            'changed_by_role' => 'teacher',
            'changed_by_user_id' => $teacher->id,
        ]);

        return back()->with('success', 'Username updated successfully.');
    }

    /**
     * Mark teacher notification as read
     */
    public function markNotificationRead($notificationId)
    {
        $teacher = Auth::user();
        
        $notification = TeacherNotification::where('id', $notificationId)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadCount()
    {
        $teacher = Auth::user();
        
        $count = TeacherNotification::where('teacher_id', $teacher->id)
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }
}
