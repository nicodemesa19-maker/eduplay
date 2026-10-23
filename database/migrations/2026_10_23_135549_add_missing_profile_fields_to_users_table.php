<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add missing avatar_character column
            // This is separate from character_avatar (the original game character selection)
            // avatar_character is for the profile avatar preset selection
            $table->string('avatar_character')->nullable()->after('character_avatar');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar_character');
        });
    }
};
