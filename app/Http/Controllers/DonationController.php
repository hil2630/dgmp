<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Tournament;
use App\Models\Season;
use App\Http\Resources\TournamentResource;
use App\Http\Resources\DonationResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DonationController extends Controller
{
    public function index()
    {
        $currentSeason = Season::where('status', 'active')->first();

        if (!$currentSeason) {
            return Inertia::render('Donations', [
                'currentSeasonPot' => [
                    'total' => 0,
                    'season' => 'No Active Season',
                    'mainTournament' => [
                        'total' => 0,
                        'distribution' => [
                            'first' => 0,
                            'second' => 0,
                            'third' => 0,
                        ]
                    ],
                    'onDayTournaments' => [
                        'total' => 0,
                        'upcomingTournaments' => []
                    ]
                ],
                'topDonators' => []
            ]);
        }

        // Get main tournament
        $mainTournament = Tournament::where('season_id', $currentSeason->id)
            ->where('type', 'main')
            ->first();

        // Get on-day tournaments
        $onDayTournaments = Tournament::where('season_id', $currentSeason->id)
            ->where('type', 'on_day')
            ->where('status', 'upcoming')
            ->get();

        // Calculate total prize pool
        $totalPrizePool = $mainTournament ? $mainTournament->prize_pool : 0;
        $totalPrizePool += $onDayTournaments->sum('prize_pool');

        // Get top donators
        $topDonators = Donation::with('user')
            ->select('user_id', 'guild', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('user_id', 'guild')
            ->orderByDesc('total_amount')
            ->limit(5)
            ->get()
            ->map(function ($donation) {
                return [
                    'name' => $donation->is_anonymous ? 'Anonymous' : $donation->user->name,
                    'amount' => $donation->total_amount,
                    'guild' => $donation->guild
                ];
            });

        return Inertia::render('Donations', [
            'currentSeasonPot' => [
                'total' => $totalPrizePool,
                'season' => $currentSeason->name,
                'mainTournament' => [
                    'total' => $mainTournament ? $mainTournament->prize_pool : 0,
                    'distribution' => $mainTournament ? $mainTournament->distribution : [
                        'first' => 0,
                        'second' => 0,
                        'third' => 0,
                    ]
                ],
                'onDayTournaments' => [
                    'total' => $onDayTournaments->sum('prize_pool'),
                    'upcomingTournaments' => TournamentResource::collection($onDayTournaments)
                ]
            ],
            'topDonators' => $topDonators
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|integer|min:1',
            'guild' => 'nullable|string|max:255',
            'is_anonymous' => 'boolean'
        ]);

        $donation = Donation::create([
            'user_id' => $request->user()->id,
            'amount' => $validated['amount'],
            'guild' => $validated['guild'],
            'is_anonymous' => $validated['is_anonymous'] ?? false
        ]);

        return back()->with('success', 'Thank you for your donation!');
    }
}
