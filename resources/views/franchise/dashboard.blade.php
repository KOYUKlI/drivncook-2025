@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">My Dashboard</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="p-4 bg-gray-50 rounded border">
            <h2 class="text-xl font-semibold">My Trucks</h2>
            <p class="text-3xl mt-2">{{ $truckCount }}</p>
        </div>
        <div class="p-4 bg-gray-50 rounded border">
            <h2 class="text-xl font-semibold">My Warehouses</h2>
            <p class="text-3xl mt-2">{{ $warehouseCount }}</p>
        </div>
        <div class="p-4 bg-gray-50 rounded border">
            <h2 class="text-xl font-semibold">Stock Orders (Total)</h2>
            <p class="text-3xl mt-2">{{ $totalOrders }}</p>
            <p class="text-sm text-gray-600">{{ $pendingOrders }} pending</p>
        </div>
    </div>

    <div class="mt-6">
        <h3 class="text-xl font-semibold mb-2">Recent Stock Orders</h3>
        @if($totalOrders > 0)
            <table class="min-w-full bg-white border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2 text-left">Order #</th>
                        <th class="border px-4 py-2 text-left">Truck</th>
                        <th class="border px-4 py-2 text-left">Warehouse</th>
                        <th class="border px-4 py-2 text-left">Status</th>
                        <th class="border px-4 py-2 text-left">Date</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($latestOrders ?? [] as $order)
                    <tr>
                        <td class="border px-4 py-1">{{ $order->id }}</td>
                        <td class="border px-4 py-1">{{ $order->truck->name }}</td>
                        <td class="border px-4 py-1">{{ $order->warehouse->name }}</td>
                        <td class="border px-4 py-1">{{ ucfirst($order->status) }}</td>
                        <td class="border px-4 py-1">{{ $order->ordered_at->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p>No stock orders yet.</p>
        @endif
    </div>
</div>
@endsection