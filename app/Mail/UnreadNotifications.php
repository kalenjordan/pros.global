<?php

namespace App\Mail;

use DB;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UnreadNotifications extends Mailable
{
    use Queueable, SerializesModels;

    /** @var User */
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $notifications = $this->user->notificationsToEmail()
            ->groupBy('type')
            ->select([
                'type',
                DB::raw('count(*) as cnt'),
            ])
            ->get();
        foreach ($notifications as $notification) {
            $search = [
                'App\Notifications\BeenTagged',
                'App\Notifications\BeenUpvoted',
                'App\Notifications\MessageSentNotification',
            ];
            $replace = [
                'tag',
                'upvote',
                'message',
            ];
            $shortType = str_replace($search, $replace, $notification->type);
            $parts[] = $notification->cnt . " " . $shortType . ($notification->cnt > 1 ? "s" : "");
        }
        $subject = "You've received " . implode(', ', $parts);

        return $this->from('no-reply@pros.global', 'pros.global')
            ->subject($subject)
            ->markdown('emails.unread-notifications');
    }
}
