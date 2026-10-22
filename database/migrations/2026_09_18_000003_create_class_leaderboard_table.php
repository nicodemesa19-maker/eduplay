<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('class_leaderboard', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained('classrooms')->onDelete('cascade');
            $table->foreignId('game_id')->nullable()->constrained('games')->onDelete('cascade'); // NULL for all games combined
            
            // Anonymous rankings (no names stored here)
            $table->integer('top_score')->default(0);
            $table->integer('second_score')->default(0);
            $table->integer('third_score')->default(0);
            $table->integer('class_average')->default(0);
            $table->integer('total_students')->default(0);
            
            // Metadata
            $table->timestamp('last_updated')->useCurrent();
            $table->timestamps();
            
            // Indexes
            $table->index(['classroom_id', 'game_id']);
            $table->unique(['classroom_id', 'game_id']); // One leaderboard per classroom per game
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_leaderboard');
    }
};
