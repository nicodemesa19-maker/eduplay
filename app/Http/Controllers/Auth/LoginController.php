<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required',
        ]);

        $loginInput = trim($request->input('login'));
        $password   = $request->input('password');

        // --- 1. Try email-based login (teachers & admins) ---
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $loginInput)->first();
            if ($user && Hash::check($password, $user->password)) {
                return $this->loginUser($request, $user);
            }
        }

        // --- 2. Try student name-based login ---
        // Find student accounts whose name matches (case-insensitive)
        $students = User::where('role', 'student')
            ->whereRaw('LOWER(name) = ?', [strtolower($loginInput)])
            ->get();

        foreach ($students as $student) {
            if (Hash::check($password, $student->password)) {
                return $this->loginUser($request, $student);
            }
        }

        // --- 3. Try student username-based login ---
        $students = User::where('role', 'student')
            ->whereRaw('LOWER(username) = ?', [strtolower($loginInput)])
            ->get();

        foreach ($students as $student) {
            if (Hash::check($password, $student->password)) {
                return $this->loginUser($request, $student);
            }
        }

        // --- 4. Fallback: try email for non-email input (e.g. partial match edge cases) ---
        $user = User::where('email', $loginInput)->first();
        if ($user && Hash::check($password, $user->password)) {
            return $this->loginUser($request, $user);
        }

        return back()->withErrors([
            'login' => 'These credentials do not match our records.',
        ])->onlyInput('login');
    }

    /**
     * Authenticate and redirect based on role.
     */
    private function loginUser(Request $request, User $user)
    {
        // Check account is active
        if (isset($user->is_active) && $user->is_active === false) {
            return back()->withErrors(['login' => 'Your account has been deactivated.'])->onlyInput('login');
        }

        // Teachers must be approved
        if ($user->role === 'teacher' && !$user->is_approved) {
            return back()->withErrors(['login' => 'Your teacher account is pending admin approval.'])->onlyInput('login');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        if ($user->role === 'admin')   return redirect()->intended(route('admin.dashboard'));
        if ($user->role === 'teacher') return redirect()->intended(route('teacher.dashboard'));

        // Students go to welcome page (which checks if icebreaker is done)
        return redirect()->intended(route('student.welcome'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Check user type for forgot password flow (API endpoint).
     * Returns JSON with user role to determine if they should see student help or password reset.
     */
    public function checkUserType(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
        ]);

        $loginInput = trim($request->input('login'));

        // Check if it's an email (teachers/admins)
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $loginInput)->first();
            if ($user) {
                return response()->json([
                    'found' => true,
                    'role' => $user->role,
                    'email' => $user->email,
                ]);
            }
        }

        // Check if it's a student name or username
        $student = User::where('role', 'student')
            ->where(function($query) use ($loginInput) {
                $query->whereRaw('LOWER(name) = ?', [strtolower($loginInput)])
                      ->orWhereRaw('LOWER(username) = ?', [strtolower($loginInput)]);
            })
            ->first();

        if ($student) {
            return response()->json([
                'found' => true,
                'role' => 'student',
                'email' => null,
            ]);
        }

        // Not found
        return response()->json([
            'found' => false,
        ]);
    }
}
