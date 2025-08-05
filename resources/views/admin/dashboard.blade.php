@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="p-4 bg-gray-50 rounded border">
            <h2 class="text-xl font-semibold">Franchises</h2>
            <p class="text-3xl mt-2">{{ $franchiseCount }}</p>
        </div>
        <div class="p-4 bg-gray-50 rounded border">
            <h2 class="text-xl font-semibold">Trucks</h2>
            <p class="text-3xl mt-2">{{ $truckCount }}</p>
        </div>
        <div class="p-4 bg-gray-50 rounded border">
            <h2 class="text-xl font-semibold">Warehouses</h2>
            <p class="text-3xl mt-2">{{ $warehouseCount }}</p>
        </div>
        <div class="p-4 bg-gray-50 rounded border">
            <h2 class="text-xl font-semibold">Total Sales</h2>
            <p class="text-3xl mt-2">${{ number_format($totalSalesSum, 2) }}</p>
            <p class="text-sm text-gray-600">{{ $totalSalesCount }} orders</p>
        </div>
        <div class="p-4 bg-gray-50 rounded border">
            <h2 class="text-xl font-semibold">Pending Stock Orders</h2>
            <p class="text-3xl mt-2">{{ $pendingStockOrders }}</p>
        </div>
    </div>
</div>
@endsection
