<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notification;

class NotificationController extends Controller
{
    public function list(Request $request)
    {
        return DatabaseNotification::whereNull('read_at')->get();
    }

}
