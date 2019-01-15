<?php

namespace App\Http\Controllers;

use Conner\Tagging\Model\Tag;
use Conner\Tagging\Model\Tagged;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function addTag(Request $request, $username) {
        /** @var \App\User $user */
        $user = User::where('username', $username)->first();
        $tag = $request->input('tag');
        if (! $tag) {
            throw new \Exception("Empty tag");
        }

        $user->tag($tag);

        return $user->tagsArray();
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
