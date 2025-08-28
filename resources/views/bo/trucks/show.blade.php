@php $statusCounts = array_merge([ 'pending'=>0,'active'=>0,'in_maintenance'=>0,'retired'=>0,'maintenance'=>0,'deployed'=>0,'idle'=>0 ], $statusCounts ?? []); @endphp
@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.dashboard'), 'url' => route('bo.dashboard')],
        ['title' => __('ui.trucks'), 'url' => route('bo.trucks.index')],
    ['title' => $truck['code'] ?? '—']
    ]" />

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('ui.truck') }} {{ $truck['code'] ?? '—' }}</h1>
        <div class="flex items-center gap-4 mt-2">
            @php
            $statusColors = [
                'active' => 'bg-green-100 text-green-800',
                'maintenance' => 'bg-orange-100 text-orange-800',
                'inactive' => 'bg-gray-100 text-gray-800'
            ];
            @endphp
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$truck['status'] ?? 'inactive'] ?? 'bg-gray-100 text-gray-800' }}">
                {{ __('ui.' . ($truck['status'] ?? 'inactive')) }}
            </span>
            <span class="text-gray-600">{{ __('ui.assigned_to') }}: {{ $truck['franchisee'] ?? '—' }}</span>
        </div>
    </div>

    <!-- Tabs -->
    <div class="border-b border-gray-200 mb-6" x-data="{ activeTab: 'deployments' }">
        <nav class="-mb-px flex space-x-8">
            <button 
                @click="activeTab = 'deployments'"
                :class="{ 'border-orange-500 text-orange-600': activeTab === 'deployments', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'deployments' }"
                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
            >
                {{ __('ui.deployments') }}
            </button>
            <button 
                @click="activeTab = 'maintenance'"
                :class="{ 'border-orange-500 text-orange-600': activeTab === 'maintenance', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'maintenance' }"
                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
            >
                {{ __('ui.maintenance') }}
            </button>
        </nav>
        
        <!-- Deployments Tab -->
        <div x-show="activeTab === 'deployments'" class="mt-6">
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('ui.recent_deployments') }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('ui.date') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('ui.location') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('ui.revenue') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach(($truck['deployments'] ?? []) as $deployment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ isset($deployment['date']) ? \Carbon\Carbon::parse($deployment['date'])->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $deployment['location'] ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ isset($deployment['revenue']) ? number_format(($deployment['revenue'] ?? 0) / 100, 2, ',', ' ') : '0,00' }}€
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Actions pour déploiements -->
                <div class="px-6 py-4 border-t border-gray-200">
                    @can('update', App\Models\Truck::class)
                    <form method="POST" action="{{ route('bo.trucks.schedule-deployment', $truck['id']) }}" class="inline mr-4">
                        @csrf
                        <input type="hidden" name="deployment_date" value="{{ now()->addDay()->toDateString() }}">
                        <input type="hidden" name="territory" value="{{ $truck['franchisee'] }}">
                        <input type="hidden" name="franchisee_id" value="1">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                            {{ __('ui.schedule_deployment') }}
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Maintenance Tab -->
        <div x-show="activeTab === 'maintenance'" class="mt-6">
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('ui.maintenance_history') }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('ui.date') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('ui.type') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('ui.cost') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('ui.status') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach(($truck['maintenance'] ?? []) as $maintenance)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $maintenance['date'] ? \Carbon\Carbon::parse($maintenance['date'])->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $maintenance['type'] ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ isset($maintenance['cost']) ? number_format(($maintenance['cost'] ?? 0) / 100, 2, ',', ' ') : '0,00' }}€
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                    $statusColors = [
                                        'completed' => 'bg-green-100 text-green-800',
                                        'scheduled' => 'bg-blue-100 text-blue-800',
                                        'in_progress' => 'bg-yellow-100 text-yellow-800',
                                        'pending' => 'bg-gray-100 text-gray-800',
                                    ];
                                    @endphp
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$maintenance['status'] ?? 'pending'] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ __('ui.' . ($maintenance['status'] ?? 'pending')) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Actions pour maintenance -->
                <div class="px-6 py-4 border-t border-gray-200">
                    @can('update', App\Models\Truck::class)
                    <form method="POST" action="{{ route('bo.trucks.schedule-maintenance', $truck['id']) }}" class="inline mr-4">
                        @csrf
                        <input type="hidden" name="date" value="{{ now()->addWeek()->toDateString() }}">
                        <input type="hidden" name="type" value="Révision générale">
                        <input type="hidden" name="technician" value="Garage Central">
                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded text-sm">
                            {{ __('ui.schedule_maintenance') }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('bo.trucks.open-maintenance', [$truck['id'], 1]) }}" class="inline mr-4">
                        @csrf
                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded text-sm">
                            {{ __('ui.start_maintenance') }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('bo.trucks.close-maintenance', [$truck['id'], 1]) }}" class="inline">
                        @csrf
                        <input type="hidden" name="actual_cost" value="50000">
                        <input type="hidden" name="work_performed" value="Révision complète">
                        <input type="hidden" name="truck_operational" value="1">
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm">
                            {{ __('ui.complete_maintenance') }}
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
