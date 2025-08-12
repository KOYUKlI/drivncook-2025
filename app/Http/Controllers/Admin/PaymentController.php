<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Models\CustomerOrder;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(): View
    {
        $payments = Payment::with('order')->orderByDesc('id')->paginate(15);
        return view('admin.payments.index', compact('payments'));
    }
    public function show(Payment $payment): View
    {
        $payment->load('order.truck.franchise');
        return view('admin.payments.show', compact('payment'));
    }

    public function store(StorePaymentRequest $request, CustomerOrder $order): RedirectResponse
    {
        $data = $request->validated();
        $data['customer_order_id'] = $order->id;
        $payment = Payment::create($data);
        if ($payment->method !== 'card') {
            $payment->markCaptured();
        }
        return redirect()->route('admin.sales.show', $order)->with('success','Payment recorded.');
    }

    public function capture(Payment $payment): RedirectResponse
    {
        if ($payment->status !== 'pending') {
            return redirect()->route('admin.payments.show', $payment)->with('error','Only pending payments can be captured.');
        }
        $payment->markCaptured();
        return redirect()->route('admin.payments.show', $payment)->with('success','Payment captured.');
    }

    public function refund(Payment $payment): RedirectResponse
    {
        if ($payment->status !== 'captured') {
            return redirect()->route('admin.payments.show', $payment)->with('error','Only captured payments can be refunded.');
        }
        $payment->markRefunded();
        return redirect()->route('admin.payments.show', $payment)->with('success','Payment refunded.');
    }
}
