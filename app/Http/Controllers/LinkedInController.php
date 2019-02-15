<?php

namespace App\Http\Controllers;

use App\Date;
use App\Image;
use App\User;
use Auth;
use Laravel\Socialite\Two\InvalidStateException;
use Socialite;

use Illuminate\Http\Request;

class LinkedInController extends Controller
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

    /**
     * @param Request $request
     *
     * @throws \Exception
     */
    public function callback(Request $request)
    {
        try {
            $linkedinUser = Socialite::driver('linkedin')->user();
        } catch (InvalidStateException $e) {
            return "Invalid state exception";
        }

        $email = $linkedinUser->email;

        $user = Auth::user();
        if (! $user) {
            $user = User::findByEmail($email);
            if (! $user) {
                $user = User::findStubbedUserByName($linkedinUser->name);
                if ($user) {
                    $user->email = $linkedinUser->email;
                }
            }
        }

        if (! $user) {
            $user = new User;
            $user->email = $email;
            $user->name = $linkedinUser->name;
            $user->password = md5(env('APP_KEY') . time());
            $user->username = User::generateUniqueUsername($user->name);
            $user->headline = $user->name . " is a pro who hasn't updated their headline yet";
            //$user->about = "Click here to edit";
            $user->save();
        }

        $imageUrl = $linkedinUser->avatar;
        if (! $user->avatar_path && $imageUrl) {
            $user->avatar_path = $user->downloadAndSave($imageUrl);
        }

        $user->linkedin_token = $linkedinUser->token;
        \Log::info("LinkedIn Auth: " . json_encode($linkedinUser, true));
        $user->save();

        $user->last_online_at = Date::now();
        Auth::login($user, true);

        return view('auth.linkedin-callback', [
            'user' => $user,
        ]);
    }

    public function me(Request $request) {
        $user = Auth::user();
        if (! $user) {
            return ['error_message' => 'Not logged in'];
        }

        return $user->toArrayForCookie();
    }

    public function logout(Request $request) {
        Auth::logout();

        return response(['success' => 1]);
    }
}