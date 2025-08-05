@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Stock Order #{{ $stockOrder->id }}</h1>

<div class="bg-white p-6 rounded shadow">
    <p><strong>Truck:</strong> {{ $stockOrder->truck->name }}</p>
    <p><strong>Warehouse:</strong> {{ $stockOrder->warehouse->name }}</p>
    <p><strong>Status:</strong> {{ ucfirst($stockOrder->status) }}</p>
    <p><strong>Ordered Date:</strong> {{ \Carbon\Carbon::parse($stockOrder->ordered_at)->format('Y-m-d H:i') }}</p>
    <div class="mt-4">
        <h2 class="text-xl font-semibold mb-2">Items in Order:</h2>
        @if($stockOrder->items->count())
            <ul class="list-disc list-inside">
                @foreach($stockOrder->items as $item)
                    <li>{{ $item->quantity }} × {{ $item->supply->name }}</li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-600">No items added to this order yet.</p>
        @endif
    </div>
</div>

<a href="{{ route('franchise.stockorders.index') }}" class="inline-block mt-4 text-blue-600 hover:underline">← Back to Stock Orders</a>
@endsection