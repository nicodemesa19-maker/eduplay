<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds teacher profile fields: display_name, avatar_url, bio, pronouns
     * These fields are ONLY for teachers (role='teacher')
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Teacher display name (1-40 chars, required for teachers)
            $table->string('display_name', 40)->nullable()->after('name');
            
            // Teacher avatar URL (uploaded or preset)
            $table->string('avatar_url')->nullable()->after('display_name');
            
            // Teacher bio (max 200 chars for teachers, nullable)
            // Note: Students already have bio (max 60), this extends it for teachers
            
            // Teacher pronouns (max 30 chars, nullable)
            $table->string('pronouns', 30)->nullable()->after('bio');
            
            // Approval date (for read-only display)
            $table->timestamp('approved_at')->nullable()->after('is_approved');
        });

        // Update bio column length for teachers (students keep 60, teachers get 200)
        Schema::table('users', function (Blueprint $table) {
            $table->string('bio', 200)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['display_name', 'avatar_url', 'pronouns', 'approved_at']);
        });

        // Revert bio back to 60 chars
        Schema::table('users', function (Blueprint $table) {
            $table->string('bio', 60)->nullable()->change();
        });
    }
};
