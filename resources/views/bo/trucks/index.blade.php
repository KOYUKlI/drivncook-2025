@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.nav.dashboard'), 'url' => route('bo.dashboard')],
        ['title' => __('ui.nav.trucks')]
    ]" />

    <div class="mb-8 flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.bo.trucks.title') }}</h1>
            <p class="text-gray-600">{{ __('ui.bo.trucks.subtitle') }}</p>
        </div>
        @can('create', App\Models\Truck::class)
        <a href="{{ route('bo.trucks.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 text-white rounded-md shadow-sm hover:bg-orange-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/></svg>
            {{ __('ui.bo.trucks.create') }}
        </a>
        @endcan
    </div>

    <!-- KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-ui.tile title="{{ __('ui.labels.total') }}" value="{{ $stats['total'] ?? 0 }}" color="gray" />
        <x-ui.tile title="{{ __('ui.bo.trucks.status.active') }}" value="{{ $stats['active'] ?? 0 }}" color="green" />
        <x-ui.tile title="{{ __('ui.bo.trucks.status.in_maintenance') }}" value="{{ $stats['maintenance'] ?? 0 }}" color="orange" />
        <x-ui.tile title="{{ __('ui.bo.trucks.status.retired') }}" value="{{ $stats['inactive'] ?? 0 }}" color="red" />
    </div>

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
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
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.414-1.414l4.387 4.387-1.414 1.414-4.387-4.387zM14 8a6 6 0 11-12 0 6 6 0 0112 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('ui.bo.trucks.filters.search_placeholder') }}" class="block w-full rounded-md border-gray-300 pl-10" />
                    </div>
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
                <thead class="bg-gray-50 sticky top-0 z-10">
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
                    <tr class="hover:bg-gray-50 odd:bg-white even:bg-gray-50/40">
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
                            @can('viewAny', App\Models\Truck::class)
                            <a href="{{ route('bo.trucks.show', $truck['id']) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md border border-orange-200 bg-orange-50 text-orange-700 hover:bg-orange-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
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
