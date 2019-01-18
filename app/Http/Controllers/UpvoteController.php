<?php

namespace App\Http\Controllers;

use App\TaggedUpvote;
use Illuminate\Http\Request;
use App\User;
use App\Tag;
use App\Tagged;

class UpvoteController extends Controller
{
    public function post(Request $request, $id) {
        $upvote = TaggedUpvote::with('user')->find($id);
        $upvote->message = $request->input('message');
        $upvote->save();

        return $upvote->tagged_user->upvotes;
    }

    public function view(Request $request, $id) {
        $upvote = TaggedUpvote::with('user')->find($id);

        return $upvote->toArray();
    }
}
