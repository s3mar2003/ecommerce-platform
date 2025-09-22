<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PaymentController extends Controller
{
    // عرض جميع المدفوعات (للمشرف)
    public function index()
    {
        $payments = Payment::with(['order.customer'])
            ->latest()
            ->paginate(10);

        return Inertia::render('Admin/Payments', [
            'payments' => $payments
        ]);
    }

    // معالجة الدفع
    public function processPayment(Request $request, Order $order)
    {
        $request->validate([
            'payment_method' => 'required|in:credit_card,paypal,bank_transfer,cash_on_delivery',
            'amount' => 'required|numeric|min:0'
        ]);

        // التحقق من أن المطالب هو صاحب الطلب
        if (Auth::id() !== $order->customer_id) {
            abort(403, 'غير مصرح بتنفيذ هذه العملية');
        }

        // التحقق من أن المبلغ مطابق لمبلغ الطلب
        if ($request->amount != $order->total_amount) {
            return redirect()->back()->with('error', 'المبلغ غير مطابق لمبلغ الطلب');
        }

        // إنشاء سجل الدفع
        $payment = Payment::create([
            'order_id' => $order->id,
            'method' => $request->payment_method,
            'amount' => $request->amount,
            'status' => 'completed'
        ]);

        // تحديث حالة الطلب
        $order->update([
            'payment_status' => 'paid',
            'order_status' => 'processing'
        ]);

        return redirect()->route('customer.orders')
            ->with('success', 'تمت عملية الدفع بنجاح');
    }

    // عرض تفاصيل الدفع
    public function show(Payment $payment)
    {
        $payment->load(['order.customer', 'order.orderItems.product']);

        return Inertia::render('Payments/Show', [
            'payment' => $payment
        ]);
    }

    // تحديث حالة الدفع (للمشرف/البائع)
    public function updateStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,failed,refunded'
        ]);

        $payment->update(['status' => $request->status]);

        // إذا تم استرداد المبلغ، تحديث حالة الطلب
        if ($request->status === 'refunded') {
            $payment->order->update([
                'payment_status' => 'refunded',
                'order_status' => 'cancelled'
            ]);
        }

        return redirect()->back()->with('success', 'تم تحديث حالة الدفع');
    }

    // استرداد المبلغ
    public function refundPayment(Payment $payment)
    {
        // التحقق من إمكانية الاسترداد
        if ($payment->status !== 'completed') {
            return redirect()->back()->with('error', 'لا يمكن استرداد هذه العملية');
        }

        $payment->update(['status' => 'refunded']);
        $payment->order->update([
            'payment_status' => 'refunded',
            'order_status' => 'cancelled'
        ]);

        return redirect()->back()->with('success', 'تم استرداد المبلغ بنجاح');
    }

    // مدفوعات البائع
    public function vendorPayments()
    {
        $vendor = Auth::user();
        
        $payments = Payment::whereHas('order.orderItems', function($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
        })
        ->with(['order.customer', 'order.orderItems.product'])
        ->latest()
        ->paginate(10);

        return Inertia::render('Vendor/Payments', [
            'payments' => $payments
        ]);
    }
}