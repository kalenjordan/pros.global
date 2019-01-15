<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Tag;
use App\Tagged;

class TagController extends Controller
{
    public function index(Request $request) {
        $query = Tag::where('id', '>', 1);
        if ($request->input('limit')) {
            $query->limit($request->input('limit'));
        }

        $tags = $query->get();
        return $tags->toArray();
    }

    public function view(Request $request, $slug) {
        $tag = Tag::findBySlug($slug);
        return $tag->toArray();
    }

    public function deleteTag(Request $request, $username, $tagSlug) {
        $user = User::findByUsername($username);
        $tagged = Tagged::findByUserIdAndSlug($user->id, $tagSlug);

        if ($tagged) {
            $tagged->delete();
        }

        return $user->tagsArray();
    }
}
