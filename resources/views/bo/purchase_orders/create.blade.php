@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.dashboard'), 'url' => route('bo.dashboard')],
        ['title' => __('ui.bo.purchase_orders.title'), 'url' => route('bo.purchase-orders.index')],
        ['title' => __('ui.bo.purchase_orders.create')]
    ]" />

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.bo.purchase_orders.create') }}</h1>
        <p class="text-gray-600">{{ __('ui.bo.purchase_orders.create_subtitle') }}</p>
    </div>

    @if($errors->any())
        <div class="mb-6 rounded-md bg-red-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">{{ __('ui.bo.purchase_orders.validation_errors') }}</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <form method="POST" action="{{ route('bo.purchase-orders.store') }}" class="space-y-6 p-6" x-data="purchaseOrderForm()">
            @csrf
            
            <!-- Basic Information -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <x-input-label for="warehouse_id" :value="__('ui.bo.purchase_orders.fields.warehouse')" />
                    <select id="warehouse_id" name="warehouse_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="">{{ __('ui.bo.purchase_orders.fields.select_warehouse') }}</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->name }} - {{ $warehouse->city }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('warehouse_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="franchisee_id" :value="__('ui.bo.purchase_orders.fields.franchisee')" />
                    <select id="franchisee_id" name="franchisee_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">{{ __('ui.bo.purchase_orders.fields.select_franchisee') }}</option>
                        @foreach($franchisees as $franchisee)
                            <option value="{{ $franchisee->id }}" {{ old('franchisee_id') == $franchisee->id ? 'selected' : '' }}>
                                {{ $franchisee->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('franchisee_id')" class="mt-2" />
                </div>
            </div>

            <!-- Order Lines -->
            <div class="border-t border-gray-200 pt-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('ui.bo.purchase_orders.lines.title') }}</h3>
                    <button type="button" @click="addLine()" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        {{ __('ui.bo.purchase_orders.lines.add') }}
                    </button>
                </div>

                <div class="space-y-4">
                    <template x-for="(line, index) in lines" :key="index">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <!-- Stock Item -->
                            <div class="md:col-span-2">
                                <x-input-label x-bind:for="'stock_item_id_'+index" :value="__('ui.bo.purchase_orders.lines.stock_item')" />
                                <select x-bind:id="'stock_item_id_'+index" x-bind:name="'lines['+index+'][stock_item_id]'" x-model="line.stock_item_id" @change="updateUnitPrice(index)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">{{ __('ui.bo.purchase_orders.lines.select_item') }}</option>
                                    @foreach($stockItems as $item)
                                        <option value="{{ $item->id }}" data-price="{{ $item->price_cents }}" data-unit="{{ $item->unit }}">
                                            {{ $item->name }} ({{ $item->sku }}) - {{ $item->unit }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('lines.*.stock_item_id')" class="mt-1" />
                            </div>

                            <!-- Quantity -->
                            <div>
                                <x-input-label x-bind:for="'qty_'+index" :value="__('ui.bo.purchase_orders.lines.quantity')" />
                                <input type="number" min="1" step="1" x-bind:id="'qty_'+index" x-bind:name="'lines['+index+'][qty]'" x-model.number="line.qty" @input="updateLineTotal(index)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
                                <x-input-error :messages="$errors->get('lines.*.qty')" class="mt-1" />
                            </div>

                            <!-- Unit Price -->
                            <div>
                                <x-input-label x-bind:for="'unit_price_'+index" :value="__('ui.bo.purchase_orders.lines.unit_price')" />
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" min="0" step="1" x-bind:id="'unit_price_'+index" x-bind:name="'lines['+index+'][unit_price_cents]'" x-model.number="line.unit_price_cents" @input="updateLineTotal(index)" class="block w-full pr-12 rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required />
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">¢</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-sm text-gray-500" x-text="formatPrice(line.unit_price_cents)"></p>
                                <x-input-error :messages="$errors->get('lines.*.unit_price_cents')" class="mt-1" />
                            </div>

                            <!-- Actions -->
                            <div class="flex items-end justify-between">
                                <div class="text-sm">
                                    <span class="font-medium text-gray-700">{{ __('ui.bo.purchase_orders.lines.total') }}:</span>
                                    <div class="text-lg font-bold text-gray-900" x-text="formatPrice(line.qty * line.unit_price_cents)"></div>
                                </div>
                                <button type="button" @click="removeLine(index)" x-show="lines.length > 1" class="inline-flex items-center p-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Order Total -->
                <div class="mt-6 bg-gray-100 rounded-lg p-4">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-medium text-gray-900">{{ __('ui.bo.purchase_orders.total') }}:</span>
                        <span class="text-2xl font-bold text-gray-900" x-text="formatPrice(totalAmount)"></span>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                <a href="{{ route('bo.purchase-orders.index') }}" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                    {{ __('ui.actions.cancel') }}
                </a>
                <x-primary-button>
                    {{ __('ui.bo.purchase_orders.actions.create') }}
                </x-primary-button>
            </div>
        </form>
    </div>

    <script>
        function purchaseOrderForm() {
            return {
                lines: [{ stock_item_id: '', qty: 1, unit_price_cents: 0 }],
                
                get totalAmount() {
                    return this.lines.reduce((total, line) => {
                        return total + (line.qty * line.unit_price_cents);
                    }, 0);
                },
                
                addLine() {
                    this.lines.push({ stock_item_id: '', qty: 1, unit_price_cents: 0 });
                },
                
                removeLine(index) {
                    if (this.lines.length > 1) {
                        this.lines.splice(index, 1);
                    }
                },
                
                updateUnitPrice(index) {
                    const select = document.querySelector(`select[name="lines[${index}][stock_item_id]"]`);
                    const selectedOption = select.options[select.selectedIndex];
                    if (selectedOption && selectedOption.dataset.price) {
                        this.lines[index].unit_price_cents = parseInt(selectedOption.dataset.price);
                    }
                },
                
                updateLineTotal(index) {
                    // Trigger reactivity for computed total
                    this.$nextTick(() => {});
                },
                
                formatPrice(cents) {
                    if (!cents || cents === 0) return '0,00€';
                    return new Intl.NumberFormat('fr-FR', {
                        style: 'currency',
                        currency: 'EUR',
                        minimumFractionDigits: 2
                    }).format(cents / 100);
                }
            }
        }
    </script>
@endsection
