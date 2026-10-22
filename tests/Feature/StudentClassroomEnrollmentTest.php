<?php

namespace Tests\Feature;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentClassroomEnrollmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_enroll_in_a_teacher_classroom_using_class_code(): void
    {
        $teacher = User::factory()->create([
            'role' => 'teacher',
            'is_approved' => true,
        ]);

        $classroom = Classroom::create([
            'teacher_id' => $teacher->id,
            'name' => 'Math Club',
            'description' => 'Fun math challenges',
        ]);

        $student = User::factory()->create([
            'role' => 'student',
        ]);

        $this->actingAs($student);

        $response = $this->post(route('student.enroll-classroom'), [
            'class_code' => $classroom->class_code,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('classroom_student', [
            'classroom_id' => $classroom->id,
            'student_id' => $student->id,
        ]);
    }
}
