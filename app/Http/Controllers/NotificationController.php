<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('notifiable_type', Auth::user()::class)
            ->where('notifiable_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('backend.notifications.index', compact('notifications'));
    }

    public function markAllRead()
    {
        Notification::where('notifiable_type', Auth::user()::class)
            ->where('notifiable_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back();
    }
}
