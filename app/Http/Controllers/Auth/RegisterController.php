<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /**
     * Show the registration form for a specific role.
     * Student self-registration is disabled — only teacher-created accounts.
     */
    public function showRegistrationForm($role = 'student')
    {
        if (!in_array($role, ['student', 'teacher'])) {
            abort(404);
        }

        // Students cannot self-register — accounts are created by their teacher
        if ($role === 'student') {
            return redirect()->route('login')
                ->with('info', 'Student accounts are created by your teacher. Please ask your teacher for your login credentials.');
        }

        return view('auth.register', compact('role'));
    }

    /**
     * Handle a registration request for the application.
     */
    public function register(Request $request)
    {
        $role = $request->role;

        // Disallow student self-registration
        if ($role === 'student') {
            return redirect()->route('login')
                ->with('info', 'Student accounts are created by your teacher.');
        }
        
        $emailRule = function ($attribute, $value, $fail) use ($role) {
            if ($role !== 'teacher') {
                return;
            }

            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $fail('Please enter a valid email address.');
                return;
            }

            $domain = strtolower(substr($value, strpos($value, '@') + 1));
            if ($domain === '' || (!checkdnsrr($domain, 'MX') && !checkdnsrr($domain, 'A') && !checkdnsrr($domain, 'AAAA'))) {
                $fail('Please use a real email address that can receive messages.');
            }
        };

        // Validation rules
        $rules = [
            'first_name'  => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name'   => 'required|string|max:100',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', $emailRule],
            'password' => [
                'required', 'string', 'min:8', 'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/'
            ],
            'grade_level' => 'required_if:role,student|in:1,2,3,4,5,6',
        ];

        if ($role === 'teacher') {
            $rules['faculty_id'] = 'required|image|mimes:jpeg,png,jpg|max:5120';
        } else {
            $rules['school_id'] = 'required|image|mimes:jpeg,png,jpg|max:5120';
        }

        $request->validate($rules, [
            'password.regex' => 'Password must be at least 8 characters and include uppercase, lowercase, a number, and a special character.',
        ]);

        // Compose full name from the three name fields
        $firstName  = trim($request->first_name);
        $middleName = $request->middle_name ? trim($request->middle_name) : null;
        $lastName   = trim($request->last_name);
        $fullName   = $firstName . ($middleName ? ' ' . $middleName : '') . ' ' . $lastName;

        // Prepare user data
        $userData = [
            'name'        => $fullName,
            'first_name'  => $firstName,
            'middle_name' => $middleName,
            'last_name'   => $lastName,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => $role,
            'grade_level' => $role === 'student' ? $request->grade_level : null,
        ];

        // Handle file upload
        if ($role === 'teacher') {
            // Upload faculty ID to Supabase Storage (REQUIRED - no local fallback)
            $facultyIdFile = $request->file('faculty_id');
            $fileName = 'faculty_' . time() . '_' . uniqid() . '.' . $facultyIdFile->getClientOriginalExtension();
            
            $supabaseService = app(\App\Services\SupabaseStorageService::class);
            
            if (!$supabaseService->isConfigured()) {
                return back()->withErrors([
                    'faculty_id' => 'Storage system not configured. Please contact administrator.'
                ])->withInput();
            }
            
            $result = $supabaseService->uploadToPath($facultyIdFile, 'faculty_ids/' . $fileName);
            
            if (!$result['success']) {
                \Log::error('Faculty ID upload to Supabase failed', [
                    'error' => $result['error'] ?? 'Unknown error',
                    'fileName' => $fileName,
                ]);
                
                return back()->withErrors([
                    'faculty_id' => 'Failed to upload faculty ID: ' . ($result['error'] ?? 'Unknown error')
                ])->withInput();
            }
            
            $userData['faculty_id_path'] = $result['url'];
            $userData['is_approved'] = false; // Teachers need admin approval
        } else {
            $path = $request->file('school_id')->store('school_ids', 'public');
            $userData['school_id_path'] = $path;
            $userData['is_approved'] = true; // Students auto-approved
        }

        // Create the user
        $user = User::create($userData);

        if ($role === 'teacher') {
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                ['token' => Hash::make($otp), 'created_at' => Carbon::now()]
            );

            Mail::send([], [], function ($m) use ($user, $otp) {
                $m->to($user->email)
                  ->subject('Verify your EduPlay teacher email')
                  ->html("<div style='font-family:Inter,sans-serif;background:#07081a;padding:40px 20px;'><div style='max-width:420px;margin:0 auto;background:rgba(255,255,255,0.05);border:1px solid rgba(99,102,241,0.3);border-radius:20px;padding:36px;'><h2 style='color:#a78bfa;font-size:1.4rem;margin:0 0 8px;'>EduPlay</h2><p style='color:rgba(255,255,255,0.6);font-size:.9rem;margin:0 0 28px;'>Teacher email verification</p><p style='color:rgba(255,255,255,0.8);font-size:.9rem;margin:0 0 20px;'>Use this code to verify your teacher email before admin approval. It expires in <strong>10 minutes</strong>.</p><div style='background:rgba(99,102,241,0.15);border:2px solid rgba(99,102,241,0.5);border-radius:14px;padding:20px;text-align:center;margin:0 0 24px;'><span style='font-family:monospace;font-size:2.6rem;font-weight:900;color:#a78bfa;letter-spacing:10px;'>{$otp}</span></div><p style='color:rgba(255,255,255,0.35);font-size:.78rem;margin:0;'>If you didn't register as a teacher, ignore this email.</p></div></div>");
            });

            session(['otp_email' => $user->email, 'otp_purpose' => 'teacher_verification']);
            Auth::logout();

            return redirect()->route('teacher.verify-email')
                ->with('success', 'A verification code has been sent to your email. Verify it before admin approval.');
        }

        // Log the user in
        Auth::login($user);

        // Redirect based on role
        
        return redirect()->route('student.dashboard')
            ->with('success', 'Welcome to EduPlay! Choose your character to get started.');
    }
}