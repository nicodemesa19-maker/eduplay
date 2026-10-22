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
        Schema::create('student_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            
            // Badge details
            $table->string('badge_type', 50); // 'streak', 'score', 'skill', 'social', 'effort'
            $table->string('badge_name', 100); // 'Fire Streak 7', '1000 Points Master', etc.
            $table->string('badge_icon', 10)->default('🏆'); // Emoji icon
            $table->text('description')->nullable(); // What they did to earn it
            
            // Metadata
            $table->integer('badge_level')->default(1); // For tiered badges (Bronze=1, Silver=2, Gold=3)
            $table->integer('value')->nullable(); // The actual value (7 days, 1000 points, etc.)
            
            $table->timestamp('earned_at')->useCurrent();
            $table->timestamps();
            
            // Indexes
            $table->index(['student_id', 'badge_type']);
            $table->index(['student_id', 'earned_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_badges');
    }
};
