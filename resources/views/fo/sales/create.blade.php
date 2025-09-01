@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.fo-sidebar')
@endsection

@section('title', __('ui.fo.sales.create.title'))

@section('content')
<div class="container py-6">
    <div class="flex justify-between mb-4">
        <h1 class="text-2xl font-bold">{{ __('ui.fo.sales.create.title') }}</h1>
        <a href="{{ route('fo.sales.index') }}" class="btn btn-ghost">
            {{ __('ui.fo.sales.create.back_to_list') }}
        </a>
    </div>

    <form action="{{ route('fo.sales.store') }}" method="POST" id="sale-form">
        @csrf
        
        <div class="card bg-base-100 shadow-xl mb-6">
            <div class="card-body">
                <h2 class="card-title">{{ __('ui.fo.sales.create.sale_info') }}</h2>
                
                <div class="form-control w-full max-w-xs">
                    <label class="label" for="sale_date">
                        <span class="label-text">{{ __('ui.fo.sales.create.sale_date') }}</span>
                    </label>
                    <input 
                        type="date" 
                        id="sale_date" 
                        name="sale_date" 
                        value="{{ old('sale_date', now()->format('Y-m-d')) }}"
                        class="input input-bordered w-full max-w-xs @error('sale_date') input-error @enderror" 
                        required
                    />
                    @error('sale_date')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-xl mb-6">
            <div class="card-body">
                <div class="flex justify-between">
                    <h2 class="card-title">{{ __('ui.fo.sales.create.sale_lines') }}</h2>
                    <button type="button" id="add-line" class="btn btn-sm btn-primary">
                        {{ __('ui.fo.sales.create.add_line') }}
                    </button>
                </div>
                
                @error('lines')
                    <div class="alert alert-error shadow-lg mt-4">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>{{ $message }}</span>
                        </div>
                    </div>
                @enderror
                
                <div class="overflow-x-auto">
                    <table class="table w-full" id="lines-table">
                        <thead>
                            <tr>
                                <th>{{ __('ui.fo.sales.create.table.item') }}</th>
                                <th>{{ __('ui.fo.sales.create.table.quantity') }}</th>
                                <th>{{ __('ui.fo.sales.create.table.price') }}</th>
                                <th>{{ __('ui.fo.sales.create.table.subtotal') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="lines-container">
                            <!-- Sale lines will be added here dynamically -->
                            @if(old('lines'))
                                @foreach(old('lines') as $index => $line)
                                    <tr class="sale-line" data-index="{{ $index }}">
                                        <td>
                                            <div class="form-control">
                                                <select 
                                                    name="lines[{{ $index }}][stock_item_id]" 
                                                    class="select select-bordered stock-item-select @error('lines.'.$index.'.stock_item_id') select-error @enderror"
                                                >
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
                                                
                                                <input 
                                                    type="text" 
                                                    name="lines[{{ $index }}][item_label]" 
                                                    placeholder="{{ __('ui.fo.sales.create.custom_item_placeholder') }}"
                                                    value="{{ $line['item_label'] ?? '' }}"
                                                    class="input input-bordered mt-2 custom-label @error('lines.'.$index.'.item_label') input-error @enderror"
                                                    @if(!empty($line['stock_item_id'])) style="display: none;" @endif
                                                />
                                                
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
                                            <div class="form-control">
                                                <input 
                                                    type="number" 
                                                    name="lines[{{ $index }}][qty]" 
                                                    value="{{ $line['qty'] ?? 1 }}"
                                                    step="0.01"
                                                    min="0.01"
                                                    class="input input-bordered w-24 line-qty @error('lines.'.$index.'.qty') input-error @enderror"
                                                    required
                                                />
                                                @error('lines.'.$index.'.qty')
                                                    <label class="label">
                                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                                    </label>
                                                @enderror
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-control">
                                                <div class="input-group">
                                                    <input 
                                                        type="number" 
                                                        name="lines[{{ $index }}][unit_price_cents]" 
                                                        value="{{ $line['unit_price_cents'] ?? 0 }}"
                                                        min="1"
                                                        class="input input-bordered w-24 line-price @error('lines.'.$index.'.unit_price_cents') input-error @enderror"
                                                        required
                                                    />
                                                    <span class="btn btn-disabled">¢</span>
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
                                            <button type="button" class="btn btn-sm btn-ghost text-error remove-line">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-right">{{ __('ui.fo.sales.create.table.total') }}</th>
                                <th id="total-amount">0.00 €</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="mt-4">
                    <div class="alert alert-info shadow-lg" id="no-lines-alert" @if(old('lines')) style="display: none;" @endif>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current flex-shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>{{ __('ui.fo.sales.create.no_lines_yet') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex justify-end">
            <button type="submit" class="btn btn-primary">
                {{ __('ui.fo.sales.create.submit') }}
            </button>
        </div>
    </form>
</div>

<template id="line-template">
    <tr class="sale-line" data-index="{index}">
        <td>
            <div class="form-control">
                <select 
                    name="lines[{index}][stock_item_id]" 
                    class="select select-bordered stock-item-select"
                >
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
                
                <input 
                    type="text" 
                    name="lines[{index}][item_label]" 
                    placeholder="{{ __('ui.fo.sales.create.custom_item_placeholder') }}"
                    class="input input-bordered mt-2 custom-label"
                    style="display: none;"
                />
            </div>
        </td>
        <td>
            <div class="form-control">
                <input 
                    type="number" 
                    name="lines[{index}][qty]" 
                    value="1"
                    step="0.01"
                    min="0.01"
                    class="input input-bordered w-24 line-qty"
                    required
                />
            </div>
        </td>
        <td>
            <div class="form-control">
                <div class="input-group">
                    <input 
                        type="number" 
                        name="lines[{index}][unit_price_cents]" 
                        value="0"
                        min="1"
                        class="input input-bordered w-24 line-price"
                        required
                    />
                    <span class="btn btn-disabled">¢</span>
                </div>
            </div>
        </td>
        <td>
            <div class="line-subtotal">0.00 €</div>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-ghost text-error remove-line">
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
