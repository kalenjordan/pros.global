<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Auth;
use Socialite;

use Illuminate\Http\Request;

class LinkedInController extends \App\Http\Controllers\Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function login()
    {
        return Socialite::with('linkedin')->redirect();
    }

    public function callback(Request $request)
    {
        $linkedinUser = Socialite::driver('linkedin')->user();
        $email = $linkedinUser->email;

        $user = Auth::user();
        if (! $user) {
            $user = User::findByEmail($email);
        }

        if (! $user) {
            $user = new User;
            $user->email = $email;
            $user->name = $linkedinUser->name;
            $user->password = md5(env('APP_KEY') . time());
            $user->username = preg_replace("/[^a-z0-9.]+/i", "", strtolower($user->name));
            $user->save();
        }

        if (! $user->avatar_path) {
            $user->avatar_path = $linkedinUser->avatar;
        }

        $user->linkedin_token = $linkedinUser->token;
        \Log::info("LinkedIn Auth: " . json_encode($linkedinUser, true));
        $user->save();

        Auth::login($user, true);

        return view('auth.linkedin-callback', [
            'user' => $user,
        ]);
    }
}