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

    public function me(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $data = $user->toArray();
        $data['api_token'] = $user->api_token;

        return $data;
    }

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
            //$users->skip($page * $limit);
        }

        if ($request->input('show_sql')) {
            echo \SqlFormatter::format($users->toSql());
            exit;
        }

        return $users->get();
    }

    public function search(Request $request)
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
            //$users->skip($page * $limit);
        }

        if ($request->input('show_sql')) {
            echo \SqlFormatter::format($users->toSql());
            exit;
        }

        return view('search', [
            'users' => $users,
            'query' => $query,
        ]);
    }

    public function view(Request $request, $username)
    {
        if ($request->input('api_token')) {
            if ($user = User::findByApiToken($request->input('api_token'))) {
                Auth::login(User::findByApiToken($request->input('api_token')));
            }
        }

        $user = User::with(['tags', 'upvotes', 'posts'])->where('username', $username)->first();
        if (!$user) {
            return ['error_message' => "User not found"];
        }

        return $user->toArray();
    }

    public function post(Request $request, $username)
    {
        $user = User::findByUsername($username);
        $content = json_decode($request->getContent(), true);
        $data = $content['data'];

        $user->about = $data['about'];
        $user->headline = $data['headline'];
        if ($user->avatar_path != $data['avatar_path']) {
            $user->avatar_path = $user->downloadAndSave($data['avatar_path']);
        }
        $user->name = $data['name'];

        if ($data['twitter_username']) {
            if (substr($data['twitter_username'], 0, 1) == '@') {
                $data['twitter_username'] = substr($data['twitter_username'], 1, strlen($data['twitter_username']) - 1);
            }
        }
        $user->twitter_username = $data['twitter_username'];

        $user->save();

        return $user->toArray();
    }

    public function addTag(Request $request, $username)
    {
        $loggedInUser = Auth::user();
        if (!$loggedInUser) {
            return ['error_message' => "Please login first before you can add a tag"];
        }

        $user = User::findByUsername($username);

        $tag = $request->input('tag');
        if (!$tag) {
            return ['error_message' => 'Empty tag'];
        }

        $user->tag($tag);
        if ($loggedInUser->id != $user->id) {
            $user->notify(new BeenTagged($loggedInUser, $loggedInUser->name . " tagged you with: " . $tag));
        }

        $user->load('tagged');
        return $user->tagged()->get();
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

    public function upvoteTag($username, $taggedId)
    {
        $loggedInUser = Auth::user();

        $user = User::findByUsername($username);
        $tagged = Tagged::find($taggedId);
        $upvote = $tagged->toggleUpvote();

        $user->notify(new BeenUpvoted($loggedInUser, $loggedInUser->name . " upvoted you for: " . $tagged->tag_name));

        return ['upvote' => $upvote, 'all_upvotes' => $tagged->taggedUser()->upvotes];
    }

    public function profile($username)
    {
        $user = User::findByUsername($username);
        if (!$user) {
            return abort(404);
        }

        return view('user-profile', [
            'user' => $user,
            'footerSavedSearches'
        ]);
    }

    public function twitterCard($username)
    {
        $user = User::findByUsername($username);

        return view('twitter-cards.user-profile', [
            'user' => $user,
        ]);
    }

    /**
     * @param $username
     * @param $mergedUsername
     *
     * @return array
     * @throws \Exception
     */
    public function merge($username, $mergedUsername)
    {
        $user = User::findByUsername($username);
        $mergingUser = User::findByUsername($mergedUsername);

        if ($user->linkedin_token) {
            return ['success' => false, 'message' => "User already has a linkedin token, not going to merge"];
        }

        if (!$user->linkedin_token && $mergingUser->linkedin_token) {
            $user->name = $mergingUser->name;
            $user->linkedin_token = $mergingUser->linkedin_token;
            $user->email = $mergingUser->email;
            $user->last_online_at = $mergingUser->last_online_at;

            $mergingUser->email = $mergingUser->email . "-deleting";
            $mergingUser->save();

            $user->save();
            $mergingUser->delete();
            return ['success' => true, 'message' => "Merged"];
        }

        return ['success' => false, 'message' => "Problem merging"];
    }

    /**
     * @param Request $request
     *
     * @return array
     * @throws \Exception
     */
    public function newUser(Request $request)
    {
        if (!Auth::user()) {
            return ['error_message' => 'Not logged in'];
        }

        if (!Auth::user()->is_admin) {
            return ['error_message' => 'Access denied'];
        }

        $name = $request->input('name');
        if (!$name) {
            return ['error_message' => 'Missing name'];
        }
        $user = User::findByName($name);
        if ($user) {
            return ['success' => false, 'message' => "User already exists by name: $name"];
        }

        $username = User::generateUniqueUsername($name);
        $user = User::create([
            'name'     => $name,
            'username' => $username,
            'email'    => $username . '@example.com',
            'headline' => $name . " is a pro who hasn't updated their headline yet",
            'password' => md5(time() . env('APP_KEY')),
            'added_by' => Auth::user()->id,
        ]);

        return $user->toArray();
    }
}
