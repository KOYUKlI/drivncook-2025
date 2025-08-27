@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.dashboard'), 'route' => 'fo.dashboard'],
        ['title' => __('ui.reports')]
    ]" />

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.reports') }}</h1>
        <p class="text-gray-600">{{ __('ui.reports_subtitle') }}</p>
    </div>

    <!-- Report Filters -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('ui.filters') }}</h2>
        
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('ui.start_date') }}</label>
                <input type="date" id="start_date" name="start_date" 
                       value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}"
                       class="w-full border-gray-300 rounded-md focus:border-orange-500 focus:ring-orange-500">
            </div>
            
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('ui.end_date') }}</label>
                <input type="date" id="end_date" name="end_date" 
                       value="{{ request('end_date', now()->format('Y-m-d')) }}"
                       class="w-full border-gray-300 rounded-md focus:border-orange-500 focus:ring-orange-500">
            </div>
            
            <div>
                <label for="report_type" class="block text-sm font-medium text-gray-700 mb-1">{{ __('ui.report_type') }}</label>
                <select id="report_type" name="report_type" class="w-full border-gray-300 rounded-md focus:border-orange-500 focus:ring-orange-500">
                    <option value="sales" {{ request('report_type') === 'sales' ? 'selected' : '' }}>{{ __('ui.sales_report') }}</option>
                    <option value="locations" {{ request('report_type') === 'locations' ? 'selected' : '' }}>{{ __('ui.locations_report') }}</option>
                    <option value="products" {{ request('report_type') === 'products' ? 'selected' : '' }}>{{ __('ui.products_report') }}</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    {{ __('ui.generate_report') }}
                </button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Summary Stats -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('ui.period_summary') }}</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">{{ __('ui.total_sales') }}</span>
                    <span class="text-lg font-bold text-green-600">{{ number_format($summary['total_sales'] / 100, 2, ',', ' ') }}€</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">{{ __('ui.transactions_count') }}</span>
                    <span class="text-lg font-semibold text-gray-900">{{ $summary['transactions_count'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">{{ __('ui.average_transaction') }}</span>
                    <span class="text-lg font-semibold text-gray-900">{{ number_format($summary['avg_transaction'] / 100, 2, ',', ' ') }}€</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">{{ __('ui.best_day') }}</span>
                    <span class="text-lg font-semibold text-gray-900">{{ $summary['best_day'] }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('ui.export_reports') }}</h2>
            <div class="space-y-3">
                <a href="#" class="w-full inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ __('ui.export_pdf') }}
                </a>
                
                <a href="#" class="w-full inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ __('ui.export_excel') }}
                </a>
                
                <a href="#" class="w-full inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    {{ __('ui.email_report') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Report Data -->
    @if(request('report_type') === 'sales' || !request('report_type'))
        <!-- Sales Report -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('ui.sales_by_day') }}</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.date') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.transactions') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.items_sold') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.total_amount') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.avg_transaction') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reportData['daily_sales'] as $day)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($day['date'])->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $day['transactions'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $day['items_sold'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-gray-900">
                                {{ number_format($day['total_amount'] / 100, 2, ',', ' ') }}€
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                                {{ number_format($day['avg_transaction'] / 100, 2, ',', ' ') }}€
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if(request('report_type') === 'locations')
        <!-- Locations Report -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('ui.sales_by_location') }}</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.location') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.visits') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.transactions') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.total_amount') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.conversion_rate') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reportData['location_sales'] as $location)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $location['name'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $location['visits'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $location['transactions'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-gray-900">
                                {{ number_format($location['total_amount'] / 100, 2, ',', ' ') }}€
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                                {{ number_format($location['conversion_rate'], 1) }}%
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if(request('report_type') === 'products')
        <!-- Products Report -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('ui.sales_by_product') }}</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.product') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.quantity_sold') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.unit_price') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.total_revenue') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.share') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reportData['product_sales'] as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product['name'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product['quantity_sold'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                                {{ number_format($product['unit_price'] / 100, 2, ',', ' ') }}€
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-gray-900">
                                {{ number_format($product['total_revenue'] / 100, 2, ',', ' ') }}€
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                                {{ number_format($product['share'], 1) }}%
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
