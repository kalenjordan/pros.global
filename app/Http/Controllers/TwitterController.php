<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Illuminate\Http\Request;

class TwitterController extends Controller
{

    /**
     * @param $username
     *
     * @return array
     * @throws \Exception
     */
    public function addUser($username)
    {
        if (!Auth::user()) {
            return ['error_message' => 'Not logged in'];
        }

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
        $parts = explode('/', $imageUrl);
        $fileName = $parts[sizeof($parts) - 1];

        $img = public_path("avatars/$fileName");
        file_put_contents($img, file_get_contents($imageUrl));

        $user = User::create([
            'name'        => $twitter->name,
            'username'    => User::generateUniqueUsername($twitter->screen_name),
            'email'       => $twitter->screen_name . '@example.com',
            'avatar_path' => "/avatars/$fileName",
            'headline'    => $twitter->description ? $twitter->description : '(No description)',
            'password'    => md5(time() . env('APP_KEY')),
            'added_by'    => Auth::user()->id,
        ]);

        return $user->toArray();
    }
}
