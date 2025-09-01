@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.fo-sidebar')
@endsection

@section('title', __('ui.fo.sales.index.title'))

@section('content')
<div class="py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.fo.sales.index.title') }}</h1>
        <a href="{{ route('fo.sales.create') }}" class="btn-primary">
            {{ __('ui.fo.sales.index.create_new') }}
        </a>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
        <div class="p-4 md:p-6">
            <h2 class="text-base font-semibold text-gray-900 mb-4">{{ __('ui.fo.sales.index.filter') }}</h2>
            <form action="{{ route('fo.sales.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-sm text-gray-700 mb-1">{{ __('ui.fo.sales.index.from_date') }}</label>
                    <input type="date" name="from_date" value="{{ $fromDate->format('Y-m-d') }}" class="form-input w-56" />
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1">{{ __('ui.fo.sales.index.to_date') }}</label>
                    <input type="date" name="to_date" value="{{ $toDate->format('Y-m-d') }}" class="form-input w-56" />
                </div>
                <div class="ml-auto flex gap-2">
                    <button type="submit" class="btn-primary">{{ __('ui.fo.sales.index.filter_submit') }}</button>
                    <a href="{{ route('fo.sales.index') }}" class="btn-secondary">{{ __('ui.fo.sales.index.reset') }}</a>
                    <button type="submit" name="export" value="csv" class="btn-secondary">
                        {{ __('ui.fo.sales.index.export_csv') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="text-sm text-gray-500">{{ __('ui.fo.sales.index.period') }}</div>
            <div class="mt-1 text-xl font-semibold text-gray-900">{{ $fromDate->format('d/m/Y') }} - {{ $toDate->format('d/m/Y') }}</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="text-sm text-gray-500">{{ __('ui.fo.sales.index.total_sales') }}</div>
            <div class="mt-1 text-xl font-semibold text-gray-900">{{ $totalSales }}</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="text-sm text-gray-500">{{ __('ui.fo.sales.index.total_amount') }}</div>
            <div class="mt-1 text-xl font-semibold text-gray-900">{{ number_format($totalAmount, 2) }} €</div>
        </div>
    </div>

    <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.sales.index.table.date') }}</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.sales.index.table.items') }}</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.sales.index.table.total') }}</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.sales.index.table.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($sales as $sale)
                <tr>
                    <td class="px-4 py-2">{{ $sale->sale_date->format('d/m/Y') }}</td>
                    <td class="px-4 py-2">{{ $sale->lines()->count() }}</td>
                    <td class="px-4 py-2 text-right">{{ number_format($sale->total_cents / 100, 2) }} €</td>
                    <td class="px-4 py-2 text-right">
                        <a href="{{ route('fo.sales.show', $sale->id) }}" class="text-orange-600 hover:text-orange-800 inline-flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">{{ __('ui.fo.sales.index.no_sales_found') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $sales->links() }}
    </div>
</div>
@endsection
