<?php

namespace App\Http\Controllers;

use Auth;

use Illuminate\Http\Request;

use App\Tagged;
use App\UserSearch;
use App\User;

class AdminController extends Controller
{

    public function impersonate(Request $request, $username)
    {
        if (! Auth::user() || ! Auth::user()->is_admin) {
            die('Not authorized');
        }

        if (! $user = User::findByUsername($username) ) {
            die('User not found: ' . $username);
        }

        Auth::user()->impersonate($user);

        return redirect('/');
    }

    public function impersonateLeave(Request $request) {
        Auth::user()->leaveImpersonation();
        return redirect('/');
    }

    public function view(Request $request, $username)
    {
        if ($request->input('api_token')) {
            if ($user = User::findByApiToken($request->input('api_token'))) {
                Auth::login(User::findByApiToken($request->input('api_token')));
            }
        }

        $user = User::with(['tags', 'upvotes'])->where('username', $username)->first();
        return $user->toArray();
    }

    public function post(Request $request, $username)
    {
        $user = User::findByUsername($username);
        $content = json_decode($request->getContent(), true);
        $data = $content['data'];
        $user->about = $data['about'];
        $user->headline = $data['headline'];
        $user->save();

        return $user->toArray();
    }

    public function addTag(Request $request, $username)
    {
        $user = User::findByUsername($username);
        $tag = $request->input('tag');
        if (!$tag) {
            throw new \Exception("Empty tag");
        }

        $user->tag($tag);

        return $user->tagged;
    }

    public function deleteTag(Request $request, $username, $taggedId)
    {
        $user = User::findByUsername($username);
        $tagged = Tagged::find($taggedId);

        if ($tagged) {
            $tagged->delete();
            $user->load('tagged');
        }

        return $user->tagged;
    }

    public function upvoteTag(Request $request, $username, $taggedId)
    {
        // todo check against auth'd user
        $user = User::findByUsername($username);
        $tagged = Tagged::find($taggedId);
        $upvote = $tagged->toggleUpvote();

        return $upvote;
    }
}
