<?php

namespace App\Notifications;

use App\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MessageSentNotification extends Notification
{

    use Queueable;

    /** @var Message */
    public $message;
    public $text;
    public $link;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message, $text, $link)
    {
        $this->message = $message;
        $this->text = $text;
        $this->link = $link;
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
            'message' => $this->message,
            'text'    => $this->text,
            'link'  => $this->link,
        ];
    }
}
