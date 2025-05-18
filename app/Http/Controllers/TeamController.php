<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Actions\Jetstream\UpdateTeamMemberRole;
use App\Models\User;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::with(['users', 'owner'])->get();

        return Inertia::render('Teams/Index', [
            'teams' => $teams,
        ]);
    }

    public function show(Team $team)
    {
        $this->authorize('view', $team);

        $team->load(['owner', 'users', 'teamInvitations']);
        $teamArr = $team->toArray();
        $teamArr['users'] = $team->users->map(function ($user) {
            $userArr = $user->toArray();
            $userArr['membership'] = [
                'role' => $user->pivot->role ?? null,
            ];
            return $userArr;
        });

        return Inertia::render('Teams/Show', [
            'team' => $teamArr,
            'availableRoles' => [
                ['key' => 'tank', 'name' => 'Tank'],
                ['key' => 'healer', 'name' => 'Healer'],
                ['key' => 'dps1', 'name' => 'DPS'],
                ['key' => 'dps2', 'name' => 'DPS'],
                ['key' => 'dps3', 'name' => 'DPS'],
            ],
            'permissions' => [
                'canAddTeamMembers' => $team->user_id === Auth::id(),
                'canUpdateTeam' => $team->user_id === Auth::id(),
                'canDeleteTeam' => $team->user_id === Auth::id(),
            ],
        ]);
    }

    public function updateMemberRole(Request $request, Team $team, User $user, UpdateTeamMemberRole $action)
    {
        $request->validate([
            'role' => ['required', 'string'],
        ]);
        $action->update($request->user(), $team, $user, $request->role);
        return redirect()->back(303);
    }
}
