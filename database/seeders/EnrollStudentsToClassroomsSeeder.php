<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Classroom;
use Illuminate\Database\Seeder;

class EnrollStudentsToClassroomsSeeder extends Seeder
{
    /**
     * Enroll existing students into classrooms for each teacher.
     */
    public function run(): void
    {
        $this->command->info('Starting to enroll students into classrooms...');

        // Teacher and classroom mapping based on the existing seeders
        $teacherMapping = [
            [
                'teacher_id' => 94,  // Cassandra Moon
                'teacher_email' => 'iconnick659@gmail.com',
                'teacher_name' => 'Cassandra Moon',
                'grade_level' => 5,
                'classroom_name' => 'Cassandra Moon\'s Class',
                'classroom_description' => 'Grade 5 Mathematics Class'
            ],
            [
                'teacher_id' => 53,  // Ethan Sterling
                'teacher_email' => 'demesanick72@gmail.com',
                'teacher_name' => 'Ethan Sterling',
                'grade_level' => 4,
                'classroom_name' => 'Ethan Sterling\'s Class',
                'classroom_description' => 'Grade 4 Mathematics Class'
            ],
            [
                'teacher_id' => 115, // Samantha Moore
                'teacher_email' => 'demesanico91@gmail.com',
                'teacher_name' => 'Samantha Moore',
                'grade_level' => 6,
                'classroom_name' => 'Samantha Moore\'s Class',
                'classroom_description' => 'Grade 6 Mathematics Class'
            ],
        ];

        foreach ($teacherMapping as $mapping) {
            // Verify teacher exists
            $teacher = User::where('id', $mapping['teacher_id'])
                ->where('role', 'teacher')
                ->first();

            if (!$teacher) {
                $this->command->error("Teacher not found: {$mapping['teacher_name']} (ID: {$mapping['teacher_id']})");
                continue;
            }

            $this->command->info("Processing teacher: {$teacher->name} (Grade {$mapping['grade_level']})");

            // Create or find classroom for this teacher
            $classroom = Classroom::firstOrCreate(
                [
                    'teacher_id' => $teacher->id,
                    'name' => $mapping['classroom_name']
                ],
                [
                    'description' => $mapping['classroom_description']
                ]
            );

            $this->command->info("  Classroom: {$classroom->name} (Code: {$classroom->class_code})");

            // Find students created by this teacher (using created_by_teacher_id)
            $students = User::where('role', 'student')
                ->where('grade_level', $mapping['grade_level'])
                ->where('created_by_teacher_id', $teacher->id)
                ->get();

            if ($students->count() === 0) {
                $this->command->warn("  No students found for Grade {$mapping['grade_level']} under this teacher");
                continue;
            }

            // Enroll students in the classroom
            $studentIds = $students->pluck('id')->toArray();
            $classroom->students()->syncWithoutDetaching($studentIds);

            $this->command->info("  Enrolled {$students->count()} students");

            // Show first 5 students
            foreach ($students->take(5) as $student) {
                $this->command->info("    - {$student->name} ({$student->student_id_number})");
            }

            $this->command->newLine();
        }

        $this->command->info('✓ Finished enrolling students into classrooms!');
    }
}
