<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Tag;
use App\Tagged;

class TagController extends Controller
{

    public function index(Request $request)
    {
        $query = Tag::where('id', '>', 0);
        if ($request->input('limit')) {
            $query->limit($request->input('limit'));
        }

        $tags = $query->get();
        return $tags->toArray();
    }

    public function view(Request $request, $slug)
    {
        $tag = Tag::findBySlug($slug);
        return $tag->toArray();
    }

    public function deleteTag(Request $request, $username, $tagSlug)
    {
        $user = User::findByUsername($username);
        $tagged = Tagged::findByUserIdAndSlug($user->id, $tagSlug);

        if ($tagged) {
            try {
                $tagged->delete();
            } catch (\Exception $e) {
                return ['error_message' => $e->getMessage()];
            }
        }

        return $user->tags;
    }

    public function viewHtml($slug)
    {
        $tag = Tag::findBySlug($slug);

        return view('tag', [
            'tag' => $tag,
        ]);
    }

    public function edit(Request $request, $slug)
    {
        $tag = Tag::findBySlug($slug);

        $tag->name = $request->input('name');
        $tag->slug = $request->input('slug');
        $tag->icon = $request->input('icon');

        $tag->save();

        return $tag;
    }
}
