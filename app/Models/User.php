<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'role',
        'grade_level',
        'student_id_number',
        'created_by_teacher_id',
        'character_avatar',
        'faculty_id_path',
        'school_id_path',
        'is_approved',
        'is_active',
        'email_verified_at',
        // Student profile fields
        'nickname',
        'avatar_character',
        'avatar_upload_path',
        'birthdate',
        'favorite_math_topic',
        'bio',
        'hobbies',
        'game_mode_preference',
        'sound_effects_volume',
        'music_volume',
        'practice_reminders',
        'streak_alerts',
        'display_badge',
        'original_username',
        // Teacher profile fields
        'display_name',
        'avatar_url',
        'pronouns',
        'approved_at',
        'last_username_change',
        'username_changes_count',
        'profile_updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_approved' => 'boolean',
        'is_active' => 'boolean',
        'birthdate' => 'datetime',
        'hobbies' => 'array',
        'practice_reminders' => 'boolean',
        'streak_alerts' => 'boolean',
        'last_username_change' => 'datetime',
        'profile_updated_at' => 'datetime',
        'display_badge' => 'array',
    ];

    // ========== RELATIONSHIPS ==========

    /**
     * Classrooms this student belongs to (many-to-many).
     */
    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class, 'classroom_student', 'student_id', 'classroom_id');
    }

    /**
     * Classrooms this teacher created (one-to-many).
     */
    public function taughtClassrooms()
    {
        return $this->hasMany(Classroom::class, 'teacher_id');
    }

    /**
     * Progress records for games (one-to-many).
     */
    public function gameProgress()
    {
        return $this->hasMany(StudentGameProgress::class, 'student_id');
    }

    /**
     * The teacher who created this student account.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_teacher_id');
    }

    /**
     * Students created by this teacher.
     */
    public function teacherStudents()
    {
        return $this->hasMany(User::class, 'created_by_teacher_id');
    }

    /**
     * Profile change logs for this user.
     */
    public function profileChangeLogs()
    {
        return $this->hasMany(ProfileChangeLog::class, 'user_id');
    }

    /**
     * Teacher notifications for this teacher.
     */
    public function teacherNotifications()
    {
        return $this->hasMany(TeacherNotification::class, 'teacher_id');
    }

    /**
     * General notifications for this user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    /**
     * Get unread notifications count.
     */
    public function unreadNotificationsCount(): int
    {
        return $this->notifications()->unread()->count();
    }

    /**
     * Get student's earned badges.
     */
    public function badges()
    {
        return $this->hasMany(StudentBadge::class, 'student_id');
    }

    /**
     * Get student's earned badges count.
     */
    public function badgesCount(): int
    {
        return $this->badges()->count();
    }

    // ========== HELPER METHODS ==========

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is teacher and approved.
     */
    public function isTeacher(): bool
    {
        return $this->role === 'teacher' && $this->is_approved;
    }

    /**
     * Check if user is student.
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Check if teacher is pending approval.
     */
    public function isPendingTeacher(): bool
    {
        return $this->role === 'teacher' && !$this->is_approved;
    }

    /**
     * Get the character avatar emoji or icon class.
     */
    public function getCharacterEmoji(): string
    {
        return match ($this->character_avatar) {
            'math_wizard' => '🧙‍♂️',
            'number_ninja' => '🥷',
            'fraction_fighter' => '⚔️',
            'algebra_ace' => '🎯',
            'geometry_genius' => '🤖',
            default => '🧑‍🎓',
        };
    }

    /**
     * Check if student can change username (not exceeded daily limit).
     */
    public function canChangeUsername(): bool
    {
        // If never changed before, can change
        if (!$this->last_username_change) {
            return true;
        }

        // If last change was before today, reset counter (allow change)
        $today = \Carbon\Carbon::today();
        $lastChange = \Carbon\Carbon::parse($this->last_username_change);
        
        if ($lastChange->isBefore($today)) {
            return true;
        }

        // Check if under daily limit (3 changes per day)
        return ($this->username_changes_count ?? 0) < 3;
    }

    /**
     * Get remaining username changes for today.
     */
    public function getRemainingUsernameChanges(): int
    {
        if (!$this->last_username_change) {
            return 3;
        }

        $today = \Carbon\Carbon::today();
        $lastChange = \Carbon\Carbon::parse($this->last_username_change);
        
        if ($lastChange->isBefore($today)) {
            return 3;
        }

        return max(0, 3 - ($this->username_changes_count ?? 0));
    }

    /**
     * Get the avatar URL (either uploaded or preset character).
     * Includes cache-busting timestamp for uploaded avatars to prevent browser caching issues.
     */
    public function getAvatarUrl(): string
    {
        if ($this->avatar_upload_path) {
            // If it's already a full URL (from Supabase), return as-is with cache buster
            if (str_starts_with($this->avatar_upload_path, 'http')) {
                $timestamp = $this->updated_at ? $this->updated_at->timestamp : time();
                return $this->avatar_upload_path . '?v=' . $timestamp;
            }
            
            // Otherwise it's a local path, add cache-busting timestamp
            $timestamp = $this->updated_at ? $this->updated_at->timestamp : time();
            return asset('storage/' . $this->avatar_upload_path . '?v=' . $timestamp);
        }

        // Return preset character image path
        if ($this->avatar_character && str_starts_with($this->avatar_character, 'avatar')) {
            return asset('images/avatars/' . $this->avatar_character . '.png');
        }

        // Return old emoji character or default
        return $this->avatar_character ?? $this->character_avatar ?? '🧑‍🎓';
    }
}