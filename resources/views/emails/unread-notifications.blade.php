<?php
/** @var \App\User $user */
/** @var \Illuminate\Notifications\DatabaseNotification $notification */
$count = $user->notificationsToEmail()->count();
?>
@component('mail::message')
# You have {{ $count }} unread notification{{ $count > 1 ? "s" : "" }}

@foreach ($user->notificationsToEmail()->orderby('created_at', 'desc')->get() as $notification)
- {{ $notification->data['text'] }} - {{ \App\Date::parse($notification->created_at)->diffForHumans() }}
@endforeach

@component('mail::button', ['url' => $user->url()])
    Go to pros.global
@endcomponent

Thanks,
pros.global
@endcomponent