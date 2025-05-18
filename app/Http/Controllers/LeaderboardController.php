<?php

namespace App\Http\Controllers;

use App\Models\Bracket;
use App\Models\Run;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeaderboardController extends Controller
{
    public function index(Request $request, ?Bracket $bracket = null)
    {
        $query = $bracket
            ? $bracket->runs()
            : Run::query();

        $runs = $query
            ->with('team')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('dungeon_name', 'like', "%{$search}%")
                        ->orWhereHas('team', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy(
                $request->input('sort_field', 'key_level'),
                $request->input('sort_direction', 'desc')
            )
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Leaderboard', [
            'bracket' => $bracket,
            'brackets' => Bracket::all(),
            'runs' => $runs,
        ]);
    }
}
