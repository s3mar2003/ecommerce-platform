<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NotificationController extends Controller
{
    // عرض جميع الإشعارات
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()
            ->latest()
            ->paginate(10);

        return Inertia::render('Notifications/Index', [
            'notifications' => $notifications
        ]);
    }

    // عرض إشعارات العميل
    public function customerNotifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()
            ->latest()
            ->paginate(10);

        return Inertia::render('Customer/Notifications', [
            'notifications' => $notifications
        ]);
    }

    // تحديد الإشعار كمقروء
    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);

        $notification->update(['status' => 'read']);

        return redirect()->back()->with('success', 'تم تحديد الإشعار كمقروء');
    }

    // تحديد جميع الإشعارات كمقروءة
    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->notifications()->where('status', 'unread')->update(['status' => 'read']);

        return redirect()->back()->with('success', 'تم تحديد جميع الإشعارات كمقروءة');
    }

    // حذف إشعار
    public function destroy($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);

        $notification->delete();

        return redirect()->back()->with('success', 'تم حذف الإشعار');
    }

    // حذف جميع الإشعارات المقروءة
    public function clearRead()
    {
        $user = Auth::user();
        $user->notifications()->where('status', 'read')->delete();

        return redirect()->back()->with('success', 'تم حذف جميع الإشعارات المقروءة');
    }

    // الحصول على عدد الإشعارات غير المقروءة (للعرض في الـ Navbar)
    public function getUnreadCount()
    {
        $user = Auth::user();
        $count = $user->notifications()->where('status', 'unread')->count();

        return response()->json(['count' => $count]);
    }
}