<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Student ID number (used as password, stored separately for display)
            $table->string('student_id_number')->nullable()->after('grade_level');

            // Track which teacher created this student account
            $table->unsignedBigInteger('created_by_teacher_id')->nullable()->after('student_id_number');
            $table->foreign('created_by_teacher_id')->references('id')->on('users')->onDelete('set null');

            // Make email nullable — teacher-created students won't have emails
            $table->string('email')->nullable()->change();
        });

        // Remove unique constraint on email then re-add with nullable support
        // (Laravel/MySQL already handles nullable + unique correctly — NULLs are not considered duplicates)
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['created_by_teacher_id']);
            $table->dropColumn(['student_id_number', 'created_by_teacher_id']);
            $table->string('email')->nullable(false)->change();
        });
    }
};
