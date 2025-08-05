@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">My Stock Orders</h1>

<a href="{{ route('franchise.stockorders.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">New Stock Order</a>

@if(session('success'))
    <div class="mt-4 p-2 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="mt-4 p-2 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
@endif

<table class="min-w-full bg-white mt-4">
    <thead class="bg-gray-100">
        <tr>
            <th class="text-left py-2 px-4">Order #</th>
            <th class="text-left py-2 px-4">Truck</th>
            <th class="text-left py-2 px-4">Warehouse</th>
            <th class="text-left py-2 px-4">Status</th>
            <th class="text-left py-2 px-4">Date</th>
            <th class="py-2 px-4">Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr class="border-b">
            <td class="py-2 px-4">#{{ $order->id }}</td>
            <td class="py-2 px-4">{{ $order->truck->name }}</td>
            <td class="py-2 px-4">{{ $order->warehouse->name }}</td>
            <td class="py-2 px-4">{{ ucfirst($order->status) }}</td>
            <td class="py-2 px-4">{{ \Carbon\Carbon::parse($order->ordered_at)->format('Y-m-d') }}</td>
            <td class="py-2 px-4 text-center">
                <a href="{{ route('franchise.stockorders.show', $order) }}" class="text-blue-600 hover:underline">View</a>
                @if($order->status === 'pending')
                    | <a href="{{ route('franchise.stockorders.edit', $order) }}" class="text-yellow-600 hover:underline">Edit</a>
                    | <form action="{{ route('franchise.stockorders.destroy', $order) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Cancel this order?')" class="text-red-600 hover:underline">Cancel</button>
                    </form>
                @endif
            </td>
        </tr>
    @endforeach
    @if($orders->isEmpty())
        <tr><td colspan="6" class="py-4 px-4 text-center text-gray-600">No stock orders found.</td></tr>
    @endif
    </tbody>
</table>
@endsection