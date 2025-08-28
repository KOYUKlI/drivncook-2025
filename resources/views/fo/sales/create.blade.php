@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.fo.dashboard'), 'url' => route('fo.dashboard')],
        ['title' => __('ui.fo.sales.title'), 'url' => route('fo.sales.index')],
        ['title' => __('ui.fo.sales.create.title')]
    ]" />

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.fo.sales.create.title') }}</h1>
        <p class="text-gray-600">{{ __('ui.fo.sales.create.subtitle') }}</p>
    </div>

    <form action="{{ route('fo.sales.store') }}" method="POST" class="space-y-8">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Sale Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Location & Time -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ __('ui.fo.sales.create.location_time') }}
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">{{ __('ui.fo.sales.create.location') }}</label>
                            <input type="text" id="location" name="location" 
                                   value="{{ old('location', 'Centre-ville') }}"
                                   class="w-full border-gray-300 rounded-md focus:border-orange-500 focus:ring-orange-500" 
                                   placeholder="{{ __('ui.fo.sales.create.location_placeholder') }}">
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="coordinates" class="block text-sm font-medium text-gray-700 mb-2">{{ __('ui.fo.sales.create.coordinates') }}</label>
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
                        <label for="sale_date" class="block text-sm font-medium text-gray-700 mb-2">{{ __('ui.fo.sales.create.sale_date') }}</label>
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
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            {{ __('ui.fo.sales.create.items') }}
                        </h2>
                        <button type="button" id="add-item" class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('ui.fo.sales.create.add_item') }}
                        </button>
                    </div>

                    <div id="items-container" class="space-y-4">
                        <!-- Default item -->
                        <div class="item-row bg-gray-50 border border-gray-200 rounded-lg p-4 hover:bg-gray-100 transition-colors duration-200">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('ui.fo.sales.create.product') }}</label>
                                    <select name="items[0][product]" class="w-full border-gray-300 rounded-md focus:border-orange-500 focus:ring-orange-500">
                                        <option value="">{{ __('ui.fo.sales.create.select_product') }}</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product['id'] }}" data-price="{{ $product['price'] }}">
                                                {{ $product['name'] }} - {{ number_format($product['price'] / 100, 2, ',', ' ') }}€
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('ui.fo.sales.create.quantity') }}</label>
                                    <input type="number" name="items[0][quantity]" value="1" min="1" 
                                           class="w-full border-gray-300 rounded-md focus:border-orange-500 focus:ring-orange-500 item-quantity">
                                </div>
                                <div class="flex items-end">
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('ui.fo.sales.create.line_total') }}</label>
                                        <div class="item-total text-lg font-semibold text-orange-600 bg-white px-3 py-2 rounded-md border border-gray-200">0,00€</div>
                                    </div>
                                    <button type="button" class="ml-3 p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md remove-item transition-colors duration-200" style="display: none;">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Summary -->
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600" id="items-count">1 {{ __('ui.fo.sales.create.item_selected') }}</span>
                            <span class="text-sm font-medium text-gray-900">{{ __('ui.fo.sales.create.subtotal') }}: <span id="items-subtotal" class="text-orange-600">0,00€</span></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Summary -->
            <div class="space-y-6">
                <!-- Payment Method -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        {{ __('ui.fo.sales.create.payment_method') }}
                    </h2>
                    
                    <div class="space-y-3">
                        <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="payment_method" value="card" checked 
                                   class="text-orange-600 focus:ring-orange-500">
                            <div class="ml-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900">{{ __('ui.fo.sales.create.payment.card') }}</span>
                            </div>
                        </label>
                        <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="payment_method" value="cash" 
                                   class="text-orange-600 focus:ring-orange-500">
                            <div class="ml-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900">{{ __('ui.fo.sales.create.payment.cash') }}</span>
                            </div>
                        </label>
                        <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="payment_method" value="mobile" 
                                   class="text-orange-600 focus:ring-orange-500">
                            <div class="ml-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900">{{ __('ui.fo.sales.create.payment.mobile') }}</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Summary -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        {{ __('ui.fo.sales.create.summary') }}
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ __('ui.fo.sales.create.subtotal') }}</span>
                            <span id="subtotal" class="font-medium">0,00€</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ __('ui.fo.sales.create.tax') }} (20%)</span>
                            <span id="tax" class="font-medium">0,00€</span>
                        </div>
                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between">
                                <span class="text-xl font-semibold text-gray-900">{{ __('ui.fo.sales.create.total') }}</span>
                                <span id="total" class="text-xl font-bold text-orange-600">0,00€</span>
                            </div>
                        </div>
                        
                        <!-- Sale Summary Info -->
                        <div class="mt-4 p-3 bg-orange-50 rounded-lg border border-orange-200">
                            <div class="text-xs text-orange-800">
                                <div class="flex justify-between">
                                    <span>{{ __('ui.fo.sales.create.items_count') }}:</span>
                                    <span id="summary-items-count">0</span>
                                </div>
                                <div class="flex justify-between mt-1">
                                    <span>{{ __('ui.fo.sales.create.avg_price') }}:</span>
                                    <span id="summary-avg-price">0,00€</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="space-y-3">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 bg-orange-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wider hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200 shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('ui.fo.sales.create.confirm_sale') }}
                        </button>
                        
                        <a href="{{ route('fo.sales.index') }}" class="w-full inline-flex justify-center items-center px-6 py-3 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 uppercase tracking-wider hover:bg-gray-50 focus:bg-gray-50 active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200">
                            {{ __('ui.fo.sales.create.cancel') }}
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
            updateGrandTotal();
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
            let itemsCount = 0;
            let validItems = 0;
            
            document.querySelectorAll('.item-row').forEach(row => {
                const select = row.querySelector('select');
                const quantity = row.querySelector('.item-quantity');
                const selectedOption = select.selectedOptions[0];
                const price = selectedOption ? parseFloat(selectedOption.dataset.price) : 0;
                const qty = parseInt(quantity.value) || 0;
                
                if (price > 0 && qty > 0) {
                    subtotal += price * qty;
                    itemsCount += qty;
                    validItems++;
                }
            });
            
            const tax = subtotal * 0.2;
            const total = subtotal + tax;
            const avgPrice = validItems > 0 ? subtotal / validItems : 0;
            
            // Update main summary
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
            
            // Update items summary
            const itemsCountText = itemsCount + (itemsCount <= 1 ? ' {{ __("ui.fo.sales.create.item_selected") }}' : ' {{ __("ui.fo.sales.create.items_selected") }}');
            document.getElementById('items-count').textContent = itemsCountText;
            document.getElementById('items-subtotal').textContent = (subtotal / 100).toLocaleString('fr-FR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }) + '€';
            
            // Update detailed summary
            document.getElementById('summary-items-count').textContent = itemsCount;
            document.getElementById('summary-avg-price').textContent = (avgPrice / 100).toLocaleString('fr-FR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }) + '€';
        }
        
        // Attach events to initial item
        attachItemEvents(document.querySelector('.item-row'));
        
        // Initial calculation
        updateGrandTotal();
    </script>
@endsection
