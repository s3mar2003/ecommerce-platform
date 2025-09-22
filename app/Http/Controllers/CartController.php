<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CartController extends Controller
{
    // عرض محتويات السلة
    public function index()
    {
        $user = Auth::user();
        $cart = $user->cart()->with('cartItems.product')->first();

        return Inertia::render('Customer/Cart', [
            'cart' => $cart
        ]);
    }

    // إضافة منتج إلى السلة
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = Auth::user();
        $cart = $user->cart()->firstOrCreate(['customer_id' => $user->id]);

        // التحقق من توفر المنتج
        $product = Product::findOrFail($request->product_id);
        if ($product->stock < $request->quantity) {
            return redirect()->back()->with('error', 'الكمية المطلوبة غير متوفرة في المخزون');
        }

        $cartItem = $cart->cartItems()->where('product_id', $request->product_id)->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($product->stock < $newQuantity) {
                return redirect()->back()->with('error', 'الكمية الإجمالية تتجاوز المخزون المتاح');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            $cart->cartItems()->create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        return redirect()->back()->with('success', 'تمت إضافة المنتج إلى السلة');
    }

    // تحديث كمية المنتج في السلة
    public function updateCartItem(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $user = Auth::user();
        $cartItem = CartItem::whereHas('cart', function($query) use ($user) {
            $query->where('customer_id', $user->id);
        })->findOrFail($itemId);

        // التحقق من المخزون
        if ($cartItem->product->stock < $request->quantity) {
            return redirect()->back()->with('error', 'الكمية المطلوبة غير متوفرة في المخزون');
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return redirect()->back()->with('success', 'تم تحديث الكمية');
    }

    // إزالة منتج من السلة
    public function removeFromCart($itemId)
    {
        $user = Auth::user();
        $cartItem = CartItem::whereHas('cart', function($query) use ($user) {
            $query->where('customer_id', $user->id);
        })->findOrFail($itemId);

        $cartItem->delete();

        return redirect()->back()->with('success', 'تم إزالة المنتج من السلة');
    }

    // تفريغ السلة بالكامل
    public function clearCart()
    {
        $user = Auth::user();
        $cart = $user->cart;

        if ($cart) {
            $cart->cartItems()->delete();
        }

        return redirect()->back()->with('success', 'تم تفريغ السلة');
    }

    // الحصول على عدد العناصر في السلة (للعرض في الـ Navbar)
    public function getCartCount()
    {
        $user = Auth::user();
        $cart = $user->cart;

        if ($cart) {
            $count = $cart->cartItems()->count();
            return response()->json(['count' => $count]);
        }

        return response()->json(['count' => 0]);
    }
}
