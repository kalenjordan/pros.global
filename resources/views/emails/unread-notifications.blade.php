<?php
/** @var \App\User $user */
/** @var \Illuminate\Notifications\DatabaseNotification $notification */
?>
@component('mail::message')
    # You have X unread notifications

    Name: {{ $user->name }}

    @foreach ($user->notificationsToEmail()->get() as $notification)
        - {{ $notification->data['text'] }} - {{ \App\Date::parse($notification->created_at)->diffForHumans() }}
    @endforeach

    Thanks,
    pros.global
@endcomponent