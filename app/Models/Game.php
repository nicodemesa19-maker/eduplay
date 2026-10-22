<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = ['name', 'display_name', 'description', 'total_levels'];

    public function studentProgress()
    {
        return $this->hasMany(StudentGameProgress::class);
    }
}
