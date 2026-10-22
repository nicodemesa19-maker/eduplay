<?php

namespace App\Http\Controllers;

use App\Models\StudentDailyActivity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    /**
     * Show the welcome/icebreaker page
     */
    public function showWelcome()
    {
        $user = Auth::user();
        
        if ($user->role !== 'student') {
            return redirect()->route($user->role . '.dashboard');
        }

        $today = Carbon::today();
        
        // Check if student already completed icebreaker today
        $todayActivity = StudentDailyActivity::where('student_id', $user->id)
            ->where('activity_date', $today)
            ->first();

        // Determine if icebreaker is already done
        $icebreakerDone = $todayActivity && $todayActivity->completed_icebreaker;

        // Get current streak
        $streak = $this->calculateStreak($user->id);
        
        // Get avg score across all games (fair metric — not inflated by number of levels played)
        $allProgress = $user->gameProgress()->get();
        $allAvgs = $allProgress->map(fn($p) => $p->avg_score)->filter(fn($s) => $s > 0);
        $totalScore = $allAvgs->isNotEmpty() ? round($allAvgs->average(), 1) : 0;
        
        // Get total levels completed across all games (current_level - 1 per game, since level starts at 1)
        $totalLevels = max(0, $user->gameProgress()->sum('current_level') - $user->gameProgress()->count());
        
        // Get total possible levels from games table
        $totalPossibleLevels = \App\Models\Game::sum('total_levels') ?: 45;
        
        return view('student.welcome', compact('user', 'streak', 'totalScore', 'totalLevels', 'totalPossibleLevels', 'icebreakerDone'));
    }

    /**
     * Submit icebreaker results
     */
    public function submitIcebreaker(Request $request)
    {
        $request->validate([
            'correct' => 'required|integer|min:0|max:4',
            'total' => 'required|integer|min:4|max:4',
        ]);

        $user = Auth::user();
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Get yesterday's activity to calculate streak
        $yesterdayActivity = StudentDailyActivity::where('student_id', $user->id)
            ->where('activity_date', $yesterday)
            ->first();

        // Calculate new streak
        $newStreak = $yesterdayActivity ? $yesterdayActivity->current_streak + 1 : 1;

        // Record today's activity
        StudentDailyActivity::updateOrCreate(
            [
                'student_id' => $user->id,
                'activity_date' => $today,
            ],
            [
                'completed_icebreaker' => true,
                'icebreaker_correct' => $request->correct,
                'icebreaker_total' => $request->total,
                'current_streak' => $newStreak,
            ]
        );

        return response()->json([
            'success' => true,
            'streak' => $newStreak,
        ]);
    }

    /**
     * Calculate current streak
     */
    private function calculateStreak($studentId)
    {
        $today = Carbon::today();
        $streak = 0;
        $checkDate = $today;

        while (true) {
            $activity = StudentDailyActivity::where('student_id', $studentId)
                ->where('activity_date', $checkDate)
                ->where('completed_icebreaker', true)
                ->first();

            if ($activity) {
                $streak++;
                $checkDate = $checkDate->copy()->subDay();
            } else {
                break;
            }

            // Safety limit
            if ($streak > 365) break;
        }

        return $streak;
    }

    /**
     * Calculate level based on total score
     */
    private function calculateLevel($totalScore)
    {
        // Simple level calculation: 1000 points per level
        return max(1, floor($totalScore / 1000) + 1);
    }

    /**
     * Get icebreaker questions (API endpoint for JavaScript)
     */
    public function getQuestions()
    {
        $questions = [
            // Easy Addition (1-2 digit)
            [
                'text' => 'What is 8 + 7?',
                'options' => ['15', '14', '16', '13'],
                'correct' => 0
            ],
            [
                'text' => 'Add 25 + 34',
                'options' => ['59', '58', '60', '57'],
                'correct' => 0
            ],
            [
                'text' => 'What is 16 + 19?',
                'options' => ['35', '34', '36', '33'],
                'correct' => 0
            ],
            [
                'text' => 'Calculate 42 + 38',
                'options' => ['80', '79', '81', '78'],
                'correct' => 0
            ],
            
            // Easy Subtraction (1-2 digit)
            [
                'text' => 'What is 15 - 8?',
                'options' => ['7', '6', '8', '9'],
                'correct' => 0
            ],
            [
                'text' => 'Subtract 50 - 27',
                'options' => ['23', '22', '24', '25'],
                'correct' => 0
            ],
            [
                'text' => 'What is 32 - 19?',
                'options' => ['13', '12', '14', '11'],
                'correct' => 0
            ],
            
            // Easy Multiplication (1 digit)
            [
                'text' => 'What is 6 × 7?',
                'options' => ['42', '36', '48', '35'],
                'correct' => 0
            ],
            [
                'text' => 'Multiply 8 × 9',
                'options' => ['72', '63', '81', '64'],
                'correct' => 0
            ],
            [
                'text' => 'What is 5 × 8?',
                'options' => ['40', '35', '45', '48'],
                'correct' => 0
            ],
            [
                'text' => 'Calculate 7 × 4',
                'options' => ['28', '24', '32', '21'],
                'correct' => 0
            ],
            
            // Easy Division (1-2 digit)
            [
                'text' => 'What is 24 ÷ 6?',
                'options' => ['4', '3', '5', '6'],
                'correct' => 0
            ],
            [
                'text' => 'Divide 36 by 9',
                'options' => ['4', '3', '5', '6'],
                'correct' => 0
            ],
            [
                'text' => 'What is 48 ÷ 8?',
                'options' => ['6', '5', '7', '8'],
                'correct' => 0
            ],
            
            // Number Sentences
            [
                'text' => 'Fill in the blank: 5 + ___ = 12',
                'options' => ['7', '6', '8', '5'],
                'correct' => 0
            ],
            [
                'text' => 'What number makes this true? ___ × 3 = 18',
                'options' => ['6', '5', '7', '9'],
                'correct' => 0
            ],
            [
                'text' => 'Complete: 20 - ___ = 13',
                'options' => ['7', '6', '8', '9'],
                'correct' => 0
            ],
            [
                'text' => 'Find the missing number: ___ ÷ 4 = 5',
                'options' => ['20', '15', '25', '16'],
                'correct' => 0
            ],
            [
                'text' => 'Solve: 10 + 5 - 3 = ?',
                'options' => ['12', '11', '13', '10'],
                'correct' => 0
            ],
        ];

        // Randomly select 4 questions
        $selected = collect($questions)->random(4)->values();

        return response()->json($selected);
    }
}
