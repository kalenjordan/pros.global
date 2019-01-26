<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat_between_{user1}_{user2}', function (\App\User $user, $username1, $username2) {
    return true;
    if (! Auth::check()) {
        return false;
    }

    return ($user->username == $username1 || $user->username == $username2);
});

Broadcast::channel('online_presence', function (\App\User $user) {
    return $user->toArray();
});