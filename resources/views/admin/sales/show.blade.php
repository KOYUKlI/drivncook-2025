@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="page-title">Order #{{ $order->id }}</h1>
</div>

<div class="card mb-6">
    <div class="card-body">
        <dl class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <dt class="text-gray-500">Truck</dt>
                <dd class="text-gray-900">{{ optional($order->truck)->name }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Franchise</dt>
                <dd class="text-gray-900">{{ optional(optional($order->truck)->franchise)->name }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Ordered At</dt>
                <dd class="text-gray-900">{{ \Carbon\Carbon::parse($order->ordered_at)->format('Y-m-d H:i') }}</dd>
            </div>
        </dl>
    </div>
</div>

<div class="card">
    <div class="card-header"><h2 class="font-semibold">Items</h2></div>
    <div class="card-body p-0">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Dish</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                </tr>
            </thead>
            <tbody>
                @forelse($order->items as $item)
                    <tr>
                        <td>{{ optional($item->dish)->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>€ {{ number_format($item->price ?? 0, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-3 text-gray-500">No items.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
