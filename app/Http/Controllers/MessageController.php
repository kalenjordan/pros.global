<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Events\MessageSentNotificationEvent;
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

        $text = $user->name . " sent you a ";
        $link = [
            'cta'    => 'message',
            'link'   => $user->url() . '?messages=1',
        ];

        $notification = new MessageSentNotification($message, $text, $link);
        $toUser->notify($notification);
        broadcast(new MessageSentNotificationEvent($notification, $toUser))->toOthers();
        broadcast(new MessageSent($message, $user))->toOthers();

        return $message;
    }

    public function withOtherUser(Request $request, $otherUserId)
    {
        $user = Auth::user();

        $messages = Message::where('id', '>', 0);
        $messages->whereNested(function ($model) use ($user, $otherUserId) {
            /** @var $model \Illuminate\Database\Query\Builder */
            $model->where('user_id', $user->id);
            $model->where('to_user_id', $otherUserId);
        });
        $messages->orWhere(function ($model) use ($user, $otherUserId) {
            /** @var $model \Illuminate\Database\Query\Builder */
            $model->where('user_id', $otherUserId);
            $model->where('to_user_id', $user->id);
        });
        $messages->orderBy('created_at', 'asc');

        $data = [];
        foreach ($messages->get() as $message) {
            /** @var Message $message */
            $data[] = [
                'id'     => $message->id,
                'type'   => 'text',
                'author' => ($message->user_id == $user->id ? 'me' : $message->author->username),
                'data'   => [
                    'text' => $message->message,
                ]
            ];
        }

        return $data;
    }
}
