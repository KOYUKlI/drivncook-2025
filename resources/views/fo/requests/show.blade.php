@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.fo-sidebar')
@endsection

@section('content')
<x-ui.page-header :title="__('ui.fo.orders_request.show.title', ['ref' => $order->reference])" />
<div class="max-w-4xl mx-auto">
    <x-ui.card>
        <div class="text-sm text-gray-600">{{ __('ui.labels.status') }}: {{ $order->status }}</div>
        <div class="mt-4">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500">
                        <th class="px-2 py-1">{{ __('ui.labels.item') }}</th>
                        <th class="px-2 py-1">{{ __('ui.quantity') }}</th>
                        <th class="px-2 py-1">{{ __('ui.labels.unit_price') }}</th>
                        <th class="px-2 py-1">{{ __('ui.total') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($order->lines as $l)
                        <tr>
                            <td class="px-2 py-1">{{ $l->stockItem->name ?? __('ui.sales.show.unknown_item') }}</td>
                            <td class="px-2 py-1">{{ $l->qty }}</td>
                            <td class="px-2 py-1">{{ number_format($l->unit_price_cents/100, 2, app()->getLocale()==='fr'?',':'.') }} {{ __('ui.misc.euro') }}</td>
                            <td class="px-2 py-1">{{ number_format(($l->qty*$l->unit_price_cents)/100, 2, app()->getLocale()==='fr'?',':'.') }} {{ __('ui.misc.euro') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4 text-sm">
            {{ __('ui.replenishments.csv.ratio8020') }}: {{ number_format((float)$order->corp_ratio_cached, 2) }}%
            @if((float)$order->corp_ratio_cached < 80)
                <span class="ml-2 inline-flex items-center text-amber-700 bg-amber-100 px-2 py-0.5 rounded text-xs">
                    {{ __('ui.fo.orders_request.ratio_warning_hint') }}
                </span>
            @endif
        </div>
        @can('submit', $order)
            <form method="POST" action="{{ route('fo.orders.submit', $order) }}" class="mt-4">
                @csrf
                <x-ui.button type="submit">{{ __('ui.fo.orders_request.actions.submit') }}</x-ui.button>
            </form>
        @endcan
    </x-ui.card>
    <div class="mt-4"><a href="{{ route('fo.orders.index') }}" class="text-sm text-gray-600">{{ __('ui.common.back') }}</a></div>
</div>
@endsection
