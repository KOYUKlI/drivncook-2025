@extends('layouts.app')
@section('content')
<h1 class="text-xl font-bold mb-2">Payment #{{ $payment->id }}</h1>
<p><strong>Order:</strong> <a href="{{ route('admin.sales.show',$payment->order) }}" class="text-blue-600">#{{ $payment->order->id }}</a></p>
<p><strong>Amount:</strong> {{ $payment->amount }}</p>
<p><strong>Method:</strong> {{ $payment->method }}</p>
<p><strong>Status:</strong> {{ $payment->status }}</p>
<p><strong>Captured at:</strong> {{ optional($payment->captured_at)->format('Y-m-d H:i') }}</p>
<p><strong>Refunded at:</strong> {{ optional($payment->refunded_at)->format('Y-m-d H:i') }}</p>
<div class="mt-4 flex space-x-2">
@if($payment->status==='pending')
<form method="POST" action="{{ route('admin.payments.capture',$payment) }}">@csrf <button class="btn btn-primary">Capture</button></form>
@endif
@if($payment->status==='captured')
<form method="POST" action="{{ route('admin.payments.refund',$payment) }}">@csrf <button class="btn btn-warning">Refund</button></form>
@endif
<a class="btn" href="{{ route('admin.sales.show',$payment->order) }}">Back to order</a>
</div>
@endsection
