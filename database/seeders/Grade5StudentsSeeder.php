<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Grade5StudentsSeeder extends Seeder
{
    /**
     * Run the database seeder.
     * Creates 20 Grade 5 students under teacher Cassandra Moon (ID: 94)
     */
    public function run(): void
    {
        $teacherId = 94; // Cassandra Moon
        $gradeLevel = 5;
        
        $students = [
            ['first_name' => 'Adrian', 'middle_name' => 'Luis', 'last_name' => 'Santos', 'username' => 'Adrian Santos', 'student_id' => '2024-021'],
            ['first_name' => 'Bianca', 'middle_name' => 'Marie', 'last_name' => 'Dela Rosa', 'username' => 'Bianca Dela Rosa', 'student_id' => '2024-022'],
            ['first_name' => 'Carlos', 'middle_name' => 'Manuel', 'last_name' => 'Villanueva', 'username' => 'Carlos Villanueva', 'student_id' => '2024-023'],
            ['first_name' => 'Daniela', 'middle_name' => 'Sofia', 'last_name' => 'Bautista', 'username' => 'Daniela Bautista', 'student_id' => '2024-024'],
            ['first_name' => 'Eduardo', 'middle_name' => 'Rafael', 'last_name' => 'Navarro', 'username' => 'Eduardo Navarro', 'student_id' => '2024-025'],
            ['first_name' => 'Felicia', 'middle_name' => 'Rose', 'last_name' => 'Gutierrez', 'username' => 'Felicia Gutierrez', 'student_id' => '2024-026'],
            ['first_name' => 'Gabriel', 'middle_name' => 'Dominic', 'last_name' => 'Pascual', 'username' => 'Gabriel Pascual', 'student_id' => '2024-027'],
            ['first_name' => 'Hannah', 'middle_name' => 'Grace', 'last_name' => 'Ocampo', 'username' => 'Hannah Ocampo', 'student_id' => '2024-028'],
            ['first_name' => 'Isaac', 'middle_name' => 'David', 'last_name' => 'Montero', 'username' => 'Isaac Montero', 'student_id' => '2024-029'],
            ['first_name' => 'Jasmine', 'middle_name' => 'Faith', 'last_name' => 'Santiago', 'username' => 'Jasmine Santiago', 'student_id' => '2024-030'],
            ['first_name' => 'Kevin', 'middle_name' => 'James', 'last_name' => 'Miranda', 'username' => 'Kevin Miranda', 'student_id' => '2024-031'],
            ['first_name' => 'Lara', 'middle_name' => 'Nicole', 'last_name' => 'Magno', 'username' => 'Lara Magno', 'student_id' => '2024-032'],
            ['first_name' => 'Marco', 'middle_name' => 'Andres', 'last_name' => 'Valdez', 'username' => 'Marco Valdez', 'student_id' => '2024-033'],
            ['first_name' => 'Natalie', 'middle_name' => 'Joy', 'last_name' => 'Espinosa', 'username' => 'Natalie Espinosa', 'student_id' => '2024-034'],
            ['first_name' => 'Oliver', 'middle_name' => 'Sebastian', 'last_name' => 'Herrera', 'username' => 'Oliver Herrera', 'student_id' => '2024-035'],
            ['first_name' => 'Patricia', 'middle_name' => 'Ann', 'last_name' => 'Lim', 'username' => 'Patricia Lim', 'student_id' => '2024-036'],
            ['first_name' => 'Quincy', 'middle_name' => 'Miguel', 'last_name' => 'Tan', 'username' => 'Quincy Tan', 'student_id' => '2024-037'],
            ['first_name' => 'Rachel', 'middle_name' => 'Mae', 'last_name' => 'Chavez', 'username' => 'Rachel Chavez', 'student_id' => '2024-038'],
            ['first_name' => 'Samuel', 'middle_name' => 'Joshua', 'last_name' => 'Aguilar', 'username' => 'Samuel Aguilar', 'student_id' => '2024-039'],
            ['first_name' => 'Tiffany', 'middle_name' => 'Kate', 'last_name' => 'Romero', 'username' => 'Tiffany Romero', 'student_id' => '2024-040'],
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
        
        $this->command->info("✅ Successfully created 20 Grade 5 students under teacher Cassandra Moon");
    }
}
