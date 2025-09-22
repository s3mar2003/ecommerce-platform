<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VendorController extends Controller
{
    public function dashboard()
    {
        $vendor = Auth::user();

        $stats = [
            'products_count' => $vendor->products()->count(),
            'orders_count' => Order::whereHas('items', function($q) use($vendor) {
                $q->where('vendor_id', $vendor->id);
            })->count(),
            'revenue' => Order::whereHas('items', function($q) use($vendor) {
                $q->where('vendor_id', $vendor->id);
            })->where('order_status', 'delivered')->sum('total_amount'),
            'pending_orders' => Order::whereHas('items', function($q) use($vendor) {
                $q->where('vendor_id', $vendor->id);
            })->where('order_status', 'pending')->count(),
            'active_products' => $vendor->products()->where('status', 'active')->count(),
            'inactive_products' => $vendor->products()->where('status', 'inactive')->count(),
        ];

        // الطلبات الحديثة
        $recentOrders = Order::whereHas('items', function($q) use($vendor) {
                $q->where('vendor_id', $vendor->id);
            })
            ->with(['customer', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        return Inertia::render('Vendor/Dashboard', [
            'stats' => $stats,
            'recentOrders' => $recentOrders
        ]);
    }

    public function products(Request $request)
{
    $vendor = Auth::user();
    
    $products = $vendor->products()
        ->with('vendor')
        ->latest()
        ->when($request->filled('search'), function($query) use($request) {
            $query->where('name', 'like', '%' . $request->search . '%');
        })
        ->when($request->filled('status') && $request->status !== 'all', function($query) use($request) {
            $query->where('status', $request->status);
        })
        ->paginate(10);

    return Inertia::render('Vendor/Products/Index', [
        'products' => $products,
        'filters' => $request->only(['search', 'status'])
    ]);
}

    public function createProduct()
    {
        return Inertia::render('Vendor/Products/Create');
    }

    public function storeProduct(Request $request)
{
    $vendor = Auth::user();

    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'status' => 'required|in:active,inactive',
    ]);

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('products', 'public');
    }

    Product::create([
        'vendor_id' => $vendor->id,
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
        'stock' => $request->stock,
        'image_url' => $imagePath, 
        'status' => $request->status,
    ]);

    return redirect()->route('vendor.products')->with('success', 'تم إنشاء المنتج بنجاح');
}
public function editProduct(Product $product)
{
    $vendor = Auth::user();
    
    if ($product->vendor_id !== $vendor->id) {
        abort(403, 'غير مصرح بتعديل هذا المنتج');
    }

    return Inertia::render('Vendor/Products/Edit', [
        'product' => $product
    ]);
}
  public function updateProduct(Request $request, Product $product)
{
    if ($product->vendor_id !== Auth::id()) {
        abort(403, 'غير مصرح بتعديل هذا المنتج');
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'status' => 'required|in:active,inactive',
        'remove_image' => 'nullable|boolean',
    ]);

    $data = $request->only('name', 'description', 'price', 'stock', 'status');

    if ($request->remove_image) {
        if ($product->image_url) {
            Storage::disk('public')->delete($product->image_url);
        }
        $data['image_url'] = null;
    }

    if ($request->hasFile('image')) {
        if ($product->image_url) {
            Storage::disk('public')->delete($product->image_url);
        }
        
        $imagePath = $request->file('image')->store('products', 'public');
        $data['image_url'] = $imagePath;
    }

    $product->update($data);

    return redirect()->route('vendor.products')->with('success', 'تم تحديث المنتج بنجاح');
}

   public function deleteProduct(Product $product)
{
    $vendor = Auth::user();
    
    // تحقق أكثر دقة من ملكية المنتج
    if ($product->vendor_id !== $vendor->id) {
        return redirect()->route('vendor.products')
            ->with('error', 'غير مصرح بحذف هذا المنتج');
    }

    // تحقق إذا كان المنتج مرتبط بأي طلبات
    if ($product->orderItems()->exists()) {
        return redirect()->route('vendor.products')
            ->with('error', 'لا يمكن حذف المنتج لأنه مرتبط بطلبات');
    }

    try {
        // حذف الصورة إذا كانت موجودة
        if ($product->image_url && Storage::disk('public')->exists($product->image_url)) {
            Storage::disk('public')->delete($product->image_url);
        }

        $product->delete();

        return redirect()->route('vendor.products')
            ->with('success', 'تم حذف المنتج بنجاح');

    } catch (\Exception $e) {
        return redirect()->route('vendor.products')
            ->with('error', 'حدث خطأ أثناء حذف المنتج: ' . $e->getMessage());
    }
}
public function orders(Request $request)
{
    $user = Auth::user();
    
    // جلب الطلبات التي تحتوي على منتجات البائع
    $query = Order::whereHas('orderItems', function($query) use ($user) {
        $query->where('vendor_id', $user->id);
    })->with(['orderItems.product', 'customer'])->latest();

    if ($request->has('status')) {
        $query->where('order_status', $request->status);
    }

    $orders = $query->paginate(10);

    return Inertia::render('Vendor/Orders/Index', [
        'orders' => $orders,
        'filters' => $request->only(['status'])
    ]);
}

public function showOrder(Order $order)
{
    $user = Auth::user();
    
    // التحقق من أن الطلب يحتوي على منتجات البائع
    if (!$order->orderItems()->where('vendor_id', $user->id)->exists()) {
        abort(403, 'ليس لديك صلاحية لعرض هذا الطلب');
    }

    $order->load(['orderItems.product', 'customer', 'payment']);

    return Inertia::render('Vendor/Orders/Show', [
        'order' => $order
    ]);
}

public function updateOrderStatus(Request $request, Order $order)
{
    $user = Auth::user();
    
    // التحقق من أن الطلب يحتوي على منتجات البائع
    if (!$order->orderItems()->where('vendor_id', $user->id)->exists()) {
        abort(403, 'ليس لديك صلاحية لتحديث هذا الطلب');
    }

    $request->validate([
        'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
    ]);

    $order->update(['order_status' => $request->status]);

    return redirect()->back()->with('success', 'تم تحديث حالة الطلب بنجاح');
}

   
   

    // public function exportProducts(Request $request)
    // {
    //     $vendor = Auth::user();
        
    //     $query = $vendor->products()->with('vendor');

    //     if ($request->has('search')) {
    //         $query->where('name', 'like', '%' . $request->search . '%');
    //     }

    //     if ($request->has('status')) {
    //         $query->where('status', $request->status);
    //     }

    //     $products = $query->get();

    //     // هنا يمكنك إضافة منطق التصدير إلى Excel أو PDF
    //     if ($request->type === 'excel') {
    //         return response()->json(['message' => 'تصدير Excel جاهز للتطوير']);
    //     }

    //     return response()->json(['message' => 'تصدير PDF جاهز للتطوير']);
    // }

public function manageStockOrders(Request $request)
{
    $vendor = Auth::user();

    // الإحصائيات
    $stats = [
        'total_products' => $vendor->products()->count(),
        'low_stock_products' => $vendor->products()->where('stock', '>', 0)->where('stock', '<=', 10)->count(),
        'pending_orders' => Order::whereHas('items', function($q) use($vendor) {
            $q->where('vendor_id', $vendor->id);
        })->where('order_status', 'pending')->count(),
        'today_sales' => Order::whereHas('items', function($q) use($vendor) {
            $q->where('vendor_id', $vendor->id);
        })->where('order_status', 'delivered')
          ->whereDate('created_at', today())
          ->sum('total_amount'),
    ];

    // المنتجات مع التصفية
    $products = $vendor->products()
        ->when($request->filled('product_search'), function($query) use($request) {
            $query->where('name', 'like', '%' . $request->product_search . '%');
        })
        ->when($request->filled('stock_status') && $request->stock_status !== 'all', function($query) use($request) {
            switch ($request->stock_status) {
                case 'low': 
                    $query->where('stock', '>', 0)->where('stock', '<=', 10);
                    break;
                case 'out': 
                    $query->where('stock', 0);
                    break;
                case 'sufficient': 
                    $query->where('stock', '>', 10);
                    break;
            }
        })
        ->latest()
        ->get();

    // الطلبات مع التصفية
    $orders = Order::whereHas('items', function($q) use($vendor) {
            $q->where('vendor_id', $vendor->id);
        })
        ->with(['customer', 'items.product'])
        ->when($request->filled('order_status') && $request->order_status !== 'all', function($query) use($request) {
            $query->where('order_status', $request->order_status);
        })
        ->when($request->filled('period') && $request->period !== 'all', function($query) use($request) {
            if ($request->period === 'today') {
                $query->whereDate('created_at', today());
            } elseif ($request->period === 'week') {
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($request->period === 'month') {
                $query->whereMonth('created_at', now()->month);
            }
        })
        ->latest()
        ->get();

    return Inertia::render('Vendor/ManageStockOrders', [
        'stats' => $stats,
        'products' => $products,
        'orders' => $orders,
        'filters' => $request->only(['product_search', 'stock_status', 'order_status', 'period'])
    ]);
}

public function exportStockOrdersReport(Request $request)
{
    $vendor = Auth::user();

    // نفس منطق التصفية من manageStockOrders
    $products = $vendor->products()
        ->when($request->filled('product_search'), function($query) use($request) {
            $query->where('name', 'like', '%' . $request->product_search . '%');
        })
        ->when($request->filled('stock_status') && $request->stock_status !== 'all', function($query) use($request) {
            switch ($request->stock_status) {
                case 'low': 
                    $query->where('stock', '>', 0)->where('stock', '<=', 10);
                    break;
                case 'out': 
                    $query->where('stock', 0);
                    break;
                case 'sufficient': 
                    $query->where('stock', '>', 10);
                    break;
            }
        })
        ->get();

    $fileName = 'stock_orders_report_' . date('Y-m-d') . '.csv';
    
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
    ];

    $callback = function() use ($products, $vendor) {
        $file = fopen('php://output', 'w');
        
        // كتابة العناوين
        fputcsv($file, ['اسم المنتج', 'السعر', 'المخزون الحالي', 'حالة المخزون', 'آخر تحديث']);
        
        // كتابة البيانات
        foreach ($products as $product) {
            $stockStatus = $product->stock == 0 ? 'منتهي' : ($product->stock <= 10 ? 'منخفض' : 'كافي');
            
            fputcsv($file, [
                $product->name,
                $product->price,
                $product->stock,
                $stockStatus,
                $product->updated_at->format('Y-m-d H:i')
            ]);
        }
        
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
}