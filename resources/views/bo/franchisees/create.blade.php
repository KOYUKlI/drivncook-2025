@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.dashboard'), 'url' => route('bo.dashboard')],
        ['title' => __('ui.franchisees'), 'url' => route('bo.franchisees.index')],
        ['title' => __('ui.create')]
    ]" />

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.add_franchisee') }}</h1>
        <p class="text-gray-600">{{ __('ui.add_franchisee_description') }}</p>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
        <form action="{{ route('bo.franchisees.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('ui.franchise_name') }}
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                        placeholder="{{ __('ui.franchise_name_placeholder') }}"
                        value="{{ old('name') }}"
                    >
                    <x-input-error :messages="$errors->get('name')" />
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('ui.email') }} *
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                        placeholder="{{ __('ui.email_placeholder') }}"
                        value="{{ old('email') }}"
                    >
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                <div class="md:col-span-2">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('ui.phone') }}
                    </label>
                    <input 
                        type="tel" 
                        id="phone" 
                        name="phone"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                        placeholder="{{ __('ui.phone_placeholder') }}"
                        value="{{ old('phone') }}"
                    >
                    <x-input-error :messages="$errors->get('phone')" />
                </div>
            </div>

            <div>
                <label for="billing_address" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('ui.billing_address') }}
                </label>
                <textarea 
                    id="billing_address" 
                    name="billing_address" 
                    rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                    placeholder="{{ __('ui.address_placeholder') }}"
                >{{ old('billing_address') }}</textarea>
                <x-input-error :messages="$errors->get('billing_address')" />
            </div>

            <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('bo.franchisees.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                    {{ __('ui.cancel') }}
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-orange-500 rounded-md hover:bg-orange-600">
                    {{ __('ui.create_franchisee') }}
                </button>
            </div>
        </form>
    </div>
@endsection
