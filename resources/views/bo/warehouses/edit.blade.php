@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.dashboard'), 'url' => route('bo.dashboard')],
        ['title' => __('ui.bo.warehouses.title'), 'url' => route('bo.warehouses.index')],
        ['title' => __('ui.bo.warehouses.edit')]
    ]" />

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.bo.warehouses.edit') }}</h1>
        <p class="text-gray-600">{{ __('ui.bo.warehouses.edit_subtitle') }}</p>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <form method="POST" action="{{ route('bo.warehouses.update', $warehouse) }}" class="space-y-6 p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <x-input-label for="code" :value="__('ui.bo.warehouses.fields.code')" />
                    <x-text-input id="code" name="code" value="{{ old('code', $warehouse->code) }}" required class="mt-1 block w-full" placeholder="WH-001" />
                    <x-input-error :messages="$errors->get('code')" class="mt-2" />
                </div>
                
                <div>
                    <x-input-label for="name" :value="__('ui.bo.warehouses.fields.name')" />
                    <x-text-input id="name" name="name" value="{{ old('name', $warehouse->name) }}" required class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="city" :value="__('ui.bo.warehouses.fields.city')" />
                    <x-text-input id="city" name="city" value="{{ old('city', $warehouse->city) }}" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('city')" class="mt-2" />
                </div>
                
                <div>
                    <x-input-label for="region" :value="__('ui.bo.warehouses.fields.region')" />
                    <x-text-input id="region" name="region" value="{{ old('region', $warehouse->region) }}" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('region')" class="mt-2" />
                </div>
                
                <div class="sm:col-span-2">
                    <x-input-label for="address" :value="__('ui.bo.warehouses.fields.address')" />
                    <x-textarea-input id="address" name="address" class="mt-1 block w-full" rows="3">{{ old('address', $warehouse->address) }}</x-textarea-input>
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                </div>
                
                <div>
                    <x-input-label for="phone" :value="__('ui.bo.warehouses.fields.phone')" />
                    <x-text-input id="phone" name="phone" value="{{ old('phone', $warehouse->phone) }}" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>
                
                <div>
                    <x-input-label for="email" :value="__('ui.bo.warehouses.fields.email')" />
                    <x-text-input id="email" type="email" name="email" value="{{ old('email', $warehouse->email) }}" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                
                <div class="sm:col-span-2">
                    <x-input-label for="notes" :value="__('ui.bo.warehouses.fields.notes')" />
                    <x-textarea-input id="notes" name="notes" class="mt-1 block w-full" rows="3">{{ old('notes', $warehouse->notes) }}</x-textarea-input>
                    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                </div>
                
                <div>
                    <div class="flex items-center space-x-2">
                        <x-checkbox-input id="is_active" name="is_active" :checked="old('is_active', $warehouse->is_active)" />
                        <x-input-label for="is_active" :value="__('ui.bo.warehouses.fields.is_active')" />
                    </div>
                    <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                <a href="{{ route('bo.warehouses.index') }}" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                    {{ __('ui.actions.cancel') }}
                </a>
                <x-primary-button>
                    {{ __('ui.actions.update') }}
                </x-primary-button>
            </div>
        </form>
    </div>
@endsection
