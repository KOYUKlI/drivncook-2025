@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.dashboard'), 'route' => 'fo.dashboard'],
        ['title' => __('ui.sales'), 'route' => 'fo.sales.index'],
        ['title' => __('ui.new_sale')]
    ]" />

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.new_sale') }}</h1>
        <p class="text-gray-600">{{ __('ui.new_sale_subtitle') }}</p>
    </div>

    <form action="{{ route('fo.sales.store') }}" method="POST" class="space-y-8">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Sale Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Location & Time -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('ui.sale_location_time') }}</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">{{ __('ui.location') }}</label>
                            <input type="text" id="location" name="location" 
                                   value="{{ old('location', 'Centre-ville') }}"
                                   class="w-full border-gray-300 rounded-md focus:border-orange-500 focus:ring-orange-500" 
                                   placeholder="{{ __('ui.location_placeholder') }}">
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="coordinates" class="block text-sm font-medium text-gray-700 mb-2">{{ __('ui.coordinates') }}</label>
                            <input type="text" id="coordinates" name="coordinates" 
                                   value="{{ old('coordinates') }}"
                                   class="w-full border-gray-300 rounded-md focus:border-orange-500 focus:ring-orange-500" 
                                   placeholder="46.1234, 6.5678">
                            @error('coordinates')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="sale_date" class="block text-sm font-medium text-gray-700 mb-2">{{ __('ui.sale_date') }}</label>
                        <input type="datetime-local" id="sale_date" name="sale_date" 
                               value="{{ old('sale_date', now()->format('Y-m-d\TH:i')) }}"
                               class="w-full md:w-auto border-gray-300 rounded-md focus:border-orange-500 focus:ring-orange-500">
                        @error('sale_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Items -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">{{ __('ui.items') }}</h2>
                        <button type="button" id="add-item" class="inline-flex items-center px-3 py-1.5 bg-orange-600 text-white text-sm rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('ui.add_item') }}
                        </button>
                    </div>

                    <div id="items-container" class="space-y-4">
                        <!-- Default item -->
                        <div class="item-row border border-gray-200 rounded-md p-4">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('ui.product') }}</label>
                                    <select name="items[0][product]" class="w-full border-gray-300 rounded-md focus:border-orange-500 focus:ring-orange-500">
                                        <option value="">{{ __('ui.select_product') }}</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product['id'] }}" data-price="{{ $product['price'] }}">
                                                {{ $product['name'] }} - {{ number_format($product['price'] / 100, 2, ',', ' ') }}€
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('ui.quantity') }}</label>
                                    <input type="number" name="items[0][quantity]" value="1" min="1" 
                                           class="w-full border-gray-300 rounded-md focus:border-orange-500 focus:ring-orange-500 item-quantity">
                                </div>
                                <div class="flex items-end">
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('ui.total') }}</label>
                                        <div class="item-total text-lg font-semibold text-gray-900">0,00€</div>
                                    </div>
                                    <button type="button" class="ml-2 text-red-600 hover:text-red-800 remove-item" style="display: none;">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Summary -->
            <div class="space-y-6">
                <!-- Payment Method -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('ui.payment_method') }}</h2>
                    
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="radio" name="payment_method" value="card" checked 
                                   class="text-orange-600 focus:ring-orange-500">
                            <span class="ml-2 text-sm text-gray-900">{{ __('ui.card') }}</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="payment_method" value="cash" 
                                   class="text-orange-600 focus:ring-orange-500">
                            <span class="ml-2 text-sm text-gray-900">{{ __('ui.cash') }}</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="payment_method" value="mobile" 
                                   class="text-orange-600 focus:ring-orange-500">
                            <span class="ml-2 text-sm text-gray-900">{{ __('ui.mobile_payment') }}</span>
                        </label>
                    </div>
                </div>

                <!-- Summary -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('ui.summary') }}</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ __('ui.subtotal') }}</span>
                            <span id="subtotal" class="font-medium">0,00€</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">TVA (20%)</span>
                            <span id="tax" class="font-medium">0,00€</span>
                        </div>
                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between">
                                <span class="text-lg font-semibold text-gray-900">{{ __('ui.total') }}</span>
                                <span id="total" class="text-lg font-bold text-gray-900">0,00€</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="space-y-3">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('ui.confirm_sale') }}
                        </button>
                        
                        <a href="{{ route('fo.sales.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:bg-gray-50 active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('ui.cancel') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        let itemIndex = 1;
        
        document.getElementById('add-item').addEventListener('click', function() {
            const container = document.getElementById('items-container');
            const itemRow = container.querySelector('.item-row').cloneNode(true);
            
            // Update form names
            itemRow.querySelectorAll('select, input').forEach(input => {
                const name = input.name.replace('[0]', `[${itemIndex}]`);
                input.name = name;
                input.value = input.type === 'number' ? '1' : '';
            });
            
            // Show remove button
            itemRow.querySelector('.remove-item').style.display = 'block';
            
            // Reset totals
            itemRow.querySelector('.item-total').textContent = '0,00€';
            
            container.appendChild(itemRow);
            itemIndex++;
            
            attachItemEvents(itemRow);
        });
        
        function attachItemEvents(itemRow) {
            const select = itemRow.querySelector('select');
            const quantity = itemRow.querySelector('.item-quantity');
            const total = itemRow.querySelector('.item-total');
            const remove = itemRow.querySelector('.remove-item');
            
            function updateItemTotal() {
                const selectedOption = select.selectedOptions[0];
                const price = selectedOption ? parseFloat(selectedOption.dataset.price) : 0;
                const qty = parseInt(quantity.value) || 0;
                const itemTotal = price * qty;
                
                total.textContent = (itemTotal / 100).toLocaleString('fr-FR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + '€';
                
                updateGrandTotal();
            }
            
            select.addEventListener('change', updateItemTotal);
            quantity.addEventListener('input', updateItemTotal);
            
            remove.addEventListener('click', function() {
                if (document.querySelectorAll('.item-row').length > 1) {
                    itemRow.remove();
                    updateGrandTotal();
                }
            });
        }
        
        function updateGrandTotal() {
            let subtotal = 0;
            
            document.querySelectorAll('.item-row').forEach(row => {
                const select = row.querySelector('select');
                const quantity = row.querySelector('.item-quantity');
                const selectedOption = select.selectedOptions[0];
                const price = selectedOption ? parseFloat(selectedOption.dataset.price) : 0;
                const qty = parseInt(quantity.value) || 0;
                
                subtotal += price * qty;
            });
            
            const tax = subtotal * 0.2;
            const total = subtotal + tax;
            
            document.getElementById('subtotal').textContent = (subtotal / 100).toLocaleString('fr-FR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }) + '€';
            
            document.getElementById('tax').textContent = (tax / 100).toLocaleString('fr-FR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }) + '€';
            
            document.getElementById('total').textContent = (total / 100).toLocaleString('fr-FR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }) + '€';
        }
        
        // Attach events to initial item
        attachItemEvents(document.querySelector('.item-row'));
    </script>
@endsection
