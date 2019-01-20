<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class TwitterController extends Controller
{
    public function addUser(Request $request, $username)
    {
        $twitter = \Thujohn\Twitter\Facades\Twitter::get('/users/show.json', [
            'screen_name' => $username
        ]);

        $user = User::findByName($twitter->name);
        if ($user) {
            return ['success' => false, 'message' => "User already exists by name: " . $twitter->name];
        }

        // The regular image it gives you is tiny
        $imageUrl = $twitter->profile_image_url;
        $imageUrl = str_replace('_normal', '_400x400', $imageUrl);

        $user = User::create([
            'name'        => $twitter->name,
            'username'    => $twitter->screen_name,
            'email'       => $twitter->screen_name . '@example.com',
            'avatar_path' => $imageUrl,
            'headline'    => $twitter->description ? $twitter->description : '(No description)',
            'password'    => md5(time() . env('APP_KEY')),
        ]);

        return $user->toArray();
    }
}
