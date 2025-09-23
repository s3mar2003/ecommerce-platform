<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// الصفحة الرئيسية
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('welcome');

// مسارات المصادقة Breeze
require __DIR__.'/auth.php';



// Admin Routes
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/vendors', [AdminController::class, 'vendors'])->name('vendors');
        Route::get('/vendors/create', [AdminController::class, 'createVendor'])->name('vendors.create');
        Route::post('/vendors', [AdminController::class, 'storeVendor'])->name('vendors.store');
        Route::get('/vendors/{vendor}/edit', [AdminController::class, 'editVendor'])->name('vendors.edit');
        Route::put('/vendors/{vendor}', [AdminController::class, 'updateVendor'])->name('vendors.update');
        Route::delete('/vendors/{vendor}', [AdminController::class, 'deleteVendor'])->name('vendors.delete');
        
        Route::get('/products', [AdminController::class, 'products'])->name('products');
        Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
        Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
        Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
        Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
        Route::delete('/products/{product}', [AdminController::class, 'deleteProduct'])->name('products.delete');
        
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
        Route::put('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.updateStatus');
        
        Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
        Route::put('/payments/{payment}/status', [AdminController::class, 'updatePaymentStatus'])->name('payments.updateStatus');
    });

// Vendor Routes
Route::middleware(['auth', 'role:vendor'])
    ->prefix('vendor')
    ->name('vendor.')
    ->group(function () {
        Route::get('/dashboard', [VendorController::class, 'dashboard'])->name('dashboard');

       Route::prefix('manage')->name('manage.')->group(function () {
        Route::get('/stock-orders', [VendorController::class, 'manageStockOrders'])->name('stock-orders');
    });
        // مسارات المنتجات
        Route::get('/products', [VendorController::class, 'products'])->name('products');
        Route::get('/products/create', [VendorController::class, 'createProduct'])->name('products.create');
        Route::post('/products', [VendorController::class, 'storeProduct'])->name('products.store');
        Route::get('/products/{product}/edit', [VendorController::class, 'editProduct'])->name('products.edit');
        Route::put('/products/{product}', [VendorController::class, 'updateProduct'])->name('products.update');
        Route::delete('/products/{product}', [VendorController::class, 'deleteProduct'])->name('products.delete');
        
        // مسارات الطلبات
         Route::get('/orders', [VendorController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [VendorController::class, 'showOrder'])->name('orders.show'); // هذا السطر المطلوب
        Route::put('/orders/{order}/status', [VendorController::class, 'updateOrderStatus'])->name('orders.updateStatus');

      
});

   
   

    
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
    Route::get('/products', [CustomerController::class, 'products'])->name('products');
    Route::get('/products/{product}', [CustomerController::class, 'showProduct'])->name('products.show');
    
    // السلة
    Route::get('/cart', [CustomerController::class, 'cart'])->name('cart');
    Route::post('/cart/add', [CustomerController::class, 'addToCart'])->name('cart.add');
    Route::put('/cart/{cartItem}', [CustomerController::class, 'updateCartItem'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CustomerController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/clear', [CustomerController::class, 'clearCart'])->name('cart.clear');
    Route::get('/cart/data', [CustomerController::class, 'getCartData'])->name('cart.data'); // إضافة هذا الـ route
    
    // الدفع والطلبات
    Route::get('/checkout', [CustomerController::class, 'checkout'])->name('checkout');
    Route::post('/orders/place', [CustomerController::class, 'placeOrder'])->name('orders.place');
    Route::get('/orders', [CustomerController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [CustomerController::class, 'showOrder'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [CustomerController::class, 'cancelOrder'])->name('orders.cancel');
    
    // الإشعارات
    Route::get('/notifications', [CustomerController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/{notification}/read', [CustomerController::class, 'markNotificationAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [CustomerController::class, 'markAllNotificationsAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{notification}', [CustomerController::class, 'deleteNotification'])->name('notifications.delete');
    Route::get('/notifications/count', [CustomerController::class, 'getUnreadNotificationsCount'])->name('notifications.count'); // إضافة هذا الـ route
    
    // الملف الشخصي
    Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');
    Route::put('/profile', [CustomerController::class, 'updateProfile'])->name('profile.update');
});
    Route::fallback(function () {
    return inertia('Error/404');
});

// Profile (مشترك بين الجميع)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
