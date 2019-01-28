<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notification;

class NotificationController extends Controller
{

    public function list()
    {
        $user = Auth::user();
        return [
            'notifications' => $user->notifications,
            'unread_count'  => $user->unreadNotifications()->count(),
        ];
    }

    public function markRead(Request $request)
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        return ['success'];
    }
}
