<?php
/** @var \App\User $user */
/** @var \Illuminate\Notifications\DatabaseNotification $notification */
$count = $user->notificationsToEmail()->count();
?>
@component('mail::message')
# You have {{ $count }} unread notification{{ $count > 1 ? "s" : "" }}

@foreach ($user->notificationsToEmail()->orderby('created_at', 'desc')->get() as $notification)
 - {{ $notification->data['text'] }}
  @if (isset($notification->data['link']) && isset($notification->data['link']['cta']) && isset($notification->data['link']['link']))
   [{{ $notification->data['link']['cta'] }}]({{ $notification->data['link']['link'] }})
  @endif
 ({{ \App\Date::parse($notification->created_at)->diffForHumans() }})
@endforeach

@component('mail::button', ['url' => $user->url()])
    Go to pros.global
@endcomponent

Thanks,<br/>
Management
@endcomponent