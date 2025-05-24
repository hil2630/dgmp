<?php

namespace App\Http\Controllers;

use App\Models\Run;
use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RunController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'season_id' => 'required|exists:seasons,id',
            'dungeon_name' => 'required|string|in:' . implode(',', Run::DUNGEONS),
            'key_level' => 'required|integer|min:1',
            'time_taken_seconds' => 'required|integer|min:1',
            'warcraft_log_url' => 'nullable|url',
            'status' => 'required|string|in:completed,failed,in_progress',
        ]);

        $user = Auth::user();
        $team = $user->currentTeam;

        // Only team owners can register runs
        if (!$team || $user->id !== $team->user_id) {
            return back()->withErrors(['error' => 'Only team owners can register runs.']);
        }

        $run = Run::create([
            'team_id' => $team->id,
            'season_id' => $validated['season_id'],
            'dungeon_name' => $validated['dungeon_name'],
            'key_level' => $validated['key_level'],
            'time_taken_seconds' => $validated['time_taken_seconds'],
            'warcraft_log_url' => $validated['warcraft_log_url'],
            'status' => $validated['status'],
            'approval_status' => 'pending', // All runs start as pending
        ]);

        return back()->with('success', 'Run registered successfully! It will be reviewed by administrators.');
    }
}
