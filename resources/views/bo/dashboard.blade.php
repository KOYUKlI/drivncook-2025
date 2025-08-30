@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.dashboard'), 'url' => route('bo.dashboard')]
    ]" />

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.dashboard') }}</h1>
        <p class="text-gray-600">{{ __('ui.back_office_welcome') }}</p>
    </div>

    <!-- Tiles row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-ui.tile 
            title="{{ __('ui.revenue_last_month') }}"
            value="{{ number_format($data['revenue_last_month'] / 100, 0, ',', ' ') }}€"
            color="green"
        />

        <x-ui.tile 
            title="{{ __('ui.compliance_80_20') }}"
            value="{{ $data['compliance_ratio'] }}%"
            :color="$data['compliance_ratio'] >= 80 ? 'green' : 'red'"
        />

        <x-ui.tile 
            title="{{ __('ui.trucks_in_maintenance') }}"
            value="{{ $data['trucks_in_maintenance'] }}"
            color="orange"
        />

        <x-ui.tile 
            title="{{ __('ui.pending_orders') }}"
            value="{{ $data['pending_orders'] }}"
            color="blue"
        />
    </div>

    <!-- Recent Events Table -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">{{ __('ui.recent_events') }}</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.type') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.description') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.time') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($data['recent_events'] as $event)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                            $typeColors = [
                                'sale' => 'bg-green-100 text-green-800',
                                'maintenance' => 'bg-orange-100 text-orange-800',
                                'order' => 'bg-blue-100 text-blue-800'
                            ];
                            @endphp
                            @php
                                $labelKey = match($event['type']) {
                                    'sale' => 'ui.labels.sales',
                                    'maintenance' => 'ui.labels.maintenance',
                                    'order' => 'ui.labels.purchase_orders',
                                    default => 'ui.labels.type',
                                };
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $typeColors[$event['type']] }}">
                                {{ __($labelKey) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $event['description'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $event['time'] }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
