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
