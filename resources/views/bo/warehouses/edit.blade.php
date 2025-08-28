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
