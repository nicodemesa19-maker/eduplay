<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentDailyActivity extends Model
{
    use HasFactory;

    protected $table = 'student_daily_activity';

    protected $fillable = [
        'student_id',
        'activity_date',
        'completed_icebreaker',
        'icebreaker_correct',
        'icebreaker_total',
        'current_streak',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'completed_icebreaker' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
