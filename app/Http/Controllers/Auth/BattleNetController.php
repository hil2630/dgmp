<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;

class BattleNetController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('battlenet')->redirect();
    }

    public function handleProviderCallback()
    {
        $battlenetUser = Socialite::driver('battlenet')->user();
        $email = $battlenetUser->getEmail() ?? ($battlenetUser->getId() . '@battlenet.local');

        // First try to find user by battlenet_id
        $user = User::where('battlenet_id', $battlenetUser->getId())->first();

        // If not found by battlenet_id, try to find by email
        if (!$user) {
            $user = User::where('email', $email)->first();
        }

        // If still not found, create new user
        if (!$user) {
            $user = new User();
            $user->battlenet_id = $battlenetUser->getId();
            $user->password = bcrypt(Str::random(24));
        }

        $user->name = $battlenetUser->getNickname() ?? $battlenetUser->getName();
        $user->email = $email;
        $user->save();

        // Get the user's teams
        $teams = $user->teams;

        // If user has teams but no current team, set it
        if ($teams->isNotEmpty() && !$user->current_team_id) {
            $firstTeam = $teams->first();
            $user->current_team_id = $firstTeam->id;
            $user->save();

            // Force refresh the user model
            $user->refresh();
        }

        Auth::login($user, true);

        return redirect()->intended('/dashboard');
    }
}
