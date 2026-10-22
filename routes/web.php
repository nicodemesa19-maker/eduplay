<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OtpPasswordController;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Auth\TeacherController;
use App\Http\Controllers\Auth\StudentController;
use App\Models\Game;

// Network Info Page - Shows your IP and accessible URL
Route::get('/network-info', function () {
    $ipv4 = gethostbyname(gethostname());
    // Fallback to REQUEST_ADDR if gethostname fails
    if ($ipv4 === gethostname()) {
        $ipv4 = $_SERVER['SERVER_ADDR'] ?? '127.0.0.1';
    }
    $port = 8000;
    $url = "http://{$ipv4}:{$port}";
    return view('network-info', ['ipv4' => $ipv4, 'port' => $port, 'url' => $url]);
});

// Guest welcome page
Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        if ($role === 'admin') return redirect()->route('admin.dashboard');
        if ($role === 'teacher') return redirect()->route('teacher.dashboard');
        return redirect()->route('student.dashboard');
    }
    return view('welcome', ['totalGames' => Game::count()]);
});

// Auth routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register/{role}', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
// Password reset via OTP
Route::get('/password/reset', [OtpPasswordController::class, 'showEmailForm'])->name('password.request');
Route::post('/password/otp/send', [OtpPasswordController::class, 'sendOtp'])->name('password.email');
Route::get('/password/otp/verify', [OtpPasswordController::class, 'showVerifyForm'])->name('password.otp.verify');
Route::post('/password/otp/verify', [OtpPasswordController::class, 'verifyOtp'])->name('password.otp.verify.post');
Route::get('/password/otp/reset', [OtpPasswordController::class, 'showResetForm'])->name('password.otp.reset');
Route::post('/password/otp/reset', [OtpPasswordController::class, 'resetPassword'])->name('password.otp.update');
Route::get('/teacher/verify-email', [OtpPasswordController::class, 'showTeacherVerificationForm'])->name('teacher.verify-email');
Route::post('/teacher/verify-email', [OtpPasswordController::class, 'verifyTeacherOtp'])->name('teacher.verify-email.post');
Route::get('/home', fn() => redirect('/'))->name('home');

// API: Check user type for forgot password flow
Route::post('/api/check-user-type', [LoginController::class, 'checkUserType']);

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/pending-teachers', [AdminController::class, 'pendingTeachers'])->name('pending-teachers');
    Route::post('/approve-teacher/{id}', [AdminController::class, 'approveTeacher'])->name('approve-teacher');
    Route::post('/reject-teacher/{id}', [AdminController::class, 'rejectTeacher'])->name('reject-teacher');
    Route::get('/manage-users', [AdminController::class, 'manageUsers'])->name('manage-users');
    Route::delete('/delete-user/{id}', [AdminController::class, 'deleteUser'])->name('delete-user');
    Route::post('/activate-user/{id}', [AdminController::class, 'activateUser'])->name('activate-user');
    Route::post('/deactivate-user/{id}', [AdminController::class, 'deactivateUser'])->name('deactivate-user');
});

// Teacher Routes
Route::prefix('teacher')->middleware(['auth', 'teacher'])->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
    Route::get('/classrooms', [TeacherController::class, 'classrooms'])->name('classrooms');
    Route::get('/students', [TeacherController::class, 'students'])->name('students');
    Route::post('/create-classroom', [TeacherController::class, 'createClassroom'])->name('create-classroom');
    Route::post('/join-classroom', [TeacherController::class, 'joinClassroom'])->name('join-classroom');
    Route::get('/export-progress', [TeacherController::class, 'exportProgress'])->name('export-progress');
    Route::get('/jeopardy/play', [TeacherController::class, 'jeopardyPlay'])->name('jeopardy.play');
    Route::get('/jeopardy/edit', [TeacherController::class, 'jeopardyEdit'])->name('jeopardy.edit');
    // Classroom navigation & management
    Route::get('/classroom/{id}', [TeacherController::class, 'showClassroom'])->name('classroom.show');
    Route::delete('/delete-classroom/{id}', [TeacherController::class, 'deleteClassroom'])->name('delete-classroom');
    // Student management
    Route::match(['delete', 'post', 'get'], '/create-student', [TeacherController::class, 'createStudent'])->name('create-student');
    Route::put('/update-student/{id}', [TeacherController::class, 'updateStudent'])->name('update-student');
    Route::match(['delete', 'post', 'get'], '/delete-student/{id}', [TeacherController::class, 'deleteStudent'])->name('delete-student');
    Route::match(['delete', 'post', 'get'], '/reset-student-password/{id}', [TeacherController::class, 'resetStudentPassword'])->name('reset-student-password');
    Route::match(['delete', 'post', 'get'], '/move-student/{id}', [TeacherController::class, 'moveStudent'])->name('move-student');
    Route::get('/progress-api', [TeacherController::class, 'progressApi'])->name('progress-api');
});

// Student Routes
Route::prefix('student')->middleware(['auth', 'student'])->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard-new', [StudentController::class, 'dashboard'])->name('dashboard.new'); // Test route for new design
    Route::post('/enroll-classroom', [StudentController::class, 'enrollClassroom'])->name('enroll-classroom');
    Route::get('/games', [StudentController::class, 'games'])->name('games');
    Route::get('/choose-character', [StudentController::class, 'chooseCharacter'])->name('choose-character');
    Route::post('/choose-character', [StudentController::class, 'chooseCharacter']);
    Route::get('/map/{game}', [StudentController::class, 'gameMap'])->name('map');
    Route::get('/play/{game}', [StudentController::class, 'playGame'])->name('play');
    Route::post('/update-progress', [StudentController::class, 'updateProgress'])->name('update-progress');
    Route::get('/my-progress', [StudentController::class, 'myProgress'])->name('myProgress'); // Fixed route name
    Route::get('/progress', [StudentController::class, 'myProgress'])->name('my-progress'); // Alternative route
    
    // Learning Stats & Analytics Routes
    Route::get('/learning-stats', [StudentController::class, 'learningStats'])->name('learning-stats');
    Route::get('/api/learning-stats', [StudentController::class, 'getLearningStatsApi'])->name('api.learning-stats');
    Route::get('/badges', [StudentController::class, 'badgesCollection'])->name('badges');
    
    // Welcome/Icebreaker Routes
    Route::get('/welcome', [\App\Http\Controllers\WelcomeController::class, 'showWelcome'])->name('welcome');
    Route::post('/welcome/submit', [\App\Http\Controllers\WelcomeController::class, 'submitIcebreaker'])->name('welcome.submit');
    Route::get('/welcome/questions', [\App\Http\Controllers\WelcomeController::class, 'getQuestions'])->name('welcome.questions');
});
