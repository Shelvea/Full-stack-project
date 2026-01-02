<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //
    public function index(Request $request)
    {
        return response()->json([
            'notifications' => $request->user()->notifications,
            'unread_count' => $request->user()->unreadNotifications()->count(),
        ]);
    }

    //mark as read notification
    public function read($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        return response()->noContent(); // HTTP 204
    }

    public function destroy($id)
    {
        $notification = auth()->user()
        ->notifications()
        ->where('id', $id)
        ->firstOrFail();

        $notification->delete();

        return response()->json([
        'message' => 'Notification deleted successfully'
    ]);
    
    }
}
