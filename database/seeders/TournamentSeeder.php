<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tournament;
use App\Models\Season;

class TournamentSeeder extends Seeder
{
    public function run()
    {
        $season = Season::where('status', 'active')->first();

        if (!$season) {
            return;
        }

        // Create main tournament
        Tournament::create([
            'season_id' => $season->id,
            'name' => 'Season 1 Main Tournament',
            'type' => 'main',
            'date' => now()->addDays(30),
            'prize_pool' => 500000,
            'description' => 'Main tournament for Season 1',
            'status' => 'active',
            'distribution' => [
                'first' => 250000,
                'second' => 150000,
                'third' => 100000
            ]
        ]);

        // Create on-day tournaments
        $onDayTournaments = [
            [
                'name' => 'Fastest Key',
                'date' => now()->addDays(7),
                'prize_pool' => 200000,
                'description' => 'Complete your key in the fastest time possible'
            ],
            [
                'name' => 'Perfect Run',
                'date' => now()->addDays(7),
                'prize_pool' => 150000,
                'description' => 'Complete your key with no deaths and perfect mechanics'
            ],
            [
                'name' => 'Community Choice',
                'date' => now()->addDays(7),
                'prize_pool' => 100000,
                'description' => 'Voted by the community for the most impressive run'
            ]
        ];

        foreach ($onDayTournaments as $tournament) {
            Tournament::create([
                'season_id' => $season->id,
                'name' => $tournament['name'],
                'type' => 'on_day',
                'date' => $tournament['date'],
                'prize_pool' => $tournament['prize_pool'],
                'description' => $tournament['description'],
                'status' => 'upcoming'
            ]);
        }
    }
}
