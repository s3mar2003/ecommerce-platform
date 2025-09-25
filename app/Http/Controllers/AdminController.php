<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function dashboard()
{
    $stats = [
        'users_count' => User::count(),
        'vendors_count' => User::where('role', 'vendor')->count(),
        'products_count' => Product::count(),
        'orders_count' => Order::count(),
        'revenue' => Order::where('order_status', 'delivered')->sum('total_amount'),
        'active_products' => Product::where('status', 'active')->count(),
        'inactive_products' => Product::where('status', 'inactive')->count(),
        'average_order_value' => Order::where('order_status', 'delivered')->avg('total_amount'),
    ];

    $recentOrders = Order::with('customer')
        ->latest()
        ->take(5)
        ->get();

    $chartData = [
        'monthlySales' => $this->getMonthlySales(),
        'orderStatuses' => $this->getOrderStatusCounts(),
    ];

    return Inertia::render('Admin/Dashboard', [
        'stats' => $stats,
        'recentOrders' => $recentOrders,
        'chartData' => $chartData
    ]);
}

private function getMonthlySales()
{
    return [
        Order::whereMonth('created_at', 1)->where('order_status', 'delivered')->sum('total_amount'),
        Order::whereMonth('created_at', 2)->where('order_status', 'delivered')->sum('total_amount'),
    ];
}

private function getOrderStatusCounts()
{
    return [
        Order::where('order_status', 'pending')->count(),
        Order::where('order_status', 'processing')->count(),
        Order::where('order_status', 'shipped')->count(),
        Order::where('order_status', 'delivered')->count(),
        Order::where('order_status', 'cancelled')->count(),
    ];
}

    public function vendors(Request $request)
{
    $query = User::where('role', 'vendor')
        ->withCount('products')
        ->latest();


    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('email', 'like', '%' . $search . '%')
              ->orWhere('phone', 'like', '%' . $search . '%');
        });
    }

    // التصفية بالحالة
    if ($request->has('status') && !empty($request->status)) {
        $query->where('status', $request->status);
    }

    $vendors = $query->paginate(10);

    return Inertia::render('Admin/Vendors/Index', [
        'vendors' => $vendors,
        'filters' => $request->only(['search', 'status'])
    ]);
}


public function createVendor()
{
    return Inertia::render('Admin/Vendors/Create');
}

public function storeVendor(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|min:5', // اسم رباعي (4 كلمات × 2.5 حرف متوسط)
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|confirmed|min:6',
        'phone' => 'required|string|min:8|max:20',
        'address' => 'required|string|min:3|max:500'
    ], [
        'name.required' => 'حقل الاسم مطلوب',
        'name.min' => 'الاسم يجب أن يكون رباعي ',
        'email.required' => 'حقل البريد الإلكتروني مطلوب',
        'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
        'email.unique' => 'البريد الإلكتروني مسجل مسبقاً',
        'password.required' => 'حقل كلمة المرور مطلوب',
        'password.min' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
        'password.confirmed' => 'كلمة المرور غير متطابقة',
        'phone.required' => 'حقل الهاتف مطلوب',
        'phone.min' => 'رقم الهاتف يجب أن يكون 8 أرقام على الأقل',
        'address.required' => 'حقل العنوان مطلوب',
        'address.min' => 'العنوان يجب أن يكون تفصيلياً '
    ]);

    // تحقق إضافي للاسم الرباعي
    $nameWords = count(explode(' ', trim($request->name)));
    if ($nameWords < 3) {
        return redirect()->back()->withErrors([
            'name' => 'الاسم يجب أن يكون رباعي '
        ]);
    }

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role' => 'vendor',
        'phone' => $request->phone,
        'address' => $request->address,
        'status' => 'active'
    ]);

    return redirect()->route('admin.vendors')->with('success', 'تم إنشاء البائع بنجاح');
}


public function editVendor(User $vendor)
{
    return Inertia::render('Admin/Vendors/Edit', ['vendor' => $vendor]);
}

public function updateVendor(Request $request, User $vendor)
{
    $request->validate([
        'name' => 'required|string|max:255|min:3',
        'email' => 'required|email|unique:users,email,' . $vendor->id,
        'phone' => 'required|string|min:8|max:20',
        'address' => 'required|string|min:3|max:500'
    ], [
        'name.required' => 'حقل الاسم مطلوب',
        'name.min' => 'الاسم يجب أن يكون رباعي ',
        'email.required' => 'حقل البريد الإلكتروني مطلوب',
        'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
        'email.unique' => 'البريد الإلكتروني مسجل مسبقاً',
        'phone.required' => 'حقل الهاتف مطلوب',
        'phone.min' => 'رقم الهاتف يجب أن يكون 8 أرقام على الأقل',
        'address.required' => 'حقل العنوان مطلوب',
        'address.min' => 'العنوان يجب أن يكون تفصيلياً'
    ]);

    // تحقق إضافي للاسم الرباعي
    $nameWords = count(explode(' ', trim($request->name)));
    if ($nameWords < 3) {
        return redirect()->back()->withErrors([
            'name' => 'الاسم يجب أن يكون رباعي '
        ]);
    }

    $vendor->update($request->only('name', 'email', 'phone', 'address'));

    return redirect()->route('admin.vendors')->with('success', 'تم تحديث البائع بنجاح');
}
 public function deleteVendor(User $vendor)
    {
        if ($vendor->role !== 'vendor') {
            return redirect()->back()->with('error', 'المستخدم ليس بائعاً');
        }

        // التحقق من أن البائع ليس لديه منتجات
        if ($vendor->products()->count() > 0) {
            return redirect()->back()->with('error', 'لا يمكن حذف البائع لأنه لديه منتجات');
        }

        try {
            $vendor->delete();
            return redirect()->route('admin.vendors')->with('success', 'تم حذف البائع بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف البائع: ' . $e->getMessage());
        }
    }

// public function toggleStatus(User $vendor)
// {
//     try {
//         $newStatus = $vendor->status === 'active' ? 'inactive' : 'active';
        
//         $vendor->update([
//             'status' => $newStatus
//         ]);

//         return response()->json([
//             'success' => true,
//             'message' => 'تم تحديث حالة البائع بنجاح',
//             'new_status' => $newStatus
//         ]);

//     } catch (\Exception $e) {
//         return response()->json([
//             'success' => false,
//             'message' => 'حدث خطأ أثناء تحديث الحالة'
//         ], 500);
//     }
// }

   public function products(Request $request)
{
    $query = Product::with('vendor')->latest();

    if ($request->has('search') && $request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('description', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->has('status') && $request->status) {
        $query->where('status', $request->status);
    }

    if ($request->has('vendor') && $request->vendor) {
        $query->where('vendor_id', $request->vendor);
    }

    $products = $query->paginate(10);
    $vendors = User::where('role', 'vendor')->get(['id', 'name']);

    return Inertia::render('Admin/Products/Index', [
        'products' => $products,
        'vendors' => $vendors,
        'filters' => $request->only(['search', 'status', 'vendor'])
    ]);
}

    public function createProduct()
    {
        $vendors = User::where('role', 'vendor')->get(['id', 'name']);
        return Inertia::render('Admin/Products/Create', ['vendors' => $vendors]);
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image_url' => 'nullable|url|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        Product::create($request->all());

        return redirect()->route('admin.products')->with('success', 'تم إنشاء المنتج بنجاح');
    }

    public function editProduct(Product $product)
    {
        $vendors = User::where('role', 'vendor')->get(['id', 'name']);
        return Inertia::render('Admin/Products/Edit', [
            'product' => $product,
            'vendors' => $vendors
        ]);
    }

    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'vendor_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image_url' => 'nullable|url|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $product->update($request->all());

        return redirect()->route('admin.products')->with('success', 'تم تحديث المنتج بنجاح');
    }

   public function deleteProduct(Product $product)
{
    try {
        // التحقق من عدم وجود طلبات
        if ($product->orderItems()->count() > 0) {
            return redirect()->back()->with('error', 'لا يمكن حذف المنتج لأنه مرتبط بطلبات');
        }

        $product->delete();

        return redirect()->route('admin.products')->with('success', 'تم حذف المنتج بنجاح');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'حدث خطأ أثناء حذف المنتج: ' . $e->getMessage());
    }
}

    // إدارة الطلبات
    public function orders(Request $request)
    {
        $query = Order::with(['customer', 'orderItems.product'])->latest();

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                  ->orWhereHas('customer', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->has('status')) {
            $query->where('order_status', $request->status);
        }

        $orders = $query->paginate(10);

        return Inertia::render('Admin/Orders/Index', [
            'orders' => $orders,
            'filters' => $request->only(['search', 'status'])
        ]);
    }

    public function showOrder(Order $order)
    {
        $order->load(['customer', 'orderItems.product', 'payment']);
        return Inertia::render('Admin/Orders/Show', ['order' => $order]);
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $oldStatus = $order->order_status;
        $order->update(['order_status' => $request->order_status]);

        // إرسال إشعار عند تغيير الحالة
        if ($oldStatus !== $request->order_status) {
            Notification::create([
                'user_id' => $order->customer_id,
                'message' => 'تم تحديث حالة طلبك #' . $order->id . ' إلى: ' . $request->order_status,
                'type' => 'order_status_updated',
                'status' => 'unread'
            ]);
        }

        return redirect()->back()->with('success', 'تم تحديث حالة الطلب');
    }

    public function payments(Request $request)
    {
        $query = Payment::with(['order.customer'])->latest();

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                  ->orWhereHas('order.customer', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->paginate(10);

        return Inertia::render('Admin/Payments/Index', [
            'payments' => $payments,
            'filters' => $request->only(['search', 'status'])
        ]);
    }

    public function updatePaymentStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,failed,refunded'
        ]);

        $payment->update(['status' => $request->status]);

        // إذا تم استرداد المبلغ
        if ($request->status === 'refunded') {
            $payment->order->update([
                'payment_status' => 'refunded',
                'order_status' => 'cancelled'
            ]);
        }

        return redirect()->back()->with('success', 'تم تحديث حالة الدفع');
    }

    // إدارة الإشعارات
    public function notifications()
    {
        $notifications = Notification::with('user')
            ->latest()
            ->paginate(20);

        return Inertia::render('Admin/Notifications/Index', [
            'notifications' => $notifications
        ]);
    }

    public function markNotificationAsRead(Notification $notification)
    {
        $notification->update(['status' => 'read']);
        return redirect()->back()->with('success', 'تم تحديد الإشعار كمقروء');
    }

    public function deleteNotification(Notification $notification)
    {
        $notification->delete();
        return redirect()->back()->with('success', 'تم حذف الإشعار');
    }
}