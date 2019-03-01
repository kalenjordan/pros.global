<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function view($username, $slugOrId)
    {
        $post = Post::findBySlugOrId($slugOrId);
        if (!$post) {
            abort(404);
        }

        $author = $post->user;
        $user = User::findByUsername($username);
        if ($user->id != $author->id) {
            abort(404);
        }

        return view('post', [
            'post'   => $post,
            'author' => $author,
        ]);
    }

    public function twitterCard($username, $slugOrId)
    {
        $post = Post::findBySlugOrId($slugOrId);
        if (!$post) {
            abort(404);
        }

        $author = $post->user;
        $user = User::findByUsername($username);
        if ($user->id != $author->id) {
            abort(404);
        }

        return view('twitter-cards.post', [
            'post'   => $post,
            'author' => $author,
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function newPost()
    {
        if (! Auth::user()) {
            throw new \Exception("Not logged in");
        }

        $post = Post::create([
            'user_id' => Auth::user()->id,
        ]);

        return redirect($post->url());
    }

    public function save(Request $request, $postId)
    {
        $post = Post::find($postId);
        if (! $post) {
            return ['error_message' => "Post not found: $postId"];
        }

        $content = json_decode($request->getContent(), true);
        $data = $content['data'];

        $post->title = $data['title'];
        $post->body = $data['body'];
        $post->published_at = $data['published_at'];

        if (! $data['slug']) {
            $title = str_replace(" ", "-", $post->title);
            $post->slug = preg_replace("/[^a-z0-9.-]+/i", "", strtolower($title));
        } else {
            $post->slug = $data['slug'];
        }

        $post->save();

        return $post->toArray();
    }
}
