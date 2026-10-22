<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Classroom extends Model
{
    protected $fillable = ['teacher_id', 'name', 'class_code', 'description'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($classroom) {
            $classroom->class_code = Str::upper(Str::random(6));
        });
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'classroom_student', 'classroom_id', 'student_id');
    }
}