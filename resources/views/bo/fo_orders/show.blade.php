@extends('layouts.app')
@section('content')
<x-ui.page-header :title="__('ui.bo.fo_orders.show.title', ['ref' => $order->reference])" />
<div class="max-w-5xl mx-auto">
    <x-ui.card>
        <div class="text-sm text-gray-600">{{ __('ui.labels.status') }}: {{ $order->status }} · {{ __('ui.replenishments.csv.ratio8020') }}: {{ number_format($ratio, 2) }}%</div>
        <div class="mt-4">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500">
                        <th class="px-2 py-1">{{ __('ui.labels.item') }}</th>
                        <th class="px-2 py-1">{{ __('ui.quantity') }}</th>
                        <th class="px-2 py-1">Pick</th>
                        <th class="px-2 py-1">Ship</th>
                        <th class="px-2 py-1">Deliver</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($order->lines as $l)
                        <tr>
                            <td class="px-2 py-1">{{ $l->stockItem->name ?? '-' }}</td>
                            <td class="px-2 py-1">{{ $l->qty }}</td>
                            <td class="px-2 py-1">{{ $l->qty_picked }}</td>
                            <td class="px-2 py-1">{{ $l->qty_shipped }}</td>
                            <td class="px-2 py-1">{{ $l->qty_delivered }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @can('approve', $order)
        <form method="POST" action="{{ route('bo.fo-orders.approve', $order) }}" class="mt-4 flex gap-2 items-center">
            @csrf
            <label class="text-sm">WH</label>
            <input type="text" name="warehouse_id" class="border rounded px-2 py-1 text-sm" placeholder="warehouse_id" />
            <x-ui.button type="submit">{{ __('ui.bo.fo_orders.actions.approve') }}</x-ui.button>
        </form>
        @endcan

        @can('pick', $order)
        <form method="POST" action="{{ route('bo.fo-orders.pick', $order) }}" class="mt-4 space-y-2">
            @csrf
            @foreach($order->lines as $l)
                <div class="flex items-center gap-2 text-sm">
                    <span class="w-48 truncate">{{ $l->stockItem->name ?? '-' }}</span>
                    <input type="number" min="0" max="{{ $l->qty }}" name="lines[{{ $l->id }}]" value="{{ $l->qty_picked ?? 0 }}" class="border rounded px-2 py-1 w-28" />
                </div>
            @endforeach
            <x-ui.button type="submit">{{ __('ui.bo.fo_orders.actions.pick') }}</x-ui.button>
        </form>
        @endcan

        @can('ship', $order)
        <form method="POST" action="{{ route('bo.fo-orders.ship', $order) }}" class="mt-4 space-y-2">
            @csrf
            @foreach($order->lines as $l)
                <div class="flex items-center gap-2 text-sm">
                    <span class="w-48 truncate">{{ $l->stockItem->name ?? '-' }}</span>
                    <input type="number" min="0" max="{{ $l->qty - ($l->qty_shipped ?? 0) }}" name="lines[{{ $l->id }}]" value="0" class="border rounded px-2 py-1 w-28" />
                </div>
            @endforeach
            <x-ui.button type="submit">{{ __('ui.bo.fo_orders.actions.ship') }}</x-ui.button>
        </form>
        @endcan

        @can('deliver', $order)
        <form method="POST" action="{{ route('bo.fo-orders.deliver', $order) }}" class="mt-4 space-y-2">
            @csrf
            @foreach($order->lines as $l)
                <div class="flex items-center gap-2 text-sm">
                    <span class="w-48 truncate">{{ $l->stockItem->name ?? '-' }}</span>
                    <input type="number" min="0" max="{{ ($l->qty_shipped ?? 0) - ($l->qty_delivered ?? 0) }}" name="lines[{{ $l->id }}]" value="0" class="border rounded px-2 py-1 w-28" />
                </div>
            @endforeach
            <x-ui.button type="submit">{{ __('ui.bo.fo_orders.actions.deliver') }}</x-ui.button>
        </form>
        @endcan

        <div class="mt-4 flex gap-2">
            @can('close', $order)
            <form method="POST" action="{{ route('bo.fo-orders.close', $order) }}">@csrf<x-ui.button>{{ __('ui.bo.fo_orders.actions.close') }}</x-ui.button></form>
            @endcan
            @can('cancel', $order)
            <form method="POST" action="{{ route('bo.fo-orders.cancel', $order) }}">@csrf<x-ui.button>{{ __('ui.bo.fo_orders.actions.cancel') }}</x-ui.button></form>
            @endcan
        </div>
    </x-ui.card>
    <div class="mt-4"><a href="{{ route('bo.fo-orders.index') }}" class="text-sm text-gray-600">{{ __('ui.common.back') }}</a></div>
</div>
@endsection
