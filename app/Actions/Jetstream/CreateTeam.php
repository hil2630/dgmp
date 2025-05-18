<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\Events\AddingTeam;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Str;

class CreateTeam implements CreatesTeams
{
    /**
     * Validate and create a new team for the given user.
     *
     * @param  array<string, string>  $input
     */
    public function create(User $user, array $input): Team
    {
        Gate::forUser($user)->authorize('create', Jetstream::newTeamModel());

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'members' => ['required', 'array'],
            'members.tank.battlenet_tag' => ['required', 'string', 'max:255'],
            'members.healer.battlenet_tag' => ['required', 'string', 'max:255'],
            'members.dps1.battlenet_tag' => ['required', 'string', 'max:255'],
            'members.dps2.battlenet_tag' => ['required', 'string', 'max:255'],
            'members.dps3.battlenet_tag' => ['required', 'string', 'max:255'],
        ])->validateWithBag('createTeam');

        AddingTeam::dispatch($user);

        $team = $user->ownedTeams()->create([
            'name' => $input['name'],
            'personal_team' => false,
        ]);

        // Add team owner as a member
        $team->users()->attach($user->id, ['role' => 'owner']);

        // Process team members
        $members = $input['members'];
        foreach ($members as $role => $memberData) {
            if (!empty($memberData['battlenet_tag'])) {
                // Find or create user by battlenet tag
                $memberUser = User::firstOrCreate(
                    ['battlenet_id' => $memberData['battlenet_tag']],
                    [
                        'name' => explode('#', $memberData['battlenet_tag'])[0],
                        'email' => Str::random(10) . '@battlenet.local',
                        'password' => bcrypt(Str::random(24)),
                    ]
                );

                // Add member to team with their role
                $team->users()->attach($memberUser->id, ['role' => $role]);
            }
        }

        $user->switchTeam($team);

        return $team;
    }
}
