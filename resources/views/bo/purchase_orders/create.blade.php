@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
<x-ui.page-header title="{{ __('ui.create_purchase_order') }}"/>

<form method="POST" action="{{ route('bo.purchase-orders.store') }}" class="space-y-6 bg-white p-6 border rounded">
  @csrf
  <div>
    <x-input-label for="warehouse_id" :value="__('ui.warehouse')" />
    <x-text-input id="warehouse_id" name="warehouse_id" placeholder="ULID" value="{{ old('warehouse_id') }}" required />
    <x-input-error :messages="$errors->get('warehouse_id')" />
  </div>
  <div x-data="{ lines: [{stock_item_id:'', qty:1, unit_price_cents:0}] }" class="space-y-4">
    <h3 class="text-lg font-medium text-gray-900">{{ __('ui.order_lines') }}</h3>
    <template x-for="(line, index) in lines" :key="index">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 border border-gray-200 rounded">
        <div>
          <x-input-label x-bind:for="'stock_item_id_'+index" value="Stock Item ULID" />
          <input class="w-full px-3 py-2 border border-gray-300 rounded-md" type="text" x-bind:id="'stock_item_id_'+index" x-bind:name="'lines['+index+'][stock_item_id]'" x-model="line.stock_item_id" required />
        </div>
        <div>
          <x-input-label x-bind:for="'qty_'+index" :value="__('ui.quantity')" />
          <input class="w-full px-3 py-2 border border-gray-300 rounded-md" type="number" min="1" step="1" x-bind:id="'qty_'+index" x-bind:name="'lines['+index+'][qty]'" x-model.number="line.qty" required />
        </div>
        <div>
          <x-input-label x-bind:for="'unit_price_'+index" :value="__('ui.unit_price_cents')" />
          <input class="w-full px-3 py-2 border border-gray-300 rounded-md" type="number" min="0" step="1" x-bind:id="'unit_price_'+index" x-bind:name="'lines['+index+'][unit_price_cents]'" x-model.number="line.unit_price_cents" required />
        </div>
        <div class="flex items-end">
          <button type="button" class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600" @click="lines.splice(index,1)" x-show="lines.length > 1">{{ __('ui.remove') }}</button>
        </div>
      </div>
    </template>
    <button type="button" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600" @click="lines.push({stock_item_id:'', qty:1, unit_price_cents:0})">+ {{ __('ui.add_line') }}</button>
  </div>
  <x-primary-button>{{ __('ui.save') }}</x-primary-button>
  <a href="{{ route('bo.purchase-orders.index') }}" class="btn">{{ __('ui.cancel') }}</a>
 </form>
@endsection
