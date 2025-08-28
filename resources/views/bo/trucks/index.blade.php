@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.dashboard'), 'url' => route('bo.dashboard')],
        ['title' => __('ui.trucks')]
    ]" />

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.trucks') }}</h1>
        <p class="text-gray-600">{{ __('ui.manage_truck_fleet') }}</p>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm text-gray-700">{{ __('ui.status') }}</label>
                    <select name="status" class="mt-1 block w-full border-gray-300 rounded-md">
                        @php $current = request('status', 'all'); @endphp
                        <option value="all" {{ $current==='all' ? 'selected' : '' }}>{{ __('ui.all_statuses') }}</option>
                        <option value="active" {{ $current==='active' ? 'selected' : '' }}>{{ __('ui.active') }}</option>
                        <option value="maintenance" {{ $current==='maintenance' ? 'selected' : '' }}>{{ __('ui.maintenance') }}</option>
                        <option value="inactive" {{ $current==='inactive' ? 'selected' : '' }}>{{ __('ui.inactive') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-700">{{ __('ui.franchisee') }}</label>
                    <select name="franchisee" class="mt-1 block w-full border-gray-300 rounded-md">
                        @php $currentFranchisee = request('franchisee', 'all'); @endphp
                        <option value="all" {{ $currentFranchisee==='all' ? 'selected' : '' }}>{{ __('ui.all_franchisees') }}</option>
                        <option value="paris-nord" {{ $currentFranchisee==='paris-nord' ? 'selected' : '' }}>Paris Nord</option>
                        <option value="lyon-centre" {{ $currentFranchisee==='lyon-centre' ? 'selected' : '' }}>Lyon Centre</option>
                        <option value="marseille-sud" {{ $currentFranchisee==='marseille-sud' ? 'selected' : '' }}>Marseille Sud</option>
                        <option value="toulouse-nord" {{ $currentFranchisee==='toulouse-nord' ? 'selected' : '' }}>Toulouse Nord</option>
                        <option value="bordeaux-est" {{ $currentFranchisee==='bordeaux-est' ? 'selected' : '' }}>Bordeaux Est</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <x-primary-button>{{ __('ui.filter') }}</x-primary-button>
                </div>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.truck_code') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.status') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.franchisee') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.last_maintenance') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($trucks as $truck)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $truck['code'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                            $statusColors = [
                                'active' => 'bg-green-100 text-green-800',
                                'maintenance' => 'bg-orange-100 text-orange-800',
                                'inactive' => 'bg-gray-100 text-gray-800'
                            ];
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$truck['status']] }}">
                                {{ __('ui.' . $truck['status']) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $truck['franchisee'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($truck['last_maintenance'])->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('bo.trucks.show', $truck['id']) }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ __('ui.view_details') }}
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
