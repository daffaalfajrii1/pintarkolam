<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\FeedReminderService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request, FeedReminderService $feedReminder)
    {
        $feedReminder->dispatchDueReminders($request->user());
        $notifications = $request->user()->notifications()->paginate(20);

        return view('user.notifications.index', compact('notifications'));
    }

    public function markRead(Request $request, string $id)
    {
        $notification = $request->user()->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();

        $url = $notification->data['action_url'] ?? null;

        return $url ? redirect($url) : back();
    }

    public function markAllRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return back()->with('status', 'Semua notifikasi ditandai dibaca.');
    }
}
