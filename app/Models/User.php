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

    // ========== HELPER METHODS ==========

    /**
     * Check if user is admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is teacher and approved.
     */
    public function isTeacher()
    {
        return $this->role === 'teacher' && $this->is_approved;
    }

    /**
     * Check if user is student.
     */
    public function isStudent()
    {
        return $this->role === 'student';
    }

    /**
     * Check if teacher is pending approval.
     */
    public function isPendingTeacher()
    {
        return $this->role === 'teacher' && !$this->is_approved;
    }

    /**
     * Get the character avatar emoji or icon class.
     */
    public function getCharacterEmoji()
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
}