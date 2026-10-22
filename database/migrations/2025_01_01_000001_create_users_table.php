<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'teacher', 'student'])->default('student');
            $table->string('grade_level')->nullable(); // e.g., '1', '2', '3', '4', '5', '6'
            $table->string('character_avatar')->nullable(); // character name or path
            $table->string('faculty_id_path')->nullable(); // for teachers
            $table->string('school_id_path')->nullable(); // for students
            $table->boolean('is_approved')->default(false); // for teachers
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};