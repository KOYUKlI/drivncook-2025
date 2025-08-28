@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.dashboard'), 'url' => route('bo.dashboard')],
        ['title' => __('ui.purchase_orders'), 'url' => route('bo.purchase-orders.index')],
        ['title' => $order['reference']]
    ]" />

    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.purchase_order') }} {{ $order['reference'] }}</h1>
                <p class="text-gray-600">{{ $order['franchisee'] }} - {{ \Carbon\Carbon::parse($order['date'])->format('d/m/Y') }}</p>
            </div>
            
            <div class="flex items-center gap-4">
                @php
                $statusColors = [
                    'completed' => 'bg-green-100 text-green-800',
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'cancelled' => 'bg-red-100 text-red-800'
                ];
                $ratioColor = $order['ratio_80_20'] >= 80 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                @endphp
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$order['status']] }}">
                    {{ __('ui.' . $order['status']) }}
                </span>
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $ratioColor }}">
                    {{ __('ui.ratio') }}: {{ $order['ratio_80_20'] }}%
                </span>
                <a href="{{ route('bo.purchase-orders.compliance-report') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    {{ __('ui.view_compliance_report') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <x-ui.tile 
            title="{{ __('ui.total_amount') }}"
            value="{{ number_format($order['total'] / 100, 2, ',', ' ') }}€"
            color="blue"
        />
        
        <x-ui.tile 
            title="{{ __('ui.compliance_ratio') }}"
            value="{{ $order['ratio_80_20'] }}%"
            :color="$order['ratio_80_20'] >= 80 ? 'green' : 'red'"
        />
        
        <x-ui.tile 
            title="{{ __('ui.items_count') }}"
            value="{{ count($order['lines']) }}"
            color="gray"
        />
    </div>

    <!-- Order Lines -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">{{ __('ui.order_details') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.item') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.category') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.quantity') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.unit_price') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.total') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($order['lines'] as $line)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $line['item'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                            $categoryColors = [
                                'obligatoire' => 'bg-blue-100 text-blue-800',
                                'libre' => 'bg-gray-100 text-gray-800'
                            ];
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $categoryColors[$line['category']] }}">
                                {{ __('ui.' . $line['category']) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                            {{ $line['quantity'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                            {{ number_format($line['price'] / 100, 2, ',', ' ') }}€
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900 font-medium">
                            {{ number_format($line['total'] / 100, 2, ',', ' ') }}€
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                            {{ __('ui.total_order') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-lg font-bold text-gray-900">
                            {{ number_format($order['total'] / 100, 2, ',', ' ') }}€
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Compliance Note -->
    @if($order['ratio_80_20'] < 80)
    <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <div>
                <h4 class="text-sm font-medium text-red-800">{{ __('ui.compliance_warning') }}</h4>
                <p class="text-sm text-red-700">{{ __('ui.compliance_warning_text', ['ratio' => $order['ratio_80_20']]) }}</p>
            </div>
        </div>
    </div>
    @endif
@endsection
