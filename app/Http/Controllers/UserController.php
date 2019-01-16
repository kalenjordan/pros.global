<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Tagged;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{

    public function list(Request $request)
    {
        $users = User::with('tags');
        if ($query = $request->input('query')) {
            $users->where('name', 'like', "%$query%");
        }

        return $users->get();
    }

    public function view(Request $request, $username)
    {
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
        /** @var \App\User $user */
        $user = User::where('username', $username)->first();
        $tag = $request->input('tag');
        if (!$tag) {
            throw new \Exception("Empty tag");
        }

        $user->tag($tag);
        $user->relations = [];

        return $user->tags();
    }

    public function deleteTag(Request $request, $username, $taggedId)
    {
        $user = User::findByUsername($username);
        $tagged = Tagged::find($taggedId);

        if ($tagged) {
            $tagged->delete();
            // $user->load('tags');
        }

        return $user->tags();
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
