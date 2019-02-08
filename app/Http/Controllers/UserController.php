<?php

namespace App\Http\Controllers;

use App\Notifications\BeenTagged;
use App\Notifications\BeenUpvoted;
use Auth;

use Illuminate\Http\Request;

use App\Tagged;
use App\UserSearch;
use App\User;

class UserController extends Controller
{

    public function list(Request $request)
    {
        $users = User::with('tags');
        $limit = $request->input('limit') ? $request->input('limit') : 10;

        $users->limit($limit)
            ->leftJoin("tagging_tagged_upvotes as upvotes", function ($join) {
                /** @var $join \Illuminate\Database\Query\JoinClause */
                $join->on("upvotes.tagged_user_id", '=', 'users.id');
            })->leftJoin("tagging_tagged as tagged", function ($join) {
                /** @var $join \Illuminate\Database\Query\JoinClause */
                $join->on("tagged.taggable_id", '=', 'users.id');
            })->select([
                'users.*',
            ])->groupBy('users.id');

        if ($query = $request->input('q')) {
            $search = new UserSearch($users);
            $search->query($query);
            //$count = $users->count();
            //$filters = $search->parseSearchFilters($query);
            //$users->skip($page * $limit); // todo pagination
        }

        if ($request->input('show_sql')) {
            echo \SqlFormatter::format($users->toSql());
            exit;
        }

        return $users->get();
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
        $loggedInUser = Auth::user();
        if (! $loggedInUser) {
            return ['error_message' => "Please login first before you can add a tag"];
        }

        $user = User::findByUsername($username);

        $tag = $request->input('tag');
        if (!$tag) {
            return ['error_message' => 'Empty tag'];
        }

        $user->tag($tag);
        $user->notify(new BeenTagged($loggedInUser->name . " tagged you with: " . $tag));

        return $user->tagged;
    }

    public function deleteTag(Request $request, $username, $taggedId)
    {
        $user = User::findByUsername($username);
        $tagged = Tagged::find($taggedId);

        if ($tagged) {
            try {
                $tagged->delete();
            } catch (\Exception $e) {
                return ['error_message' => $e->getMessage()];
            }

            $user->load('tagged');
        }

        return $user->tagged;
    }

    public function upvoteTag(Request $request, $username, $taggedId)
    {
        // todo check against auth'd user
        $loggedInUser = Auth::user();

        $user = User::findByUsername($username);
        $tagged = Tagged::find($taggedId);
        $upvote = $tagged->toggleUpvote();

        $user->notify(new BeenUpvoted($loggedInUser->name . " upvoted you for: " . $tagged->tag_name));

        return ['upvote' => $upvote, 'all_upvotes' => $tagged->taggedUser()->upvotes];
    }

    public function viewHtml($username)
    {
        $user = User::findByUsername($username);

        if ($user) {
            return view('user-profile', ['user' => $user,]);
        } else {
            return view('app');
        }
    }

    public function twitterCard($username)
    {
        $user = User::findByUsername($username);

        return view('twitter-user-profile', [
            'user' => $user,
        ]);
    }
}
