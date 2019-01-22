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

    public function viewHtml(Request $request, $id) {
        $upvote = TaggedUpvote::with('user')->find($id);

        return view('upvote', [
            'upvote' => $upvote->toArray(),
        ]);
    }

    public function twitterCardImage(Request $request, $id) {
        $upvote = TaggedUpvote::with('user')->find($id);

        $phantomJsBin = env('PHANTOMJS_PATH');
        if (! $phantomJsBin) {
            throw new \Exception("Phantom JS path not defined");
        }

        $url = url("upvotes/{$id}/twitter-card-html");
        $fileName = "upvote_" . $id . ".png";
        $process = new Process("cd .. && $phantomJsBin capture-twitter-card.js $url $fileName");
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }


        return response()->file(public_path("img/generated/$fileName"));
    }

    public function twitterCardHtml(Request $request, $id) {
        $upvote = TaggedUpvote::with('user')->find($id);

        return view('twitter-upvote-card', [
            'upvote' => $upvote->toArray(),
        ]);
    }
}
