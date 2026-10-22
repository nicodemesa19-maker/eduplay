<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentGameProgress extends Model
{
    protected $fillable = ['student_id', 'game_id', 'current_level', 'high_score', 'level_scores'];

    protected $casts = [
        'level_scores' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Average score across all played levels.
     * Fairer comparison metric than high_score (sum), because a student
     * who has played 12 levels will not automatically outrank one who has
     * played 5 but scored better on each level.
     */
    public function getAvgScoreAttribute(): float
    {
        $scores = $this->level_scores;
        if (empty($scores)) {
            return 0.0;
        }
        return round(array_sum($scores) / count($scores), 1);
    }
}