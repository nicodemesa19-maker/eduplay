<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function dashboard()
    {
        // User Statistics
        $pendingTeachers = User::where('role', 'teacher')->where('is_approved', false)->count();
        $totalTeachers = User::where('role', 'teacher')->where('is_approved', true)->count();
        $totalStudents = User::where('role', 'student')->count();
        $activeUsers = User::where('is_active', true)->whereIn('role', ['teacher', 'student'])->count();
        $inactiveUsers = User::where('is_active', false)->whereIn('role', ['teacher', 'student'])->count();
        
        // Content Statistics
        $totalClassrooms = \App\Models\Classroom::count();
        $totalQuizzes = \App\Models\Quiz::count();
        $totalGames = \App\Models\Game::count();
        
        // Recent Registrations (Last 7 days)
        $recentTeachers = User::where('role', 'teacher')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();
        $recentStudents = User::where('role', 'student')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();
        
        // User Growth Data (Last 6 months)
        $userGrowth = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $userGrowth[] = [
                'month' => $month->format('M'),
                'teachers' => User::where('role', 'teacher')
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
                'students' => User::where('role', 'student')
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
            ];
        }
        
        // Top Teachers by Classroom Count
        $topTeachers = User::where('role', 'teacher')
            ->where('is_approved', true)
            ->withCount('taughtClassrooms')
            ->orderBy('taught_classrooms_count', 'desc')
            ->limit(5)
            ->get();
        
        // Recent Users
        $recentUsers = User::whereIn('role', ['teacher', 'student'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'pendingTeachers',
            'totalTeachers',
            'totalStudents',
            'activeUsers',
            'inactiveUsers',
            'totalClassrooms',
            'totalQuizzes',
            'totalGames',
            'recentTeachers',
            'recentStudents',
            'userGrowth',
            'topTeachers',
            'recentUsers'
        ));
    }

    public function pendingTeachers()
    {
        $teachers = User::where('role', 'teacher')->where('is_approved', false)->get();
        return view('admin.pending-teachers', compact('teachers'));
    }

    public function approveTeacher($id)
    {
        $teacher = User::findOrFail($id);

        if (!$teacher->email_verified_at) {
            return redirect()->back()->withErrors(['email' => 'This teacher must verify their email before approval.']);
        }

        $teacher->is_approved = true;
        $teacher->save();

        // Send approval email to teacher
        Mail::send([], [], function ($m) use ($teacher) {
            $m->to($teacher->email)
              ->subject('Your EduPlay Teacher Account Has Been Approved!')
              ->html("
                <div style='font-family:Inter,sans-serif;background:#07081a;padding:40px 20px;'>
                  <div style='max-width:500px;margin:0 auto;background:rgba(255,255,255,0.05);border:1px solid rgba(16,185,129,0.3);border-radius:20px;padding:36px;'>
                    <h2 style='color:#34d399;font-size:1.5rem;margin:0 0 12px;'><i style='font-size:2rem;'>✓</i></h2>
                    <h2 style='color:#34d399;font-size:1.4rem;margin:0 0 8px;'>Account Approved!</h2>
                    <p style='color:rgba(255,255,255,0.6);font-size:.9rem;margin:0 0 24px;'>Your teacher account on EduPlay has been approved</p>
                    
                    <div style='background:rgba(16,185,129,0.15);border:1px solid rgba(16,185,129,0.3);border-radius:12px;padding:20px;margin:0 0 24px;'>
                      <p style='color:rgba(255,255,255,0.9);font-size:.95rem;margin:0 0 16px;'><strong>Welcome, {$teacher->name}!</strong></p>
                      <p style='color:rgba(255,255,255,0.8);font-size:.9rem;margin:0 0 16px;'>Your teacher account is now active and ready to use. You can now:</p>
                      <ul style='color:rgba(255,255,255,0.8);font-size:.9rem;margin:0 0 0 20px;padding:0;'>
                        <li style='margin-bottom:8px;'>Create classrooms and manage students</li>
                        <li style='margin-bottom:8px;'>Design engaging quizzes and games</li>
                        <li style='margin-bottom:8px;'>Track student progress and performance</li>
                        <li>Export results and analytics</li>
                      </ul>
                    </div>
                    
                    <a href='" . route('login') . "' style='display:inline-block;background:linear-gradient(135deg,#6366f1,#7c3aed);color:#fff;text-decoration:none;padding:12px 32px;border-radius:10px;font-weight:700;font-size:.95rem;margin:0 0 24px;'>
                      Log In to EduPlay
                    </a>
                    
                    <p style='color:rgba(255,255,255,0.35);font-size:.78rem;margin:16px 0 0;border-top:1px solid rgba(255,255,255,0.1);padding-top:16px;'>
                      If you have any questions, contact our support team at eduplay004@gmail.com
                    </p>
                  </div>
                </div>
              ");
        });

        return redirect()->back()->with('success', 'Teacher approved successfully and approval email sent.');
    }

    public function rejectTeacher($id)
    {
        $teacher = User::findOrFail($id);
        $teacher->delete();

        return redirect()->back()->with('success', 'Teacher registration rejected and removed.');
    }

    public function manageUsers()
    {
        $users = User::whereIn('role', ['teacher', 'student'])->paginate(20);
        return view('admin.manage-users', compact('users'));
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->role !== 'admin') {
            $user->delete();
        }
        return redirect()->back()->with('success', 'User deleted');
    }

    public function activateUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->role !== 'admin') {
            $user->is_active = true;
            $user->save();
        }
        return redirect()->back()->with('success', ucfirst($user->role) . ' activated successfully.');
    }

    public function deactivateUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->role !== 'admin') {
            $user->is_active = false;
            $user->save();
        }
        return redirect()->back()->with('success', ucfirst($user->role) . ' deactivated successfully.');
    }
}
