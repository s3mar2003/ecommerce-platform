<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Payment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OrderController extends Controller
{
    // عرض جميع الطلبات (للمشرف)
    public function index()
    {
        $orders = Order::with(['customer', 'orderItems.product', 'payment'])
            ->latest()
            ->paginate(10);

        return Inertia::render('Admin/Orders', [
            'orders' => $orders
        ]);
    }

    // عرض طلبات العميل
    public function customerOrders()
    {
        $user = Auth::user();
        $orders = $user->orders()
            ->with(['orderItems.product', 'orderItems.vendor', 'payment'])
            ->latest()
            ->paginate(10);

        return Inertia::render('Customer/Orders', [
            'orders' => $orders
        ]);
    }

    // عرض طلبات البائع
    public function vendorOrders()
    {
        $vendor = Auth::user();
        
        $orders = Order::whereHas('orderItems', function($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
        })
        ->with(['customer', 'orderItems.product', 'payment'])
        ->latest()
        ->paginate(10);

        return Inertia::render('Vendor/Orders', [
            'orders' => $orders
        ]);
    }

    // إنشاء طلب جديد
    public function store(Request $request)
    {
        $user = Auth::user();
        $cart = $user->cart()->with('cartItems.product')->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'السلة فارغة');
        }

        // حساب المبلغ الإجمالي
        $totalAmount = $cart->cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        // إنشاء الطلب
        $order = Order::create([
            'customer_id' => $user->id,
            'total_amount' => $totalAmount,
            'payment_status' => 'pending',
            'order_status' => 'pending'
        ]);

        // إضافة عناصر الطلب
        foreach ($cart->cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'vendor_id' => $cartItem->product->vendor_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->price
            ]);

            // تحديث المخزون
            $cartItem->product->decrement('stock', $cartItem->quantity);
        }

        // تفريغ السلة
        $cart->cartItems()->delete();

        // إرسال إشعار
        Notification::create([
            'user_id' => $user->id,
            'message' => 'تم إنشاء طلب جديد #' . $order->id,
            'type' => 'order_created',
            'status' => 'unread'
        ]);

        return redirect()->route('customer.orders')
            ->with('success', 'تم إنشاء الطلب بنجاح');
    }

    // عرض تفاصيل الطلب
    public function show(Order $order)
    {
        $order->load(['customer', 'orderItems.product', 'orderItems.vendor', 'payment']);

        // التحقق من الصلاحية
        $user = Auth::user();
        if ($user->isCustomer() && $order->customer_id !== $user->id) {
            abort(403, 'غير مصرح بالوصول إلى هذا الطلب');
        }

        if ($user->isVendor()) {
            $order->orderItems = $order->orderItems->filter(function($item) use ($user) {
                return $item->vendor_id === $user->id;
            });
        }

        return Inertia::render('Orders/Show', [
            'order' => $order
        ]);
    }

    // تحديث حالة الطلب
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $oldStatus = $order->order_status;
        $order->update(['order_status' => $request->order_status]);

        // إرسال إشعار في حالة تغيير الحالة
        if ($oldStatus !== $request->order_status) {
            Notification::create([
                'user_id' => $order->customer_id,
                'message' => 'تم تحديث حالة الطلب #' . $order->id . ' إلى: ' . $request->order_status,
                'type' => 'order_status_updated',
                'status' => 'unread'
            ]);
        }

        return redirect()->back()->with('success', 'تم تحديث حالة الطلب');
    }

    // إلغاء الطلب
    public function cancelOrder(Order $order)
    {
        if ($order->order_status === 'delivered') {
            return redirect()->back()->with('error', 'لا يمكن إلغاء طلب تم توصيله');
        }

        $order->update(['order_status' => 'cancelled']);

        // استعادة المخزون
        foreach ($order->orderItems as $item) {
            $item->product->increment('stock', $item->quantity);
        }

        Notification::create([
            'user_id' => $order->customer_id,
            'message' => 'تم إلغاء الطلب #' . $order->id,
            'type' => 'order_cancelled',
            'status' => 'unread'
        ]);

        return redirect()->back()->with('success', 'تم إلغاء الطلب');
    }
}