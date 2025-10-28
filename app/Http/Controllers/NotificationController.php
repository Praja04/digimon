<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class NotificationController extends Controller
{
    public function index()
    {
        $id = Session::get('id');
        $notifications = Notification::with('productionBatch')
            ->where('user_id', $id)
            ->orderByRaw("FIELD(status, 'unread', 'read')")
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('notifications.index', compact('notifications'));
    }

    public function unreadNotifications()
    {
        $userId = Session::get('id');

        if (!$userId) {
            return response()->json(['error' => 'User ID tidak ditemukan di session'], 401);
        }

        $notifications = Notification::where('user_id', $userId)
            ->where('status', 'unread')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notifications);
    }

    public function markAllAsRead()
    {
        $userId = Session::get('id');

        if (!$userId) {
            return response()->json(['error' => 'User ID tidak ditemukan di session'], 401);
        }

        Notification::where('user_id', $userId)
            ->where('status', 'unread')
            ->update(['status' => 'read']);

        return response()->json(['success' => true]);
    }

    public function markAsRead($id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            $notification->status = 'read';
            $notification->save();
        }
        return response()->json(['message' => 'Notifikasi sudah dibaca']);
    }
}
