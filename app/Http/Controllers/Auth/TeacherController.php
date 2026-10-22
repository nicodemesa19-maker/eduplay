<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\StudentAnalytic;
use App\Models\User;
use App\Models\Classroom;
use App\Models\StudentGameProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    public function dashboard(): \Illuminate\View\View
    {
        $classrooms = Classroom::where('teacher_id', auth()->id())
            ->with(['students' => fn($q) => $q->where('role', 'student')])
            ->get();

        $students   = $this->getDashboardStudents($classrooms);
        $progressData = $this->getDashboardProgress($students);
        $activityData = $this->getDashboardActivity($students);
        $games      = Game::orderBy('id')->get(['id', 'name', 'display_name', 'total_levels']);

        return view('teacher.dashboard', compact('classrooms', 'students', 'progressData', 'activityData', 'games'));
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Collection<int, Classroom> $classrooms
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    private function getDashboardStudents(\Illuminate\Database\Eloquent\Collection $classrooms): \Illuminate\Support\Collection
    {
        return User::where('role', 'student')
            ->where(function($q) use ($classrooms) {
                $q->where('created_by_teacher_id', auth()->id())
                  ->orWhereHas('classrooms', fn($cq) => $cq->whereIn('classroom_id', $classrooms->pluck('id')));
            })
            ->with('classrooms')
            ->get()
            ->map(fn(User $student) => [
                'id'          => $student->id,
                'name'        => $student->name,
                'grade_level' => $student->grade_level,
                'classrooms'  => $student->classrooms->map(fn(Classroom $c) => [
                    'id'   => $c->id,
                    'name' => $c->name,
                ])->values()->toArray(),
            ]);
    }

    /**
     * @param  \Illuminate\Support\Collection<int, array<string, mixed>> $students
     * @return array<int, array<int, array<string, mixed>>>
     */
    private function getDashboardProgress(\Illuminate\Support\Collection $students): array
    {
        $progressData = [];
        foreach ($students as $student) {
            $progressData[$student['id']] = StudentGameProgress::where('student_id', $student['id'])
                ->with('game')
                ->get()
                ->map(fn(StudentGameProgress $progress) => [
                    'id'            => $progress->id,
                    'student_id'    => $progress->student_id,
                    'game_id'       => $progress->game_id,
                    'current_level' => $progress->current_level,
                    'high_score'    => $progress->high_score,
                    'avg_score'     => $progress->avg_score,
                    'level_scores'  => $progress->level_scores,
                    'game'          => [
                        'id'           => $progress->game->id,
                        'name'         => $progress->game->name,
                        'display_name' => $progress->game->display_name,
                        'total_levels' => $progress->game->total_levels,
                    ],
                    'last_played'   => $progress->updated_at?->toDateTimeString(),
                    'updated_at'    => $progress->updated_at?->toDateTimeString(),
                ])->toArray();
        }
        return $progressData;
    }

    /**
     * Returns 6 weeks of daily activity keyed by student ID.
     *
     * @param  \Illuminate\Support\Collection<int, array<string, mixed>> $students
     * @return \Illuminate\Support\Collection<int, \Illuminate\Support\Collection<int, array<string, mixed>>>
     */
    private function getDashboardActivity(\Illuminate\Support\Collection $students): \Illuminate\Support\Collection
    {
        /** @var \Illuminate\Support\Collection<int, \Illuminate\Support\Collection<int, StudentAnalytic>> $grouped */
        $grouped = StudentAnalytic::query()
            ->whereIn('student_id', $students->pluck('id'))
            ->where('date', '>=', now()->subDays(42)->toDateString())
            ->get(['student_id', 'date', 'levels_completed', 'minutes_played', 'games_played'])
            ->groupBy('student_id');

        return $grouped->map(fn(\Illuminate\Support\Collection $rows) => $rows->map(fn(StudentAnalytic $r) => [
            'date'             => \Carbon\Carbon::parse($r->date)->toDateString(),
            'levels_completed' => (int) $r->levels_completed,
            'minutes_played'   => (int) $r->minutes_played,
            // games_played is a JSON array of game IDs; derive a numeric count from it
            'games_played'     => is_array($r->games_played) ? count($r->games_played) : 0,
        ])->values());
    }

    /**
     * List all classrooms owned by the teacher.
     */
    public function classrooms()
    {
        $classrooms = Classroom::where('teacher_id', auth()->id())
            ->with(['students' => function($q) {
                $q->where('role', 'student');
            }])
            ->get();
        return view('teacher.classrooms', compact('classrooms'));
    }

    /**
     * List all students managed by the teacher.
     */
    public function students()
    {
        $classrooms = Classroom::where('teacher_id', auth()->id())->get();
        
        $students = User::where('role', 'student')
            ->where(function($q) use ($classrooms) {
                $q->where('created_by_teacher_id', auth()->id())
                  ->orWhereHas('classrooms', function($cq) use ($classrooms) {
                      $cq->whereIn('classroom_id', $classrooms->pluck('id'));
                  });
            })
            ->with('classrooms')
            ->orderBy('name')
            ->get();

        return view('teacher.students', compact('students', 'classrooms'));
    }

    /**
     * Create a new classroom (auto-generates unique class code).
     */
    public function createClassroom(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        
        Classroom::create([
            'teacher_id' => auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Classroom created! Code generated automatically.');
    }

    /**
     * Show a specific classroom.
     */
    public function showClassroom($id)
    {
        $classroom = Classroom::where('id', $id)
            ->where('teacher_id', auth()->id())
            ->with(['students' => function($q) {
                $q->where('role', 'student');
            }])
            ->firstOrFail();

        return view('teacher.view-classroom', compact('classroom'));
    }

    /**
     * Join a classroom as a student (teachers can also be students? But here it's for testing).
     */
    public function joinClassroom(Request $request)
    {
        $request->validate(['class_code' => 'required|exists:classrooms,class_code']);
        
        $classroom = Classroom::where('class_code', $request->class_code)->first();
        
        // Check if student already in classroom
        if (!auth()->user()->classrooms()->where('classroom_id', $classroom->id)->exists()) {
            auth()->user()->classrooms()->attach($classroom->id);
        }

        return redirect()->back()->with('success', 'Joined classroom!');
    }

    /**
     * Export student progress data as CSV.
     */
    public function exportProgress(Request $request)
    {
        $students = User::whereHas('classrooms', function($q) {
            $q->whereIn('classroom_id', Classroom::where('teacher_id', auth()->id())->pluck('id'));
        })->with('gameProgress')->get();

        $fileName = 'student_progress_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
        ];

        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Student Name', 'Email', 'Game', 'Current Level', 'Avg Score']);
            
            foreach ($students as $student) {
                foreach ($student->gameProgress as $progress) {
                    fputcsv($file, [
                        $student->name,
                        $student->email,
                        $progress->game->name ?? 'Unknown',
                        $progress->current_level,
                        $progress->avg_score,
                    ]);
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show the standalone Jeopardy Play Page (Game Proper).
     */
    public function jeopardyPlay()
    {
        return view('teacher.jeopardy-play');
    }

    /**
     * Show the Jeopardy Edit Page.
     */
    public function jeopardyEdit()
    {
        return view('teacher.jeopardy-edit');
    }

    // =====================================================================
    // STUDENT MANAGEMENT — Teacher creates/manages student accounts
    // =====================================================================

    /**
     * Create a new student account and enrol them in a classroom.
     * Username = student full name | Password = student ID number.
     */
    public function createStudent(Request $request)
    {
        $request->validate([
            'first_name'        => 'required|string|max:255',
            'middle_name'       => 'nullable|string|max:255',
            'last_name'         => 'required|string|max:255',
            'username'          => 'required|string|max:255|unique:users,username',
            'student_id_number' => 'required|string|max:100',
            'grade_level'       => 'required|in:1,2,3,4,5,6',
            'classroom_id'      => 'required|exists:classrooms,id',
        ]);

        // Construct full name from individual name fields
        $firstName = trim($request->first_name);
        $middleName = $request->middle_name ? trim($request->middle_name) : null;
        $lastName = trim($request->last_name);
        $username = trim($request->username);
        
        // Build full name: "FirstName MiddleName LastName" or "FirstName LastName"
        $fullName = $firstName . ($middleName ? ' ' . $middleName : '') . ' ' . $lastName;

        // Ensure the classroom belongs to this teacher
        $classroom = Classroom::where('id', $request->classroom_id)
            ->where('teacher_id', auth()->id())
            ->firstOrFail();

        // Create student — username is custom, password is the ID number
        $dummyEmail = 'std_' . Str::slug($username, '_') . '_' . substr(md5($request->student_id_number . time()), 0, 6) . '@student.local';

        $student = User::create([
            'name'                  => $fullName,
            'username'              => $username,
            'first_name'            => $firstName,
            'middle_name'           => $middleName,
            'last_name'             => $lastName,
            'email'                 => $dummyEmail,
            'password'              => Hash::make(trim($request->student_id_number)),
            'role'                  => 'student',
            'grade_level'           => $request->grade_level,
            'student_id_number'     => trim($request->student_id_number),
            'created_by_teacher_id' => auth()->id(),
            'is_approved'           => true,
            'is_active'             => true,
        ]);

        // Enrol student into the selected classroom
        $student->classrooms()->attach($classroom->id);

        return back()->with('success', "Student \"" . $fullName . "\" created. They can log in with username: " . $username);
    }

    /**
     * Update an existing student account.
     */
    public function updateStudent(Request $request, $id)
    {
        $request->validate([
            'first_name'        => 'required|string|max:255',
            'middle_name'       => 'nullable|string|max:255',
            'last_name'         => 'required|string|max:255',
            'username'          => 'required|string|max:255|unique:users,username,' . $id,
            'student_id_number' => 'required|string|max:100',
            'grade_level'       => 'required|in:1,2,3,4,5,6',
        ]);

        // Find the student and ensure they belong to this teacher
        $student = $this->findTeacherStudent($id);

        // Construct full name from individual name fields
        $firstName = trim($request->first_name);
        $middleName = $request->middle_name ? trim($request->middle_name) : null;
        $lastName = trim($request->last_name);
        $username = trim($request->username);
        
        // Build full name: "FirstName MiddleName LastName" or "FirstName LastName"
        $fullName = $firstName . ($middleName ? ' ' . $middleName : '') . ' ' . $lastName;

        // Update student information
        $student->update([
            'name'              => $fullName,
            'username'          => $username,
            'first_name'        => $firstName,
            'middle_name'       => $middleName,
            'last_name'         => $lastName,
            'student_id_number' => trim($request->student_id_number),
            'grade_level'       => $request->grade_level,
            // Update password if ID number changed
            'password'          => Hash::make(trim($request->student_id_number)),
        ]);

        return back()->with('success', "Student \"" . $fullName . "\" updated successfully. Username: " . $username . " | Password is their ID number.");
    }

    /**
     * Helper to find a student belonging to this teacher (either created by them or enrolled in their classrooms).
     */
    private function findTeacherStudent($id)
    {
        $teacherClassroomIds = Classroom::where('teacher_id', auth()->id())->pluck('id');

        return User::where('id', $id)
            ->where('role', 'student')
            ->where(function($q) use ($teacherClassroomIds) {
                $q->where('created_by_teacher_id', auth()->id())
                  ->orWhereHas('classrooms', function($cq) use ($teacherClassroomIds) {
                      $cq->whereIn('classroom_id', $teacherClassroomIds);
                  });
            })
            ->firstOrFail();
    }

    /**
     * Delete a student account.
     */
    public function deleteStudent($id)
    {
        $student = $this->findTeacherStudent($id);

        $name = $student->name;

        // Detach from all classrooms first
        $student->classrooms()->detach();

        // Remove game progress records
        StudentGameProgress::where('student_id', $student->id)->delete();

        // Delete user account
        $student->delete();

        return back()->with('success', "Student account \"{$name}\" deleted successfully.");
    }

    /**
     * Reset a student's password back to their ID number.
     */
    public function resetStudentPassword($id)
    {
        $student = $this->findTeacherStudent($id);

        $idNumber = $student->student_id_number ?: '123456';
        $student->password = Hash::make($idNumber);
        $student->save();

        return back()->with('success', "Password for \"{$student->name}\" has been reset to: {$idNumber}");
    }

    /**
     * Move a student to a different classroom.
     */
    public function moveStudent(Request $request, $id)
    {
        $request->validate(['classroom_id' => 'required|exists:classrooms,id']);

        $student = $this->findTeacherStudent($id);

        // Ensure target classroom belongs to this teacher
        $newClassroom = Classroom::where('id', $request->classroom_id)
            ->where('teacher_id', auth()->id())
            ->firstOrFail();

        // Detach from all this teacher's classrooms then attach to new one
        $teacherClassroomIds = Classroom::where('teacher_id', auth()->id())->pluck('id');
        $student->classrooms()->detach($teacherClassroomIds);
        $student->classrooms()->attach($newClassroom->id);

        return back()->with('success', "{$student->name} moved to {$newClassroom->name}.");
    }

    /**
     * API endpoint: return student progress as JSON for real-time dashboard polling.
     */
    public function progressApi()
    {
        $classrooms = Classroom::where('teacher_id', auth()->id())->pluck('id');

        $students = User::where('role', 'student')
            ->where(function($q) use ($classrooms) {
                $q->where('created_by_teacher_id', auth()->id())
                  ->orWhereHas('classrooms', function($cq) use ($classrooms) {
                      $cq->whereIn('classroom_id', $classrooms);
                  });
            })
            ->with(['gameProgress.game', 'classrooms'])
            ->get();

        $rows = [];
        foreach ($students as $student) {
            if ($student->gameProgress->isEmpty()) {
                $rows[] = [
                    'student_name'  => $student->name,
                    'student_init'  => strtoupper(substr($student->name, 0, 1)),
                    'classroom'     => $student->classrooms->pluck('name')->first() ?? '—',
                    'game'          => null,
                    'level'         => 0,
                    'total_levels'  => 0,
                    'high_score'    => 0,
                    'avg_score'     => 0,
                    'level_scores'  => [],
                    'last_played'   => null,
                ];
            } else {
                foreach ($student->gameProgress as $prog) {
                    $rows[] = [
                        'student_name'  => $student->name,
                        'student_init'  => strtoupper(substr($student->name, 0, 1)),
                        'classroom'     => $student->classrooms->pluck('name')->first() ?? '—',
                        'game'          => $prog->game->display_name ?? $prog->game->name,
                        'game_slug'     => $prog->game->name,
                        'level'         => $prog->current_level,
                        'total_levels'  => $prog->game->total_levels ?? 15,
                        'high_score'    => $prog->high_score,
                        'avg_score'     => $prog->avg_score,
                        'level_scores'  => $prog->level_scores ?? [],
                        'last_played'   => $prog->updated_at?->diffForHumans(),
                    ];
                }
            }
        }

        return response()->json([
            'rows'       => $rows,
            'updated_at' => now()->format('M d, Y h:i:s A'),
        ]);
    }

}