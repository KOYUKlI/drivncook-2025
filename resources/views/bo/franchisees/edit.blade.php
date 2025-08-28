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
        <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.edit_franchisee') }}</h1>
        <p class="text-gray-600">{{ __('ui.edit_franchisee_description') }}</p>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
        <form action="{{ route('bo.franchisees.update', $franchisee['id']) }}" method="POST" class="space-y-6">
            @csrf
            @method('PATCH')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('ui.franchise_name') }}
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ $franchisee['name'] }}"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                    >
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('ui.email') }} *
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email', $franchisee['email']) }}"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                    >
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('ui.status') }}
                    </label>
                    <select 
                        id="status" 
                        name="status" 
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                    >
                        <option value="active" {{ $franchisee['status'] === 'active' ? 'selected' : '' }}>{{ __('ui.active') }}</option>
                        <option value="pending" {{ $franchisee['status'] === 'pending' ? 'selected' : '' }}>{{ __('ui.pending') }}</option>
                        <option value="inactive" {{ $franchisee['status'] === 'inactive' ? 'selected' : '' }}>{{ __('ui.inactive') }}</option>
                    </select>
                </div>

                <div>
                    <label for="territory" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('ui.territory') }}
                    </label>
                    <select 
                        id="territory" 
                        name="territory" 
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                    >
                        <option value="paris-nord">Paris Nord</option>
                        <option value="paris-sud">Paris Sud</option>
                        <option value="lyon-centre">Lyon Centre</option>
                        <option value="marseille-sud">Marseille Sud</option>
                        <option value="toulouse-nord">Toulouse Nord</option>
                    </select>
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
                    placeholder="{{ __('ui.billing_address_placeholder') }}"
                >{{ old('billing_address', $franchisee['billing_address'] ?? '') }}</textarea>
                <x-input-error :messages="$errors->get('billing_address')" />
            </div>

            <div class="flex items-center justify-between pt-6 border-t">
                <form action="{{ route('bo.franchisees.destroy', $franchisee['id']) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button 
                        type="submit" 
                        onclick="return confirm('{{ __('ui.confirm_delete_franchisee') }}')"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700"
                    >
                        {{ __('ui.delete') }}
                    </button>
                </form>

                <div class="space-x-4">
                    <a href="{{ route('bo.franchisees.show', $franchisee['id']) }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        {{ __('ui.cancel') }}
                    </a>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-orange-500 rounded-md hover:bg-orange-600">
                        {{ __('ui.save_changes') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
