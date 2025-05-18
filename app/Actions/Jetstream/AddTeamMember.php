<?php

namespace App\Actions\Jetstream;

use App\Models\User;
use Closure;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\InvitesTeamMembers;
use Laravel\Jetstream\Events\InvitingTeamMember;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Mail\TeamInvitation;

class AddTeamMember implements InvitesTeamMembers
{
    /**
     * Invite a new team member to the given team.
     */
    public function invite(User $user, $team, string $battlenetTag, string $role = null): void
    {
        Gate::forUser($user)->authorize('addTeamMember', $team);

        $this->validate($team, $battlenetTag, $role);

        InvitingTeamMember::dispatch($team, $battlenetTag, $role);

        // Find user by Battle.net tag
        $newTeamMember = User::where('battlenet_id', $battlenetTag)->first();

        if (!$newTeamMember) {
            // If user not found, create an invitation
            $team->teamInvitations()->create([
                'battlenet_tag' => $battlenetTag,
                'role' => $role,
            ]);

            return;
        }

        $team->users()->attach(
            $newTeamMember, ['role' => $role]
        );

        // Set current team if user doesn't have one
        if (!$newTeamMember->current_team_id) {
            $newTeamMember->forceFill([
                'current_team_id' => $team->id,
            ])->save();
        }
    }

    /**
     * Validate the invite member operation.
     */
    protected function validate($team, string $battlenetTag, ?string $role): void
    {
        Validator::make([
            'battlenet_tag' => $battlenetTag,
            'role' => $role,
        ], $this->rules(), [
            'battlenet_tag.required' => 'The Battle.net tag is required.',
            'battlenet_tag.unique' => 'This user is already a member of the team.',
            'role.required' => 'The role is required.',
            'role.in' => 'The role must be one of: tank, healer, dps1, dps2, dps3.',
        ])->validateWithBag('addTeamMember');
    }

    /**
     * Get the validation rules for inviting a team member.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    protected function rules(): array
    {
        return array_filter([
            'battlenet_tag' => ['required', 'string'],
            'role' => Jetstream::hasRoles()
                            ? ['required', 'string']
                            : null,
        ]);
    }
}
