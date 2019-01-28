<?php

namespace App\Http\Controllers;

use App\TaggedUpvote;
use Illuminate\Http\Request;
use App\User;
use App\Tag;
use App\Tagged;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class UpvoteController extends Controller
{

    public function post(Request $request, $id)
    {
        $upvote = TaggedUpvote::with('user')->find($id);
        $upvote->message = $request->input('message');
        $upvote->save();

        return $upvote->tagged_user->upvotes;
    }

    public function view($id)
    {
        $upvote = TaggedUpvote::with('user')->find($id);

        return $upvote->toArray();
    }

    public function viewHtml($id)
    {
        $upvote = TaggedUpvote::with('user')->find($id);

        return view('upvote', [
            'upvote' => $upvote->toArray(),
        ]);
    }
    
    public function twitterCard($id)
    {
        $upvote = TaggedUpvote::with('user')->find($id);

        return view('twitter-upvote-card', [
            'upvote' => $upvote->toArray(),
        ]);
    }
}
