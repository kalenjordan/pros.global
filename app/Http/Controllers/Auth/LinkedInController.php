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
            $user->name = data_get($linkedinUser, 'user.formattedName');;
            $user->save();
        }

        // todo add linkedin url
        //if (! $user->linkedin_url) {
        //    $user->linkedin_url = data_get($linkedinUser, 'user.publicProfileUrl');
        //}

        if (! $user->avatar_path) {
            $user->avatar_path = data_get($linkedinUser, 'user.pictureUrl');
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