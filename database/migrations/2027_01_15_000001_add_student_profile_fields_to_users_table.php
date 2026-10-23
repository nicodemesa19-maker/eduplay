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
        Schema::table('users', function (Blueprint $table) {
            // Student-editable fields
            $table->string('nickname', 20)->nullable()->after('character_avatar');
            $table->string('avatar_upload_path')->nullable()->after('nickname'); // For custom avatar uploads
            $table->date('birthdate')->nullable()->after('avatar_upload_path');
            $table->string('favorite_math_topic', 50)->nullable()->after('birthdate');
            $table->string('bio', 60)->nullable()->after('favorite_math_topic');
            $table->json('hobbies')->nullable()->after('bio');
            $table->string('game_mode_preference', 50)->nullable()->after('hobbies');
            $table->integer('sound_effects_volume')->default(80)->after('game_mode_preference');
            $table->integer('music_volume')->default(60)->after('sound_effects_volume');
            $table->boolean('practice_reminders')->default(true)->after('music_volume');
            $table->boolean('streak_alerts')->default(true)->after('practice_reminders');
            $table->string('display_badge', 100)->nullable()->after('streak_alerts');
            
            // Username management
            $table->string('original_username')->nullable()->after('username');
            $table->timestamp('last_username_change')->nullable()->after('original_username');
            $table->integer('username_changes_count')->default(0)->after('last_username_change');
            
            // Audit/metadata
            $table->timestamp('profile_updated_at')->nullable()->after('username_changes_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nickname', 'avatar_upload_path', 'birthdate', 'favorite_math_topic', 'bio', 'hobbies',
                'game_mode_preference', 'sound_effects_volume', 'music_volume',
                'practice_reminders', 'streak_alerts', 'display_badge',
                'original_username', 'last_username_change', 'username_changes_count',
                'profile_updated_at'
            ]);
        });
    }
};
