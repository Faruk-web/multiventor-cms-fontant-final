<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //
     public function index()
    {
        $user = auth()->user();

        $notifications = $user->notifications()->latest()->paginate(20);
        $unreadCount   = $user->unreadNotifications()->count();

        // mark unread as read
        $user->unreadNotifications->markAsRead();

        return view('notifications.index', compact('notifications','unreadCount'));
    }
}

