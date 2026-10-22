<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class TeacherRegistrationVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_registration_rejects_invalid_email_domain(): void
    {
        Mail::fake();

        $response = $this->post(route('register'), [
            'role' => 'teacher',
            'name' => 'Test Teacher',
            'email' => 'teacher@example.invalid',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
            'faculty_id' => UploadedFile::fake()->image('faculty.jpg', 200, 200),
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('users', ['email' => 'teacher@example.invalid']);
    }

    public function test_teacher_registration_requires_email_verification_before_approval(): void
    {
        Mail::fake();

        $response = $this->post(route('register'), [
            'role' => 'teacher',
            'name' => 'Verified Teacher',
            'email' => 'teacher@example.com',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
            'faculty_id' => UploadedFile::fake()->image('faculty.jpg', 200, 200),
        ]);

        $response->assertRedirect(route('teacher.verify-email'));

        $teacher = User::where('email', 'teacher@example.com')->first();
        $this->assertNotNull($teacher);
        $this->assertFalse($teacher->is_approved);
        $this->assertNull($teacher->email_verified_at);
        $this->assertGuest();
    }

    public function test_teacher_registration_accepts_large_faculty_id_image(): void
    {
        Mail::fake();

        $response = $this->post(route('register'), [
            'role' => 'teacher',
            'name' => 'Large Image Teacher',
            'email' => 'largeimage@gmail.com',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
            'faculty_id' => UploadedFile::fake()->image('faculty.jpg', 1200, 1200)->size(3072),
        ]);

        $response->assertRedirect(route('teacher.verify-email'));
        $this->assertDatabaseHas('users', ['email' => 'largeimage@gmail.com']);
    }

    public function test_wrong_teacher_otp_does_not_record_the_registration(): void
    {
        Mail::fake();

        $this->post(route('register'), [
            'role' => 'teacher',
            'name' => 'Wrong OTP Teacher',
            'email' => 'wrongotp@example.com',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
            'faculty_id' => UploadedFile::fake()->image('faculty.jpg', 200, 200),
        ]);

        $this->assertDatabaseHas('users', ['email' => 'wrongotp@example.com']);

        $this->post(route('teacher.verify-email.post'), ['otp' => '000000']);

        $this->assertDatabaseMissing('users', ['email' => 'wrongotp@example.com']);
    }
}
