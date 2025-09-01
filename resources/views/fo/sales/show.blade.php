@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.fo-sidebar')
@endsection

@section('title', __('ui.fo.sales.show.title'))

@section('content')
<div class="container py-6">
    <div class="flex justify-between mb-4">
        <h1 class="text-2xl font-bold">{{ __('ui.fo.sales.show.title') }}</h1>
        <a href="{{ route('fo.sales.index') }}" class="btn btn-ghost">
            {{ __('ui.fo.sales.show.back_to_list') }}
        </a>
    </div>

    @if (session('status'))
        <div class="alert alert-success shadow-lg mb-6">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>{{ session('status') }}</span>
            </div>
        </div>
    @endif

    <div class="card bg-base-100 shadow-xl mb-6">
        <div class="card-body">
            <h2 class="card-title">{{ __('ui.fo.sales.show.sale_info') }}</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="font-bold">{{ __('ui.fo.sales.show.sale_id') }}</p>
                    <p>{{ $sale->id }}</p>
                </div>
                <div>
                    <p class="font-bold">{{ __('ui.fo.sales.show.sale_date') }}</p>
                    <p>{{ $sale->sale_date->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="font-bold">{{ __('ui.fo.sales.show.created_at') }}</p>
                    <p>{{ $sale->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="font-bold">{{ __('ui.fo.sales.show.total') }}</p>
                    <p class="text-lg font-bold">{{ number_format($sale->total_cents / 100, 2) }} €</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-base-100 shadow-xl mb-6">
        <div class="card-body">
            <h2 class="card-title">{{ __('ui.fo.sales.show.sale_items') }}</h2>
            
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>{{ __('ui.fo.sales.show.table.item') }}</th>
                            <th class="text-right">{{ __('ui.fo.sales.show.table.quantity') }}</th>
                            <th class="text-right">{{ __('ui.fo.sales.show.table.unit_price') }}</th>
                            <th class="text-right">{{ __('ui.fo.sales.show.table.subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sale->lines as $line)
                            <tr>
                                <td>
                                    @if ($line->stock_item_id)
                                        {{ $line->stockItem->name ?? __('ui.fo.sales.show.unknown_item') }}
                                    @else
                                        {{ $line->item_label ?? __('ui.fo.sales.show.custom_item') }}
                                    @endif
                                </td>
                                <td class="text-right">{{ $line->qty }}</td>
                                <td class="text-right">{{ number_format($line->unit_price_cents / 100, 2) }} €</td>
                                <td class="text-right">{{ number_format($line->qty * $line->unit_price_cents / 100, 2) }} €</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-right">{{ __('ui.fo.sales.show.table.total') }}</th>
                            <th class="text-right">{{ number_format($sale->total_cents / 100, 2) }} €</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
