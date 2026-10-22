<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class OtpPasswordController extends Controller
{
    // ── Step 1: Show email form ──────────────────────────────────────────
    public function showEmailForm()
    {
        return view('auth.passwords.otp-email');
    }

    // ── Step 1: Send OTP ─────────────────────────────────────────────────
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $email = $request->email;
        $otp   = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store hashed OTP (expires in 10 min — we check created_at)
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            ['token' => Hash::make($otp), 'created_at' => Carbon::now()]
        );

        // Send email
        Mail::send([], [], function ($m) use ($email, $otp) {
            $m->to($email)
              ->subject('Your EduPlay Password Reset OTP')
              ->html("
                <div style='font-family:Inter,sans-serif;background:#07081a;padding:40px 20px;'>
                  <div style='max-width:420px;margin:0 auto;background:rgba(255,255,255,0.05);
                       border:1px solid rgba(99,102,241,0.3);border-radius:20px;padding:36px;'>
                    <h2 style='color:#a78bfa;font-size:1.4rem;margin:0 0 8px;'>EduPlay</h2>
                    <p style='color:rgba(255,255,255,0.6);font-size:.9rem;margin:0 0 28px;'>Password Reset OTP</p>
                    <p style='color:rgba(255,255,255,0.8);font-size:.9rem;margin:0 0 20px;'>
                      Use the code below to reset your password. It expires in <strong>10 minutes</strong>.
                    </p>
                    <div style='background:rgba(99,102,241,0.15);border:2px solid rgba(99,102,241,0.5);
                         border-radius:14px;padding:20px;text-align:center;margin:0 0 24px;'>
                      <span style='font-family:monospace;font-size:2.6rem;font-weight:900;
                           color:#a78bfa;letter-spacing:10px;'>{$otp}</span>
                    </div>
                    <p style='color:rgba(255,255,255,0.35);font-size:.78rem;margin:0;'>
                      If you didn't request this, ignore this email.
                    </p>
                  </div>
                </div>
              ");
        });

        // Store email in session for next step
        session(['otp_email' => $email]);

        return redirect()->route('password.otp.verify')
            ->with('success', 'OTP sent to ' . $email);
    }

    // ── Step 2: Show OTP verify form ─────────────────────────────────────
    public function showVerifyForm()
    {
        if (!session('otp_email')) {
            return redirect()->route('password.request');
        }
        return view('auth.passwords.otp-verify');
    }

    public function showTeacherVerificationForm()
    {
        if (!session('otp_email') || session('otp_purpose') !== 'teacher_verification') {
            return redirect()->route('register.form', 'teacher');
        }

        return view('auth.passwords.otp-verify');
    }

    // ── Step 2: Verify OTP ───────────────────────────────────────────────
    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        $email  = session('otp_email');
        if (!$email) return redirect()->route('password.request');

        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'No OTP found. Please request a new one.']);
        }

        // Check expiry (10 minutes)
        if (Carbon::parse($record->created_at)->addMinutes(10)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return back()->withErrors(['otp' => 'OTP has expired. Please request a new one.']);
        }

        if (!Hash::check($request->otp, $record->token)) {
            return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
        }

        // Mark OTP as verified
        session(['otp_verified' => true]);

        return redirect()->route('password.otp.reset');
    }

    public function verifyTeacherOtp(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        $email = session('otp_email');
        if (!$email || session('otp_purpose') !== 'teacher_verification') {
            return redirect()->route('register.form', 'teacher');
        }

        $record = DB::table('password_reset_tokens')->where('email', $email)->first();
        if (!$record) {
            User::where('email', $email)->delete();
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            session()->forget(['otp_email', 'otp_purpose']);

            return redirect()->route('register.form', 'teacher')
                ->withErrors(['otp' => 'Verification failed. Registration was cancelled. Please register again.']);
        }

        if (Carbon::parse($record->created_at)->addMinutes(10)->isPast()) {
            User::where('email', $email)->delete();
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            session()->forget(['otp_email', 'otp_purpose']);

            return redirect()->route('register.form', 'teacher')
                ->withErrors(['otp' => 'OTP has expired. Registration was cancelled. Please register again.']);
        }

        if (!Hash::check($request->otp, $record->token)) {
            User::where('email', $email)->delete();
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            session()->forget(['otp_email', 'otp_purpose']);

            return redirect()->route('register.form', 'teacher')
                ->withErrors(['otp' => 'Incorrect OTP. Registration was cancelled. Please register again.']);
        }

        User::where('email', $email)->update(['email_verified_at' => now()]);
        DB::table('password_reset_tokens')->where('email', $email)->delete();
        session()->forget(['otp_email', 'otp_purpose']);

        return view('auth.teacher-approval-pending', ['email' => $email]);
    }

    // ── Step 3: Show new password form ───────────────────────────────────
    public function showResetForm()
    {
        if (!session('otp_email') || !session('otp_verified')) {
            return redirect()->route('password.request');
        }
        return view('auth.passwords.otp-reset');
    }

    // ── Step 3: Save new password ────────────────────────────────────────
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => [
                'required', 'string', 'min:8', 'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/'
            ],
        ], [
            'password.regex' => 'Password must be at least 8 characters and include uppercase, lowercase, a number, and a special character.',
        ]);

        $email = session('otp_email');
        if (!$email || !session('otp_verified')) {
            return redirect()->route('password.request');
        }

        User::where('email', $email)->update([
            'password' => Hash::make($request->password),
        ]);

        // Clean up
        DB::table('password_reset_tokens')->where('email', $email)->delete();
        session()->forget(['otp_email', 'otp_verified']);

        return redirect()->route('login')
            ->with('success', 'Password reset successfully. You can now log in.');
    }
}
