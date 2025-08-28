@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.dashboard'), 'url' => route('bo.dashboard')],
        ['title' => __('ui.bo.purchase_orders.title'), 'url' => route('bo.purchase-orders.index')],
        ['title' => __('ui.bo.purchase_orders.compliance_report.title')]
    ]" />

    <div class="mb-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.bo.purchase_orders.compliance_report.title') }}</h1>
                <p class="text-gray-600">{{ __('ui.bo.purchase_orders.compliance_report.subtitle') }}</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <form method="GET" class="flex items-center space-x-3">
                    <select name="period" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="current_month" {{ $period === 'current_month' ? 'selected' : '' }}>
                            {{ __('ui.bo.purchase_orders.compliance_report.current_month') }}
                        </option>
                        <option value="last_month" {{ $period === 'last_month' ? 'selected' : '' }}>
                            {{ __('ui.bo.purchase_orders.compliance_report.last_month') }}
                        </option>
                        <option value="current_quarter" {{ $period === 'current_quarter' ? 'selected' : '' }}>
                            {{ __('ui.bo.purchase_orders.compliance_report.current_quarter') }}
                        </option>
                    </select>
                    <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('ui.bo.purchase_orders.compliance_report.update') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Overview Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <x-ui.tile 
            title="{{ __('ui.bo.purchase_orders.compliance_report.stats.total_orders') }}"
            value="{{ $complianceData['total_orders'] }}"
            color="blue"
        />
        
        <x-ui.tile 
            title="{{ __('ui.bo.purchase_orders.compliance_report.stats.compliant_orders') }}"
            value="{{ $complianceData['compliant_orders'] }}"
            color="green"
        />
        
        <x-ui.tile 
            title="{{ __('ui.bo.purchase_orders.compliance_report.stats.non_compliant_orders') }}"
            value="{{ $complianceData['non_compliant_orders'] }}"
            color="red"
        />
        
        <x-ui.tile 
            title="{{ __('ui.bo.purchase_orders.compliance_report.stats.compliance_rate') }}"
            value="{{ number_format($complianceData['compliance_rate'], 1) }}%"
            :color="$complianceData['compliance_rate'] >= 80 ? 'green' : 'red'"
        />
    </div>

    <!-- Overall Compliance -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">{{ __('ui.bo.purchase_orders.compliance_report.overall.title') }}</h3>
        </div>
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium text-gray-700">{{ __('ui.bo.purchase_orders.compliance_report.overall.average_ratio') }}</span>
                <span class="text-lg font-bold {{ $complianceData['average_ratio'] >= 80 ? 'text-green-600' : 'text-red-600' }}">
                    {{ number_format($complianceData['average_ratio'], 1) }}%
                </span>
            </div>
            
            <!-- Progress Bar -->
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-{{ $complianceData['average_ratio'] >= 80 ? 'green' : 'red' }}-600 h-2.5 rounded-full" 
                     style="width: {{ min($complianceData['average_ratio'], 100) }}%"></div>
            </div>
            
            <div class="flex justify-between text-sm text-gray-500 mt-2">
                <span>0%</span>
                <span class="font-medium">{{ __('ui.bo.purchase_orders.compliance_report.overall.target') }}: 80%</span>
                <span>100%</span>
            </div>
        </div>
    </div>

    <!-- By Franchisee -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">{{ __('ui.bo.purchase_orders.compliance_report.by_franchisee.title') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.purchase_orders.compliance_report.by_franchisee.franchisee') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.purchase_orders.compliance_report.by_franchisee.orders') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.purchase_orders.compliance_report.by_franchisee.compliance_rate') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.purchase_orders.compliance_report.by_franchisee.avg_ratio') }}
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.purchase_orders.compliance_report.by_franchisee.status') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($complianceData['by_franchisee'] as $franchiseeData)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $franchiseeData['name'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                            {{ $franchiseeData['orders'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                            {{ number_format($franchiseeData['compliance_rate'], 1) }}%
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium {{ $franchiseeData['avg_ratio'] >= 80 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($franchiseeData['avg_ratio'], 1) }}%
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($franchiseeData['compliance_rate'] >= 80)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ __('ui.bo.purchase_orders.compliance_report.status.compliant') }}
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    {{ __('ui.bo.purchase_orders.compliance_report.status.non_compliant') }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M8 14v20c0 4.418 7.163 8 16 8 1.381 0 2.721-.087 4-.252M8 14c0 4.418 7.163 8 16 8s16-3.582 16-8M8 14c0-4.418 7.163-8 16-8s16 3.582 16 8m0 0v14m-16-4c0 4.418 7.163 8 16 8 1.381 0 2.721-.087 4-.252" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('ui.bo.purchase_orders.compliance_report.empty.title') }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{ __('ui.bo.purchase_orders.compliance_report.empty.description') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-8 flex justify-between">
        <a href="{{ route('bo.purchase-orders.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            {{ __('ui.bo.purchase_orders.compliance_report.back_to_orders') }}
        </a>
        
        <button type="button" onclick="window.print()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            {{ __('ui.bo.purchase_orders.compliance_report.print') }}
        </button>
    </div>
@endsection
