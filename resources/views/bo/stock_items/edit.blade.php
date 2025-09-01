@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.dashboard'), 'url' => route('bo.dashboard')],
        ['title' => __('ui.bo.stock_items.title'), 'url' => route('bo.stock-items.index')],
        ['title' => __('ui.bo.stock_items.edit')]
    ]" />

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.bo.stock_items.edit') }}</h1>
        <p class="text-gray-600">{{ __('ui.bo.stock_items.edit_subtitle') }}</p>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <form method="POST" action="{{ route('bo.stock-items.update', $item) }}" class="space-y-6 p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <x-input-label for="sku" :value="__('ui.bo.stock_items.fields.sku')" />
                    <x-text-input id="sku" name="sku" value="{{ old('sku', $item->sku) }}" required class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('sku')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="name" :value="__('ui.bo.stock_items.fields.name')" />
                    <x-text-input id="name" name="name" value="{{ old('name', $item->name) }}" required class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="unit" :value="__('ui.bo.stock_items.fields.unit')" />
                    <x-text-input id="unit" name="unit" value="{{ old('unit', $item->unit) }}" required class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="price_cents" :value="__('ui.bo.stock_items.fields.price_cents')" />
                    <x-text-input id="price_cents" name="price_cents" type="number" min="0" step="1" value="{{ old('price_cents', $item->price_cents) }}" required class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('price_cents')" class="mt-2" />
                    <p class="mt-1 text-sm text-gray-500">{{ __('ui.bo.stock_items.fields.price_cents_help') }}</p>
                </div>
            </div>

            <div class="flex items-center">
                <input id="is_central" name="is_central" type="checkbox" value="1" {{ old('is_central', $item->is_central) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" />
                <x-input-label for="is_central" :value="__('ui.bo.stock_items.fields.is_central')" class="ml-2" />
                <p class="ml-2 text-sm text-gray-500">{{ __('ui.bo.stock_items.fields.is_central_help') }}</p>
            </div>

            <div class="flex items-center">
                <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', $item->is_active) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" />
                <x-input-label for="is_active" :value="__('ui.bo.stock_items.fields.is_active')" class="ml-2" />
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                <a href="{{ route('bo.stock-items.index') }}" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                    {{ __('ui.actions.cancel') }}
                </a>
                <x-primary-button>
                    {{ __('ui.actions.update') }}
                </x-primary-button>
            </div>
        </form>
    </div>
@endsection
