<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Payment;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
     public function dashboard()
    {
        $user = Auth::user();
        
        $stats = [
            'orders_count' => $user->orders()->count(),
            'cart_items'=> $user->cart ? $user->cart->cartItems()->count() : 0,
            'pending_orders' => $user->orders()->where('order_status', 'pending')->count(),
            'completed_orders' => $user->orders()->where('order_status', 'delivered')->count(),
            'total_spent' => $user->orders()->where('payment_status', 'paid')->sum('total_amount'),
        ];

        $recentOrders = $user->orders()
            ->with(['orderItems.product', 'payment'])
            ->latest()
            ->take(5)
            ->get();

        $featuredProducts = Product::where('status', 'active')
            ->where('stock', '>', 0)
            ->with('vendor')
            ->inRandomOrder()
            ->take(8)
            ->get();

        return Inertia::render('Customer/Dashboard', [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'featuredProducts' => $featuredProducts
        ]);
    }

    public function products(Request $request)
    {
        $query = Product::where('status', 'active')
            ->where('stock', '>', 0)
            ->with('vendor')
            ->latest();

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        $products = $query->paginate(12);

        return Inertia::render('Customer/Products/Index', [
            'products' => $products,
            'filters' => $request->only(['search'])
        ]);
    }

    public function showProduct(Product $product)
    {
        if ($product->status !== 'active') {
            abort(404, 'المنتج غير متوفر');
        }

        $product->load('vendor');
        
        $relatedProducts = Product::where('status', 'active')
            ->where('id', '!=', $product->id)
            ->where('stock', '>', 0)
            ->where('vendor_id', $product->vendor_id)
            ->with('vendor')
            ->inRandomOrder()
            ->take(4)
            ->get();

        return Inertia::render('Customer/Products/Show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ]);
    }

  public function addToCart(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1'
    ]);
    

    try {
        $user = Auth::user();
        $cart = Cart::firstOrCreate(['customer_id' => $user->id]);
        

        $product = Product::where('status', 'active')->find($request->product_id);

        if (!$product) {
            return redirect()->back()->with('error', 'المنتج غير متوفر حالياً');
        }

        // البحث عن العنصر في السلة
        $cartItem = CartItem::where('cart_id', $cart->id)
                           ->where('product_id', $request->product_id)
                           ->first();

        if ($cartItem) {
            // إذا العنصر موجود، نزيد الكمية
            $newQuantity = $cartItem->quantity + $request->quantity;
            
            if ($product->stock < $newQuantity) {
                return redirect()->back()->with('error', 'الكمية الإجمالية تتجاوز المخزون المتاح. المتاح: ' . $product->stock);
            }
            
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            // إذا العنصر غير موجود، نتحقق من الكمية أولاً
            if ($product->stock < $request->quantity) {
                return redirect()->back()->with('error', 'الكمية المطلوبة غير متوفرة. المتاح: ' . $product->stock);
            }
            
            // إنشاء عنصر جديد
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        return redirect()->back()->with('success', 'تمت إضافة المنتج إلى السلة بنجاح');

    } catch (\Exception $e) {
        error_log('Add to cart error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'حدث خطأ: ' . $e->getMessage());
    }
}
    public function cart()
    {
        $user = Auth::user();
        
        $cart = $user->cart()->with(['cartItems.product.vendor'])->first();
        
        if (!$cart) {
            $cart = $user->cart()->create(['customer_id' => $user->id]);
            $cart->load(['cartItems.product.vendor']);
        }

        $total = 0;
        foreach ($cart->cartItems as $item) {
            if ($item->product) {
                $total += $item->product->price * $item->quantity;
            }
        }

        return Inertia::render('Customer/Cart/Index', [
            'cart' => $cart,
            'total' => $total
        ]);
    }

    public function updateCartItem(Request $request, CartItem $cartItem)
    {
        // التحقق من أن العنصر يخص المستخدم
        if ($cartItem->cart->customer_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        if ($cartItem->product->stock < $request->quantity) {
            return redirect()->back()->with('error', 'الكمية غير متوفرة في المخزون');
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return redirect()->back()->with('success', 'تم تحديث الكمية');
    }

    public function removeFromCart(CartItem $cartItem)
    {
        // التحقق من أن العنصر يخص المستخدم
        if ($cartItem->cart->customer_id !== Auth::id()) {
            abort(403);
        }

        $cartItem->delete();

        return redirect()->back()->with('success', 'تم إزالة المنتج من السلة');
    }

    public function clearCart()
    {
        $user = Auth::user();
        $cart = $user->cart;

        if ($cart) {
            $cart->cartItems()->delete();
        }

        return redirect()->back()->with('success', 'تم تفريغ السلة');
    }

   

    public function checkout()
    {
        $user = Auth::user();
        $cart = $user->cart()->with(['cartItems.product.vendor'])->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('customer.cart')->with('error', 'السلة فارغة');
        }

        foreach ($cart->cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return redirect()->route('customer.cart')->with('error', 
                    'المنتج "' . $item->product->name . '" لم يعد متوفراً بالكمية المطلوبة');
            }
        }

        $total = $cart->cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        return Inertia::render('Customer/Checkout/Index', [
            'cart' => $cart,
            'total' => $total,
            'user' => $user
        ]);
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:cash_on_delivery,credit_card,paypal,bank_transfer',
            'shipping_address' => 'required|string|max:500'
        ]);

        $user = Auth::user();
        $cart = $user->cart()->with(['cartItems.product.vendor'])->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('customer.cart')->with('error', 'السلة فارغة');
        }

        $outOfStockItems = [];
        foreach ($cart->cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                $outOfStockItems[] = $item->product->name;
            }
        }

        if (!empty($outOfStockItems)) {
            return redirect()->route('customer.cart')->with('error', 
                'المنتجات التالية لم تعد متوفرة: ' . implode(', ', $outOfStockItems));
        }

        $totalAmount = $cart->cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        // إنشاء الطلب
        $order = Order::create([
            'customer_id' => $user->id,
            'total_amount' => $totalAmount,
            'payment_method' => $request->payment_method,
            'shipping_address' => $request->shipping_address,
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
                'price' => $cartItem->product->price,
                'total_price' => $cartItem->product->price * $cartItem->quantity
            ]);

            // تحديث المخزون
            $cartItem->product->decrement('stock', $cartItem->quantity);
        }

        // تفريغ السلة
        $cart->cartItems()->delete();

        // إرسال إشعار للعميل
        Notification::create([
            'user_id' => $user->id,
            'message' => 'تم إنشاء طلب جديد #' . $order->id . ' بمبلغ ' . $totalAmount . ' ر.س',
            'type' => 'order_created',
            'status' => 'unread'
        ]);

        // إرسال إشعار للبائعين
        $vendorIds = $cart->cartItems->pluck('product.vendor_id')->unique();
        foreach ($vendorIds as $vendorId) {
            Notification::create([
                'user_id' => $vendorId,
                'message' => 'لديك طلب جديد #' . $order->id . ' من العميل ' . $user->name,
                'type' => 'new_order',
                'status' => 'unread'
            ]);
        }

        return redirect()->route('customer.orders.show', $order->id)
            ->with('success', 'تم إنشاء الطلب بنجاح. رقم الطلب: #' . $order->id);
    }

    public function orders(Request $request)
    {
        $user = Auth::user();
        $query = $user->orders()->with(['orderItems.product', 'payment'])->latest();

        if ($request->has('status')) {
            $query->where('order_status', $request->status);
        }

        $orders = $query->paginate(10);

        return Inertia::render('Customer/Orders/Index', [
            'orders' => $orders,
            'filters' => $request->only(['status'])
        ]);
    }

    public function showOrder(Order $order)
{
    if ($order->customer_id !== Auth::id()) {
        abort(403);
    }

    $order->load([
        'customer', // تأكد من تحميل العلاقة
        'orderItems.product.vendor', 
        'payment'
    ]);

    // التحقق من وجود customer
    if (!$order->customer) {
        abort(404, 'العميل غير موجود');
    }

    return Inertia::render('Customer/Orders/Show', [
        'order' => $order
    ]);
}

    public function cancelOrder(Request $request, Order $order)
    {
        if ($order->customer_id !== Auth::id()) {
            abort(403);
        }

        if ($order->order_status === 'delivered') {
            return redirect()->back()->with('error', 'لا يمكن إلغاء طلب تم توصيله');
        }

        $order->update(['order_status' => 'cancelled']);

        // استعادة المخزون
        foreach ($order->orderItems as $item) {
            $item->product->increment('stock', $item->quantity);
        }

        // إرسال إشعار
        Notification::create([
            'user_id' => $order->customer_id,
            'message' => 'تم إلغاء الطلب #' . $order->id,
            'type' => 'order_cancelled',
            'status' => 'unread'
        ]);

        return redirect()->back()->with('success', 'تم إلغاء الطلب بنجاح');
    }

    public function notifications()
    {
        $notifications = Auth::user()->notifications()
            ->latest()
            ->paginate(15);

        return Inertia::render('Customer/Notifications/Index', [
            'notifications' => $notifications
        ]);
    }

    public function markNotificationAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->update(['status' => 'read']);

        return redirect()->back()->with('success', 'تم تحديد الإشعار كمقروء');
    }

    public function markAllNotificationsAsRead()
    {
        Auth::user()->notifications()->where('status', 'unread')->update(['status' => 'read']);

        return redirect()->back()->with('success', 'تم تحديد جميع الإشعارات كمقروءة');
    }

    public function deleteNotification(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->delete();

        return redirect()->back()->with('success', 'تم حذف الإشعار');
    }

    public function profile()
    {
        $user = Auth::user();
        
        $stats = [
            'total_orders' => $user->orders()->count(),
            'pending_orders' => $user->orders()->where('order_status', 'pending')->count(),
            'completed_orders' => $user->orders()->where('order_status', 'delivered')->count(),
            'total_spent' => $user->orders()->where('payment_status', 'paid')->sum('total_amount'),
            'cart_items' => $user->cart ? $user->cart->cartItems()->count() : 0,
        ];

        return Inertia::render('Customer/Profile/Index', [
            'user' => $user,
            'stats' => $stats
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update($request->only('name', 'email', 'phone', 'address'));

        return redirect()->back()->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }
}