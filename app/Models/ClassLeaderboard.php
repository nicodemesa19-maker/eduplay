<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassLeaderboard extends Model
{
    use HasFactory;

    protected $table = 'class_leaderboard';

    protected $fillable = [
        'classroom_id',
        'game_id',
        'top_score',
        'second_score',
        'third_score',
        'class_average',
        'total_students',
        'last_updated',
    ];

    protected $casts = [
        'top_score' => 'integer',
        'second_score' => 'integer',
        'third_score' => 'integer',
        'class_average' => 'integer',
        'total_students' => 'integer',
        'last_updated' => 'datetime',
    ];

    /**
     * Get the classroom this leaderboard belongs to.
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * Get the game this leaderboard is for (null = all games).
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Scope to get overall leaderboard (all games).
     */
    public function scopeOverall($query)
    {
        return $query->whereNull('game_id');
    }

    /**
     * Scope to get leaderboard for a specific game.
     */
    public function scopeForGame($query, $gameId)
    {
        return $query->where('game_id', $gameId);
    }
}
