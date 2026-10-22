<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'date',
        'points_earned',
        'levels_completed',
        'stars_earned',
        'minutes_played',
        'games_played',
        'total_score',
        'current_streak',
        'longest_streak',
        'hour_of_day',
        'day_of_week',
    ];

    protected $casts = [
        'date' => 'date',
        'games_played' => 'array',
        'points_earned' => 'integer',
        'levels_completed' => 'integer',
        'stars_earned' => 'integer',
        'minutes_played' => 'integer',
        'total_score' => 'integer',
        'current_streak' => 'integer',
        'longest_streak' => 'integer',
        'hour_of_day' => 'integer',
        'day_of_week' => 'integer',
    ];

    /**
     * Get the student that owns this analytic record.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Scope to get analytics for a specific date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope to get analytics for last N days.
     */
    public function scopeLastDays($query, $days = 7)
    {
        return $query->where('date', '>=', now()->subDays($days)->toDateString());
    }
}
