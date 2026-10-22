<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;

class GamesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Math Card Match game
        Game::updateOrCreate(
            ['name' => 'math_card_match'],
            [
                'display_name' => 'Math Card Match',
                'description' => 'Flip cards to match word problems with their correct answers. Test your memory and math skills — match all pairs to complete the level!',
                'total_levels' => 15,
            ]
        );

        // Create Number Wars game
        Game::updateOrCreate(
            ['name' => 'number_wars'],
            [
                'display_name' => 'Number Wars',
                'description' => 'Type the correct answer to shoot falling math problems. Defeat enemies before they reach the bottom!',
                'total_levels' => 15,
            ]
        );

        // Create Rocket Math game
        Game::updateOrCreate(
            ['name' => 'rocket_math'],
            [
                'display_name' => 'Rocket Fuel Loader',
                'description' => 'Solve math word problems to fuel your rocket! Select fuel canisters that add up to the correct answer and launch to space!',
                'total_levels' => 15,
            ]
        );

        // Create Factor & Multiples Pond game
        Game::updateOrCreate(
            ['name' => 'factor_pond'],
            [
                'display_name' => 'Factor & Multiples Pond',
                'description' => 'Catch all the fish that are factors or multiples of the target number! Click the right fish and cast your net to score.',
                'total_levels' => 15,
            ]
        );

        // Create Time Quest game
        Game::updateOrCreate(
            ['name' => 'time_quest'],
            [
                'display_name' => 'Time Quest',
                'description' => 'Explore the world map and solve time zone puzzles! Calculate the correct time across different cities to complete your mission.',
                'total_levels' => 15,
            ]
        );

        // Create MDAS Maze game
        Game::updateOrCreate(
            ['name' => 'mdas_maze'],
            [
                'display_name' => 'MDAS Maze',
                'description' => 'Roll the dice, race through the maze, and master the MDAS Order of Operations: Multiplication, Division, Addition, and Subtraction!',
                'total_levels' => 10,
            ]
        );
        // Create The Metric Quest game
        Game::updateOrCreate(
            ['name' => 'the_metric_quest'],
            [
                'display_name' => 'The Metric Quest',
                'description' => 'A Time Traveler\'s Journey! Convert metric units, calculate time, and master elapsed time to complete your adventure.',
                'total_levels' => 50,
            ]
        );

        // Create Geometry Grid game
        Game::updateOrCreate(
            ['name' => 'geometry_grid'],
            [
                'display_name' => 'Geometry Grid Explorer',
                'description' => 'Count squares and use formulas to find the area of parallelograms, triangles, and trapezoids!',
                'total_levels' => 15,
            ]
        );

    }
}