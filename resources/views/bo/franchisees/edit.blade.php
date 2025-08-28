@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.dashboard'), 'url' => route('bo.dashboard')],
        ['title' => __('ui.franchisees'), 'url' => route('bo.franchisees.index')],
        ['title' => $franchisee['name'], 'url' => route('bo.franchisees.show', $franchisee['id'])],
        ['title' => __('ui.edit')]
    ]" />

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.bo.franchisees.edit_title') }}</h1>
        <p class="text-gray-600">{{ __('ui.bo.franchisees.edit_subtitle') }}</p>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
        <form action="{{ route('bo.franchisees.update', $franchisee['id']) }}" method="POST" class="space-y-6">
            @csrf
            @method('PATCH')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('ui.labels.name') }} *
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $franchisee['name']) }}"
                        required
                        class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 {{ $errors->has('name') ? 'border-red-300' : 'border-gray-300' }}"
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
                        value="{{ old('email', $franchisee['email']) }}"
                        required
                        class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 {{ $errors->has('email') ? 'border-red-300' : 'border-gray-300' }}"
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
                        value="{{ old('phone', $franchisee['phone']) }}"
                        class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 {{ $errors->has('phone') ? 'border-red-300' : 'border-gray-300' }}"
                        placeholder="{{ __('ui.bo.franchisees.phone_placeholder') }}"
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
                        <option value="active" {{ old('status', $franchisee['status']) === 'active' ? 'selected' : '' }}>
                            {{ __('ui.status.active') }}
                        </option>
                        <option value="inactive" {{ old('status', $franchisee['status']) === 'inactive' ? 'selected' : '' }}>
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
                >{{ old('billing_address', $franchisee['billing_address'] ?? '') }}</textarea>
                <x-input-error :messages="$errors->get('billing_address')" />
            </div>

            <div class="flex items-center justify-between pt-6 border-t">
                <form action="{{ route('bo.franchisees.destroy', $franchisee['id']) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button 
                        type="submit" 
                        onclick="return confirm('{{ __('ui.bo.franchisees.confirm_delete') }}')"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700"
                    >
                        {{ __('ui.actions.delete') }}
                    </button>
                </form>

                <div class="space-x-4">
                    <a href="{{ route('bo.franchisees.show', $franchisee['id']) }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        {{ __('ui.actions.cancel') }}
                    </a>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-orange-500 rounded-md hover:bg-orange-600">
                        {{ __('ui.actions.save') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
