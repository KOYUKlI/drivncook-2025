@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="mb-5 md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    {{ __('ui.inventory.stock_movements.create_title') }}
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('bo.warehouses.inventory') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                    {{ __('ui.common.back') }}
                </a>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="mb-5">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <button type="button" class="tab-button border-amber-500 text-amber-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="receipt">
                                {{ __('ui.inventory.stock_movements.receipt') }}
                            </button>
                            <button type="button" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="withdrawal">
                                {{ __('ui.inventory.stock_movements.withdrawal') }}
                            </button>
                            <button type="button" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="adjustment">
                                {{ __('ui.inventory.stock_movements.adjustment') }}
                            </button>
                            <button type="button" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="transfer">
                                {{ __('ui.inventory.stock_movements.transfer') }}
                            </button>
                        </nav>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="rounded-md bg-red-50 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    {{ __('ui.common.error_message') }}
                                </h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Receipt Form -->
                <div id="receipt-tab" class="tab-content">
                    <form action="{{ route('bo.stock-movements.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="receipt">

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label for="warehouse_id" class="block text-sm font-medium text-gray-700">
                                        {{ __('ui.inventory.warehouse') }} *
                                    </label>
                                    <div class="mt-1">
                                        <select id="warehouse_id" name="warehouse_id" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="">{{ __('ui.common.select_option') }}</option>
                                            @foreach($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                                    {{ $warehouse->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="stock_item_id" class="block text-sm font-medium text-gray-700">
                                        {{ __('ui.inventory.stock_item') }} *
                                    </label>
                                    <div class="mt-1">
                                        <select id="stock_item_id" name="stock_item_id" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="">{{ __('ui.common.select_option') }}</option>
                                            @foreach($stockItems as $stockItem)
                                                <option value="{{ $stockItem->id }}" {{ old('stock_item_id') == $stockItem->id ? 'selected' : '' }}>
                                                    {{ $stockItem->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="quantity" class="block text-sm font-medium text-gray-700">
                                        {{ __('ui.inventory.quantity') }} *
                                    </label>
                                    <div class="mt-1">
                                        <input type="number" min="1" id="quantity" name="quantity" value="{{ old('quantity') }}" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>

                                <div class="sm:col-span-6">
                                    <label for="reason" class="block text-sm font-medium text-gray-700">
                                        {{ __('ui.inventory.reason') }}
                                    </label>
                                    <div class="mt-1">
                                        <textarea id="reason" name="reason" rows="3" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('reason') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                                    {{ __('ui.common.create') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Withdrawal Form -->
                <div id="withdrawal-tab" class="tab-content hidden">
                    <form action="{{ route('bo.stock-movements.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="withdrawal">

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label for="withdrawal_warehouse_id" class="block text-sm font-medium text-gray-700">
                                        {{ __('ui.inventory.warehouse') }} *
                                    </label>
                                    <div class="mt-1">
                                        <select id="withdrawal_warehouse_id" name="warehouse_id" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="">{{ __('ui.common.select_option') }}</option>
                                            @foreach($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                                    {{ $warehouse->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="withdrawal_stock_item_id" class="block text-sm font-medium text-gray-700">
                                        {{ __('ui.inventory.stock_item') }} *
                                    </label>
                                    <div class="mt-1">
                                        <select id="withdrawal_stock_item_id" name="stock_item_id" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="">{{ __('ui.common.select_option') }}</option>
                                            @foreach($stockItems as $stockItem)
                                                <option value="{{ $stockItem->id }}" {{ old('stock_item_id') == $stockItem->id ? 'selected' : '' }}>
                                                    {{ $stockItem->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="withdrawal_quantity" class="block text-sm font-medium text-gray-700">
                                        {{ __('ui.inventory.quantity') }} *
                                    </label>
                                    <div class="mt-1">
                                        <input type="number" min="1" id="withdrawal_quantity" name="quantity" value="{{ old('quantity') }}" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>

                                <div class="sm:col-span-6">
                                    <label for="withdrawal_reason" class="block text-sm font-medium text-gray-700">
                                        {{ __('ui.inventory.reason') }}
                                    </label>
                                    <div class="mt-1">
                                        <textarea id="withdrawal_reason" name="reason" rows="3" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('reason') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                                    {{ __('ui.common.create') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Adjustment Form -->
                <div id="adjustment-tab" class="tab-content hidden">
                    <form action="{{ route('bo.stock-movements.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="adjustment">

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label for="adjustment_warehouse_id" class="block text-sm font-medium text-gray-700">
                                        {{ __('ui.inventory.warehouse') }} *
                                    </label>
                                    <div class="mt-1">
                                        <select id="adjustment_warehouse_id" name="warehouse_id" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="">{{ __('ui.common.select_option') }}</option>
                                            @foreach($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                                    {{ $warehouse->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="adjustment_stock_item_id" class="block text-sm font-medium text-gray-700">
                                        {{ __('ui.inventory.stock_item') }} *
                                    </label>
                                    <div class="mt-1">
                                        <select id="adjustment_stock_item_id" name="stock_item_id" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="">{{ __('ui.common.select_option') }}</option>
                                            @foreach($stockItems as $stockItem)
                                                <option value="{{ $stockItem->id }}" {{ old('stock_item_id') == $stockItem->id ? 'selected' : '' }}>
                                                    {{ $stockItem->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="adjustment_type" class="block text-sm font-medium text-gray-700">
                                        {{ __('ui.inventory.adjustment_type') }} *
                                    </label>
                                    <div class="mt-1">
                                        <select id="adjustment_type" name="adjustment_type" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="increase" {{ old('adjustment_type') == 'increase' ? 'selected' : '' }}>{{ __('ui.inventory.increase') }}</option>
                                            <option value="decrease" {{ old('adjustment_type') == 'decrease' ? 'selected' : '' }}>{{ __('ui.inventory.decrease') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="adjustment_quantity" class="block text-sm font-medium text-gray-700">
                                        {{ __('ui.inventory.quantity') }} *
                                    </label>
                                    <div class="mt-1">
                                        <input type="number" min="1" id="adjustment_quantity" name="quantity" value="{{ old('quantity') }}" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>

                                <div class="sm:col-span-6">
                                    <label for="adjustment_reason" class="block text-sm font-medium text-gray-700">
                                        {{ __('ui.inventory.reason') }} *
                                    </label>
                                    <div class="mt-1">
                                        <textarea id="adjustment_reason" name="reason" rows="3" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-gray-300 rounded-md" required>{{ old('reason') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                                    {{ __('ui.common.create') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Transfer Form -->
                <div id="transfer-tab" class="tab-content hidden">
                    <form action="{{ route('bo.stock-movements.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="transfer_out">

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label for="source_warehouse_id" class="block text-sm font-medium text-gray-700">
                                        {{ __('ui.inventory.source_warehouse') }} *
                                    </label>
                                    <div class="mt-1">
                                        <select id="source_warehouse_id" name="warehouse_id" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="">{{ __('ui.common.select_option') }}</option>
                                            @foreach($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                                    {{ $warehouse->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="destination_warehouse_id" class="block text-sm font-medium text-gray-700">
                                        {{ __('ui.inventory.destination_warehouse') }} *
                                    </label>
                                    <div class="mt-1">
                                        <select id="destination_warehouse_id" name="destination_warehouse_id" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="">{{ __('ui.common.select_option') }}</option>
                                            @foreach($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}" {{ old('destination_warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                                    {{ $warehouse->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="transfer_stock_item_id" class="block text-sm font-medium text-gray-700">
                                        {{ __('ui.inventory.stock_item') }} *
                                    </label>
                                    <div class="mt-1">
                                        <select id="transfer_stock_item_id" name="stock_item_id" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="">{{ __('ui.common.select_option') }}</option>
                                            @foreach($stockItems as $stockItem)
                                                <option value="{{ $stockItem->id }}" {{ old('stock_item_id') == $stockItem->id ? 'selected' : '' }}>
                                                    {{ $stockItem->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="transfer_quantity" class="block text-sm font-medium text-gray-700">
                                        {{ __('ui.inventory.quantity') }} *
                                    </label>
                                    <div class="mt-1">
                                        <input type="number" min="1" id="transfer_quantity" name="quantity" value="{{ old('quantity') }}" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>

                                <div class="sm:col-span-6">
                                    <label for="transfer_reason" class="block text-sm font-medium text-gray-700">
                                        {{ __('ui.inventory.reason') }}
                                    </label>
                                    <div class="mt-1">
                                        <textarea id="transfer_reason" name="reason" rows="3" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('reason') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                                    {{ __('ui.common.create') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const tabName = button.dataset.tab;
                    
                    // Update button states
                    tabButtons.forEach(btn => {
                        if (btn.dataset.tab === tabName) {
                            btn.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                            btn.classList.add('border-amber-500', 'text-amber-600');
                        } else {
                            btn.classList.remove('border-amber-500', 'text-amber-600');
                            btn.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                        }
                    });

                    // Show the correct tab content
                    tabContents.forEach(content => {
                        if (content.id === `${tabName}-tab`) {
                            content.classList.remove('hidden');
                        } else {
                            content.classList.add('hidden');
                        }
                    });
                });
            });
        });
    </script>
    @endpush
@endsection
