<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = ['teacher_id', 'title', 'type', 'settings'];

    protected $casts = ['settings' => 'array'];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }
}
