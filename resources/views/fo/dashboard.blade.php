@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.dashboard')]
    ]" />

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.welcome_franchisee') }}</h1>
        <p class="text-gray-600">{{ __('ui.franchisee_dashboard_subtitle') }}</p>
    </div>

    <!-- Tiles row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-ui.tile 
            title="{{ __('ui.monthly_sales') }}"
            value="{{ number_format($data['monthly_sales'] / 100, 0, ',', ' ') }}€"
            subtitle="+{{ $data['sales_growth'] }}% {{ __('ui.vs_last_month') }}"
            color="green"
            :icon="'<svg class=\"w-6 h-6\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M13 7h8m0 0v8m0-8l-8 8-4-4-6 6\"></path></svg>'"
        />

        <x-ui.tile 
            title="{{ __('ui.pending_orders') }}"
            value="{{ $data['pending_orders'] }}"
            color="blue"
            :icon="'<svg class=\"w-6 h-6\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2\"></path></svg>'"
        />

        <x-ui.tile 
            title="{{ __('ui.truck_status') }}"
            value="{{ __('ui.' . $data['truck_status']) }}"
            :color="$data['truck_status'] === 'active' ? 'green' : 'orange'"
            :icon="'<svg class=\"w-6 h-6\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z\"></path></svg>'"
        />

        <x-ui.tile 
            title="{{ __('ui.today_location') }}"
            value="{{ __('ui.ready_to_deploy') }}"
            color="gray"
            :icon="'<svg class=\"w-6 h-6\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z\"></path><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M15 11a3 3 0 11-6 0 3 3 0 016 0z\"></path></svg>'"
        />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Sales -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('ui.recent_sales') }}</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($data['recent_sales'] as $sale)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $sale['location'] }}</div>
                            <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($sale['date'])->format('d/m/Y') }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-semibold text-gray-900">{{ number_format($sale['amount'] / 100, 2, ',', ' ') }}€</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('fo.sales.index') }}" class="text-orange-600 hover:text-orange-700 text-sm font-medium">
                        {{ __('ui.view_all_sales') }} →
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('ui.quick_actions') }}</h2>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @foreach($data['quick_links'] as $link)
                    <a href="{{ $link['route'] === '#' ? '#' : route($link['route']) }}" 
                       class="flex items-center p-3 border border-gray-200 rounded-md hover:bg-gray-50 transition-colors">
                        <div class="flex-shrink-0 w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                            @if($link['icon'] === 'document')
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            @elseif($link['icon'] === 'plus')
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            @elseif($link['icon'] === 'chart')
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            @endif
                        </div>
                        <span class="text-sm font-medium text-gray-900">{{ $link['title'] }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
