@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.fo-sidebar')
@endsection

@section('title', __('ui.fo.sales.show.title'))

@section('content')
<div class="py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.fo.sales.show.title') }}</h1>
        <a href="{{ route('fo.sales.index') }}" class="btn-secondary">{{ __('ui.fo.sales.show.back_to_list') }}</a>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-md border border-green-300 bg-green-50 text-green-800 p-3 text-sm">{{ session('status') }}</div>
    @endif

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
        <div class="p-4 md:p-6">
            <h2 class="text-base font-semibold text-gray-900 mb-4">{{ __('ui.fo.sales.show.sale_info') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">{{ __('ui.fo.sales.show.sale_id') }}</p>
                    <p class="font-medium text-gray-900">{{ $sale->id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">{{ __('ui.fo.sales.show.sale_date') }}</p>
                    <p class="font-medium text-gray-900">{{ $sale->sale_date->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">{{ __('ui.fo.sales.show.created_at') }}</p>
                    <p class="font-medium text-gray-900">{{ $sale->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">{{ __('ui.fo.sales.show.total') }}</p>
                    <p class="text-lg font-bold text-gray-900">{{ number_format($sale->total_cents / 100, 2) }} €</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
        <div class="p-4 md:p-6">
            <h2 class="text-base font-semibold text-gray-900 mb-4">{{ __('ui.fo.sales.show.sale_items') }}</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.sales.show.table.item') }}</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.sales.show.table.quantity') }}</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.sales.show.table.unit_price') }}</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.sales.show.table.subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($sale->lines as $line)
                            <tr>
                                <td class="px-4 py-2">
                                    @if ($line->stock_item_id)
                                        {{ $line->stockItem->name ?? __('ui.fo.sales.show.unknown_item') }}
                                    @else
                                        {{ $line->item_label ?? __('ui.fo.sales.show.custom_item') }}
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-right">{{ $line->qty }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format($line->unit_price_cents / 100, 2) }} €</td>
                                <td class="px-4 py-2 text-right">{{ number_format($line->qty * $line->unit_price_cents / 100, 2) }} €</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <th colspan="3" class="px-4 py-2 text-right text-sm font-medium text-gray-700">{{ __('ui.fo.sales.show.table.total') }}</th>
                            <th class="px-4 py-2 text-right">{{ number_format($sale->total_cents / 100, 2) }} €</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
