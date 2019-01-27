<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Message;
use App\Notifications\MessageSentNotification;
use App\User;
use Auth;
use Illuminate\Http\Request;

class MessageController extends Controller
{

    public function send(Request $request)
    {
        $user = Auth::user();
        $toUser = User::find($request->input('to_user_id'));

        $message = Message::create([
            'user_id'    => $user->id,
            'message'    => $request->input('message'),
            'to_user_id' => $toUser->id,
        ]);

        $text = $user->name . " sent you a";
        $link = [
            'cta' => 'message',
            'name' => 'profile',
            'params' => [
                'username' => $user->username,
            ],
        ];

        $toUser->notify(new MessageSentNotification($message, $text, $link));
        broadcast(new MessageSent($message, $user))->toOthers();

        return $message;
    }

    public function list(Request $request)
    {
        return [
            [
                'id'      => 1,
                'message' => 'Test',
            ]
        ];
    }
}
