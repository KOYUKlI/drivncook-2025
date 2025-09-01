@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.fo-sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.fo.nav.dashboard'), 'url' => route('fo.dashboard')]
    ]" />
    
    <div class="py-2">
        <h1 class="text-2xl font-semibold text-gray-900 mb-4">{{ __('ui.fo.dashboard.welcome', ['name' => Auth::user()->name]) }}</h1>
        
        <!-- Sales Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <!-- Monthly Sales Card -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 truncate">
                        {{ __('ui.fo.dashboard.monthly_sales') }}
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">
                        {{ number_format($monthlySales / 100, 2) }} €
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-4 sm:px-6 border-t border-gray-200 rounded-b-lg">
                    <div class="text-sm">
                        <a href="{{ route('fo.sales.index') }}" class="font-medium text-orange-600 hover:text-orange-500">
                            {{ __('ui.fo.dashboard.view_details') }} <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Last 30 Days Sales Card -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 truncate">
                        {{ __('ui.fo.dashboard.thirty_days_sales') }}
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">
                        {{ number_format($thirtyDaysSales / 100, 2) }} €
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-4 sm:px-6 border-t border-gray-200 rounded-b-lg">
                    <div class="text-sm">
                        <a href="{{ route('fo.sales.index') }}" class="font-medium text-orange-600 hover:text-orange-500">
                            {{ __('ui.fo.dashboard.view_details') }} <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __('ui.fo.dashboard.quick_actions') }}</h3>
                    <div class="mt-3 max-w-xl text-sm text-gray-500">
                        <p>{{ __('ui.fo.dashboard.quick_actions_description') }}</p>
                    </div>
                    <div class="mt-5">
                        <a href="{{ route('fo.sales.create') }}" class="btn-primary">
                            {{ __('ui.fo.dashboard.new_sale') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Sales -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __('ui.fo.dashboard.recent_sales') }}</h3>
            </div>
            <div class="bg-white overflow-hidden">
                <ul role="list" class="divide-y divide-gray-200">
                    @forelse($recentSales as $sale)
                        <li class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="text-sm font-medium text-orange-600 truncate">
                                    {{ $sale->sale_date->format('d/m/Y') }}
                                </div>
                                <div class="ml-2 flex-shrink-0 flex">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ number_format($sale->total_cents / 100, 2) }} €
                                    </span>
                                </div>
                            </div>
                            <div class="mt-2 sm:flex sm:justify-between">
                                <div class="sm:flex">
                                    <p class="flex items-center text-sm text-gray-500">
                                        {{ $sale->payment_method }}
                                    </p>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="px-4 py-4 sm:px-6 text-center text-gray-500">
                            {{ __('ui.fo.dashboard.no_recent_sales') }}
                        </li>
                    @endforelse
                </ul>
            </div>
            <div class="bg-gray-50 px-4 py-4 sm:px-6 border-t border-gray-200 rounded-b-lg">
                <div class="text-sm">
                    <a href="{{ route('fo.sales.index') }}" class="font-medium text-orange-600 hover:text-orange-500">
                        {{ __('ui.fo.dashboard.view_all_sales') }} <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
