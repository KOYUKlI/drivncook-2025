@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.fo.dashboard'), 'url' => route('fo.dashboard')],
        ['title' => __('ui.fo.sales.title')]
    ]" />

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.fo.sales.title') }}</h1>
            <p class="text-gray-600">{{ __('ui.fo.sales.subtitle') }}</p>
        </div>
        @can('create', \App\Models\Sale::class)
        <a href="{{ route('fo.sales.create') }}" class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            {{ __('ui.fo.sales.new_sale') }}
        </a>
        @endcan
    </div>

    <!-- Period Filter and Stats -->
    <div class="mb-8">
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">{{ __('ui.fo.sales.filters.title') }}</h3>
            </div>
            <div class="p-6">
                <form method="GET" class="flex flex-col sm:flex-row sm:items-end sm:space-x-4 space-y-4 sm:space-y-0">
                    <div class="flex-1">
                        <label for="from" class="block text-sm font-medium text-gray-700">
                            {{ __('ui.fo.sales.filters.from') }}
                        </label>
                        <input type="date" 
                               name="from" 
                               id="from" 
                               value="{{ request('from', $stats['period_from']->format('Y-m-d')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                    </div>
                    
                    <div class="flex-1">
                        <label for="to" class="block text-sm font-medium text-gray-700">
                            {{ __('ui.fo.sales.filters.to') }}
                        </label>
                        <input type="date" 
                               name="to" 
                               id="to" 
                               value="{{ request('to', $stats['period_to']->format('Y-m-d')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                    </div>
                    
                    <div class="flex space-x-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                            {{ __('ui.fo.sales.filters.apply') }}
                        </button>
                        <a href="{{ route('fo.sales.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                            {{ __('ui.fo.sales.filters.reset') }}
                        </a>
                    </div>
                </form>
                
                <!-- Period Summary -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="text-center p-4 bg-orange-50 rounded-lg">
                            <div class="text-2xl font-bold text-orange-600">{{ $stats['count'] }}</div>
                            <div class="text-sm text-gray-600">{{ __('ui.fo.sales.stats.total_sales') }}</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ number_format($stats['sum_cents'] / 100, 2, ',', ' ') }}€</div>
                            <div class="text-sm text-gray-600">{{ __('ui.fo.sales.stats.total_amount') }}</div>
                        </div>
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ $stats['count'] > 0 ? number_format($stats['sum_cents'] / 100 / $stats['count'], 2, ',', ' ') : '0,00' }}€</div>
                            <div class="text-sm text-gray-600">{{ __('ui.fo.sales.stats.average_sale') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales List -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">{{ __('ui.fo.sales.list.title') }}</h2>
        </div>

        @if($sales->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.sales.list.date') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.sales.list.items') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.sales.list.amount') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.sales.list.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($sales as $sale)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $sale->created_at->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $sale->created_at->format('H:i') }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                @if($sale->lines->count() > 0)
                                    @foreach($sale->lines->take(2) as $line)
                                        <div>{{ $line->qty }}x {{ $line->stock_item?->name ?? __('ui.fo.sales.list.custom_item') }}</div>
                                    @endforeach
                                    @if($sale->lines->count() > 2)
                                        <div class="text-xs text-gray-500">+{{ $sale->lines->count() - 2 }} {{ __('ui.fo.sales.list.more_items') }}</div>
                                    @endif
                                @else
                                    <span class="text-gray-500">{{ __('ui.fo.sales.list.no_details') }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-lg font-semibold text-gray-900">
                                {{ number_format($sale->total_cents / 100, 2, ',', ' ') }}€
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <button class="text-orange-600 hover:text-orange-900 text-sm">
                                {{ __('ui.fo.sales.list.view') }}
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <!-- Empty State -->
        <div class="px-6 py-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path d="M8 14v20c0 4.418 7.163 8 16 8 1.381 0 2.721-.087 4-.252M8 14c0 4.418 7.163 8 16 8s16-3.582 16-8M8 14c0-4.418 7.163-8 16-8s16 3.582 16 8m0 0v14m-16-4c0 4.418 7.163 8 16 8 1.381 0 2.721-.087 4-.252" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('ui.fo.sales.empty.title') }}</h3>
            <p class="mt-1 text-sm text-gray-500">{{ __('ui.fo.sales.empty.description') }}</p>
            @can('create', \App\Models\Sale::class)
            <div class="mt-6">
                <a href="{{ route('fo.sales.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ __('ui.fo.sales.empty.create_first') }}
                </a>
            </div>
            @endcan
        </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($sales && count($sales) > 0)
    <div class="bg-white px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                {{ __('ui.fo.sales.pagination.showing') }} 
                <span class="font-medium">1</span> 
                {{ __('ui.fo.sales.pagination.to') }} 
                <span class="font-medium">{{ count($sales) }}</span> 
                {{ __('ui.fo.sales.pagination.of') }} 
                <span class="font-medium">{{ count($sales) }}</span> 
                {{ __('ui.fo.sales.pagination.results') }}
            </div>
            <nav class="flex space-x-2">
                <button disabled class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md cursor-not-allowed">
                    {{ __('ui.fo.sales.pagination.previous') }}
                </button>
                <button class="px-3 py-2 text-sm font-medium text-white bg-orange-600 border border-orange-600 rounded-md">
                    1
                </button>
                <button disabled class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md cursor-not-allowed">
                    {{ __('ui.fo.sales.pagination.next') }}
                </button>
            </nav>
        </div>
    </div>
    @endif
</div>
@endsection
