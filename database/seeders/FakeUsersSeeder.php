<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Laravel\Jetstream\Jetstream;

class FakeUsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'ShadowPriest',
                'email' => 'shadowpriest@battlenet.local',
                'battlenet_id' => '123456789',
                'role' => 'dps1',
                'class' => 'Priest',
            ],
            [
                'name' => 'FrostMage',
                'email' => 'frostmage@battlenet.local',
                'battlenet_id' => '234567890',
                'role' => 'dps2',
                'class' => 'Mage',
            ],
            [
                'name' => 'ProtWarrior',
                'email' => 'protwarrior@battlenet.local',
                'battlenet_id' => '345678901',
                'role' => 'tank',
                'class' => 'Warrior',
            ],
            [
                'name' => 'RestoDruid',
                'email' => 'restodruid@battlenet.local',
                'battlenet_id' => '456789012',
                'role' => 'healer',
                'class' => 'Druid',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'battlenet_id' => $userData['battlenet_id'],
                'password' => bcrypt(Str::random(24)),
            ]);

            // Get the first team (usually the test user's team)
            $team = \App\Models\Team::first();

            if ($team) {
                // Add user to team with their role and class
                $team->users()->attach($user, ['role' => $userData['role'], 'class' => $userData['class']]);
            }
        }
    }
}
