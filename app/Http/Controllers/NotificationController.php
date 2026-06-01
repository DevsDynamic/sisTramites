<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        $notification->update([
            'read' => true
        ]);

        return back();
    }

    public function unreadCount(Request $request)
    {
        return Notification::where('user_id', $request->user()->id)
            ->where('read', false)
            ->count();
    }
}
