<?php

namespace App\Http\Controllers;

use App\Models\Season;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class SeasonController extends Controller
{
    public function index()
    {
        $seasons = Season::with(['teams', 'runs', 'winner'])
            ->orderBy('starts_at', 'desc')
            ->get();

        return Inertia::render('Seasons/Index', [
            'seasons' => $seasons,
        ]);
    }

    public function show(Season $season)
    {
        $season->load(['teams', 'runs', 'winner']);

        $user = \Illuminate\Support\Facades\Auth::user();
        $team = null;
        if ($user && $user->currentTeam) {
            $user->currentTeam->load('owner');
            $team = $user->currentTeam->toArray();
        }

        return Inertia::render('Seasons/Show', [
            'season' => $season,
            'leaderboard' => $season->teams()
                ->withCount(['runs' => function ($query) use ($season) {
                    $query->where('season_id', $season->id);
                }])
                ->orderByDesc('runs_count')
                ->get(),
            'team' => $team,
        ]);
    }

    public function signup(Season $season)
    {
        $user = Auth::user();
        $team = $user->currentTeam;
        if (!$team) {
            return redirect()->back()->withErrors(['team' => 'You do not have a team.']);
        }
        if ($team->owner->id !== $user->id) {
            return redirect()->back()->withErrors(['team' => 'Only the team owner can sign up.']);
        }
        if ($season->teams()->where('team_id', $team->id)->exists()) {
            return redirect()->back()->withErrors(['team' => 'Your team is already signed up for this season.']);
        }
        $season->teams()->attach($team->id);
        return redirect()->back()->with('success', 'Team signed up for the season!');
    }

    public function removeSignup(Season $season)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $team = $user->currentTeam;
        if (!$team) {
            return redirect()->back()->withErrors(['team' => 'You do not have a team.']);
        }
        if ($team->owner->id !== $user->id) {
            return redirect()->back()->withErrors(['team' => 'Only the team owner can remove the signup.']);
        }
        if (!$season->teams()->where('team_id', $team->id)->exists()) {
            return redirect()->back()->withErrors(['team' => 'Your team is not signed up for this season.']);
        }
        $season->teams()->detach($team->id);
        return redirect()->back()->with('success', 'Team signup removed from the season!');
    }
}
