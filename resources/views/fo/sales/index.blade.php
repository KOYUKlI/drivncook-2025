@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.dashboard'), 'route' => 'fo.dashboard'],
        ['title' => __('ui.sales')]
    ]" />

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.sales') }}</h1>
            <p class="text-gray-600">{{ __('ui.sales_subtitle') }}</p>
        </div>
    @can('create', \App\Models\Sale::class)
    <a href="{{ route('fo.sales.create') }}" class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            {{ __('ui.new_sale') }}
        </a>
    @endcan
    </div>

    <!-- Sales Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('ui.today') }}</div>
            <div class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['today_sales'] / 100, 0, ',', ' ') }}€</div>
            <div class="text-xs text-green-600 mt-1">+{{ $stats['today_count'] }} {{ __('ui.transactions') }}</div>
        </div>
        
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('ui.this_week') }}</div>
            <div class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['week_sales'] / 100, 0, ',', ' ') }}€</div>
            <div class="text-xs text-blue-600 mt-1">{{ $stats['week_count'] }} {{ __('ui.transactions') }}</div>
        </div>
        
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('ui.this_month') }}</div>
            <div class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['month_sales'] / 100, 0, ',', ' ') }}€</div>
            <div class="text-xs text-purple-600 mt-1">{{ $stats['month_count'] }} {{ __('ui.transactions') }}</div>
        </div>
        
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('ui.best_location') }}</div>
            <div class="text-lg font-bold text-gray-900 mt-1">{{ $stats['best_location'] }}</div>
            <div class="text-xs text-orange-600 mt-1">{{ number_format($stats['best_location_sales'] / 100, 0, ',', ' ') }}€</div>
        </div>
    </div>

    <!-- Sales List -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('ui.recent_sales') }}</h2>
                
                <!-- Filters -->
                <div class="flex items-center space-x-4">
                    <select class="text-sm border-gray-300 rounded-md focus:border-orange-500 focus:ring-orange-500">
                        <option>{{ __('ui.all_periods') }}</option>
                        <option>{{ __('ui.today') }}</option>
                        <option>{{ __('ui.this_week') }}</option>
                        <option>{{ __('ui.this_month') }}</option>
                    </select>
                    
                    <select class="text-sm border-gray-300 rounded-md focus:border-orange-500 focus:ring-orange-500">
                        <option>{{ __('ui.all_locations') }}</option>
                        <option>Centre-ville</option>
                        <option>Zone Industrielle</option>
                        <option>Campus Universitaire</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.date') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.location') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.payment_method') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.items') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.amount') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($sales as $sale)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($sale['created_at'])->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $sale['location'] }}</div>
                            <div class="text-xs text-gray-500">{{ $sale['coordinates'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sale['payment_method'] === 'card' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ __('ui.' . $sale['payment_method']) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $sale['items_count'] }} {{ __('ui.items') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-gray-900">
                            {{ number_format($sale['total_amount'] / 100, 2, ',', ' ') }}€
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex justify-center space-x-2">
                                <button class="text-orange-600 hover:text-orange-900">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                                <button class="text-gray-600 hover:text-gray-900">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    {{ __('ui.showing') }} <span class="font-medium">1</span> {{ __('ui.to') }} <span class="font-medium">{{ count($sales) }}</span> {{ __('ui.of') }} <span class="font-medium">{{ count($sales) }}</span> {{ __('ui.results') }}
                </div>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50" disabled>
                        {{ __('ui.previous') }}
                    </button>
                    <button class="px-3 py-1 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        {{ __('ui.next') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
