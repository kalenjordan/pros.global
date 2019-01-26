<?php

namespace App\Events;

use App\Message;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent implements ShouldBroadcast
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var Message  */
    public $message;
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message, User $user)
    {
        $this->message = $message;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $user1 = $this->user->username;
        $user2 = $this->message->toUser->username;
        $usernames = [$user1, $user2];
        sort($usernames);

        return new PrivateChannel("chat_between_" . $usernames[0] . "_" . $usernames[1]);
    }
}
