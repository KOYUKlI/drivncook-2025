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
        <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.bo.franchisees.create_title') }}</h1>
        <p class="text-gray-600">{{ __('ui.bo.franchisees.create_subtitle') }}</p>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
        <form action="{{ route('bo.franchisees.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('ui.labels.name') }} *
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        required
                        class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 {{ $errors->has('name') ? 'border-red-300' : 'border-gray-300' }}"
                        placeholder="{{ __('ui.bo.franchisees.name_placeholder') }}"
                        value="{{ old('name') }}"
                    >
                    <x-input-error :messages="$errors->get('name')" />
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('ui.labels.email') }} *
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        required
                        class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 {{ $errors->has('email') ? 'border-red-300' : 'border-gray-300' }}"
                        placeholder="{{ __('ui.bo.franchisees.email_placeholder') }}"
                        value="{{ old('email') }}"
                    >
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('ui.labels.phone') }}
                    </label>
                    <input 
                        type="tel" 
                        id="phone" 
                        name="phone"
                        class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 {{ $errors->has('phone') ? 'border-red-300' : 'border-gray-300' }}"
                        placeholder="{{ __('ui.bo.franchisees.phone_placeholder') }}"
                        value="{{ old('phone') }}"
                    >
                    <x-input-error :messages="$errors->get('phone')" />
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('ui.labels.status') }}
                    </label>
                    <select 
                        id="status" 
                        name="status"
                        class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 {{ $errors->has('status') ? 'border-red-300' : 'border-gray-300' }}"
                    >
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                            {{ __('ui.status.active') }}
                        </option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                            {{ __('ui.status.inactive') }}
                        </option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" />
                </div>
            </div>

            <div>
                <label for="billing_address" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('ui.labels.billing_address') }}
                </label>
                <textarea 
                    id="billing_address" 
                    name="billing_address" 
                    rows="3"
                    class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 {{ $errors->has('billing_address') ? 'border-red-300' : 'border-gray-300' }}"
                    placeholder="{{ __('ui.bo.franchisees.address_placeholder') }}"
                >{{ old('billing_address') }}</textarea>
                <x-input-error :messages="$errors->get('billing_address')" />
            </div>

            <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('bo.franchisees.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                    {{ __('ui.actions.cancel') }}
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-orange-500 rounded-md hover:bg-orange-600">
                    {{ __('ui.actions.create') }}
                </button>
            </div>
        </form>
    </div>
@endsection
