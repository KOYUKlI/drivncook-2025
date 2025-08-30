@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.dashboard'), 'url' => route('bo.dashboard')],
        ['title' => __('ui.bo.purchase_orders.title'), 'url' => route('bo.purchase-orders.index')],
        ['title' => $order['reference']]
    ]" />

    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.bo.purchase_orders.show.title') }} {{ $order['reference'] }}</h1>
                <p class="text-gray-600">{{ $order['franchisee'] }} - {{ \Carbon\Carbon::parse($order['date'])->format('d/m/Y') }}</p>
            </div>
            
            <div class="flex items-center gap-4">
                @php
                $statusColors = [
                    'Draft' => 'bg-gray-100 text-gray-800',
                    'Approved' => 'bg-blue-100 text-blue-800',
                    'Prepared' => 'bg-yellow-100 text-yellow-800',
                    'Shipped' => 'bg-purple-100 text-purple-800',
                    'Received' => 'bg-green-100 text-green-800',
                    'Cancelled' => 'bg-red-100 text-red-800'
                ];
                $ratioColor = $order['ratio_80_20'] >= 80 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                @endphp
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$order['status']] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ __('ui.bo.purchase_orders.status.' . strtolower($order['status'])) }}
                </span>
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $ratioColor }}">
                    {{ __('ui.bo.purchase_orders.ratio_badge') }}: {{ $order['ratio_80_20'] }}%
                </span>
                <a href="{{ route('bo.purchase-orders.compliance-report') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    {{ __('ui.bo.purchase_orders.compliance_report_link') }}
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-md bg-green-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="mb-6 rounded-md bg-yellow-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-yellow-800">{{ session('warning') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Order Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <x-ui.tile 
            title="{{ __('ui.bo.purchase_orders.summary.total_amount') }}"
            value="{{ number_format($order['total'] / 100, 2, ',', ' ') }}€"
            color="blue"
        />
        
        <x-ui.tile 
            title="{{ __('ui.bo.purchase_orders.summary.compliance_ratio') }}"
            value="{{ $order['ratio_80_20'] }}%"
            :color="$order['ratio_80_20'] >= 80 ? 'green' : 'red'"
        />
        
        <x-ui.tile 
            title="{{ __('ui.bo.purchase_orders.summary.items_count') }}"
            value="{{ count($order['lines']) }}"
            color="gray"
        />
    </div>

    <!-- Workflow Actions -->
    @php
        $workflowMap = [
            'Draft' => ['Approved', 'Cancelled'],
            'Approved' => ['Prepared', 'Cancelled'],
            'Prepared' => ['Shipped', 'Cancelled'],
            'Shipped' => ['Received'],
            'Received' => [],
            'Cancelled' => [],
        ];
        $availableActions = $workflowMap[$order['status']] ?? [];
    @endphp

    @if(!empty($availableActions))
        <div class="mb-8 bg-white rounded-lg border border-gray-200 shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('ui.bo.purchase_orders.workflow.title') }}</h3>
            <div class="flex flex-wrap gap-3">
                @foreach($availableActions as $action)
                    @can('updateStatus', $order)
                        <form method="POST" action="{{ route('bo.purchase-orders.update-status', $order['id']) }}" class="inline">
                            @csrf
                            <input type="hidden" name="status" value="{{ strtolower($action) }}">
                            @php
                                $actionColors = [
                                    'Approved' => 'bg-blue-600 hover:bg-blue-700 text-white',
                                    'Prepared' => 'bg-yellow-600 hover:bg-yellow-700 text-white',
                                    'Shipped' => 'bg-purple-600 hover:bg-purple-700 text-white',
                                    'Received' => 'bg-green-600 hover:bg-green-700 text-white',
                                    'Cancelled' => 'bg-red-600 hover:bg-red-700 text-white',
                                ];
                            @endphp
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md {{ $actionColors[$action] ?? 'bg-gray-600 hover:bg-gray-700 text-white' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    onclick="return confirm('{{ __('ui.bo.purchase_orders.workflow.confirm', ['action' => __('ui.bo.purchase_orders.status.' . strtolower($action))]) }}')">
                                {{ __('ui.bo.purchase_orders.workflow.action_' . strtolower($action)) }}
                            </button>
                        </form>
                    @endcan
                @endforeach
            </div>
        </div>
    @endif

    <!-- Order Lines -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">{{ __('ui.bo.purchase_orders.lines.title') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.purchase_orders.table.item') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.purchase_orders.table.category') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.purchase_orders.table.quantity') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.purchase_orders.table.unit_price') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.purchase_orders.table.total') }}
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
                                {{ __('ui.bo.purchase_orders.category.' . $line['category']) }}
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
                            {{ __('ui.bo.purchase_orders.summary.total_order') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-lg font-bold text-gray-900">
                            {{ number_format($order['total'] / 100, 2, ',', ' ') }}€
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Compliance Analysis -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- 80/20 Compliance -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('ui.bo.purchase_orders.compliance.title') }}</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">{{ __('ui.bo.purchase_orders.compliance.central_items') }}</span>
                    <span class="text-sm font-medium">{{ number_format($order['obligatoire_total'] / 100, 2, ',', ' ') }}€</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">{{ __('ui.bo.purchase_orders.compliance.free_items') }}</span>
                    <span class="text-sm font-medium">{{ number_format($order['libre_total'] / 100, 2, ',', ' ') }}€</span>
                </div>
                <div class="border-t pt-4">
                    <div class="flex items-center justify-between">
                        <span class="text-base font-medium">{{ __('ui.bo.purchase_orders.compliance.ratio') }}</span>
                        <span class="text-lg font-bold {{ $order['calculated_ratio'] >= 80 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $order['calculated_ratio'] }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Compliance Actions -->
        @if($order['ratio_80_20'] < 80)
        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
            <div class="flex items-center mb-4">
                <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <h4 class="text-sm font-medium text-red-800">{{ __('ui.bo.purchase_orders.compliance.warning_title') }}</h4>
            </div>
            <p class="text-sm text-red-700 mb-4">{{ __('ui.bo.purchase_orders.compliance.warning_text', ['ratio' => $order['ratio_80_20']]) }}</p>
            
            @can('validateCompliance', $order)
            <div class="space-y-3">
                <form method="POST" action="{{ route('bo.purchase-orders.validate-compliance', $order['id']) }}" class="inline">
                    @csrf
                    <input type="hidden" name="compliance_status" value="validated">
                    <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        {{ __('ui.bo.purchase_orders.compliance.actions.validate') }}
                    </button>
                </form>
                
                <form method="POST" action="{{ route('bo.purchase-orders.validate-compliance', $order['id']) }}" class="inline ml-3">
                    @csrf
                    <input type="hidden" name="compliance_status" value="needs_review">
                    <button type="submit" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('ui.bo.purchase_orders.compliance.actions.flag_review') }}
                    </button>
                </form>
                
                <form method="POST" action="{{ route('bo.purchase-orders.validate-compliance', $order['id']) }}" class="inline ml-3">
                    @csrf
                    <input type="hidden" name="compliance_status" value="rejected">
                    <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                            onclick="return confirm('{{ __('ui.bo.purchase_orders.compliance.actions.reject_confirm') }}')">
                        {{ __('ui.bo.purchase_orders.compliance.actions.reject') }}
                    </button>
                </form>
            </div>
            @endcan
        </div>
        @else
        <div class="bg-green-50 border border-green-200 rounded-lg p-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <h4 class="text-sm font-medium text-green-800">{{ __('ui.bo.purchase_orders.compliance.compliant_title') }}</h4>
                    <p class="text-sm text-green-700">{{ __('ui.bo.purchase_orders.compliance.compliant_text', ['ratio' => $order['ratio_80_20']]) }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection
