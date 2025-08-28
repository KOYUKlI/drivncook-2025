@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.dashboard'), 'url' => route('bo.dashboard')],
        ['title' => __('ui.purchase_orders'), 'url' => route('bo.purchase-orders.index')],
        ['title' => __('ui.compliance_report')]
    ]" />

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.compliance_report') }}</h1>
        <p class="text-gray-600">{{ __('ui.compliance_report_subtitle') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <x-ui.tile title="{{ __('ui.total_orders') }}" :value="$complianceData['total_orders']" color="gray" />
        <x-ui.tile title="{{ __('ui.compliant_orders') }}" :value="$complianceData['compliant_orders']" color="green" />
        <x-ui.tile title="{{ __('ui.non_compliant_orders') }}" :value="$complianceData['non_compliant_orders']" color="red" />
        <x-ui.tile title="{{ __('ui.compliance_rate') }}" value="{{ $complianceData['compliance_rate'] }}%" color="blue" />
    </div>

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">{{ __('ui.by_franchisee') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.franchisee') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.orders') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.compliance_rate') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.average_ratio') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($complianceData['by_franchisee'] as $row)
                    <tr>
                        <td class="px-6 py-4">{{ $row['name'] }}</td>
                        <td class="px-6 py-4">{{ $row['orders'] }}</td>
                        <td class="px-6 py-4">{{ $row['compliance_rate'] }}%</td>
                        <td class="px-6 py-4">{{ $row['avg_ratio'] }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
