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
        Schema::create('student_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->date('date');
            
            // Daily metrics
            $table->integer('points_earned')->default(0);
            $table->integer('levels_completed')->default(0);
            $table->integer('stars_earned')->default(0);
            $table->integer('minutes_played')->default(0);
            $table->json('games_played')->nullable(); // Array of game IDs played today
            
            // Cumulative snapshots (end of day)
            $table->integer('total_score')->default(0);
            $table->integer('current_streak')->default(0);
            $table->integer('longest_streak')->default(0);
            
            // Activity patterns
            $table->integer('hour_of_day')->nullable(); // 0-23, most active hour
            $table->integer('day_of_week')->nullable(); // 1=Mon, 7=Sun
            
            $table->timestamps();
            
            // Indexes
            $table->index(['student_id', 'date']);
            $table->unique(['student_id', 'date']); // One record per student per day
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_analytics');
    }
};
