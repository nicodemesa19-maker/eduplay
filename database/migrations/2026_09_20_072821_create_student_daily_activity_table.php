<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_daily_activity', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->date('activity_date');
            $table->boolean('completed_icebreaker')->default(false);
            $table->integer('icebreaker_correct')->default(0);
            $table->integer('icebreaker_total')->default(0);
            $table->integer('current_streak')->default(0);
            $table->timestamps();
            
            $table->unique(['student_id', 'activity_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_daily_activity');
    }
};
