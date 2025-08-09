@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="page-title">My Stock Orders</h1>
    <a href="{{ route('franchise.stockorders.create') }}" class="btn-primary">New Stock Order</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Truck</th>
                    <th>Target</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->truck->name }}</td>
                    <td>
                        @if($order->warehouse)
                            Warehouse: {{ $order->warehouse->name }}
                        @elseif($order->supplier)
                            Supplier: {{ $order->supplier->name }}
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        @php($s = $order->status)
                        <span class="badge {{ $s === 'pending' ? 'badge-warning' : ($s === 'completed' ? 'badge-success' : 'badge-muted') }}">{{ ucfirst($s) }}</span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($order->ordered_at)->format('Y-m-d') }}</td>
                    <td class="text-center space-x-2">
                        <a href="{{ route('franchise.stockorders.show', $order) }}" class="btn-link">View</a>
                        @if($order->status === 'pending')
                            <a href="{{ route('franchise.stockorders.edit', $order) }}" class="btn-link">Edit</a>
                            <form action="{{ route('franchise.stockorders.destroy', $order) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Cancel this order?')" class="btn-link text-red-600">Cancel</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-6 text-center text-gray-600">No stock orders found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection