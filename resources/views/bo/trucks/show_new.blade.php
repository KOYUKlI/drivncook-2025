@php 
$statusCounts = array_merge([ 'pending'=>0,'active'=>0,'in_maintenance'=>0,'retired'=>0,'maintenance'=>0,'deployed'=>0,'idle'=>0 ], $statusCounts ?? []); 
$statusColors = [
    'active' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
    'in_maintenance' => 'bg-amber-100 text-amber-800 border-amber-200',
    'maintenance' => 'bg-amber-100 text-amber-800 border-amber-200',
    'retired' => 'bg-red-100 text-red-800 border-red-200',
    'pending' => 'bg-blue-100 text-blue-800 border-blue-200',
    'inactive' => 'bg-gray-100 text-gray-800 border-gray-200'
];
@endphp
@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
    @if(session('success'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 4000)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 shadow-sm"
            role="alert"
            aria-live="polite"
        >
            <div class="flex items-start">
                <svg class="mr-3 h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm font-medium text-green-800 flex-1">{{ session('success') }}</p>
                <button type="button" @click="show = false" class="ml-3 text-green-700/70 hover:text-green-900" aria-label="{{ __('ui.actions.close') }}">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <x-ui.breadcrumbs :items="[
        ['title' => __('ui.nav.dashboard'), 'url' => route('bo.dashboard')],
        ['title' => __('ui.nav.trucks'), 'url' => route('bo.trucks.index')],
        ['title' => $truck['code'] ?? '—']
    ]" />

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-start justify-between mb-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $truck['code'] ?? '—' }}</h1>
                    <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full border {{ $statusColors[$truck['status'] ?? 'inactive'] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                        {{ __('ui.bo.trucks.status.' . ($truck['status'] ?? 'inactive')) }}
                    </span>
                </div>
                <div class="flex items-center gap-6 text-sm text-gray-600">
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>{{ __('ui.labels.franchisee') }}: <span class="font-medium text-gray-900">{{ $truck['franchisee'] ?? __('ui.common.unassigned') }}</span></span>
                    </div>
                    @if(!empty($truck['license_plate']))
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <span>{{ __('ui.bo_trucks.fields.plate_number') }}: <span class="font-medium text-gray-900">{{ $truck['license_plate'] }}</span></span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('bo.trucks.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700 transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('ui.actions.back') }}
                </a>
                @can('update', $truckModel)
                <a href="{{ route('bo.trucks.edit', $truck['id']) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    {{ __('ui.actions.edit') }}
                </a>
                @endcan
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">{{ __('ui.labels.deployments') }}</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ count($truck['deployments'] ?? []) }}</p>
                        <p class="text-xs text-gray-500">{{ __('ui.labels.total') }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">{{ __('ui.utilization.last_30_days') }}</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $utilization30 }}%</p>
                        <p class="text-xs text-gray-500">{{ __('ui.utilization.of_time') }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">{{ __('ui.labels.maintenance') }}</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ count($truck['maintenance'] ?? []) }}</p>
                        <p class="text-xs text-gray-500">{{ __('ui.labels.records') }}</p>
                    </div>
                    <div class="p-3 bg-amber-100 rounded-lg">
                        <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">{{ __('ui.labels.documents') }}</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ (($truck['has_registration'] ?? false) ? 1 : 0) + (($truck['has_insurance'] ?? false) ? 1 : 0) }}/2</p>
                        <p class="text-xs text-gray-500">{{ __('ui.help.files_private') }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents Section -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">{{ __('ui.labels.documents') }}</h3>
                <span class="text-sm text-gray-500">{{ __('ui.help.files_private') }}</span>
            </div>
        </div>
        <div class="px-6 py-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @can('viewAny', App\Models\Truck::class)
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="p-2 {{ ($truck['has_registration'] ?? false) ? 'bg-green-100' : 'bg-gray-100' }} rounded">
                            <svg class="h-5 w-5 {{ ($truck['has_registration'] ?? false) ? 'text-green-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">{{ __('ui.bo_trucks.fields.registration_doc') }}</h4>
                            <p class="text-sm text-gray-500">{{ __('ui.bo_trucks.sections.documents') }}</p>
                        </div>
                    </div>
                    @if(!empty($truck['id']) && !empty($truck['has_registration']))
                        <a href="{{ route('bo.trucks.files.download', ['truck' => $truck['id'], 'type' => 'registration']) }}" class="inline-flex items-center gap-2 px-3 py-2 text-sm bg-orange-50 text-orange-700 rounded-lg hover:bg-orange-100 border border-orange-200 transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            {{ __('ui.actions.download') }}
                        </a>
                    @else
                        <span class="text-sm text-gray-400">{{ __('ui.bo.trucks.not_provided') }}</span>
                    @endif
                </div>

                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="p-2 {{ ($truck['has_insurance'] ?? false) ? 'bg-green-100' : 'bg-gray-100' }} rounded">
                            <svg class="h-5 w-5 {{ ($truck['has_insurance'] ?? false) ? 'text-green-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">{{ __('ui.bo_trucks.fields.insurance_doc') }}</h4>
                            <p class="text-sm text-gray-500">{{ __('ui.bo_trucks.sections.documents') }}</p>
                        </div>
                    </div>
                    @if(!empty($truck['id']) && !empty($truck['has_insurance']))
                        <a href="{{ route('bo.trucks.files.download', ['truck' => $truck['id'], 'type' => 'insurance']) }}" class="inline-flex items-center gap-2 px-3 py-2 text-sm bg-orange-50 text-orange-700 rounded-lg hover:bg-orange-100 border border-orange-200 transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            {{ __('ui.actions.download') }}
                        </a>
                    @else
                        <span class="text-sm text-gray-400">{{ __('ui.bo.trucks.not_provided') }}</span>
                    @endif
                </div>
                @endcan
            </div>
        </div>
    </div>

    <!-- Tabs Section -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm" x-data="{ activeTab: 'deployments' }">
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6">
                <button 
                    @click="activeTab = 'deployments'"
                    :class="{ 'border-orange-500 text-orange-600 bg-orange-50': activeTab === 'deployments', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'deployments' }"
                    class="whitespace-nowrap py-4 px-3 border-b-2 font-medium text-sm transition-colors relative"
                >
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ __('ui.labels.deployments') }}
                        <span class="ml-2 inline-flex items-center px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                            {{ count($truck['deployments'] ?? []) }}
                        </span>
                    </div>
                </button>
                <button 
                    @click="activeTab = 'maintenance'"
                    :class="{ 'border-orange-500 text-orange-600 bg-orange-50': activeTab === 'maintenance', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'maintenance' }"
                    class="whitespace-nowrap py-4 px-3 border-b-2 font-medium text-sm transition-colors relative"
                >
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ __('ui.labels.maintenance') }}
                        <span class="ml-2 inline-flex items-center px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                            {{ count($truck['maintenance'] ?? []) }}
                        </span>
                    </div>
                </button>
            </nav>
        </div>        
        
        <!-- Deployments Tab -->
        <div x-show="activeTab === 'deployments'" class="p-6">
            @can('update', $truckModel)
            <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-xl border border-orange-200 p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-orange-100 rounded-lg">
                            <svg class="h-5 w-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('ui.actions.schedule_deployment') }}</h3>
                    </div>
                    <button type="button" onclick="openScheduleDeploymentModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 text-white font-medium rounded-lg hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ __('ui.actions.new_deployment') }}
                    </button>
                </div>
                <p class="text-sm text-gray-600">{{ __('ui.help.new_deployment') }}</p>
            </div>
            @endcan

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ __('ui.labels.deployments') }}</h3>
                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                {{ count($truck['deployments'] ?? []) }} {{ __('ui.labels.total') }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ __('ui.utilization.last_30_days') }}: <span class="font-bold text-orange-600">{{ $utilization30 }}%</span>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('ui.deployment.fields.location') }}</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('ui.deployment.fields.planned_start_at') }}</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('ui.deployment.fields.planned_end_at') }}</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('ui.deployment.fields.actual_start_at') }}</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('ui.deployment.fields.actual_end_at') }}</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('ui.labels.franchisee') }}</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('ui.labels.status') }}</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('ui.labels.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse(($truck['deployments'] ?? []) as $d)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    <div class="flex items-center gap-2">
                                        <div class="p-1 bg-blue-100 rounded">
                                            <svg class="h-3 w-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                        {{ $d['location'] ?? '—' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $d['planned_start_at'] ? \Carbon\Carbon::parse($d['planned_start_at'])->format('d/m/Y H:i') : '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $d['planned_end_at'] ? \Carbon\Carbon::parse($d['planned_end_at'])->format('d/m/Y H:i') : '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $d['actual_start_at'] ? \Carbon\Carbon::parse($d['actual_start_at'])->format('d/m/Y H:i') : '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $d['actual_end_at'] ? \Carbon\Carbon::parse($d['actual_end_at'])->format('d/m/Y H:i') : '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $d['franchisee'] ?? '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusConfig = match($d['status'] ?? 'planned') {
                                            'open' => ['bg-blue-100 text-blue-800 border-blue-200', 'bg-blue-500'],
                                            'closed' => ['bg-green-100 text-green-800 border-green-200', 'bg-green-500'],
                                            'cancelled' => ['bg-gray-100 text-gray-800 border-gray-200', 'bg-gray-500'],
                                            default => ['bg-yellow-100 text-yellow-800 border-yellow-200', 'bg-yellow-500'],
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full border {{ $statusConfig[0] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $statusConfig[1] }}"></span>
                                        {{ __('ui.deployment.status.' . ($d['status'] ?? 'planned')) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    @can('update', $truckModel)
                                    <div class="flex items-center justify-end gap-2">
                                        @if(($d['status'] ?? 'planned') === 'planned')
                                            <form method="POST" action="{{ route('bo.deployments.open', $d['id']) }}" class="inline-flex items-center gap-2">
                                                @csrf
                                                <input type="datetime-local" name="actual_start_at" value="{{ $d['planned_start_at'] ? \Carbon\Carbon::parse($d['planned_start_at'])->format('Y-m-d\\TH:i') : now()->format('Y-m-d\\TH:i') }}" class="text-xs rounded border-gray-300 focus:border-orange-500 focus:ring-orange-500" required />
                                                @error('actual_start_at')<p class="text-red-600 text-xs">{{ $message }}</p>@enderror
                                                <button type="submit" class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-green-700 bg-green-50 rounded border border-green-200 hover:bg-green-100 transition-colors">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    {{ __('ui.actions.open') }}
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('bo.deployments.cancel', $d['id']) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-red-700 bg-red-50 rounded border border-red-200 hover:bg-red-100 transition-colors">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    {{ __('ui.actions.cancel') }}
                                                </button>
                                            </form>
                                        @elseif(($d['status'] ?? 'planned') === 'open')
                                            <form method="POST" action="{{ route('bo.deployments.close', $d['id']) }}" class="inline-flex items-center gap-2">
                                                @csrf
                                                <input type="hidden" name="actual_start_at" value="{{ \Carbon\Carbon::parse($d['actual_start_at'])->format('Y-m-d\\TH:i') }}" />
                                                <input type="datetime-local" name="actual_end_at" class="text-xs rounded border-gray-300 focus:border-orange-500 focus:ring-orange-500" required />
                                                @error('actual_end_at')<p class="text-red-600 text-xs">{{ $message }}</p>@enderror
                                                <button type="submit" class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-orange-700 bg-orange-50 rounded border border-orange-200 hover:bg-orange-100 transition-colors">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ __('ui.actions.close') }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    @endcan
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="p-4 bg-gray-100 rounded-full">
                                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-sm font-medium text-gray-500">{{ __('ui.empty.no_data') }}</p>
                                            <p class="text-xs text-gray-400 mt-1">{{ __('ui.empty.no_deployments') }}</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Maintenance Tab -->
        <div x-show="activeTab === 'maintenance'" class="p-6">
            <!-- Maintenance logs content here -->
        </div>
    </div>

    <!-- Include the new deployment modal -->
    @include('bo.trucks.modals.schedule_deployment_new_improved', ['truck' => $truckModel])
    
@endsection

@push('scripts')
<script>
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
</script>
@endpush
