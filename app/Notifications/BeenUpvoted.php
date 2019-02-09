<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;

class BeenUpvoted extends Notification
{

    use Queueable;

    public $user;
    public $text;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $text)
    {
        $this->user = $user;
        $this->text = $text;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'from_user_id' => $this->user->id,
            'text'         => $this->text,
        ];
    }
}
