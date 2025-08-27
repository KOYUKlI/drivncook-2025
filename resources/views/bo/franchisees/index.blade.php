@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.dashboard'), 'url' => route('bo.dashboard')],
        ['title' => __('ui.franchisees')]
    ]" />

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.franchisees') }}</h1>
            <p class="text-gray-600">{{ __('ui.manage_franchisees') }}</p>
        </div>
        <a href="{{ route('bo.franchisees.create') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-md text-sm font-medium">
            {{ __('ui.add_franchisee') }}
        </a>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.name') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.email') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.status') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.monthly_revenue') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($franchisees as $franchisee)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $franchisee['name'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $franchisee['email'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                            $statusColors = [
                                'active' => 'bg-green-100 text-green-800',
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'inactive' => 'bg-gray-100 text-gray-800'
                            ];
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$franchisee['status']] }}">
                                {{ __('ui.' . $franchisee['status']) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($franchisee['revenue'] / 100, 0, ',', ' ') }}€
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="{{ route('bo.franchisees.show', $franchisee['id']) }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ __('ui.view') }}
                            </a>
                            <a href="{{ route('bo.franchisees.edit', $franchisee['id']) }}" class="text-gray-600 hover:text-gray-900">
                                {{ __('ui.edit') }}
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
