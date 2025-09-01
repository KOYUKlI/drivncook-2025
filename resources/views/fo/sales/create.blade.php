@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.fo-sidebar')
@endsection

@section('title', __('ui.fo.sales.create.title'))

@section('content')
<div class="py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.fo.sales.create.title') }}</h1>
        <a href="{{ route('fo.sales.index') }}" class="btn-secondary">{{ __('ui.fo.sales.create.back_to_list') }}</a>
    </div>

    <form action="{{ route('fo.sales.store') }}" method="POST" id="sale-form">
        @csrf
        
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
            <div class="p-4 md:p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-4">{{ __('ui.fo.sales.create.sale_info') }}</h2>
                <div>
                    <label class="block text-sm text-gray-700 mb-1" for="sale_date">{{ __('ui.fo.sales.create.sale_date') }}</label>
                    <input type="date" id="sale_date" name="sale_date" value="{{ old('sale_date', now()->format('Y-m-d')) }}" class="form-input w-56 @error('sale_date') border-red-500 ring-red-500 @enderror" required />
                    @error('sale_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
            <div class="p-4 md:p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-semibold text-gray-900">{{ __('ui.fo.sales.create.sale_lines') }}</h2>
                    <button type="button" id="add-line" class="btn-primary">{{ __('ui.fo.sales.create.add_line') }}</button>
                </div>

                @error('lines')
                    <div class="mt-4 rounded-md border border-red-300 bg-red-50 text-red-800 p-3 text-sm">
                        {{ $message }}
                    </div>
                @enderror

                <div class="overflow-x-auto mt-4">
                    <table class="min-w-full divide-y divide-gray-200" id="lines-table">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.sales.create.table.item') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.sales.create.table.quantity') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.sales.create.table.price') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.fo.sales.create.table.subtotal') }}</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody id="lines-container" class="divide-y divide-gray-200">
                            <!-- Sale lines will be added here dynamically -->
                            @if(old('lines'))
                                @foreach(old('lines') as $index => $line)
                                    <tr class="sale-line" data-index="{{ $index }}">
                                        <td>
                                            <div>
                                                <select name="lines[{{ $index }}][stock_item_id]" class="form-select stock-item-select @error('lines.'.$index.'.stock_item_id') border-red-500 ring-red-500 @enderror">
                                                    <option value="">{{ __('ui.fo.sales.create.custom_item') }}</option>
                                                    @foreach($stockItems as $item)
                                                        <option 
                                                            value="{{ $item->id }}"
                                                            data-price="{{ $item->price_cents }}"
                                                            @if($line['stock_item_id'] == $item->id) selected @endif
                                                        >
                                                            {{ $item->name }} ({{ number_format($item->price_cents / 100, 2) }} €)
                                                        </option>
                                                    @endforeach
                                                </select>
                                                
                                                <input type="text" name="lines[{{ $index }}][item_label]" placeholder="{{ __('ui.fo.sales.create.custom_item_placeholder') }}" value="{{ $line['item_label'] ?? '' }}" class="form-input mt-2 custom-label @error('lines.'.$index.'.item_label') border-red-500 ring-red-500 @enderror" @if(!empty($line['stock_item_id'])) style="display: none;" @endif />
                                                
                                                @error('lines.'.$index.'.stock_item_id')
                                                    <label class="label">
                                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                                    </label>
                                                @enderror
                                                @error('lines.'.$index.'.item_label')
                                                    <label class="label">
                                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                                    </label>
                                                @enderror
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <input type="number" name="lines[{{ $index }}][qty]" value="{{ $line['qty'] ?? 1 }}" step="0.01" min="0.01" class="form-input w-24 line-qty @error('lines.'.$index.'.qty') border-red-500 ring-red-500 @enderror" required />
                                                @error('lines.'.$index.'.qty')
                                                    <label class="label">
                                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                                    </label>
                                                @enderror
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <input type="number" name="lines[{{ $index }}][unit_price_cents]" value="{{ $line['unit_price_cents'] ?? 0 }}" min="1" class="form-input w-28 line-price @error('lines.'.$index.'.unit_price_cents') border-red-500 ring-red-500 @enderror" required />
                                                    <span class="text-sm text-gray-500">¢</span>
                                                </div>
                                                @error('lines.'.$index.'.unit_price_cents')
                                                    <label class="label">
                                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                                    </label>
                                                @enderror
                                            </div>
                                        </td>
                                        <td>
                                            <div class="line-subtotal">
                                                {{ number_format(($line['qty'] ?? 1) * ($line['unit_price_cents'] ?? 0) / 100, 2) }} €
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="text-red-600 hover:text-red-800 remove-line inline-flex items-center p-2"
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <th colspan="3" class="px-4 py-2 text-right text-sm font-medium text-gray-700">{{ __('ui.fo.sales.create.table.total') }}</th>
                                <th class="px-4 py-2 text-left" id="total-amount">0.00 €</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="mt-4" id="no-lines-alert" @if(old('lines')) style="display: none;" @endif>
                    <div class="rounded-md border border-blue-300 bg-blue-50 text-blue-800 p-3 text-sm">{{ __('ui.fo.sales.create.no_lines_yet') }}</div>
                </div>
            </div>
        </div>
        
        <div class="flex justify-end">
            <button type="submit" class="btn-primary">{{ __('ui.fo.sales.create.submit') }}</button>
        </div>
    </form>
</div>

<template id="line-template">
    <tr class="sale-line" data-index="{index}">
        <td>
            <div>
                <select name="lines[{index}][stock_item_id]" class="form-select stock-item-select">
                    <option value="">{{ __('ui.fo.sales.create.custom_item') }}</option>
                    @foreach($stockItems as $item)
                        <option 
                            value="{{ $item->id }}"
                            data-price="{{ $item->price_cents }}"
                        >
                            {{ $item->name }} ({{ number_format($item->price_cents / 100, 2) }} €)
                        </option>
                    @endforeach
                </select>
                
                <input type="text" name="lines[{index}][item_label]" placeholder="{{ __('ui.fo.sales.create.custom_item_placeholder') }}" class="form-input mt-2 custom-label" style="display: none;" />
            </div>
        </td>
        <td>
            <div>
                <input type="number" name="lines[{index}][qty]" value="1" step="0.01" min="0.01" class="form-input w-24 line-qty" required />
            </div>
        </td>
        <td>
            <div>
                <div class="flex items-center gap-2">
                    <input type="number" name="lines[{index}][unit_price_cents]" value="0" min="1" class="form-input w-28 line-price" required />
                    <span class="text-sm text-gray-500">¢</span>
                </div>
            </div>
        </td>
        <td>
            <div class="line-subtotal">0.00 €</div>
        </td>
        <td>
            <button type="button" class="text-red-600 hover:text-red-800 remove-line inline-flex items-center p-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </td>
    </tr>
</template>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const linesContainer = document.getElementById('lines-container');
        const lineTemplate = document.getElementById('line-template').innerHTML;
        const addLineButton = document.getElementById('add-line');
        const noLinesAlert = document.getElementById('no-lines-alert');
        let lineIndex = document.querySelectorAll('.sale-line').length;
        
        // Add a new line
        addLineButton.addEventListener('click', function() {
            addNewLine();
        });
        
        // If no lines exist, add one by default
        if (lineIndex === 0) {
            addNewLine();
        } else {
            updateNoLinesVisibility();
        }
        
        // Handle remove line button clicks using event delegation
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-line')) {
                const line = e.target.closest('.sale-line');
                line.remove();
                updateTotalAmount();
                updateNoLinesVisibility();
            }
        });
        
        // Handle stock item selection changes
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('stock-item-select')) {
                const line = e.target.closest('.sale-line');
                const customLabel = line.querySelector('.custom-label');
                const priceInput = line.querySelector('.line-price');
                
                if (e.target.value) {
                    customLabel.style.display = 'none';
                    customLabel.value = '';
                    
                    // Set price from selected option
                    const selectedOption = e.target.options[e.target.selectedIndex];
                    const price = selectedOption.getAttribute('data-price');
                    priceInput.value = price;
                } else {
                    customLabel.style.display = 'block';
                    priceInput.value = 0;
                }
                
                updateSubtotal(line);
                updateTotalAmount();
            }
        });
        
        // Handle quantity or price changes
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('line-qty') || e.target.classList.contains('line-price')) {
                const line = e.target.closest('.sale-line');
                updateSubtotal(line);
                updateTotalAmount();
            }
        });
        
        // Function to add a new line
        function addNewLine() {
            const newLine = lineTemplate.replace(/{index}/g, lineIndex++);
            linesContainer.insertAdjacentHTML('beforeend', newLine);
            updateNoLinesVisibility();
        }
        
        // Update the subtotal for a line
        function updateSubtotal(line) {
            const qty = parseFloat(line.querySelector('.line-qty').value) || 0;
            const price = parseInt(line.querySelector('.line-price').value) || 0;
            const subtotal = (qty * price / 100).toFixed(2);
            line.querySelector('.line-subtotal').textContent = subtotal + ' €';
        }
        
        // Update the total amount
        function updateTotalAmount() {
            const lines = document.querySelectorAll('.sale-line');
            let total = 0;
            
            lines.forEach(line => {
                const qty = parseFloat(line.querySelector('.line-qty').value) || 0;
                const price = parseInt(line.querySelector('.line-price').value) || 0;
                total += qty * price;
            });
            
            document.getElementById('total-amount').textContent = (total / 100).toFixed(2) + ' €';
        }
        
        // Update visibility of the "no lines" alert
        function updateNoLinesVisibility() {
            const lines = document.querySelectorAll('.sale-line');
            if (lines.length === 0) {
                noLinesAlert.style.display = 'block';
            } else {
                noLinesAlert.style.display = 'none';
            }
        }
        
        // Initialize subtotals and total
        document.querySelectorAll('.sale-line').forEach(line => {
            updateSubtotal(line);
        });
        updateTotalAmount();
    });
</script>
@endsection
@endsection
