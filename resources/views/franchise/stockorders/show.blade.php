@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="page-title">Stock Order #{{ $stockOrder->id }}</h1>
    @php($s = $stockOrder->status)
    <span class="badge {{ $s === 'pending' ? 'badge-warning' : ($s === 'completed' ? 'badge-success' : 'badge-muted') }}">{{ ucfirst($s) }}</span>
</div>

<div class="card">
    <div class="card-body">
        <dl class="divide-y divide-gray-100">
            <div class="py-3 grid grid-cols-3 gap-4">
                <dt class="text-sm font-medium text-gray-500">Truck</dt>
                <dd class="col-span-2 text-sm text-gray-900">{{ $stockOrder->truck->name }}</dd>
            </div>
            <div class="py-3 grid grid-cols-3 gap-4">
                <dt class="text-sm font-medium text-gray-500">Target</dt>
                <dd class="col-span-2 text-sm text-gray-900">
                    @if($stockOrder->warehouse)
                        Warehouse: {{ $stockOrder->warehouse->name }}
                    @elseif($stockOrder->supplier)
                        Supplier: {{ $stockOrder->supplier->name }}
                    @else
                        —
                    @endif
                </dd>
            </div>
            <div class="py-3 grid grid-cols-3 gap-4">
                <dt class="text-sm font-medium text-gray-500">Ordered Date</dt>
                <dd class="col-span-2 text-sm text-gray-900">{{ \Carbon\Carbon::parse($stockOrder->ordered_at)->format('Y-m-d H:i') }}</dd>
            </div>
        </dl>

        <div class="mt-6">
            <h2 class="text-lg font-semibold mb-2">Items in Order</h2>
            @if($stockOrder->items->count())
                <ul class="list-disc list-inside text-sm text-gray-800">
                    @foreach($stockOrder->items as $item)
                        <li class="flex items-center justify-between">
                            <span>{{ $item->quantity }} × {{ $item->supply->name }}</span>
                            @if($stockOrder->status === 'pending')
                                <button type="button" class="btn-link text-red-600" x-data x-on:click="$dispatch('open-modal', 'remove-item-{{ $item->id }}')">Remove</button>
                                <x-confirm-delete :name="'remove-item-' . $item->id"
                                    :action="route('franchise.stockorders.items.destroy', [$stockOrder, $item])"
                                    title="Remove item"
                                    :message="'Remove ' . $item->supply->name . ' from this order?'" />
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-600">No items added to this order yet.</p>
            @endif

            @if($stockOrder->status === 'pending')
            <div class="mt-4">
                <form action="{{ route('franchise.stockorders.items.store', $stockOrder) }}" method="POST" class="flex items-end gap-3" x-data="{ q: '', filter(e){ this.q = e.target.value.toLowerCase(); $el.querySelectorAll('option').forEach(o=>{ o.hidden = this.q && !o.textContent.toLowerCase().includes(this.q); }); } }">
                    @csrf
                    <div>
                        <label class="form-label">Supply</label>
                        <input type="text" placeholder="Search supply..." class="form-input mb-1 w-64" x-on:input="filter($event)">
                        <select name="supply_id" class="form-select w-64">
                            @foreach($supplies as $supply)
                                <option value="{{ $supply->id }}">{{ $supply->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Qty</label>
                        <input type="number" min="1" name="quantity" value="1" class="form-input w-24">
                    </div>
                    <button class="btn-secondary">Add Item</button>
                </form>
            </div>
            @endif

            @if($stockOrder->status === 'pending')
                <button type="button" class="btn-primary mt-6" x-data x-on:click="$dispatch('open-modal', 'complete-order-{{ $stockOrder->id }}')">Mark as Completed</button>
                <x-confirm-delete :name="'complete-order-' . $stockOrder->id"
                    :action="route('franchise.stockorders.complete', $stockOrder)"
                    method="POST"
                    title="Close Order"
                    message="Confirm receipt and close this order?"
                    confirmLabel="Confirm" />
            @endif
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('franchise.stockorders.index') }}" class="btn-link">← Back to Stock Orders</a>
</div>
@endsection