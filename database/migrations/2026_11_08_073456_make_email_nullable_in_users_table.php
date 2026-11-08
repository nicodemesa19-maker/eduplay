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
            // Make email nullable (students don't need email)
            $table->string('email')->nullable()->change();
            
            // Make email unique only when not null
            $table->dropUnique(['email']); // Remove old unique constraint
        });
        
        // Add conditional unique index (email unique only when not null)
        DB::statement('CREATE UNIQUE INDEX users_email_unique ON users(email) WHERE email IS NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop conditional unique index
        DB::statement('DROP INDEX IF EXISTS users_email_unique');
        
        Schema::table('users', function (Blueprint $table) {
            // Restore email as not nullable and unique
            $table->string('email')->nullable(false)->unique()->change();
        });
    }
};
