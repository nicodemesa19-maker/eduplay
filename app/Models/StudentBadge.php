<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentBadge extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'badge_type',
        'badge_name',
        'badge_icon',
        'description',
        'badge_level',
        'value',
        'earned_at',
    ];

    protected $casts = [
        'earned_at' => 'datetime',
        'badge_level' => 'integer',
        'value' => 'integer',
    ];

    /**
     * Get the student that owns this badge.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Scope to get badges of a specific type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('badge_type', $type);
    }

    /**
     * Scope to get recent badges.
     */
    public function scopeRecent($query, $limit = 5)
    {
        return $query->orderBy('earned_at', 'desc')->limit($limit);
    }

    /**
     * Badge type constants for consistency.
     */
    const TYPE_STREAK = 'streak';
    const TYPE_SCORE = 'score';
    const TYPE_SKILL = 'skill';
    const TYPE_SOCIAL = 'social';
    const TYPE_EFFORT = 'effort';
    const TYPE_ACHIEVEMENT = 'achievement';
}
