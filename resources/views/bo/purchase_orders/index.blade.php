@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.dashboard'), 'url' => route('bo.dashboard')],
        ['title' => __('ui.purchase_orders')]
    ]" />

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.purchase_orders') }}</h1>
        <p class="text-gray-600">{{ __('ui.manage_purchase_orders') }}</p>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.reference') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.franchisee') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.total') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.ratio_80_20') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.status') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.date') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('ui.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($orders as $order)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $order['reference'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $order['franchisee'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($order['total'] / 100, 2, ',', ' ') }}€
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                            $ratioColor = $order['ratio_80_20'] >= 80 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $ratioColor }}">
                                {{ $order['ratio_80_20'] }}%
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                            $statusColors = [
                                'completed' => 'bg-green-100 text-green-800',
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'cancelled' => 'bg-red-100 text-red-800'
                            ];
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$order['status']] }}">
                                {{ __('ui.' . $order['status']) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($order['date'])->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('bo.purchase-orders.show', $order['id']) }}" class="text-indigo-600 hover:text-indigo-900">
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
