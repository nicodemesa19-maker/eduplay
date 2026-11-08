<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Grade6StudentsSeeder extends Seeder
{
    /**
     * Run the database seeder.
     * Creates 20 Grade 6 students under teacher Samantha Moore (ID: 115)
     */
    public function run(): void
    {
        $teacherId = 115; // Samantha Moore
        $gradeLevel = 6;
        
        $students = [
            ['first_name' => 'Angelo', 'middle_name' => 'Jose', 'last_name' => 'Martinez', 'username' => 'Angelo Martinez', 'student_id' => 'G6-2024-001'],
            ['first_name' => 'Beatrice', 'middle_name' => 'Faith', 'last_name' => 'Lopez', 'username' => 'Beatrice Lopez', 'student_id' => 'G6-2024-002'],
            ['first_name' => 'Christian', 'middle_name' => 'Miguel', 'last_name' => 'Ramirez', 'username' => 'Christian Ramirez', 'student_id' => 'G6-2024-003'],
            ['first_name' => 'Diana', 'middle_name' => 'Rose', 'last_name' => 'Alvarez', 'username' => 'Diana Alvarez', 'student_id' => 'G6-2024-004'],
            ['first_name' => 'Emmanuel', 'middle_name' => 'Carlos', 'last_name' => 'Perez', 'username' => 'Emmanuel Perez', 'student_id' => 'G6-2024-005'],
            ['first_name' => 'Francesca', 'middle_name' => 'Marie', 'last_name' => 'Jimenez', 'username' => 'Francesca Jimenez', 'student_id' => 'G6-2024-006'],
            ['first_name' => 'Gian', 'middle_name' => 'Paolo', 'last_name' => 'Manalo', 'username' => 'Gian Manalo', 'student_id' => 'G6-2024-007'],
            ['first_name' => 'Helena', 'middle_name' => 'Joy', 'last_name' => 'Soriano', 'username' => 'Helena Soriano', 'student_id' => 'G6-2024-008'],
            ['first_name' => 'Isaiah', 'middle_name' => 'Gabriel', 'last_name' => 'Tolentino', 'username' => 'Isaiah Tolentino', 'student_id' => 'G6-2024-009'],
            ['first_name' => 'Juliana', 'middle_name' => 'Grace', 'last_name' => 'Domingo', 'username' => 'Juliana Domingo', 'student_id' => 'G6-2024-010'],
            ['first_name' => 'Kenneth', 'middle_name' => 'Luis', 'last_name' => 'Castro', 'username' => 'Kenneth Castro', 'student_id' => 'G6-2024-011'],
            ['first_name' => 'Lorraine', 'middle_name' => 'Mae', 'last_name' => 'Medina', 'username' => 'Lorraine Medina', 'student_id' => 'G6-2024-012'],
            ['first_name' => 'Martin', 'middle_name' => 'Angelo', 'last_name' => 'Salvador', 'username' => 'Martin Salvador', 'student_id' => 'G6-2024-013'],
            ['first_name' => 'Nicole', 'middle_name' => 'Sofia', 'last_name' => 'Ortega', 'username' => 'Nicole Ortega', 'student_id' => 'G6-2024-014'],
            ['first_name' => 'Oscar', 'middle_name' => 'David', 'last_name' => 'Navarro', 'username' => 'Oscar Navarro', 'student_id' => 'G6-2024-015'],
            ['first_name' => 'Pauline', 'middle_name' => 'Isabel', 'last_name' => 'Mercado', 'username' => 'Pauline Mercado', 'student_id' => 'G6-2024-016'],
            ['first_name' => 'Raphael', 'middle_name' => 'Marco', 'last_name' => 'Vicente', 'username' => 'Raphael Vicente', 'student_id' => 'G6-2024-017'],
            ['first_name' => 'Sabrina', 'middle_name' => 'Claire', 'last_name' => 'Delgado', 'username' => 'Sabrina Delgado', 'student_id' => 'G6-2024-018'],
            ['first_name' => 'Thomas', 'middle_name' => 'Rafael', 'last_name' => 'Cordero', 'username' => 'Thomas Cordero', 'student_id' => 'G6-2024-019'],
            ['first_name' => 'Vanessa', 'middle_name' => 'Nicole', 'last_name' => 'Rosales', 'username' => 'Vanessa Rosales', 'student_id' => 'G6-2024-020'],
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
        
        $this->command->info("✅ Successfully created 20 Grade 6 students under teacher Samantha Moore");
    }
}
