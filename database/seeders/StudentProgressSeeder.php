<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Game;
use App\Models\StudentGameProgress;
use App\Models\StudentBadge;
use App\Models\StudentAnalytic;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class StudentProgressSeeder extends Seeder
{
    /**
     * Seed student progress data with varied scores, badges, and analytics.
     * Each student gets unique data to demonstrate different performance levels.
     */
    public function run(): void
    {
        $this->command->info('Starting to seed student progress data...');

        // Get all games
        $games = Game::all();
        
        if ($games->count() === 0) {
            $this->command->error('No games found! Please run GamesTableSeeder first.');
            return;
        }

        // Get all students from the three teachers
        $students = User::where('role', 'student')
            ->whereIn('created_by_teacher_id', [53, 94, 115])
            ->get();

        $this->command->info("Found {$students->count()} students to seed data for");

        // Badge types and variations
        $badgeTypes = [
            ['type' => 'streak', 'name' => 'Week Warrior', 'icon' => '🔥', 'desc' => '7 day streak!'],
            ['type' => 'streak', 'name' => 'Super Streak', 'icon' => '⚡', 'desc' => '14 day streak!'],
            ['type' => 'score', 'name' => 'High Scorer', 'icon' => '⭐', 'desc' => 'Scored 1000+ points'],
            ['type' => 'achievement', 'name' => 'Level Master', 'icon' => '🎯', 'desc' => 'Completed 10 levels'],
            ['type' => 'achievement', 'name' => 'Game Champion', 'icon' => '👑', 'desc' => 'Completed 25 levels'],
        ];

        $studentIndex = 0;
        $batchSize = 10;
        $batches = $students->chunk($batchSize);
        
        foreach ($batches as $batchIndex => $batch) {
            $this->command->info("Processing batch " . ($batchIndex + 1) . " of " . $batches->count());
            
            foreach ($batch as $student) {
                $studentIndex++;
                
                // Create unique performance pattern for each student
                $performanceLevel = ($studentIndex % 5) + 1; // 1-5 scale
                
                // 1. GAME PROGRESS - 2 games per student
                $gamesToPlay = $games->random(2);
                
                foreach ($gamesToPlay as $game) {
                    $maxLevel = min($game->total_levels, 5 + ($performanceLevel * 2));
                    $currentLevel = rand(2, $maxLevel);
                    
                    // Generate level scores
                    $levelScores = [];
                    $totalScore = 0;
                    
                    for ($level = 1; $level <= $currentLevel; $level++) {
                        $baseScore = 50 + ($performanceLevel * 10);
                        $variance = rand(-15, 20);
                        $score = max(30, min(100, $baseScore + $variance));
                        
                        $levelScores[$level] = $score;
                        $totalScore += $score;
                    }
                    
                    StudentGameProgress::create([
                        'student_id' => $student->id,
                        'game_id' => $game->id,
                        'current_level' => $currentLevel,
                        'high_score' => $totalScore,
                        'level_scores' => $levelScores,
                    ]);
                }

                // 2. STUDENT ANALYTICS - Only 3 days
                $currentStreak = rand(1, 7);
                
                for ($day = 0; $day < 3; $day++) {
                    $date = Carbon::now()->subDays($day);
                    $gamesPlayedToday = $gamesToPlay->pluck('name')->toArray();
                    
                    StudentAnalytic::updateOrCreate(
                        [
                            'student_id' => $student->id,
                            'date' => $date->toDateString(),
                        ],
                        [
                            'points_earned' => rand(50, 300) * $performanceLevel,
                            'levels_completed' => rand(1, 3),
                            'stars_earned' => rand(1, 9),
                            'minutes_played' => rand(10, 45),
                            'games_played' => $gamesPlayedToday,
                            'total_score' => rand(100, 400) * $performanceLevel,
                            'current_streak' => $currentStreak,
                            'longest_streak' => $currentStreak + rand(1, 3),
                            'hour_of_day' => rand(14, 19),
                            'day_of_week' => $date->dayOfWeek,
                        ]
                    );
                }

                // 3. BADGES - 2-3 badges per student
                $numBadges = min($performanceLevel, 3);
                $selectedBadges = collect($badgeTypes)->random(min($numBadges, count($badgeTypes)));
                
                foreach ($selectedBadges as $badge) {
                    StudentBadge::create([
                        'student_id' => $student->id,
                        'badge_type' => $badge['type'],
                        'badge_name' => $badge['name'],
                        'badge_icon' => $badge['icon'],
                        'description' => $badge['desc'],
                        'badge_level' => rand(1, 2),
                        'value' => rand(50, 150),
                        'earned_at' => Carbon::now()->subDays(rand(1, 7)),
                    ]);
                }
            }
        }

        $this->command->info('✓ Successfully seeded progress data for all students!');
        
        // Show summary
        $this->command->newLine();
        $this->command->info('=== SUMMARY ===');
        $this->command->info('Total Students: ' . $students->count());
        $this->command->info('Game Progress Records: ' . StudentGameProgress::count());
        $this->command->info('Analytics Records: ' . StudentAnalytic::count());
        $this->command->info('Badges Awarded: ' . StudentBadge::count());
    }
}
