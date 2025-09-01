@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.dashboard'), 'url' => route('bo.dashboard')],
        ['title' => __('ui.bo.warehouses.title')]
    ]" />

    <div class="mb-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.bo.warehouses.title') }}</h1>
                <p class="text-gray-600">{{ __('ui.bo.warehouses.subtitle') }}</p>
            </div>
            @can('create', App\Models\Warehouse::class)
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none space-x-3">
                <a href="{{ route('bo.warehouses.inventory') }}" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                    {{ __('ui.inventory.title') }}
                </a>
                <a href="{{ route('bo.warehouses.create') }}" class="block rounded-md bg-orange-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-orange-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600">
                    {{ __('ui.bo.warehouses.create') }}
                </a>
            </div>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-md bg-green-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="mb-6 bg-white rounded-lg border border-gray-200 shadow-sm p-4">
        <form action="{{ route('bo.warehouses.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">{{ __('ui.labels.status') }}</label>
                    <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                        <option value="">{{ __('ui.misc.all_statuses') }}</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('ui.status.active') }}</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('ui.status.inactive') }}</option>
                    </select>
                </div>
                
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">{{ __('ui.labels.search') }}</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm" placeholder="{{ __('ui.common.search_placeholder') }}">
                </div>
                
                <div class="flex items-end space-x-2">
                    <button type="submit" class="inline-flex items-center rounded-md bg-orange-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600">
                        <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                        </svg>
                        {{ __('ui.actions.filter') }}
                    </button>
                    
                    @if(request()->hasAny(['status', 'search']))
                        <a href="{{ route('bo.warehouses.index') }}" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            {{ __('ui.actions.reset') }}
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.warehouses.fields.code') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.warehouses.fields.name') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.warehouses.fields.region') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.warehouses.fields.city') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.warehouses.fields.status') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.warehouses.table.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($warehouses as $warehouse)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $warehouse->code ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $warehouse->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $warehouse->region ?? __('ui.bo.warehouses.no_region') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $warehouse->city ?? __('ui.bo.warehouses.no_city') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($warehouse->is_active ?? true)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ __('ui.status.active') }}
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    {{ __('ui.status.inactive') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('bo.warehouses.dashboard', $warehouse) }}" class="text-green-600 hover:text-green-900">
                                    {{ __('warehouse_dashboard.inventory.dashboard.menu_title') }}
                                </a>
                                <a href="{{ route('bo.warehouses.inventory.show', $warehouse) }}" class="text-blue-600 hover:text-blue-900">
                                    {{ __('ui.inventory.title') }}
                                </a>
                                @can('update', $warehouse)
                                <a href="{{ route('bo.warehouses.edit', $warehouse) }}" class="text-orange-600 hover:text-orange-900">
                                    {{ __('ui.actions.edit') }}
                                </a>
                                @endcan
                                @can('delete', $warehouse)
                                <form method="POST" action="{{ route('bo.warehouses.destroy', $warehouse) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ __('ui.bo.warehouses.confirm_delete') }}')">
                                        {{ __('ui.actions.delete') }}
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M8 14v20c0 4.418 7.163 8 16 8 1.381 0 2.721-.087 4-.252M8 14c0 4.418 7.163 8 16 8s16-3.582 16-8M8 14c0-4.418 7.163-8 16-8s16 3.582 16 8m0 0v14m-16-4c0 4.418 7.163 8 16 8 1.381 0 2.721-.087 4-.252" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('ui.bo.warehouses.empty.title') }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{ __('ui.bo.warehouses.empty.description') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $warehouses->withQueryString()->links() }}
        </div>
    </div>
@endsection
