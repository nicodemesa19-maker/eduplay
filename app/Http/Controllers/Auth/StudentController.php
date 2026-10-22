<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Game;
use App\Models\Classroom;
use App\Models\StudentGameProgress;
use App\Models\StudentAnalytic;
use App\Models\StudentBadge;
use App\Models\ClassLeaderboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function games()
    {
        $games    = Game::all();
        $progress = StudentGameProgress::where('student_id', auth()->id())->get()->keyBy('game_id');
        return view('student.games', compact('games', 'progress'));
    }

    public function dashboard()
    {
        $games = Game::all();
        $progress = StudentGameProgress::where('student_id', auth()->id())
            ->with('game')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->keyBy('game_id');
        $classrooms = auth()->user()->classrooms()->with('teacher')->get();

        // Get learning stats data for widget
        $statsData = $this->getLearningStatsData();
        $leaderboardData = $this->getClassLeaderboardData();
        
        // Get today's date and check if student completed icebreaker
        $today = \Carbon\Carbon::today();
        $todayActivity = \App\Models\StudentDailyActivity::where('student_id', auth()->id())
            ->where('activity_date', $today)
            ->first();
        
        // Show streak section only if icebreaker NOT completed today
        $showStreakSection = !($todayActivity && $todayActivity->completed_icebreaker);
        
        $learningStats = $this->formatLearningStatsForDashboard($statsData);
        $scoreboard = $this->formatScoreboardForDashboard($leaderboardData);
        $totalScore = $this->calculateTotalScore($progress);

        return view('student.dashboard', compact('games', 'progress', 'classrooms', 'learningStats', 'scoreboard', 'totalScore', 'showStreakSection'));
    }

    public function enrollClassroom(Request $request)
    {
        $request->validate([
            'class_code' => 'required|exists:classrooms,class_code',
        ]);

        $classroom = Classroom::where('class_code', $request->class_code)->firstOrFail();

        if (!auth()->user()->classrooms()->where('classroom_id', $classroom->id)->exists()) {
            auth()->user()->classrooms()->attach($classroom->id);
        }

        return redirect()->route('student.dashboard')->with('success', 'You are now enrolled in the classroom.');
    }

    /**
     * Show character selection screen or save chosen character.
     */
    public function chooseCharacter(Request $request)
    {
        $characters = [
            'zip' => [
                'id'         => 'zip',
                'name'       => 'Jojo "Zip" Ilustre',
                'nickname'   => 'Zip',
                'emoji'      => '⚡',
                'color'      => 'yellow',
                'specialty'  => 'Speed Math',
                'power'      => 'Lightning Calculation',
                'trait'      => 'Blazing fast at mental arithmetic',
                'weapon'     => 'Flash Abacus',
                'quote'      => '"Done before you blink!"',
                'stats'      => ['Speed' => 99, 'Power' => 70, 'Defense' => 60, 'Wisdom' => 75],
            ],
            'calculus' => [
                'id'         => 'calculus',
                'name'       => 'Kaito "Calculus" Chen',
                'nickname'   => 'Calculus',
                'emoji'      => '🔢',
                'color'      => 'blue',
                'specialty'  => 'Algebra & Equations',
                'power'      => 'Formula Mastery',
                'trait'      => 'Solves complex equations with ease',
                'weapon'     => 'Equation Blade',
                'quote'      => '"Every problem has a solution."',
                'stats'      => ['Speed' => 70, 'Power' => 85, 'Defense' => 65, 'Wisdom' => 95],
            ],
            'algebra' => [
                'id'         => 'algebra',
                'name'       => 'Kenji "Algebra" Tanaka',
                'nickname'   => 'Algebra',
                'emoji'      => '🛡️',
                'color'      => 'green',
                'specialty'  => 'Collaborative Problem Solving',
                'power'      => 'Combo Boost',
                'trait'      => 'Gets stronger with every teammate',
                'weapon'     => 'Unity Shield',
                'quote'      => '"Together we multiply!"',
                'stats'      => ['Speed' => 65, 'Power' => 80, 'Defense' => 95, 'Wisdom' => 75],
            ],
            'circuit' => [
                'id'         => 'circuit',
                'name'       => 'Aria "Circuit" Lee',
                'nickname'   => 'Circuit',
                'emoji'      => '💡',
                'color'      => 'purple',
                'specialty'  => 'Logic & Patterns',
                'power'      => 'Circuit Overload',
                'trait'      => 'Spots patterns nobody else can see',
                'weapon'     => 'Logic Grid',
                'quote'      => '"Follow the pattern, win the game."',
                'stats'      => ['Speed' => 75, 'Power' => 80, 'Defense' => 70, 'Wisdom' => 98],
            ],
            'cryptos' => [
                'id'         => 'cryptos',
                'name'       => 'Elara "Cryptos" Kawasaki',
                'nickname'   => 'Cryptos',
                'emoji'      => '🔐',
                'color'      => 'pink',
                'specialty'  => 'Number Codes & Sequences',
                'power'      => 'Cipher Strike',
                'trait'      => 'Decodes any number sequence instantly',
                'weapon'     => 'Crypto Key',
                'quote'      => '"Numbers never lie, decode them."',
                'stats'      => ['Speed' => 80, 'Power' => 75, 'Defense' => 75, 'Wisdom' => 90],
            ],
            'apex' => [
                'id'         => 'apex',
                'name'       => 'Chloe "Apex" Miller',
                'nickname'   => 'Apex',
                'emoji'      => '🏆',
                'color'      => 'red',
                'specialty'  => 'All-Round Math Champion',
                'power'      => 'Peak Performance',
                'trait'      => 'Excels in every math discipline',
                'weapon'     => 'Golden Compass',
                'quote'      => '"Reach the apex, own the summit."',
                'stats'      => ['Speed' => 88, 'Power' => 88, 'Defense' => 88, 'Wisdom' => 88],
            ],
        ];

        if ($request->isMethod('post')) {
            $request->validate(['character' => 'required|in:' . implode(',', array_keys($characters))]);
            $user = Auth::user();
            $user->character_avatar = $request->character;
            $user->save();
            return redirect()->route('student.dashboard')->with('success', 'Character selected!');
        }

        return view('student.choose-character', compact('characters'));
    }

    /**
     * Show the game level map.
     */
    public function gameMap($gameName)
    {
        $game = Game::where('name', $gameName)->firstOrFail();
        $progress = StudentGameProgress::firstOrCreate([
            'student_id' => auth()->id(),
            'game_id'    => $game->id,
        ], ['current_level' => 1, 'high_score' => 0, 'level_scores' => []]);

        $character = Auth::user()->character_avatar ?? 'zip';
        return view('student.game-map', compact('game', 'progress', 'character'));
    }

    /**
     * Display the requested game (math_card_match or number_wars).
     */
    public function playGame($gameName)
    {
        // Validate that the game exists in the database
        $game = Game::where('name', $gameName)->firstOrFail();
        
        // Get or create progress record for this student and game
        $progress = StudentGameProgress::firstOrCreate([
            'student_id' => auth()->id(),
            'game_id' => $game->id,
        ], [
            'current_level' => 1,
            'high_score' => 0,
            'level_scores' => []
        ]);
        
        // Return the appropriate game view
        if ($gameName === 'math_card_match') {
            return view('games.math_card_match', compact('game', 'progress'));
        } elseif ($gameName === 'number_wars') {
            return view('games.number_wars', compact('game', 'progress'));
        } elseif ($gameName === 'rocket_math') {
            return view('games.rocket_math', compact('game', 'progress'));
        } elseif ($gameName === 'factor_pond') {
            return view('games.factor_pond', compact('game', 'progress'));
        } elseif ($gameName === 'time_quest') {
            return view('games.time_quest', compact('game', 'progress'));
        } elseif ($gameName === 'mdas_maze') {
            return view('games.mdas_maze', compact('game', 'progress'));
        } elseif ($gameName === 'the_metric_quest') {
            return view('games.the_metric_quest', compact('game', 'progress'));
        } elseif ($gameName === 'geometry_grid') {
            return view('games.geometry_grid', compact('game', 'progress'));
        }
        
        abort(404, 'Game not found');
    }

    /**
     * Update student progress after a game session (AJAX call from games).
     */
    public function updateProgress(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'level' => 'required|integer|min:1|max:100',
            'score' => 'required|integer|min:0',
        ]);

        $progress = StudentGameProgress::with('game')
            ->firstOrCreate([
                'student_id' => auth()->id(),
                'game_id' => $request->game_id,
            ], [
                'current_level' => 1,
                'high_score' => 0,
                'level_scores' => [],
            ]);
        
        $levelCompleted = false;
        $starsEarned = 0;
        
        // Advance level: the JS sends the already-incremented next level after winning,
        // so we simply accept it if it's higher than what's currently stored.
        $submittedLevel = (int) $request->level;
        if ($submittedLevel > $progress->current_level) {
            $progress->current_level = min($submittedLevel, $progress->game->total_levels ?? 50);
            $levelCompleted = true;
        }

        // Store level-specific score (keep best for that level)
        $levelScores = $progress->level_scores ?? [];
        $levelKey = max(1, $submittedLevel - 1); // store score under the completed level
        $previousLevelScore = $levelScores[$levelKey] ?? 0;
        
        if ($request->score > $previousLevelScore) {
            $levelScores[$levelKey] = $request->score;
            $progress->level_scores = $levelScores;
            
            // Calculate stars based on score
            if ($request->score >= 80) $starsEarned = 3;
            elseif ($request->score >= 60) $starsEarned = 2;
            elseif ($request->score > 0) $starsEarned = 1;
        }

        $progress->touch();
        $progress->save();
        
        // Record daily analytics (estimate 5 minutes per level)
        $this->recordDailyAnalytics(
            auth()->id(),
            $request->game_id,
            $request->score,
            $levelCompleted,
            $starsEarned,
            5 // Estimated minutes
        );
        
        return response()->json(['success' => true]);
    }

    /**
     * Show detailed progress of the student across all games.
     */
    public function myProgress()
    {
        $progress = StudentGameProgress::where('student_id', auth()->id())
            ->with('game')
            ->get();
        
        $allAvgs = $progress->map(fn($p) => $p->avg_score)->filter(fn($s) => $s > 0);
        $totalScore = $allAvgs->isNotEmpty() ? round($allAvgs->average(), 1) : 0;
        
        return view('student.progress', compact('progress', 'totalScore'));
    }

    // ============================================
    // LEARNING STATS & ANALYTICS MODULE
    // ============================================

    /**
     * Show the Learning Stats dashboard page.
     */
    public function learningStats()
    {
        $student = Auth::user();
        
        // Get all stats data
        $stats = $this->getLearningStatsData();
        $leaderboard = $this->getClassLeaderboardData();
        $badges = $this->getBadgesData();
        $improvement = $this->getImprovementData();
        $encouragement = $this->getSmartEncouragement();
        
        return view('student.learning-stats', compact('stats', 'leaderboard', 'badges', 'improvement', 'encouragement'));
    }

    /**
     * Show the Badges Collection page (trackable badges only).
     */
    public function badgesCollection()
    {
        $student = Auth::user();
        $studentId = $student->id;
        
        // Get current stats
        $stats = $this->getLearningStatsData();
        $currentStreak = $stats['streak']['current'];
        $totalScore = $stats['totalScore'];
        $levelsCompleted = $stats['levelsCompleted'];
        $gamesPlayed = $stats['gamesPlayed'];
        $totalStars = $stats['totalStars'];
        
        // Get earned badges
        $earnedBadges = StudentBadge::where('student_id', $studentId)
            ->get()
            ->keyBy('badge_name');
        
        // Streak badges (trackable via daily analytics)
        $streakBadgeDefinitions = [
            ['name' => 'Fire Starter', 'icon' => '🔥', 'description' => 'Play 3 days in a row', 'required' => 3],
            ['name' => 'Week Warrior', 'icon' => '💪', 'description' => 'Play 7 days in a row', 'required' => 7],
            ['name' => 'Fortnight Champion', 'icon' => '🏆', 'description' => 'Play 14 days in a row', 'required' => 14],
            ['name' => 'Monthly Master', 'icon' => '👑', 'description' => 'Play 30 days in a row', 'required' => 30],
        ];
        
        $streakBadges = [];
        foreach ($streakBadgeDefinitions as $def) {
            $badgeName = $def['name'] . ' ' . $def['icon'];
            $earned = isset($earnedBadges[$badgeName]);
            $progress = min(100, ($currentStreak / $def['required']) * 100);
            
            $streakBadges[] = [
                'name' => $def['name'],
                'icon' => $def['icon'],
                'description' => $def['description'],
                'required' => $def['required'],
                'current' => $currentStreak,
                'progress' => round($progress),
                'earned' => $earned,
            ];
        }
        
        // Score badges (trackable via high_score)
        $scoreBadgeDefinitions = [
            ['name' => 'Century Club', 'icon' => '💯', 'description' => 'Earn 100 total points', 'required' => 100],
            ['name' => 'High Scorer', 'icon' => '⭐', 'description' => 'Earn 500 total points', 'required' => 500],
            ['name' => 'Thousand Master', 'icon' => '🌟', 'description' => 'Earn 1000 total points', 'required' => 1000],
            ['name' => 'Elite Explorer', 'icon' => '🚀', 'description' => 'Earn 2500 total points', 'required' => 2500],
        ];
        
        $scoreBadges = [];
        foreach ($scoreBadgeDefinitions as $def) {
            $badgeName = $def['name'] . ' ' . $def['icon'];
            $earned = isset($earnedBadges[$badgeName]);
            $progress = min(100, ($totalScore / $def['required']) * 100);
            
            $scoreBadges[] = [
                'name' => $def['name'],
                'icon' => $def['icon'],
                'description' => $def['description'],
                'required' => $def['required'],
                'current' => $totalScore,
                'progress' => round($progress),
                'earned' => $earned,
            ];
        }
        
        // Achievement badges (trackable via current system)
        $achievementBadgeDefinitions = [
            ['name' => 'First Steps', 'icon' => '🎯', 'description' => 'Complete your first level', 'earned' => $levelsCompleted >= 1],
            ['name' => 'Game Explorer', 'icon' => '🎮', 'description' => 'Play all 4 games', 'earned' => $gamesPlayed >= 4],
            ['name' => 'Star Collector', 'icon' => '✨', 'description' => 'Collect 50 stars', 'earned' => $totalStars >= 50],
            ['name' => 'Level Master', 'icon' => '🎓', 'description' => 'Complete 25 levels', 'earned' => $levelsCompleted >= 25],
        ];
        
        $achievementBadges = [];
        foreach ($achievementBadgeDefinitions as $def) {
            $badgeName = $def['name'] . ' ' . $def['icon'];
            $earned = $def['earned'] && isset($earnedBadges[$badgeName]);
            
            $achievementBadges[] = [
                'name' => $def['name'],
                'icon' => $def['icon'],
                'description' => $def['description'],
                'earned' => $earned,
            ];
        }
        
        // Summary stats
        $totalBadges = count($streakBadgeDefinitions) + count($scoreBadgeDefinitions) + count($achievementBadgeDefinitions);
        $earnedCount = $earnedBadges->count();
        $completionPercent = $totalBadges > 0 ? round(($earnedCount / $totalBadges) * 100) : 0;
        
        // Get most recent badge
        $recentBadge = StudentBadge::where('student_id', $studentId)
            ->orderBy('earned_at', 'desc')
            ->first();
        
        return view('student.badges', compact(
            'streakBadges',
            'scoreBadges',
            'achievementBadges',
            'earnedCount',
            'totalBadges',
            'completionPercent',
            'recentBadge'
        ));
    }

    /**
     * Get learning stats data (API endpoint for AJAX).
     */
    public function getLearningStatsApi()
    {
        return response()->json([
            'stats' => $this->getLearningStatsData(),
            'leaderboard' => $this->getClassLeaderboardData(),
            'badges' => $this->getBadgesData(),
            'improvement' => $this->getImprovementData(),
            'encouragement' => $this->getSmartEncouragement(),
        ]);
    }

    /**
     * Get personal learning statistics.
     */
    private function getLearningStatsData()
    {
        $student = Auth::user();
        $studentId = $student->id;
        
        // Get daily analytics for last 30 days
        $dailyStats = StudentAnalytic::where('student_id', $studentId)
            ->where('date', '>=', now()->subDays(30))
            ->orderBy('date')
            ->get();
        
        // Calculate streak
        $streak = $this->calculateStreak($studentId);
        
        // Get behavioral patterns
        $patterns = $this->analyzeBehaviorPatterns($studentId);
        
        // Get skill levels
        $skills = $this->calculateSkillLevels($studentId);
        
        // Get total stats
        $totalProgress = StudentGameProgress::where('student_id', $studentId)->get();
        // avg_score per game, then average across all games the student has played
        $allAvgScores = $totalProgress->map(fn($p) => $p->avg_score)->filter(fn($s) => $s > 0);
        $totalScore = $allAvgScores->isNotEmpty() ? round($allAvgScores->average(), 1) : 0;
        $levelsCompleted = $totalProgress->sum('current_level');
        $gamesPlayed = $totalProgress->count();
        
        // Calculate stars (3 stars per level completed)
        $totalStars = 0;
        foreach ($totalProgress as $progress) {
            $levelScores = $progress->level_scores ?? [];
            foreach ($levelScores as $score) {
                if ($score >= 80) $totalStars += 3;
                elseif ($score >= 60) $totalStars += 2;
                elseif ($score > 0) $totalStars += 1;
            }
        }
        
        return [
            'streak' => $streak,
            'patterns' => $patterns,
            'skills' => $skills,
            'totalScore' => $totalScore,
            'levelsCompleted' => $levelsCompleted,
            'gamesPlayed' => $gamesPlayed,
            'totalStars' => $totalStars,
            'averageStars' => $levelsCompleted > 0 ? round($totalStars / $levelsCompleted, 1) : 0,
        ];
    }

    /**
     * Calculate current and longest streak.
     */
    private function calculateStreak($studentId)
    {
        // Use StudentDailyActivity table for accurate streak tracking
        $today = \Carbon\Carbon::today();
        $currentStreak = 0;
        $checkDate = $today;

        // Calculate current streak from icebreaker completions
        while (true) {
            $activity = \App\Models\StudentDailyActivity::where('student_id', $studentId)
                ->where('activity_date', $checkDate)
                ->where('completed_icebreaker', true)
                ->first();

            if ($activity) {
                $currentStreak++;
                $checkDate = $checkDate->copy()->subDay();
            } else {
                break;
            }

            // Safety limit
            if ($currentStreak > 365) break;
        }
        
        // Get longest streak from all activity records
        $longestStreak = \App\Models\StudentDailyActivity::where('student_id', $studentId)
            ->where('completed_icebreaker', true)
            ->max('current_streak') ?? $currentStreak;
        
        // Determine next milestone
        $milestones = [3, 5, 7, 14, 21, 30, 60, 100];
        $nextMilestone = 7; // default
        foreach ($milestones as $milestone) {
            if ($currentStreak < $milestone) {
                $nextMilestone = $milestone;
                break;
            }
        }
        
        return [
            'current' => $currentStreak,
            'longest' => max($currentStreak, $longestStreak),
            'nextMilestone' => $nextMilestone,
            'daysToMilestone' => $nextMilestone - $currentStreak,
        ];
    }

    /**
     * Analyze behavioral patterns (best day, best time).
     */
    private function analyzeBehaviorPatterns($studentId)
    {
        $analytics = StudentAnalytic::where('student_id', $studentId)
            ->where('date', '>=', now()->subDays(30))
            ->get();
        
        if ($analytics->isEmpty()) {
            return [
                'bestDay' => 'Not enough data yet',
                'bestDayNumber' => null,
                'bestTime' => 'afternoon',
                'totalMinutesPlayed' => 0,
                'sessionsThisWeek' => 0,
            ];
        }
        
        // Find best day of week
        $dayScores = [];
        foreach ($analytics as $record) {
            $dayOfWeek = $record->day_of_week ?? $record->date->dayOfWeekIso; // 1=Mon, 7=Sun
            if (!isset($dayScores[$dayOfWeek])) {
                $dayScores[$dayOfWeek] = 0;
            }
            $dayScores[$dayOfWeek] += $record->points_earned;
        }
        
        arsort($dayScores);
        $bestDayNumber = array_key_first($dayScores) ?? 2; // Default Tuesday
        $days = ['', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $bestDay = $days[$bestDayNumber] ?? 'Tuesday';
        
        // Find best time of day (most common hour)
        $hours = $analytics->pluck('hour_of_day')->filter()->toArray();
        $bestHour = !empty($hours) ? $this->mostCommon($hours) : 14; // Default 2pm
        
        $bestTime = 'afternoon'; // default
        if ($bestHour >= 6 && $bestHour < 12) $bestTime = 'morning';
        elseif ($bestHour >= 12 && $bestHour < 17) $bestTime = 'afternoon';
        elseif ($bestHour >= 17 && $bestHour < 21) $bestTime = 'evening';
        
        // Calculate total minutes and sessions
        $totalMinutes = $analytics->sum('minutes_played');
        $thisWeek = StudentAnalytic::where('student_id', $studentId)
            ->where('date', '>=', now()->startOfWeek())
            ->count();
        
        return [
            'bestDay' => $bestDay,
            'bestDayNumber' => $bestDayNumber,
            'bestTime' => $bestTime,
            'bestHour' => $bestHour,
            'totalMinutesPlayed' => $totalMinutes,
            'sessionsThisWeek' => $thisWeek,
        ];
    }

    /**
     * Helper: Find most common value in array.
     */
    private function mostCommon($array)
    {
        if (empty($array)) return null;
        $values = array_count_values($array);
        arsort($values);
        return array_key_first($values);
    }

    /**
     * Calculate skill levels per game.
     */
    private function calculateSkillLevels($studentId)
    {
        $progress = StudentGameProgress::where('student_id', $studentId)
            ->with('game')
            ->get();
        
        $skills = [];
        foreach ($progress as $prog) {
            if (!$prog->game) continue;
            
            $levelScores = $prog->level_scores ?? [];
            $completedLevels = count($levelScores);
            $avgScore = $completedLevels > 0 ? array_sum($levelScores) / $completedLevels : 0;
            
            // Calculate star rating (1-5 stars)
            $stars = 1;
            if ($avgScore >= 90) $stars = 5;
            elseif ($avgScore >= 75) $stars = 4;
            elseif ($avgScore >= 60) $stars = 3;
            elseif ($avgScore >= 40) $stars = 2;
            
            $skills[] = [
                'game' => $prog->game->name,
                'gameTitle' => $prog->game->title ?? ucwords(str_replace('_', ' ', $prog->game->name)),
                'stars' => $stars,
                'avgScore' => round($avgScore),
                'levelsCompleted' => $completedLevels,
                'currentLevel' => $prog->current_level,
            ];
        }
        
        return $skills;
    }

    /**
     * Get class leaderboard data (anonymous).
     */
    private function getClassLeaderboardData()
    {
        $student = Auth::user();
        $classroom = $student->classrooms()->first();
        
        if (!$classroom) {
            return null; // Not in a classroom
        }
        
        // Get all student scores in the classroom
        $studentScores = DB::table('users')
            ->join('classroom_student', 'users.id', '=', 'classroom_student.student_id')
            ->leftJoin('student_game_progress', 'users.id', '=', 'student_game_progress.student_id')
            ->where('classroom_student.classroom_id', $classroom->id)
            ->where('users.role', 'student')
            ->select('users.id', DB::raw('COALESCE(SUM(student_game_progress.high_score), 0) as total_score'))
            ->groupBy('users.id')
            ->get();
        
        // Compute avg_score per student: average of their per-level best scores across all games.
        // This is fairer than SUM(high_score) which rewards students who have simply played more levels.
        $allProgress = StudentGameProgress::whereIn('student_id', $studentScores->pluck('id'))->get();

        $studentScores = $studentScores->map(function ($row) use ($allProgress) {
            $records = $allProgress->where('student_id', $row->id);
            $perGameAvgs = $records->map(fn($p) => $p->avg_score)->filter(fn($s) => $s > 0);
            $row->total_score = $perGameAvgs->isNotEmpty() ? round($perGameAvgs->average(), 1) : 0;
            return $row;
        });
        
        // Sort scores descending
        $scores = $studentScores->pluck('total_score')->sort()->reverse()->values();
        $studentScore = $studentScores->firstWhere('id', $student->id)->total_score ?? 0;
        
        // Find student's rank
        $rank = $scores->search($studentScore);
        if ($rank === false) {
            $rank = $scores->count();
        } else {
            $rank += 1; // Convert 0-indexed to 1-indexed
        }
        
        $totalStudents = $scores->count();
        $aheadOf = $totalStudents - $rank;
        
        return [
            'topScore' => $scores->first() ?? 0,
            'secondScore' => $scores->get(1) ?? 0,
            'thirdScore' => $scores->get(2) ?? 0,
            'classAverage' => round($scores->average()),
            'yourScore' => $studentScore,
            'yourRank' => $rank,
            'totalStudents' => $totalStudents,
            'aheadOf' => $aheadOf,
            'percentile' => $totalStudents > 0 ? round(($aheadOf / $totalStudents) * 100) : 0,
            'message' => $this->getEncouragingRankMessage($rank, $totalStudents, $scores->first() - $studentScore),
            'rankLabel' => $this->getEncouragingRankLabel($rank, $totalStudents),
        ];
    }

    /**
     * Get encouraging rank label.
     */
    private function getEncouragingRankLabel($rank, $total)
    {
        if ($rank === 1) return "🥇 TOP EXPLORER!";
        if ($rank <= 3) return "🏅 PODIUM FINISHER!";
        if ($rank <= $total * 0.25) return "🌟 TOP QUARTER!";
        if ($rank <= $total * 0.5) return "⭐ ABOVE AVERAGE!";
        return "🚀 CLIMBING UP!";
    }

    /**
     * Get encouraging rank message.
     */
    private function getEncouragingRankMessage($rank, $total, $pointsBehind)
    {
        if ($rank === 1) {
            return "🎉 You're #1! Keep up the amazing work!";
        }
        if ($rank === 2) {
            return "🔥 So close to #1! Only {$pointsBehind} points away!";
        }
        if ($rank === 3) {
            return "🏆 Podium finish! Play 2 more games to reach #1!";
        }
        if ($rank <= 5) {
            return "⭐ You're in the top 5! {$pointsBehind} points to the podium!";
        }
        if ($pointsBehind < 100) {
            return "🎯 You're so close to moving up! Keep playing!";
        }
        return "📈 Every game makes you stronger! Keep going!";
    }

    /**
     * Get badges data.
     */
    private function getBadgesData()
    {
        $student = Auth::user();
        
        $badges = StudentBadge::where('student_id', $student->id)
            ->orderBy('earned_at', 'desc')
            ->get();
        
        $recentBadges = $badges->take(5);
        
        // Check for badges to award
        $this->checkAndAwardBadges($student->id);
        
        return [
            'total' => $badges->count(),
            'recent' => $recentBadges,
            'byType' => $badges->groupBy('badge_type')->map->count(),
        ];
    }

    /**
     * Check and award new badges (only trackable badges).
     */
    private function checkAndAwardBadges($studentId)
    {
        $stats = $this->getLearningStatsData();
        $existingBadges = StudentBadge::where('student_id', $studentId)->pluck('badge_name')->toArray();
        
        // Streak badges
        $streakMilestones = [
            3 => ['name' => 'Fire Starter 🔥', 'icon' => '🔥', 'desc' => 'Played 3 days in a row'],
            7 => ['name' => 'Week Warrior 💪', 'icon' => '💪', 'desc' => 'Played 7 days in a row'],
            14 => ['name' => 'Fortnight Champion 🏆', 'icon' => '🏆', 'desc' => 'Played 14 days in a row'],
            30 => ['name' => 'Monthly Master 👑', 'icon' => '👑', 'desc' => 'Played 30 days in a row'],
        ];
        
        foreach ($streakMilestones as $days => $badge) {
            if ($stats['streak']['current'] >= $days && !in_array($badge['name'], $existingBadges)) {
                StudentBadge::create([
                    'student_id' => $studentId,
                    'badge_type' => StudentBadge::TYPE_STREAK,
                    'badge_name' => $badge['name'],
                    'badge_icon' => $badge['icon'],
                    'description' => $badge['desc'],
                    'value' => $days,
                ]);
            }
        }
        
        // Score badges
        $scoreMilestones = [
            100 => ['name' => 'Century Club 💯', 'icon' => '💯', 'desc' => 'Earned 100 total points'],
            500 => ['name' => 'High Scorer ⭐', 'icon' => '⭐', 'desc' => 'Earned 500 total points'],
            1000 => ['name' => 'Thousand Master 🌟', 'icon' => '🌟', 'desc' => 'Earned 1000 total points'],
            2500 => ['name' => 'Elite Explorer 🚀', 'icon' => '🚀', 'desc' => 'Earned 2500 total points'],
        ];
        
        foreach ($scoreMilestones as $points => $badge) {
            if ($stats['totalScore'] >= $points && !in_array($badge['name'], $existingBadges)) {
                StudentBadge::create([
                    'student_id' => $studentId,
                    'badge_type' => StudentBadge::TYPE_SCORE,
                    'badge_name' => $badge['name'],
                    'badge_icon' => $badge['icon'],
                    'description' => $badge['desc'],
                    'value' => $points,
                ]);
            }
        }
        
        // Achievement badges
        // First Steps - Complete first level
        if ($stats['levelsCompleted'] >= 1 && !in_array('First Steps 🎯', $existingBadges)) {
            StudentBadge::create([
                'student_id' => $studentId,
                'badge_type' => StudentBadge::TYPE_ACHIEVEMENT,
                'badge_name' => 'First Steps 🎯',
                'badge_icon' => '🎯',
                'description' => 'Completed your first level',
            ]);
        }
        
        // Game Explorer - Play all 4 games
        if ($stats['gamesPlayed'] >= 4 && !in_array('Game Explorer 🎮', $existingBadges)) {
            StudentBadge::create([
                'student_id' => $studentId,
                'badge_type' => StudentBadge::TYPE_ACHIEVEMENT,
                'badge_name' => 'Game Explorer 🎮',
                'badge_icon' => '🎮',
                'description' => 'Played all 4 games',
            ]);
        }
        
        // Star Collector - Collect 50 stars
        if ($stats['totalStars'] >= 50 && !in_array('Star Collector ✨', $existingBadges)) {
            StudentBadge::create([
                'student_id' => $studentId,
                'badge_type' => StudentBadge::TYPE_ACHIEVEMENT,
                'badge_name' => 'Star Collector ✨',
                'badge_icon' => '✨',
                'description' => 'Collected 50 stars',
            ]);
        }
        
        // Level Master - Complete 25 levels
        if ($stats['levelsCompleted'] >= 25 && !in_array('Level Master 🎓', $existingBadges)) {
            StudentBadge::create([
                'student_id' => $studentId,
                'badge_type' => StudentBadge::TYPE_ACHIEVEMENT,
                'badge_name' => 'Level Master 🎓',
                'badge_icon' => '🎓',
                'description' => 'Completed 25 levels',
            ]);
        }
    }

    /**
     * Get improvement data (week-over-week).
     */
    private function getImprovementData()
    {
        $student = Auth::user();
        
        $thisWeek = StudentAnalytic::where('student_id', $student->id)
            ->where('date', '>=', now()->startOfWeek())
            ->get();
        
        $lastWeek = StudentAnalytic::where('student_id', $student->id)
            ->whereBetween('date', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
            ->get();
        
        $thisWeekPoints = $thisWeek->sum('points_earned');
        $lastWeekPoints = $lastWeek->sum('points_earned');
        
        $thisWeekLevels = $thisWeek->sum('levels_completed');
        $lastWeekLevels = $lastWeek->sum('levels_completed');
        
        $thisWeekStars = $thisWeek->sum('stars_earned');
        $lastWeekStars = $lastWeek->sum('stars_earned');
        
        $thisWeekMinutes = $thisWeek->sum('minutes_played');
        $lastWeekMinutes = $lastWeek->sum('minutes_played');
        
        return [
            'points' => [
                'current' => $thisWeekPoints,
                'previous' => $lastWeekPoints,
                'change' => $lastWeekPoints > 0 ? round((($thisWeekPoints - $lastWeekPoints) / $lastWeekPoints) * 100) : 0,
            ],
            'levels' => [
                'current' => $thisWeekLevels,
                'previous' => $lastWeekLevels,
                'change' => $lastWeekLevels > 0 ? round((($thisWeekLevels - $lastWeekLevels) / $lastWeekLevels) * 100) : 0,
            ],
            'stars' => [
                'current' => $thisWeekStars,
                'previous' => $lastWeekStars,
                'change' => $lastWeekStars > 0 ? round((($thisWeekStars - $lastWeekStars) / $lastWeekStars) * 100) : 0,
            ],
            'minutes' => [
                'current' => $thisWeekMinutes,
                'previous' => $lastWeekMinutes,
                'change' => $lastWeekMinutes > 0 ? round((($thisWeekMinutes - $lastWeekMinutes) / $lastWeekMinutes) * 100) : 0,
            ],
        ];
    }

    /**
     * Get context-aware encouraging message.
     */
    private function getSmartEncouragement()
    {
        $student = Auth::user();
        $stats = $this->getLearningStatsData();
        $improvement = $this->getImprovementData();
        
        // On a great streak
        if ($stats['streak']['current'] >= 5) {
            return [
                'icon' => '🔥',
                'title' => "You're On Fire!",
                'message' => "{$stats['streak']['current']} days in a row! You're unstoppable!",
                'action' => 'Keep the Streak Going',
                'actionUrl' => route('student.games'),
            ];
        }
        
        // Improved significantly
        if ($improvement['points']['change'] > 50) {
            return [
                'icon' => '📈',
                'title' => 'Amazing Growth!',
                'message' => "You've improved {$improvement['points']['change']}% this week! Your practice is working!",
                'action' => 'See Your Progress',
                'actionUrl' => route('student.myProgress'),
            ];
        }
        
        // Close to next level/milestone
        $nextBadgePoints = [100, 500, 1000, 2500, 5000];
        foreach ($nextBadgePoints as $milestone) {
            if ($stats['totalScore'] < $milestone && ($milestone - $stats['totalScore']) < 50) {
                return [
                    'icon' => '🎯',
                    'title' => 'So Close!',
                    'message' => "Only " . ($milestone - $stats['totalScore']) . " points to unlock a new badge!",
                    'action' => 'Finish Strong!',
                    'actionUrl' => route('student.games'),
                ];
            }
        }
        
        // Default encouragement
        return [
            'icon' => '⭐',
            'title' => 'Keep Going!',
            'message' => 'Every game makes you smarter. Ready to play?',
            'action' => 'Start Playing',
            'actionUrl' => route('student.games'),
        ];
    }

    /**
     * Record daily analytics (called automatically when student plays).
     */
    public function recordDailyAnalytics($studentId, $gameId, $pointsEarned, $levelCompleted, $starsEarned, $minutesPlayed)
    {
        $today = now()->toDateString();
        
        $analytic = StudentAnalytic::firstOrNew([
            'student_id' => $studentId,
            'date' => $today,
        ]);
        
        // Update daily metrics
        $analytic->points_earned += $pointsEarned;
        $analytic->levels_completed += $levelCompleted ? 1 : 0;
        $analytic->stars_earned += $starsEarned;
        $analytic->minutes_played += $minutesPlayed;
        
        // Update games played array
        $gamesPlayed = $analytic->games_played ?? [];
        if (!in_array($gameId, $gamesPlayed)) {
            $gamesPlayed[] = $gameId;
        }
        $analytic->games_played = $gamesPlayed;
        
        // Update cumulative snapshots
        $totalProgress = StudentGameProgress::where('student_id', $studentId)->get();
        $allAvgScores = $totalProgress->map(fn($p) => $p->avg_score)->filter(fn($s) => $s > 0);
        $analytic->total_score = $allAvgScores->isNotEmpty() ? (int) round($allAvgScores->average()) : 0;
        
        // Calculate current streak
        $streak = $this->calculateStreak($studentId);
        $analytic->current_streak = $streak['current'];
        $analytic->longest_streak = $streak['longest'];
        
        // Record time patterns
        $analytic->hour_of_day = now()->hour;
        $analytic->day_of_week = now()->dayOfWeekIso; // 1=Monday, 7=Sunday
        
        $analytic->save();
        
        // Check for new badges
        $this->checkAndAwardBadges($studentId);
    }

    /**
     * Format learning stats array for the dashboard view.
     */
    private function formatLearningStatsForDashboard(array $statsData): array
    {
        return [
            'peak_day' => $statsData['patterns']['bestDay'] !== 'Not enough data yet'
                ? $statsData['patterns']['bestDay']
                : null,
            'streak'     => $statsData['streak']['current'],
            'streak_map' => $this->getStreakMap($statsData['streak']['current']),
            'skills'     => $this->formatSkillsForDashboard($statsData['skills']),
        ];
    }

    /**
     * Format leaderboard data as the scoreboard array for the dashboard view.
     */
    private function formatScoreboardForDashboard(?array $leaderboardData): ?array
    {
        if (!$leaderboardData) {
            return null;
        }

        return [
            'top'     => $leaderboardData['topScore'],
            'average' => $leaderboardData['classAverage'],
            'you'     => $leaderboardData['yourScore'],
            'rank'    => $leaderboardData['yourRank'],
        ];
    }

    /**
     * Calculate the student's overall average score from their game progress.
     */
    private function calculateTotalScore($progress): float
    {
        $allAvgs = $progress->map(fn($p) => $p->avg_score)->filter(fn($s) => $s > 0);
        return $allAvgs->isNotEmpty() ? round($allAvgs->average(), 1) : 0;
    }

    /**
     * Get streak map for last 7 days (for dashboard visualization).
     */
    private function getStreakMap($currentStreak)
    {
        $map = [0, 0, 0, 0, 0, 0, 0]; // 7 days
        for ($i = 0; $i < min($currentStreak, 7); $i++) {
            $map[6 - $i] = 1; // Fill from right to left
        }
        return $map;
    }

    /**
     * Format skills for dashboard display.
     */
    private function formatSkillsForDashboard($skills)
    {
        if (empty($skills)) {
            return [];
        }
        
        $formatted = [];
        $colors = ['#00E5FF', '#FF3DAE', '#B6FF3D', '#B026FF'];
        $icons = ['bi-calculator', 'bi-lightning', 'bi-shield', 'bi-rocket'];
        
        foreach ($skills as $index => $skill) {
            $formatted[] = [
                'name' => $skill['gameTitle'],
                'unlocked' => $skill['levelsCompleted'],
                'total' => 15, // Assuming 15 levels per game
                'color' => $colors[$index % count($colors)],
                'icon' => $icons[$index % count($icons)],
            ];
        }
        
        return array_slice($formatted, 0, 3); // Limit to 3 for dashboard
    }
}