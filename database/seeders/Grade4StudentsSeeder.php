<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Grade4StudentsSeeder extends Seeder
{
    /**
     * Run the database seeder.
     * Creates 20 Grade 4 students under teacher Ethan Sterling (ID: 53)
     */
    public function run(): void
    {
        $teacherId = 53; // Ethan Sterling
        $gradeLevel = 4;
        
        $students = [
            ['first_name' => 'Miguel', 'middle_name' => 'Santos', 'last_name' => 'Reyes', 'username' => 'Miguel Reyes', 'student_id' => '2024-001'],
            ['first_name' => 'Sofia', 'middle_name' => 'Maria', 'last_name' => 'Cruz', 'username' => 'Sofia Cruz', 'student_id' => '2024-002'],
            ['first_name' => 'Gabriel', 'middle_name' => 'Jose', 'last_name' => 'Torres', 'username' => 'Gabriel Torres', 'student_id' => '2024-003'],
            ['first_name' => 'Isabella', 'middle_name' => 'Grace', 'last_name' => 'Flores', 'username' => 'Isabella Flores', 'student_id' => '2024-004'],
            ['first_name' => 'Lucas', 'middle_name' => 'Antonio', 'last_name' => 'Mendoza', 'username' => 'Lucas Mendoza', 'student_id' => '2024-005'],
            ['first_name' => 'Mia', 'middle_name' => 'Angela', 'last_name' => 'Ramos', 'username' => 'Mia Ramos', 'student_id' => '2024-006'],
            ['first_name' => 'Elijah', 'middle_name' => 'Miguel', 'last_name' => 'Santos', 'username' => 'Elijah Santos', 'student_id' => '2024-007'],
            ['first_name' => 'Camila', 'middle_name' => 'Rosa', 'last_name' => 'Garcia', 'username' => 'Camila Garcia', 'student_id' => '2024-008'],
            ['first_name' => 'Mateo', 'middle_name' => 'Luis', 'last_name' => 'Dela Cruz', 'username' => 'Mateo Dela Cruz', 'student_id' => '2024-009'],
            ['first_name' => 'Emma', 'middle_name' => 'Victoria', 'last_name' => 'Gonzales', 'username' => 'Emma Gonzales', 'student_id' => '2024-010'],
            ['first_name' => 'Noah', 'middle_name' => 'Daniel', 'last_name' => 'Aquino', 'username' => 'Noah Aquino', 'student_id' => '2024-011'],
            ['first_name' => 'Olivia', 'middle_name' => 'Mae', 'last_name' => 'Villanueva', 'username' => 'Olivia Villanueva', 'student_id' => '2024-012'],
            ['first_name' => 'Liam', 'middle_name' => 'Carlos', 'last_name' => 'Fernandez', 'username' => 'Liam Fernandez', 'student_id' => '2024-013'],
            ['first_name' => 'Ava', 'middle_name' => 'Sophia', 'last_name' => 'Castillo', 'username' => 'Ava Castillo', 'student_id' => '2024-014'],
            ['first_name' => 'Ethan', 'middle_name' => 'Rafael', 'last_name' => 'Morales', 'username' => 'Ethan Morales', 'student_id' => '2024-015'],
            ['first_name' => 'Charlotte', 'middle_name' => 'Isabel', 'last_name' => 'Rivera', 'username' => 'Charlotte Rivera', 'student_id' => '2024-016'],
            ['first_name' => 'James', 'middle_name' => 'Alexander', 'last_name' => 'Velasco', 'username' => 'James Velasco', 'student_id' => '2024-017'],
            ['first_name' => 'Amelia', 'middle_name' => 'Claire', 'last_name' => 'Diaz', 'username' => 'Amelia Diaz', 'student_id' => '2024-018'],
            ['first_name' => 'Benjamin', 'middle_name' => 'Marco', 'last_name' => 'Ponce', 'username' => 'Benjamin Ponce', 'student_id' => '2024-019'],
            ['first_name' => 'Harper', 'middle_name' => 'Nicole', 'last_name' => 'Salazar', 'username' => 'Harper Salazar', 'student_id' => '2024-020'],
        ];

        foreach ($students as $studentData) {
            $firstName = $studentData['first_name'];
            $middleName = $studentData['middle_name'];
            $lastName = $studentData['last_name'];
            $username = $studentData['username'];
            $studentId = $studentData['student_id'];
            
            // Build full name
            $fullName = $firstName . ' ' . $middleName . ' ' . $lastName;
            
            // Check if username already exists
            if (User::where('username', $username)->exists()) {
                $this->command->warn("Username {$username} already exists, skipping...");
                continue;
            }
            
            // Create student account
            User::create([
                'name' => $fullName,
                'username' => $username,
                'first_name' => $firstName,
                'middle_name' => $middleName,
                'last_name' => $lastName,
                'email' => null, // Students don't need email
                'password' => Hash::make($studentId), // Password is their student ID
                'role' => 'student',
                'grade_level' => $gradeLevel,
                'student_id_number' => $studentId,
                'created_by_teacher_id' => $teacherId,
                'is_approved' => true,
                'is_active' => true,
            ]);
            
            $this->command->info("✓ Created student: {$fullName} (username: {$username})");
        }
        
        $this->command->info("✅ Successfully created 20 Grade 4 students under teacher Ethan Sterling");
    }
}
