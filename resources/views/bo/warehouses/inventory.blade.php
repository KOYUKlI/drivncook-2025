@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="mb-5 md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    @if(isset($warehouse))
                        {{ __('ui.inventory.title_for_warehouse', ['warehouse' => $warehouse->name]) }}
                    @else
                        {{ __('ui.inventory.title') }}
                    @endif
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                @if(isset($warehouse))
                <a href="{{ route('bo.warehouses.dashboard', $warehouse->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                    {{ __('warehouse_dashboard.inventory.dashboard.menu_title') }}
                </a>
                @endif
                <a href="{{ route('bo.warehouses.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                    {{ __('ui.common.back') }}
                </a>
                <a href="{{ route('bo.stock-movements.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                    {{ __('ui.inventory.create_movement') }}
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-md bg-green-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="p-4 sm:p-6 border-b border-gray-200">
                <form action="{{ route('bo.warehouses.inventory') }}" method="GET" class="mb-0">
                    <div class="flex flex-col md:flex-row md:items-end space-y-4 md:space-y-0 md:space-x-4">
                        <div class="w-full md:w-1/3">
                            <label for="warehouse_id" class="block text-sm font-medium text-gray-700">{{ __('ui.inventory.filter_by_warehouse') }}</label>
                            <select id="warehouse_id" name="warehouse_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm rounded-md">
                                <option value="">{{ __('ui.common.all_warehouses') }}</option>
                                @foreach($warehouses as $warehouseOption)
                                    <option value="{{ $warehouseOption->id }}" {{ isset($warehouse) && $warehouse->id === $warehouseOption->id ? 'selected' : '' }}>
                                        {{ $warehouseOption->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-full md:w-1/3">
                            <label for="stock_item_id" class="block text-sm font-medium text-gray-700">{{ __('ui.inventory.filter_by_stock_item') }}</label>
                            <select id="stock_item_id" name="stock_item_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm rounded-md">
                                <option value="">{{ __('ui.common.all_stock_items') }}</option>
                                @foreach($stockItems as $stockItem)
                                    <option value="{{ $stockItem->id }}" {{ request('stock_item_id') === $stockItem->id ? 'selected' : '' }}>
                                        {{ $stockItem->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-full md:w-1/4 flex items-center space-x-4">
                            <div class="flex items-center h-5">
                                <input id="low_stock" name="low_stock" type="checkbox" value="1" {{ request('low_stock') ? 'checked' : '' }} class="focus:ring-amber-500 h-4 w-4 text-amber-600 border-gray-300 rounded">
                            </div>
                            <div>
                                <label for="low_stock" class="font-medium text-sm text-gray-700">{{ __('ui.inventory.show_low_stock') }}</label>
                            </div>
                        </div>

                        <div class="w-full md:w-auto">
                            <button type="submit" class="w-full md:w-auto inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                                {{ __('ui.common.filter') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @if(!isset($warehouse))
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('ui.warehouses.warehouse') }}
                                </th>
                            @endif
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('ui.stock_items.stock_item') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('ui.inventory.quantity') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('ui.inventory.threshold') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('ui.inventory.status') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('ui.inventory.last_movement') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('ui.common.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($inventoryItems as $item)
                            <tr>
                                @if(!isset($warehouse))
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $item->warehouse->name }}
                                    </td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->stockItem->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->qty_on_hand }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->stockItem->low_stock_threshold }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->isLowStock())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ __('ui.inventory.low_stock') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ __('ui.inventory.in_stock') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    -
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('bo.stock-movements.create') }}" class="text-amber-600 hover:text-amber-900">
                                        {{ __('ui.inventory.add_movement') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ isset($warehouse) ? 6 : 7 }}" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    {{ __('ui.common.no_records') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $inventoryItems->links() }}
            </div>
        </div>
    </div>
@endsection
