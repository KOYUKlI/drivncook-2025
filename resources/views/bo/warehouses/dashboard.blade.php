@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('title', __('warehouse_dashboard.inventory.dashboard.title', ['warehouse' => $warehouse->name]))

@section('content')
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="mb-5 md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    {{ __('warehouse_dashboard.inventory.dashboard.title', ['warehouse' => $warehouse->name]) }}
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                <a href="{{ route('bo.warehouses.inventory.show', $warehouse->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                    {{ __('warehouse_dashboard.inventory.view_inventory') }}
                </a>
                <a href="{{ route('bo.warehouses.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                    {{ __('warehouse_dashboard.inventory.back_to_warehouses') }}
                </a>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-5 mb-8">
            <!-- Active Items -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            {{ __('warehouse_dashboard.inventory.dashboard.kpis.active_items') }}
                        </dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900">
                            {{ $kpis['active_items_count'] }}
                        </dd>
                    </dl>
                </div>
            </div>

            <!-- Low Stock -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            {{ __('warehouse_dashboard.inventory.dashboard.kpis.low_stock') }}
                        </dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900">
                            {{ $kpis['low_stock_count'] }}
                        </dd>
                    </dl>
                </div>
            </div>

            <!-- Movements 7 Days -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            {{ __('warehouse_dashboard.inventory.dashboard.kpis.movements_7days') }}
                        </dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900">
                            {{ $kpis['movements_7days'] }}
                        </dd>
                    </dl>
                </div>
            </div>

            <!-- Movements 30 Days -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            {{ __('warehouse_dashboard.inventory.dashboard.kpis.movements_30days') }}
                        </dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900">
                            {{ $kpis['movements_30days'] }}
                        </dd>
                    </dl>
                </div>
            </div>

            <!-- PO Received 30 Days -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            {{ __('warehouse_dashboard.inventory.dashboard.kpis.po_received_30days') }}
                        </dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900">
                            {{ $kpis['po_received_30days'] }}
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Stock Movements -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-3 sm:mb-0">
                    {{ __('warehouse_dashboard.inventory.dashboard.recent_movements') }}
                </h3>
                
                <!-- Export Button -->
                <form action="{{ route('bo.warehouses.dashboard.export', $warehouse->id) }}" method="GET" class="inline-flex ml-0 sm:ml-4">
                    <input type="hidden" name="from_date" value="{{ $fromDate }}">
                    <input type="hidden" name="to_date" value="{{ $toDate }}">
                    <input type="hidden" name="movement_type" value="{{ $movementType }}">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        {{ __('warehouse_dashboard.inventory.dashboard.export.button') }}
                    </button>
                </form>
            </div>
            
            <!-- Filters -->
            <div class="bg-gray-50 px-4 py-5 sm:px-6 border-b border-gray-200">
                <form action="{{ route('bo.warehouses.dashboard', $warehouse->id) }}" method="GET" class="flex flex-col sm:flex-row sm:items-end space-y-4 sm:space-y-0 sm:space-x-4">
                    <div class="w-full sm:w-auto">
                        <label for="from_date" class="block text-sm font-medium text-gray-700">
                            {{ __('warehouse_dashboard.inventory.dashboard.filters.from_date') }}
                        </label>
                        <input type="date" name="from_date" id="from_date" value="{{ $fromDate }}" max="{{ $toDate }}" class="mt-1 block w-full sm:text-sm border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500">
                    </div>
                    
                    <div class="w-full sm:w-auto">
                        <label for="to_date" class="block text-sm font-medium text-gray-700">
                            {{ __('warehouse_dashboard.inventory.dashboard.filters.to_date') }}
                        </label>
                        <input type="date" name="to_date" id="to_date" value="{{ $toDate }}" min="{{ $fromDate }}" class="mt-1 block w-full sm:text-sm border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500">
                    </div>
                    
                    <div class="w-full sm:w-auto">
                        <label for="movement_type" class="block text-sm font-medium text-gray-700">
                            {{ __('warehouse_dashboard.inventory.dashboard.filters.movement_type') }}
                        </label>
                        <select id="movement_type" name="movement_type" class="mt-1 block w-full sm:text-sm border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500">
                            <option value="">{{ __('warehouse_dashboard.inventory.dashboard.filters.all_types') }}</option>
                            @foreach($movementTypes as $type)
                                <option value="{{ $type }}" {{ $type === $movementType ? 'selected' : '' }}>
                                    {{ __('warehouse_dashboard.inventory.movement_types.' . $type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="w-full sm:w-auto flex space-x-3">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                            {{ __('warehouse_dashboard.inventory.dashboard.filters.apply') }}
                        </button>
                        
                        <a href="{{ route('bo.warehouses.dashboard', $warehouse->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                            {{ __('warehouse_dashboard.inventory.dashboard.filters.reset') }}
                        </a>
                    </div>
                </form>
            </div>
            
            <!-- Movements Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('warehouse_dashboard.inventory.dashboard.table.date') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('warehouse_dashboard.inventory.dashboard.table.type') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('warehouse_dashboard.inventory.dashboard.table.item') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('warehouse_dashboard.inventory.dashboard.table.quantity') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('warehouse_dashboard.inventory.dashboard.table.user') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('warehouse_dashboard.inventory.dashboard.table.reason') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($movements as $movement)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $movement->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $typeClasses = [
                                            'receipt' => 'bg-green-100 text-green-800',
                                            'withdrawal' => 'bg-red-100 text-red-800',
                                            'adjustment' => 'bg-blue-100 text-blue-800',
                                            'transfer_in' => 'bg-purple-100 text-purple-800',
                                            'transfer_out' => 'bg-yellow-100 text-yellow-800',
                                        ];
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $typeClasses[$movement->type] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ __('warehouse_dashboard.inventory.movement_types.' . $movement->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $movement->stockItem->name ?? 'Unknown Item' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium {{ in_array($movement->type, ['withdrawal', 'transfer_out']) ? 'text-red-600' : 'text-green-600' }}">
                                    {{ in_array($movement->type, ['withdrawal', 'transfer_out']) ? '-' : '+' }}{{ $movement->quantity }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $movement->user->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                    {{ $movement->reason ?? 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    {{ __('warehouse_dashboard.inventory.dashboard.no_movements') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $movements->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>
@endsection
