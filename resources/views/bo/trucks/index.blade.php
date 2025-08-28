@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.nav.dashboard'), 'url' => route('bo.dashboard')],
        ['title' => __('ui.nav.trucks')]
    ]" />

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.bo.trucks.title') }}</h1>
        <p class="text-gray-600">{{ __('ui.bo.trucks.subtitle') }}</p>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Status Filter -->
                <div>
                    <label class="block text-sm text-gray-700">{{ __('ui.bo.trucks.filters.status') }}</label>
                    <select name="status" class="mt-1 block w-full border-gray-300 rounded-md">
                        @php $current = request('status', 'all'); @endphp
                        <option value="all" {{ $current==='all' ? 'selected' : '' }}>{{ __('ui.bo.trucks.filters.all_statuses') }}</option>
                        <option value="active" {{ $current==='active' ? 'selected' : '' }}>{{ __('ui.bo.trucks.status.active') }}</option>
                        <option value="in_maintenance" {{ $current==='in_maintenance' ? 'selected' : '' }}>{{ __('ui.bo.trucks.status.in_maintenance') }}</option>
                        <option value="retired" {{ $current==='retired' ? 'selected' : '' }}>{{ __('ui.bo.trucks.status.retired') }}</option>
                        <option value="pending" {{ $current==='pending' ? 'selected' : '' }}>{{ __('ui.bo.trucks.status.pending') }}</option>
                    </select>
                </div>
                
                <!-- Franchisee Filter -->
                <div>
                    <label class="block text-sm text-gray-700">{{ __('ui.bo.trucks.filters.franchisee') }}</label>
                    <select name="franchisee_id" class="mt-1 block w-full border-gray-300 rounded-md">
                        @php $currentFranchisee = request('franchisee_id', 'all'); @endphp
                        <option value="all" {{ $currentFranchisee==='all' ? 'selected' : '' }}>{{ __('ui.bo.trucks.filters.all_franchisees') }}</option>
                        <option value="unassigned" {{ $currentFranchisee==='unassigned' ? 'selected' : '' }}>{{ __('ui.bo.trucks.unassigned') }}</option>
                        @foreach($franchisees as $franchisee)
                            <option value="{{ $franchisee->id }}" {{ $currentFranchisee == $franchisee->id ? 'selected' : '' }}>
                                {{ $franchisee->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Search Input -->
                <div>
                    <label class="block text-sm text-gray-700">{{ __('ui.bo.trucks.filters.search') }}</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="{{ __('ui.bo.trucks.filters.search_placeholder') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md">
                </div>

                <!-- Filter Actions -->
                <div class="flex items-end space-x-2">
                    <x-primary-button>{{ __('ui.bo.trucks.filters.apply') }}</x-primary-button>
                    <a href="{{ route('bo.trucks.index') }}" class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        {{ __('ui.bo.trucks.filters.reset') }}
                    </a>
                </div>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.trucks.table.code') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.trucks.table.status') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.trucks.table.franchisee') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.trucks.table.last_maintenance') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.bo.trucks.table.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($trucks as $truck)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $truck['code'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                            $statusColors = [
                                'active' => 'bg-green-100 text-green-800',
                                'in_maintenance' => 'bg-orange-100 text-orange-800',
                                'retired' => 'bg-red-100 text-red-800',
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'inactive' => 'bg-gray-100 text-gray-800'
                            ];
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$truck['status']] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ __('ui.bo.trucks.status.' . $truck['status']) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $truck['franchisee'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $truck['last_maintenance'] ? \Carbon\Carbon::parse($truck['last_maintenance'])->format('d/m/Y') : __('ui.bo.trucks.no_maintenance') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @can('view', $truck)
                            <a href="{{ route('bo.trucks.show', $truck['id']) }}" class="text-orange-600 hover:text-orange-900">
                                {{ __('ui.bo.trucks.table.view') }}
                            </a>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M8 14v20c0 4.418 7.163 8 16 8 1.381 0 2.721-.087 4-.252M8 14c0 4.418 7.163 8 16 8s16-3.582 16-8M8 14c0-4.418 7.163-8 16-8s16 3.582 16 8m0 0v14m-16-4c0 4.418 7.163 8 16 8 1.381 0 2.721-.087 4-.252" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('ui.bo.trucks.empty.title') }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{ __('ui.bo.trucks.empty.description') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
