<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notification;

class NotificationController extends Controller
{

    public function list(Request $request)
    {
        $user = Auth::user();
        $limit = $request->input('limit') ? $request->input('limit') : 20;

        return [
            'notifications' => $user->notifications()->limit($limit)->get(),
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
