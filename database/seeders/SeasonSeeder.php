<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Season;

class SeasonSeeder extends Seeder
{
    public function run()
    {
        Season::create([
            'name' => 'Season 1',
            'description' => 'The first season of DGMP tournaments',
            'status' => 'active',
            'starts_at' => now(),
            'end_date' => now()->addMonths(3),
            'time_limit_hours' => 4,
            'is_locked' => false
        ]);
    }
}
