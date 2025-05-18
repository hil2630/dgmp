<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class UpdateTeamMemberRole
{
    /**
     * Update the given team member's role.
     */
    public function update(User $user, Team $team, User $teamMember, string $role): void
    {
        Gate::forUser($user)->authorize('updateTeamMember', $team);

        Validator::make([
            'role' => $role,
        ], [
            'role' => ['required', 'string'], // No 'in:' validation, allow any number of each role
        ])->validateWithBag('updateTeamMemberRole');

        $team->users()->updateExistingPivot($teamMember->id, [
            'role' => $role,
        ]);
    }
}
