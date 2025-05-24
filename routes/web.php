<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\Auth\BattleNetController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\SeasonController;
use App\Models\Team;
use App\Models\Season;
use App\Models\Donation;
use App\Models\Tournament;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\RunController;

Route::get('/', function () {
    // Get current active season
    $currentSeason = Season::where('status', 'active')->first();

    $totalPrizePool = 0;

    // Get total donations for prize pool (tournament prize pools are used for something else)
    try {
        $totalPrizePool = Donation::sum('amount');
    } catch (\Exception $e) {
        // If donations table doesn't exist, keep at 0
        $totalPrizePool = 0;
    }

    return Inertia::render('Welcome', [
        'canLogin' => false,
        'canRegister' => false,
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'seasons' => Season::where('status', 'active')->get(),
        'totalPrizePool' => $totalPrizePool,
    ]);
});

Route::get('/donations', [DonationController::class, 'index'])->name('donations');
Route::post('/donations', [DonationController::class, 'store'])->middleware(['auth'])->name('donations.store');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $team = $user->currentTeam;

        $teamData = null;
        if ($team) {
            $team->load('users');
            $teamData = $team->toArray();
            $teamData['users'] = $team->users->map(function ($user) {
                $userArr = $user->toArray();
                $userArr['membership'] = [
                    'role' => $user->pivot->role ?? null,
                    'class' => $user->pivot->class ?? null,
                ];
                return $userArr;
            });
        }

        return Inertia::render('Dashboard', [
            'team' => $teamData,
            'seasons' => Season::where('status', 'active')->get(),
            'tournaments' => [], // We'll add this back when we create the Tournament model
        ]);
    })->name('dashboard');

    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');

    Route::get('/seasons', [SeasonController::class, 'index'])->name('seasons.index');
    Route::get('/seasons/{season}', [SeasonController::class, 'show'])->name('seasons.show');

    Route::post('/seasons/{season}/signup', [SeasonController::class, 'signup'])->name('seasons.signup');
    Route::post('/seasons/{season}/remove-signup', [SeasonController::class, 'removeSignup'])->name('seasons.remove-signup');

    Route::post('/runs', [RunController::class, 'store'])->name('runs.store');

    Route::put('/teams/{team}/members/{user}', [\App\Http\Controllers\TeamController::class, 'updateMemberRole'])->name('team-members.update');
});

Route::get('login/battlenet', [BattleNetController::class, 'redirectToProvider'])->name('login.battlenet');
Route::get('login/battlenet/callback', [BattleNetController::class, 'handleProviderCallback']);
