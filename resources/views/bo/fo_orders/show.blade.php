@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
<x-ui.breadcrumbs :items="[
    ['title' => __('ui.dashboard'), 'url' => route('bo.dashboard')],
    ['title' => __('ui.bo.fo_orders.title'), 'url' => route('bo.fo-orders.index')],
    ['title' => __('ui.bo.fo_orders.show.title', ['ref' => $order->reference])]
]" />

<div class="p-6 max-w-6xl mx-auto">
    @php 
        $status = strtoupper($order->status);
        $orderedTotal = $order->lines->sum('qty');
        $pickedTotal = $order->lines->sum(fn($l) => $l->qty_picked ?? 0);
        $shippedTotal = $order->lines->sum(fn($l) => $l->qty_shipped ?? 0);
        $deliveredTotal = $order->lines->sum(fn($l) => $l->qty_delivered ?? 0);
        $toPickTotal = $order->lines->sum(fn($l) => max(0, $l->qty - ($l->qty_picked ?? 0)));
        $toShipTotal = $order->lines->sum(fn($l) => max(0, ($l->qty_picked ?? 0) - ($l->qty_shipped ?? 0)));
        $toDeliverTotal = $order->lines->sum(fn($l) => max(0, ($l->qty_shipped ?? 0) - ($l->qty_delivered ?? 0)));
    @endphp

    <div class="flex flex-col gap-2 md:flex-row md:items-start md:justify-between mb-6">
        <div>
            <div class="mb-1">
                <a class="text-sm text-gray-600 hover:underline" href="{{ route('bo.fo-orders.index') }}">{{ __('ui.common.back') }}</a>
            </div>
            <h1 class="text-2xl font-semibold">{{ __('ui.bo.fo_orders.show.title', ['ref' => $order->reference]) }}</h1>
            <div class="mt-1 text-sm text-gray-600">
                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                    {{ in_array($status, ['DRAFT', 'PENDING']) ? 'bg-gray-100 text-gray-800' : '' }}
                    {{ in_array($status, ['APPROVED']) ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ in_array($status, ['PICKED']) ? 'bg-amber-100 text-amber-800' : '' }}
                    {{ in_array($status, ['SHIPPED']) ? 'bg-indigo-100 text-indigo-800' : '' }}
                    {{ in_array($status, ['DELIVERED']) ? 'bg-green-100 text-green-800' : '' }}
                    {{ in_array($status, ['CANCELLED']) ? 'bg-red-100 text-red-800' : '' }}
                    {{ in_array($status, ['CLOSED']) ? 'bg-emerald-100 text-emerald-800' : '' }}
                ">
                    {{ __('ui.bo.fo_orders.status.' . strtolower($order->status)) }}
                </span>
                @if(isset($ratio))
                    <span class="ml-2 text-gray-500">
                        {{ __('ui.replenishments.csv.ratio8020') }}: {{ number_format($ratio, 2) }}%
                    </span>
                @endif
            </div>
        </div>
        <div class="flex flex-wrap gap-2 items-center">
            @can('approve', $order)
                @if($status === 'PENDING' || $status === 'DRAFT')
                    <form method="POST" action="{{ route('bo.fo-orders.approve', $order) }}" class="flex gap-2 items-center">
                        @csrf
                        <input type="text" name="warehouse_id" class="form-input w-32 text-sm" placeholder="{{ __('ui.common.warehouse') }}" />
                        <button type="submit" class="btn btn-primary">{{ __('ui.bo.fo_orders.actions.approve') }}</button>
                    </form>
                @endif
            @endcan
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="mb-6 grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-4 gap-3">
            <div class="p-3 border rounded bg-white">
                <div class="text-xs text-gray-500">{{ __('ui.bo.fo_orders.summary.ordered_total') }}</div>
                <div class="font-semibold">{{ $orderedTotal }}</div>
            </div>
            <div class="p-3 border rounded bg-white">
                <div class="text-xs text-gray-500">{{ __('ui.bo.fo_orders.summary.picked_total') }}</div>
                <div class="font-semibold">{{ $pickedTotal }} <span class="text-xs text-gray-500">({{ __('ui.bo.fo_orders.summary.to_pick') }}: {{ $toPickTotal }})</span></div>
            </div>
            <div class="p-3 border rounded bg-white">
                <div class="text-xs text-gray-500">{{ __('ui.bo.fo_orders.summary.shipped_total') }}</div>
                <div class="font-semibold">{{ $shippedTotal }} <span class="text-xs text-gray-500">({{ __('ui.bo.fo_orders.summary.to_ship') }}: {{ $toShipTotal }})</span></div>
            </div>
            <div class="p-3 border rounded bg-white">
                <div class="text-xs text-gray-500">{{ __('ui.bo.fo_orders.summary.delivered_total') }}</div>
                <div class="font-semibold">{{ $deliveredTotal }} <span class="text-xs text-gray-500">({{ __('ui.bo.fo_orders.summary.to_deliver') }}: {{ $toDeliverTotal }})</span></div>
            </div>
        </div>
        <div class="p-4 border rounded bg-white">
            <div class="text-xs text-gray-500">{{ __('ui.labels.order_info') }}</div>
            <div class="mt-2 grid grid-cols-1 gap-2 text-sm">
                <div>
                    <div class="text-gray-500 text-xs">{{ __('ui.common.franchisee') }}</div>
                    <div>{{ $order->franchisee->business_name ?? $order->franchisee->name ?? '-' }}</div>
                </div>
                @if($order->warehouse)
                <div>
                    <div class="text-gray-500 text-xs">{{ __('ui.common.warehouse') }}</div>
                    <div>{{ $order->warehouse->name ?? '-' }}</div>
                </div>
                @endif
                <div>
                    <div class="text-gray-500 text-xs">{{ __('ui.common.created_at') }}</div>
                    <div>{{ $order->created_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pick Section -->
    @can('pick', $order)
        @if($status === 'APPROVED')
            @php $anyPickable = $order->lines->some(fn($l) => max(0, $l->qty - ($l->qty_picked ?? 0)) > 0); @endphp
            <form id="pick-section" method="POST" action="{{ route('bo.fo-orders.pick', $order) }}" class="mb-6 border rounded bg-white">
                @csrf
                <div class="p-3 border-b font-medium bg-gray-50">{{ __('ui.bo.fo_orders.actions.pick') }}</div>
                <div class="overflow-x-auto p-3">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-600">
                                <th class="p-2">{{ __('ui.labels.item') }}</th>
                                <th class="p-2">{{ __('ui.quantity') }}</th>
                                <th class="p-2">{{ __('ui.bo.fo_orders.picked') }}</th>
                                <th class="p-2">{{ __('ui.bo.fo_orders.to_pick') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->lines as $l)
                                <tr class="border-t">
                                    <td class="p-2">{{ $l->stockItem->name ?? '-' }}</td>
                                    <td class="p-2">{{ $l->qty }}</td>
                                    <td class="p-2">{{ $l->qty_picked ?? 0 }}</td>
                                    <td class="p-2">
                                        <input type="number" min="0" max="{{ $l->qty }}" 
                                               name="lines[{{ $l->id }}]" 
                                               value="{{ old('lines.'.$l->id, max(0, $l->qty - ($l->qty_picked ?? 0))) }}" 
                                               class="form-input w-24">
                                        @error('lines.'.$l->id)
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-2 p-3 border-t bg-gray-50">
                    <button type="submit" class="btn btn-primary {{ $anyPickable ? '' : 'opacity-50 cursor-not-allowed' }}" 
                            {{ $anyPickable ? '' : 'disabled' }}>
                        {{ __('ui.bo.fo_orders.actions.pick') }}
                    </button>
                </div>
            </form>
        @endif
    @endcan

    <!-- Ship Section -->
    @can('ship', $order)
        @if($status === 'PICKED')
            @php $anyShippable = $order->lines->some(fn($l) => max(0, ($l->qty_picked ?? 0) - ($l->qty_shipped ?? 0)) > 0); @endphp
            <form id="ship-section" method="POST" action="{{ route('bo.fo-orders.ship', $order) }}" class="mb-6 border rounded bg-white">
                @csrf
                <div class="p-3 border-b font-medium bg-gray-50">{{ __('ui.bo.fo_orders.actions.ship') }}</div>
                <div class="overflow-x-auto p-3">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-600">
                                <th class="p-2">{{ __('ui.labels.item') }}</th>
                                <th class="p-2">{{ __('ui.quantity') }}</th>
                                <th class="p-2">{{ __('ui.bo.fo_orders.picked') }}</th>
                                <th class="p-2">{{ __('ui.bo.fo_orders.shipped') }}</th>
                                <th class="p-2">{{ __('ui.bo.fo_orders.to_ship') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->lines as $l)
                                <tr class="border-t">
                                    <td class="p-2">{{ $l->stockItem->name ?? '-' }}</td>
                                    <td class="p-2">{{ $l->qty }}</td>
                                    <td class="p-2">{{ $l->qty_picked ?? 0 }}</td>
                                    <td class="p-2">{{ $l->qty_shipped ?? 0 }}</td>
                                    <td class="p-2">
                                        <input type="number" min="0" max="{{ ($l->qty_picked ?? 0) - ($l->qty_shipped ?? 0) }}" 
                                               name="lines[{{ $l->id }}]" 
                                               value="{{ old('lines.'.$l->id, max(0, ($l->qty_picked ?? 0) - ($l->qty_shipped ?? 0))) }}" 
                                               class="form-input w-24">
                                        @error('lines.'.$l->id)
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-2 p-3 border-t bg-gray-50">
                    <button type="submit" class="btn btn-primary {{ $anyShippable ? '' : 'opacity-50 cursor-not-allowed' }}" 
                            {{ $anyShippable ? '' : 'disabled' }}>
                        {{ __('ui.bo.fo_orders.actions.ship') }}
                    </button>
                </div>
            </form>
        @endif
    @endcan

    <!-- Deliver Section -->
    @can('deliver', $order)
        @if($status === 'SHIPPED')
            @php $anyDeliverable = $order->lines->some(fn($l) => max(0, ($l->qty_shipped ?? 0) - ($l->qty_delivered ?? 0)) > 0); @endphp
            <form id="deliver-section" method="POST" action="{{ route('bo.fo-orders.deliver', $order) }}" class="mb-6 border rounded bg-white">
                @csrf
                <div class="p-3 border-b font-medium bg-gray-50">{{ __('ui.bo.fo_orders.actions.deliver') }}</div>
                <div class="overflow-x-auto p-3">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-600">
                                <th class="p-2">{{ __('ui.labels.item') }}</th>
                                <th class="p-2">{{ __('ui.quantity') }}</th>
                                <th class="p-2">{{ __('ui.bo.fo_orders.shipped') }}</th>
                                <th class="p-2">{{ __('ui.bo.fo_orders.delivered') }}</th>
                                <th class="p-2">{{ __('ui.bo.fo_orders.to_deliver') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->lines as $l)
                                <tr class="border-t">
                                    <td class="p-2">{{ $l->stockItem->name ?? '-' }}</td>
                                    <td class="p-2">{{ $l->qty }}</td>
                                    <td class="p-2">{{ $l->qty_shipped ?? 0 }}</td>
                                    <td class="p-2">{{ $l->qty_delivered ?? 0 }}</td>
                                    <td class="p-2">
                                        <input type="number" min="0" max="{{ ($l->qty_shipped ?? 0) - ($l->qty_delivered ?? 0) }}" 
                                               name="lines[{{ $l->id }}]" 
                                               value="{{ old('lines.'.$l->id, max(0, ($l->qty_shipped ?? 0) - ($l->qty_delivered ?? 0))) }}" 
                                               class="form-input w-24">
                                        @error('lines.'.$l->id)
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-2 p-3 border-t bg-gray-50">
                    <button type="submit" class="btn btn-primary {{ $anyDeliverable ? '' : 'opacity-50 cursor-not-allowed' }}" 
                            {{ $anyDeliverable ? '' : 'disabled' }}>
                        {{ __('ui.bo.fo_orders.actions.deliver') }}
                    </button>
                </div>
            </form>
        @endif
    @endcan

    <!-- Order Lines Table (for all statuses) -->
    <div class="border rounded bg-white mb-6">
        <div class="p-3 border-b font-medium bg-gray-50">{{ __('ui.bo.fo_orders.order_lines') }}</div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-600 bg-gray-50">
                        <th class="p-3 border-b">{{ __('ui.labels.item') }}</th>
                        <th class="p-3 border-b text-center">{{ __('ui.quantity') }}</th>
                        <th class="p-3 border-b text-center">{{ __('ui.bo.fo_orders.picked') }}</th>
                        <th class="p-3 border-b text-center">{{ __('ui.bo.fo_orders.shipped') }}</th>
                        <th class="p-3 border-b text-center">{{ __('ui.bo.fo_orders.delivered') }}</th>
                        <th class="p-3 border-b text-center">{{ __('ui.labels.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->lines as $l)
                        @php
                            $qtyPicked = $l->qty_picked ?? 0;
                            $qtyShipped = $l->qty_shipped ?? 0;
                            $qtyDelivered = $l->qty_delivered ?? 0;
                            
                            $lineStatus = 'pending';
                            if ($qtyDelivered >= $l->qty) {
                                $lineStatus = 'delivered';
                            } elseif ($qtyShipped > 0) {
                                $lineStatus = 'shipped';
                            } elseif ($qtyPicked > 0) {
                                $lineStatus = 'picked';
                            }
                            
                            $statusColors = [
                                'pending' => 'bg-gray-100 text-gray-800',
                                'picked' => 'bg-amber-100 text-amber-800',
                                'shipped' => 'bg-indigo-100 text-indigo-800',
                                'delivered' => 'bg-green-100 text-green-800'
                            ];
                        @endphp
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">
                                <div class="font-medium">{{ $l->stockItem->name ?? '-' }}</div>
                                @if($l->stockItem && $l->stockItem->description)
                                    <div class="text-xs text-gray-500">{{ $l->stockItem->description }}</div>
                                @endif
                            </td>
                            <td class="p-3 text-center font-medium">{{ $l->qty }}</td>
                            <td class="p-3 text-center">
                                <span class="{{ $qtyPicked > 0 ? 'font-medium text-amber-700' : 'text-gray-400' }}">
                                    {{ $qtyPicked }}
                                </span>
                            </td>
                            <td class="p-3 text-center">
                                <span class="{{ $qtyShipped > 0 ? 'font-medium text-indigo-700' : 'text-gray-400' }}">
                                    {{ $qtyShipped }}
                                </span>
                            </td>
                            <td class="p-3 text-center">
                                <span class="{{ $qtyDelivered > 0 ? 'font-medium text-green-700' : 'text-gray-400' }}">
                                    {{ $qtyDelivered }}
                                </span>
                            </td>
                            <td class="p-3 text-center">
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $statusColors[$lineStatus] }}">
                                    {{ __('ui.bo.fo_orders.line_status.' . $lineStatus) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Final Actions -->
    <div class="flex flex-wrap gap-3 items-center justify-between">
        <div class="flex flex-wrap gap-2">
            @can('close', $order)
                @if(in_array($status, ['DELIVERED', 'SHIPPED']))
                    <form method="POST" action="{{ route('bo.fo-orders.close', $order) }}" class="inline">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            {{ __('ui.bo.fo_orders.actions.close') }}
                        </button>
                    </form>
                @endif
            @endcan
            
            @can('cancel', $order)
                @if(!in_array($status, ['CLOSED', 'CANCELLED']))
                    <form method="POST" action="{{ route('bo.fo-orders.cancel', $order) }}" class="inline">
                        @csrf
                        <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('ui.common.confirm_cancel') }}')">
                            {{ __('ui.bo.fo_orders.actions.cancel') }}
                        </button>
                    </form>
                @endif
            @endcan
        </div>
        
        <div class="text-sm text-gray-500">
            {{ __('ui.common.last_updated') }}: {{ $order->updated_at->format('d/m/Y H:i') }}
        </div>
    </div>

</div>
@endsection
