@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.dashboard'), 'url' => route('bo.dashboard')],
        ['title' => __('ui.franchisees'), 'url' => route('bo.franchisees.index')],
        ['title' => $franchisee->name]
    ]" />

    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $franchisee->name }}</h1>
                <p class="text-gray-600">{{ $franchisee->email }} • {{ __('ui.bo.franchisees.franchisee') }}</p>
            </div>
            
            <div class="flex items-center space-x-3">
                @php
                    $statusColors = [
                        'active' => 'bg-green-100 text-green-800',
                        'inactive' => 'bg-gray-100 text-gray-800'
                    ];
                @endphp
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$franchisee->ui_status ?? 'active'] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ __('ui.status.' . ($franchisee->ui_status ?? 'active')) }}
                </span>
                
                <a href="{{ route('bo.franchisees.edit', $franchisee->id) }}" class="px-3 py-2 text-sm font-medium text-white bg-orange-500 rounded-md hover:bg-orange-600">
                    {{ __('ui.actions.edit') }}
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left column: Franchisee info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Franchisee Information -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('ui.bo.franchisees.information') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('ui.labels.name') }}</dt>
                        <dd class="text-sm text-gray-900">{{ $franchisee->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('ui.labels.email') }}</dt>
                        <dd class="text-sm text-gray-900">{{ $franchisee->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('ui.labels.phone') }}</dt>
                        <dd class="text-sm text-gray-900">{{ $franchisee->phone ?? __('ui.bo.franchisees.not_provided') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('ui.labels.status') }}</dt>
                        <dd class="text-sm text-gray-900">{{ __('ui.status.' . ($franchisee->ui_status ?? 'active')) }}</dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">{{ __('ui.labels.billing_address') }}</dt>
                        <dd class="text-sm text-gray-900">{{ $franchisee->billing_address ?? __('ui.bo.franchisees.not_provided') }}</dd>
                    </div>
                </div>
            </div>

            <!-- Assigned Trucks -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('ui.bo.franchisees.assigned_trucks') }}</h3>
                @if($franchisee->trucks->count() > 0)
                    <div class="space-y-3">
                        @foreach($franchisee->trucks as $truck)
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-md">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $truck->code }}</p>
                                    <p class="text-xs text-gray-500">{{ $truck->license_plate ?? 'Plaque non renseignée' }}</p>
                                </div>
                            </div>
                            @php
                            $truckStatusColors = [
                                'active' => 'bg-green-100 text-green-800',
                                'maintenance' => 'bg-orange-100 text-orange-800',
                                'inactive' => 'bg-gray-100 text-gray-800'
                            ];
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $truckStatusColors[$truck->status] }}">
                                {{ __('ui.status.' . $truck->status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">{{ __('ui.bo.franchisees.no_trucks') }}</p>
                @endif
            </div>

            <!-- Sales Performance -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('ui.bo.franchisees.sales_performance') }}</h3>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-sm font-medium text-blue-600">{{ __('ui.bo.franchisees.sales_30d') }}</div>
                        <div class="text-2xl font-bold text-blue-900">{{ $stats['sales_count_30d'] ?? 0 }}</div>
                        <div class="text-sm text-blue-600">{{ number_format(($stats['total_revenue_30d'] ?? 0) / 100, 2, ',', ' ') }}€</div>
                    </div>
                    
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="text-sm font-medium text-green-600">{{ __('ui.bo.franchisees.sales_60d') }}</div>
                        <div class="text-2xl font-bold text-green-900">{{ $stats['sales_count_60d'] ?? 0 }}</div>
                        <div class="text-sm text-green-600">{{ number_format(($stats['total_revenue_60d'] ?? 0) / 100, 2, ',', ' ') }}€</div>
                    </div>
                </div>

                @if($stats['avg_transaction'] ?? 0 > 0)
                    <div class="pt-4 border-t border-gray-200">
                        <div class="text-sm font-medium text-gray-500">{{ __('ui.bo.franchisees.avg_transaction') }}</div>
                        <div class="text-lg font-bold text-gray-900">{{ number_format(($stats['avg_transaction'] ?? 0) / 100, 2, ',', ' ') }}€</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right column: Reports -->
        <div class="space-y-6">
            <!-- PDF Reports -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('ui.bo.franchisees.reports') }}</h3>
                @if($reports->count() > 0)
                    <div class="space-y-3">
                        @foreach($reports as $report)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $report->filename }}</p>
                                        <p class="text-xs text-gray-500">{{ $report->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('fo.reports.download', $report->id) }}" 
                                   class="text-orange-600 hover:text-orange-800 text-sm font-medium">
                                    {{ __('ui.actions.download') }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">{{ __('ui.bo.franchisees.no_reports') }}</p>
                @endif
            </div>
        </div>
    </div>
@endsection
