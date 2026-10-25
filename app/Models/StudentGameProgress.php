<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $student_id
 * @property int $game_id
 * @property int $current_level
 * @property int $high_score
 * @property array|null $level_scores
 * @property float $avg_score
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $student
 * @property-read \App\Models\Game $game
 */
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