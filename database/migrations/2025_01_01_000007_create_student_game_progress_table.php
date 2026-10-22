<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_game_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->integer('current_level')->default(1);
            $table->integer('high_score')->default(0);
            $table->json('level_scores')->nullable(); // store scores per level
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_game_progress');
    }
};