<?php

namespace App\Http\Controllers;

use App\Models\ProfileChangeLog;
use App\Models\TeacherNotification;
use App\Models\User;
use App\Rules\ProfanityFilterRule;
use App\Rules\UsernameRateLimitRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;

class StudentProfileController extends Controller
{
    /**
     * Math topics available for selection
     */
    private const MATH_TOPICS = [
        'Addition & Subtraction',
        'Multiplication & Division',
        'Fractions & Decimals',
        'Geometry & Shapes',
        'Measurement & Time',
        'Patterns & Sequences',
        'Word Problems',
        'Mental Math',
        'Basic Algebra',
        'Data & Graphs',
    ];

    /**
     * Available hobbies (max 5 can be selected)
     */
    private const HOBBIES = [
        'Reading Books',
        'Drawing & Art',
        'Playing Sports',
        'Video Games',
        'Music & Singing',
        'Dancing',
        'Cooking & Baking',
        'Science Experiments',
        'Building & Crafts',
        'Outdoor Adventures',
    ];

    /**
     * Available game mode preferences
     */
    private const GAME_MODES = [
        'Timed Challenge',
        'Practice Mode',
        'Story Mode',
        'Multiplayer Race',
        'Daily Quest',
        'Mixed Adventure',
    ];

    /**
     * Show the student profile (read-only view)
     */
    public function show()
    {
        $user = Auth::user();
        return view('student.profile.show', compact('user'));
    }

    /**
     * Show the profile edit form
     */
    public function edit()
    {
        $user = Auth::user();
        
        return view('student.profile.edit', [
            'user' => $user,
            'mathTopics' => self::MATH_TOPICS,
            'hobbies' => self::HOBBIES,
            'gameModes' => self::GAME_MODES,
        ]);
    }

    /**
     * Update student profile (general fields except username, password, avatar)
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'nickname' => ['nullable', 'string', 'max:20', new ProfanityFilterRule()],
            'birthdate' => ['nullable', 'date', 'after_or_equal:2011-01-01', 'before_or_equal:2019-01-01', 'before_or_equal:today'],
            'favorite_math_topic' => ['nullable', 'string', 'max:50', 'in:' . implode(',', self::MATH_TOPICS)],
            'bio' => ['nullable', 'string', 'max:60', new ProfanityFilterRule()],
            'hobbies' => ['nullable', 'array', 'max:5'],
            'hobbies.*' => ['string', 'in:' . implode(',', self::HOBBIES)],
            'game_mode_preference' => ['nullable', 'string', 'max:50', 'in:' . implode(',', self::GAME_MODES)],
            'sound_effects_volume' => ['nullable', 'integer', 'min:0', 'max:100'],
            'music_volume' => ['nullable', 'integer', 'min:0', 'max:100'],
            'practice_reminders' => ['boolean'],
            'streak_alerts' => ['boolean'],
            'display_badge' => ['nullable', 'string', 'max:100'],
        ]);

        // Track changes for audit log
        $changes = [];
        foreach ($validated as $field => $value) {
            if ($field === 'hobbies') {
                $oldValue = $user->hobbies ?? [];
                $newValue = $value ?? [];
                if (json_encode($oldValue) !== json_encode($newValue)) {
                    $changes[$field] = [
                        'old' => $oldValue,
                        'new' => $newValue,
                    ];
                }
            } else {
                if ($user->$field != $value) {
                    $changes[$field] = [
                        'old' => $user->$field,
                        'new' => $value,
                    ];
                }
            }
        }

        // Update user profile
        $user->update($validated);
        $user->profile_updated_at = now();
        $user->save();

        // Log changes
        foreach ($changes as $field => $change) {
            ProfileChangeLog::create([
                'user_id' => $user->id,
                'field_name' => $field,
                'old_value' => is_array($change['old']) ? json_encode($change['old']) : $change['old'],
                'new_value' => is_array($change['new']) ? json_encode($change['new']) : $change['new'],
                'changed_by_role' => 'student',
                'changed_by_user_id' => $user->id,
            ]);
        }

        return redirect()->route('student.profile.edit')
            ->with('success', '✨ Profile updated successfully!');
    }

    /**
     * Update username (rate-limited to 3 times per day)
     */
    public function updateUsername(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users,username,' . $user->id,
                new ProfanityFilterRule(),
                new UsernameRateLimitRule(),
            ],
        ]);

        $oldUsername = $user->username;
        $newUsername = $validated['username'];

        // Store original username if this is the first change
        if (!$user->original_username) {
            $user->original_username = $oldUsername;
        }

        // Update username and counter
        $today = Carbon::today();
        if ($user->last_username_change && Carbon::parse($user->last_username_change)->isBefore($today)) {
            // Reset counter for new day
            $user->username_changes_count = 1;
        } else {
            // Increment counter
            $user->username_changes_count = ($user->username_changes_count ?? 0) + 1;
        }

        $user->username = $newUsername;
        $user->last_username_change = now();
        $user->save();

        // Log the change
        ProfileChangeLog::create([
            'user_id' => $user->id,
            'field_name' => 'username',
            'old_value' => $oldUsername,
            'new_value' => $newUsername,
            'changed_by_role' => 'student',
            'changed_by_user_id' => $user->id,
        ]);

        // Notify teacher
        if ($user->classrooms()->exists()) {
            foreach ($user->classrooms as $classroom) {
                TeacherNotification::create([
                    'teacher_id' => $classroom->teacher_id,
                    'student_id' => $user->id,
                    'notification_type' => 'username_change',
                    'message' => "{$user->first_name} {$user->last_name} changed username from '{$oldUsername}' to '{$newUsername}'",
                ]);
            }
        }

        $remainingChanges = 3 - $user->username_changes_count;
        return redirect()->route('student.profile.edit')
            ->with('success', "🎮 Username updated! You have {$remainingChanges} change(s) remaining today.");
    }

    /**
     * Update password/PIN
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        $user->password = Hash::make($validated['password']);
        $user->save();

        // Log the change (without storing actual passwords)
        ProfileChangeLog::create([
            'user_id' => $user->id,
            'field_name' => 'password',
            'old_value' => '********',
            'new_value' => '********',
            'changed_by_role' => 'student',
            'changed_by_user_id' => $user->id,
        ]);

        return redirect()->route('student.profile.edit')
            ->with('success', '🔒 Password updated successfully!');
    }

    /**
     * Update avatar (upload custom or select preset)
     */
    public function updateAvatar(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'avatar_type' => ['required', 'in:preset,upload'],
            'avatar_preset' => ['required_if:avatar_type,preset', 'nullable', 'string', 'max:100'],
            'avatar_upload' => ['required_if:avatar_type,upload', 'nullable', 'image', 'mimes:jpeg,jpg,png,gif', 'max:2048'], // 2MB max
        ]);

        $oldAvatar = $user->avatar_character;
        $oldAvatarUpload = $user->avatar_upload_path;

        if ($validated['avatar_type'] === 'preset') {
            // Using preset avatar
            $user->avatar_character = $validated['avatar_preset'];
            
            // Delete old uploaded avatar if exists
            if ($oldAvatarUpload) {
                Storage::disk('public')->delete($oldAvatarUpload);
                $user->avatar_upload_path = null;
            }
        } else {
            // Upload custom avatar
            if ($request->hasFile('avatar_upload')) {
                // Delete old uploaded avatar if exists
                if ($oldAvatarUpload) {
                    Storage::disk('public')->delete($oldAvatarUpload);
                }

                $file = $request->file('avatar_upload');
                $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('avatars', $filename, 'public');
                
                $user->avatar_upload_path = $path;
                $user->avatar_character = null; // Clear preset when uploading
            }
        }

        $user->save();

        // Log the change
        ProfileChangeLog::create([
            'user_id' => $user->id,
            'field_name' => 'avatar',
            'old_value' => $oldAvatar ?? $oldAvatarUpload ?? 'none',
            'new_value' => $user->avatar_character ?? $user->avatar_upload_path ?? 'none',
            'changed_by_role' => 'student',
            'changed_by_user_id' => $user->id,
        ]);

        return redirect()->route('student.profile.edit')
            ->with('success', '🎨 Avatar updated successfully!');
    }

    /**
     * View change history for student's own profile
     */
    public function history()
    {
        $user = Auth::user();
        
        $logs = ProfileChangeLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('student.profile.history', compact('logs', 'user'));
    }
}
