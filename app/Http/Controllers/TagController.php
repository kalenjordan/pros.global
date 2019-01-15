<?php

namespace App\Http\Controllers;

use Conner\Tagging\Model\Tag;
use Conner\Tagging\Model\Tagged;
use Illuminate\Http\Request;
use App\User;

class TagController extends Controller
{
    public function index(Request $request) {
        $query = Tag::where('id', '>', 1);
        if ($request->input('limit')) {
            $query->limit($request->input('limit'));
        }

        $tags = $query->get();
        foreach ($tags as & $tag) {
            $tag->users = User::withAllTags([$tag->slug])->get();
        }

        return $tags->toArray();
    }

    public function view(Request $request, $slug) {
        /** @var Tag $tags */
        $tag = Tag::where('slug', $slug)->first();
        $tag->users = User::withAllTags([$tag->slug])->get();

        return $tag->toArray();
    }

    public function deleteTag(Request $request, $username, $tagSlug) {
        /** @var \App\User $user */
        $user = User::where('username', $username)->first();

        /** @var Tagged $tagged */
        $tagged = Tagged::where('taggable_id', $user->id)
            ->where('tag_slug', $tagSlug)
            ->first();

        if ($tagged) {
            $tagged->delete();
        }

        return $user->tagsArray();
    }
}
