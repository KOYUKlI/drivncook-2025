@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
<x-ui.page-header title="{{ __('ui.edit_stock_item') }}"/>

<form method="POST" action="{{ route('bo.stock-items.update', $item) }}" class="space-y-4 bg-white p-6 border rounded">
  @csrf
  @method('PUT')
  <div>
    <x-input-label for="sku" value="SKU" />
    <x-text-input id="sku" name="sku" value="{{ old('sku', $item->sku) }}" required />
    <x-input-error :messages="$errors->get('sku')" />
  </div>
  <div>
    <x-input-label for="name" :value="__('ui.name')" />
    <x-text-input id="name" name="name" value="{{ old('name', $item->name) }}" required />
    <x-input-error :messages="$errors->get('name')" />
  </div>
  <div>
    <x-input-label for="unit" :value="__('ui.unit')" />
    <x-text-input id="unit" name="unit" value="{{ old('unit', $item->unit) }}" required />
    <x-input-error :messages="$errors->get('unit')" />
  </div>
  <div>
    <x-input-label for="price_cents" :value="__('ui.price_cents')" />
    <x-text-input id="price_cents" name="price_cents" type="number" min="0" step="1" value="{{ old('price_cents', $item->price_cents) }}" required />
    <x-input-error :messages="$errors->get('price_cents')" />
  </div>
  <div class="flex items-center gap-2">
    <input id="is_central" name="is_central" type="checkbox" value="1" {{ old('is_central', $item->is_central) ? 'checked' : '' }} />
    <x-input-label for="is_central" value="Central" />
  </div>
  <x-primary-button>{{ __('ui.save') }}</x-primary-button>
  <a href="{{ route('bo.stock-items.index') }}" class="btn">{{ __('ui.cancel') }}</a>
 </form>
@endsection
