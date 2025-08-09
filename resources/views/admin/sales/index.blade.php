@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
        <h1 class="page-title">Sales</h1>
        <a href="{{ route('admin.exports.sales.pdf') }}" target="_blank" class="btn-secondary">Export PDF</a>
    </div>

<div class="card">
    <div class="card-body p-0">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Truck</th>
                    <th>Franchise</th>
                    <th>Ordered At</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ optional($order->truck)->name }}</td>
                        <td>{{ optional(optional($order->truck)->franchise)->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($order->ordered_at)->format('Y-m-d H:i') }}</td>
                        <td class="text-center"><a class="btn-link" href="{{ route('admin.sales.show', $order) }}">View</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-3 text-gray-500">No sales yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
