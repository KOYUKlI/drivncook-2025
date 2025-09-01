@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.fo-sidebar')
@endsection

@section('title', __('ui.titles.purchase_order_show'))

@section('content')
<div class="py-6">
    <div class="mb-4">
    <a href="{{ route('fo.replenishments.index') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; {{ __('ui.common.back') }}</a>
    </div>

    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.replenishments.show_title', ['ref' => $order->reference ?? $order->id]) }}</h1>
        <div class="flex items-center gap-2">
            <a href="{{ route('fo.replenishments.delivery-note', $order->id) }}" class="btn-secondary" @disabled(!optional($order->shipped_at))>
                {{ __('ui.replenishments.pdf.download_delivery_note') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="text-sm text-gray-500">{{ __('ui.labels.status') }}</div>
            <div class="mt-1">
                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                    @class([
                        'bg-gray-100 text-gray-800' => strtolower($order->status) === 'draft',
                        'bg-blue-100 text-blue-800' => strtolower($order->status) === 'approved',
                        'bg-yellow-100 text-yellow-800' => strtolower($order->status) === 'picked',
                        'bg-purple-100 text-purple-800' => strtolower($order->status) === 'shipped',
                        'bg-green-100 text-green-800' => strtolower($order->status) === 'delivered' || strtolower($order->status) === 'closed',
                        'bg-red-100 text-red-800' => strtolower($order->status) === 'cancelled',
                    ])">
                    {{ __('ui.replenishments.status.' . strtolower($order->status)) }}
                </span>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="text-sm text-gray-500">{{ __('ui.labels.warehouse') }}</div>
            <div class="mt-1 text-xl font-semibold text-gray-900">{{ $order->warehouse->name ?? __('ui.misc.not_provided') }}</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="text-sm text-gray-500">{{ __('ui.labels.created_at') }}</div>
            <div class="mt-1 text-xl font-semibold text-gray-900">{{ optional($order->created_at)->format('d/m/Y H:i') }}</div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg">
        <div class="p-4">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('ui.common.lines') }}</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.labels.item') }}</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.labels.quantity') }}</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.labels.unit_price') }}</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.labels.total') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php $sumCents = 0; @endphp
                        @foreach($order->lines as $line)
                            @php $sumCents += ($line->unit_price_cents ?? 0); @endphp
                            <tr>
                                <td class="px-4 py-2">{{ $line->stockItem->name ?? '-' }}</td>
                                <td class="px-4 py-2 text-right">{{ $line->qty }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format(($line->unit_price_cents ?? 0) / 100, 2) }} €</td>
                                <td class="px-4 py-2 text-right">{{ number_format(($line->unit_price_cents ?? 0) / 100, 2) }} €</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="px-4 py-2 text-right font-semibold">{{ __('ui.labels.total') }}</td>
                            <td class="px-4 py-2 text-right font-semibold">{{ number_format($sumCents / 100, 2) }} €</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
