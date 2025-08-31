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
                        <p class="text-sm font-medium text-gray-600">{{ __('ui.utilization.last_30_days') }}</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $utilization30 }}%</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">{{ __('ui.labels.deployments') }}</p>
                        <p class="text-2xl font-bold text-gray-900">{{ count($truck['deployments'] ?? []) }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">{{ __('ui.labels.maintenance') }}</p>
                        <p class="text-2xl font-bold text-gray-900">{{ count(array_filter($truck['maintenance'] ?? [], fn($m) => ($m['status'] ?? 'open') === 'open')) }}</p>
                        <p class="text-xs text-gray-500">{{ __('ui.maintenance.status.open') }}</p>
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
                        <p class="text-sm font-medium text-gray-600">{{ __('ui.labels.documents') }}</p>
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
        </div>        <!-- Deployments Tab -->
        <div x-show="activeTab === 'deployments'" class="p-6">
            @can('update', $truckModel)
            @php
                $recentDeployment = count($truck['deployments'] ?? []) > 0 
                    ? collect($truck['deployments'] ?? [])->sortByDesc('planned_start_at')->first()
                    : null;
                
                // Make sure we have an array if not null
                if ($recentDeployment && !is_array($recentDeployment)) {
                    $recentDeployment = [];
                }
            @endphp
            
            <x-deployment-scheduler-card 
                :truck="$truckModel ?? $truck"
                :recent-deployment="$recentDeployment"
                class="mb-6"
            />
            @endcan

            <section class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden" aria-labelledby="deployments-title">
                <header class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 bg-orange-100 rounded-full text-orange-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 id="deployments-title" class="text-xl font-semibold text-gray-900">{{ __('deployment.deployments') }}</h2>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ __('ui.utilization.last_30_days') }}: 
                                    <span class="font-bold text-orange-600">{{ $utilization30 }}%</span>
                                </p>
                            </div>
                            <span class="px-2.5 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded-full">
                                {{ count($truck['deployments'] ?? []) }} {{ __('ui.labels.total') }}
                            </span>
                        </div>
                    </div>
                </header>

                <div x-data="{ 
                        sortField: 'planned_start', 
                        sortDirection: 'desc',
                        deployments: {{ Illuminate\Support\Js::from($truck['deployments'] ?? []) }},
                        loading: false,
                        notification: { show: false, message: '', type: 'success' },
                        
                        // Fonction pour ajuster les dates au fuseau horaire local
                        adjustTimeForTimezone(dateString) {
                            const date = new Date(dateString);
                            // On convertit en chaîne locale puis on extrait les 16 premiers caractères (YYYY-MM-DDTHH:MM)
                            return date.toLocaleDateString('fr-CA', { // fr-CA utilise le format YYYY-MM-DD
                                year: 'numeric',
                                month: '2-digit',
                                day: '2-digit',
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: false
                            }).replace(/(\d+)\/(\d+)\/(\d+), (\d+):(\d+)/, '$3-$1-$2T$4:$5');
                        },
                        
                        showNotification(message, type = 'success') {
                            this.notification = { show: true, message, type };
                            setTimeout(() => {
                                this.notification.show = false;
                            }, 3000);
                        },
                        
                        async openDeployment(deploymentId, actualStartAt) {
                            this.loading = true;
                            try {
                                // Convertir la date locale en UTC avant l'envoi
                                const startDate = new Date(actualStartAt);
                                
                                const response = await fetch(`/bo/deployments/${deploymentId}/open`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        actual_start_at: startDate.toISOString()
                                    })
                                });
                                
                                const data = await response.json();
                                
                                if (response.ok) {
                                    // Mettre à jour le déploiement localement
                                    const deploymentIndex = this.deployments.findIndex(d => d.id === deploymentId);
                                    if (deploymentIndex !== -1) {
                                        this.deployments[deploymentIndex] = { ...this.deployments[deploymentIndex], ...data.deployment };
                                    }
                                    this.showNotification('Déploiement ouvert avec succès', 'success');
                                } else {
                                    this.showNotification(data.message || 'Erreur lors de l\'ouverture du déploiement', 'error');
                                }
                            } catch (error) {
                                this.showNotification('Erreur de connexion', 'error');
                            } finally {
                                this.loading = false;
                            }
                        },
                        
                        async closeDeployment(deploymentId, actualEndAt) {
                            this.loading = true;
                            try {
                                // Convertir la date locale en UTC avant l'envoi
                                const endDate = new Date(actualEndAt);
                                
                                const response = await fetch(`/bo/deployments/${deploymentId}/close`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        actual_end_at: endDate.toISOString()
                                    })
                                });
                                
                                const data = await response.json();
                                
                                if (response.ok) {
                                    // Mettre à jour le déploiement localement
                                    const deploymentIndex = this.deployments.findIndex(d => d.id === deploymentId);
                                    if (deploymentIndex !== -1) {
                                        this.deployments[deploymentIndex] = { ...this.deployments[deploymentIndex], ...data.deployment };
                                    }
                                    this.showNotification('Déploiement fermé avec succès', 'success');
                                } else {
                                    this.showNotification(data.message || 'Erreur lors de la fermeture du déploiement', 'error');
                                }
                            } catch (error) {
                                this.showNotification('Erreur de connexion', 'error');
                            } finally {
                                this.loading = false;
                            }
                        },
                        
                        async cancelDeployment(deploymentId) {
                            const cancelReason = prompt('Veuillez indiquer la raison de l\'annulation (minimum 3 caractères) :');
                            
                            if (!cancelReason || cancelReason.trim().length < 3) {
                                if (cancelReason !== null) { // User didn't cancel the prompt
                                    this.showNotification('La raison d\'annulation doit contenir au moins 3 caractères', 'error');
                                }
                                return;
                            }
                            
                            this.loading = true;
                            try {
                                const response = await fetch(`/bo/deployments/${deploymentId}/cancel`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        cancel_reason: cancelReason.trim()
                                    })
                                });
                                
                                const data = await response.json();
                                
                                if (response.ok) {
                                    // Retirer le déploiement de la liste
                                    this.deployments = this.deployments.filter(d => d.id !== deploymentId);
                                    this.showNotification('Déploiement annulé avec succès', 'success');
                                } else {
                                    this.showNotification(data.message || 'Erreur lors de l\'annulation du déploiement', 'error');
                                }
                            } catch (error) {
                                this.showNotification('Erreur de connexion', 'error');
                            } finally {
                                this.loading = false;
                            }
                        },
                        
                        sortDeployments(field) {
                            if (this.sortField === field) {
                                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                            } else {
                                this.sortField = field;
                                this.sortDirection = 'desc';
                            }
                            
                            this.deployments.sort((a, b) => {
                                let valueA, valueB;
                                
                                if (field === 'location') {
                                    valueA = a.location || '';
                                    valueB = b.location || '';
                                } else if (field === 'planned_start') {
                                    valueA = a.planned_start_at ? new Date(a.planned_start_at) : new Date(0);
                                    valueB = b.planned_start_at ? new Date(b.planned_start_at) : new Date(0);
                                } else if (field === 'status') {
                                    valueA = a.status || '';
                                    valueB = b.status || '';
                                }
                                
                                if (this.sortDirection === 'asc') {
                                    return valueA > valueB ? 1 : -1;
                                } else {
                                    return valueA < valueB ? 1 : -1;
                                }
                            });
                        }
                    }" class="p-6">
                    
                    <!-- Notification Toast -->
                    <div x-show="notification.show" 
                         x-transition:enter="transform ease-out duration-300 transition"
                         x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                         x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed top-4 right-4 z-50 max-w-sm w-full bg-white shadow-lg rounded-lg border border-gray-200"
                         style="display: none;">
                        <div class="p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <!-- Success Icon -->
                                    <svg x-show="notification.type === 'success'" class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <!-- Error Icon -->
                                    <svg x-show="notification.type === 'error'" class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 w-0 flex-1">
                                    <p class="text-sm font-medium" 
                                       :class="notification.type === 'success' ? 'text-green-800' : 'text-red-800'" 
                                       x-text="notification.message"></p>
                                </div>
                                <div class="ml-4 flex-shrink-0 flex">
                                    <button @click="notification.show = false" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                        <span class="sr-only">Fermer</span>
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap items-center justify-between mb-5 gap-3">
                        <h3 class="font-medium text-gray-700 text-sm">{{ __('deployment.filters.sort_by') }}:</h3>
                        <div class="flex flex-wrap gap-2">
                            <button @click="sortDeployments('planned_start')" 
                                    class="flex items-center gap-1.5 px-3 py-1.5 text-xs rounded-lg transition-colors"
                                    :class="sortField === 'planned_start' ? 'bg-blue-100 text-blue-800 border border-blue-200 font-medium' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-200'"
                                    aria-label="{{ __('deployment.filters.sort_by') }} {{ __('deployment.fields.planned_start_at') }}">
                                <span>{{ __('deployment.fields.planned_start_at') }}</span>
                                <svg x-show="sortField === 'planned_start'" class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          :d="sortDirection === 'asc' ? 'M19 9l-7 7-7-7' : 'M5 15l7-7 7 7'"></path>
                                </svg>
                            </button>
                            
                            <button @click="sortDeployments('location')" 
                                    class="flex items-center gap-1.5 px-3 py-1.5 text-xs rounded-lg transition-colors"
                                    :class="sortField === 'location' ? 'bg-blue-100 text-blue-800 border border-blue-200 font-medium' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-200'"
                                    aria-label="{{ __('deployment.filters.sort_by') }} {{ __('deployment.fields.location') }}">
                                <span>{{ __('deployment.fields.location') }}</span>
                                <svg x-show="sortField === 'location'" class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          :d="sortDirection === 'asc' ? 'M19 9l-7 7-7-7' : 'M5 15l7-7 7 7'"></path>
                                </svg>
                            </button>
                            
                            <button @click="sortDeployments('status')" 
                                    class="flex items-center gap-1.5 px-3 py-1.5 text-xs rounded-lg transition-colors"
                                    :class="sortField === 'status' ? 'bg-blue-100 text-blue-800 border border-blue-200 font-medium' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-200'"
                                    aria-label="{{ __('deployment.filters.sort_by') }} {{ __('deployment.fields.status') }}">
                                <span>{{ __('deployment.fields.status') }}</span>
                                <svg x-show="sortField === 'status'" class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          :d="sortDirection === 'asc' ? 'M19 9l-7 7-7-7' : 'M5 15l7-7 7 7'"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                        <template x-if="deployments.length === 0">
                            <div class="col-span-full px-8 py-16 text-center bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex flex-col items-center justify-center max-w-md mx-auto">
                                    <div class="p-5 bg-gray-100 rounded-full mb-4">
                                        <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">{{ __('deployment.messages.no_deployments') }}</h3>
                                    <p class="text-sm text-gray-500 mb-6">{{ __('ui.empty.no_deployments') }}</p>
                                    
                                    <button
                                        type="button"
                                        onclick="openDeploymentModal()"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors shadow-sm"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        <span>{{ __('deployment.actions.schedule') }}</span>
                                    </button>
                                </div>
                            </div>
                        </template>
                        
                        <template x-for="deployment in deployments" :key="deployment.id">
                            <div class="deployment-card-wrapper">
                                <!-- We need to create a static template since we can't directly bind Alpine data to Blade components -->
                                <div
                                    class="bg-white overflow-hidden rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition duration-200"
                                    :class="{
                                        'border-orange-300': deployment.status === 'open',
                                        'border-green-300': deployment.status === 'closed',
                                        'border-gray-300': deployment.status === 'cancelled',
                                        'border-yellow-300': deployment.status === 'planned' || !deployment.status
                                    }"
                                >
                                    <!-- Card Header -->
                                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 flex flex-wrap items-center justify-between gap-2 border-b border-gray-200">
                                        <div class="flex items-center gap-2 truncate">
                                            <div class="p-1.5 bg-orange-100 text-orange-700 rounded-md" aria-hidden="true">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </div>
                                            <h3 class="font-medium text-gray-900 truncate" x-text="deployment.location || '—'"></h3>
                                        </div>
                                        
                                        <!-- Status Badge -->
                                        <template x-if="deployment.status === 'open'">
                                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full border bg-orange-100 text-orange-800 border-orange-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-orange-500" aria-hidden="true"></span>
                                                <span>{{ __('deployment.status.open') }}</span>
                                            </span>
                                        </template>
                                        <template x-if="deployment.status === 'closed'">
                                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full border bg-green-100 text-green-800 border-green-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500" aria-hidden="true"></span>
                                                <span>{{ __('deployment.status.closed') }}</span>
                                            </span>
                                        </template>
                                        <template x-if="deployment.status === 'cancelled'">
                                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full border bg-gray-100 text-gray-800 border-gray-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-500" aria-hidden="true"></span>
                                                <span>{{ __('deployment.status.cancelled') }}</span>
                                            </span>
                                        </template>
                                        <template x-if="deployment.status === 'planned' || !deployment.status">
                                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full border bg-yellow-100 text-yellow-800 border-yellow-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500" aria-hidden="true"></span>
                                                <span>{{ __('deployment.status.planned') }}</span>
                                            </span>
                                        </template>
                                    </div>
                                    
                                    <!-- Card Body -->
                                    <div class="p-4 space-y-3">
                                        <!-- Planned Dates -->
                                        <div>
                                            <h4 class="text-xs font-medium uppercase text-gray-500 tracking-wide mb-2">
                                                {{ __('deployment.fields.planned_start_at') }} / {{ __('deployment.fields.planned_end_at') }}
                                            </h4>
                                            <div class="flex flex-wrap items-center gap-1.5 text-sm" role="group" aria-label="{{ __('deployment.fields.planned_dates') }}">
                                                <template x-if="deployment.planned_start_at">
                                                    <div class="flex flex-wrap items-center gap-x-1.5 gap-y-1">
                                                        <time 
                                                            :datetime="new Date(deployment.planned_start_at).toISOString()"
                                                            class="inline-flex items-center px-2 py-1 bg-orange-50 text-orange-700 text-xs rounded-md"
                                                            x-text="new Date(deployment.planned_start_at).toLocaleDateString('fr-FR', {day: '2-digit', month: '2-digit', year: 'numeric'})">
                                                        </time>
                                                        <time 
                                                            :datetime="new Date(deployment.planned_start_at).toISOString()"
                                                            class="text-xs text-gray-600"
                                                            x-text="new Date(deployment.planned_start_at).toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'})">
                                                        </time>
                                                        <span class="text-xs text-gray-500" aria-hidden="true">→</span>
                                                    </div>
                                                </template>
                                                <template x-if="!deployment.planned_start_at">
                                                    <span class="text-xs text-gray-400">—</span>
                                                </template>
                                                
                                                <template x-if="deployment.planned_end_at">
                                                    <div class="flex flex-wrap items-center gap-x-1.5 gap-y-1">
                                                        <template x-if="deployment.planned_start_at && new Date(deployment.planned_start_at).toDateString() !== new Date(deployment.planned_end_at).toDateString()">
                                                            <time 
                                                                :datetime="new Date(deployment.planned_end_at).toISOString()"
                                                                class="inline-flex items-center px-2 py-1 bg-orange-50 text-orange-700 text-xs rounded-md"
                                                                x-text="new Date(deployment.planned_end_at).toLocaleDateString('fr-FR', {day: '2-digit', month: '2-digit', year: 'numeric'})">
                                                            </time>
                                                        </template>
                                                        <time 
                                                            :datetime="new Date(deployment.planned_end_at).toISOString()"
                                                            class="text-xs text-gray-600"
                                                            x-text="new Date(deployment.planned_end_at).toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'})">
                                                        </time>
                                                    </div>
                                                </template>
                                                <template x-if="!deployment.planned_end_at">
                                                    <span class="text-xs text-gray-400">—</span>
                                                </template>
                                            </div>
                                        </div>
                                        
                                        <!-- Actual Dates -->
                                        <template x-if="deployment.actual_start_at || deployment.actual_end_at">
                                            <div>
                                                <h4 class="text-xs font-medium uppercase text-gray-500 tracking-wide mb-2">
                                                    {{ __('deployment.fields.actual_start_at') }} / {{ __('deployment.fields.actual_end_at') }}
                                                </h4>
                                                <div class="flex flex-wrap items-center gap-1.5 text-sm" role="group" aria-label="{{ __('deployment.fields.actual_dates') }}">
                                                    <template x-if="deployment.actual_start_at">
                                                        <div class="flex flex-wrap items-center gap-x-1.5 gap-y-1">
                                                            <time 
                                                                :datetime="new Date(deployment.actual_start_at).toISOString()"
                                                                class="inline-flex items-center px-2 py-1 bg-green-50 text-green-700 text-xs rounded-md"
                                                                x-text="new Date(deployment.actual_start_at).toLocaleDateString('fr-FR', {day: '2-digit', month: '2-digit', year: 'numeric'})">
                                                            </time>
                                                            <time 
                                                                :datetime="new Date(deployment.actual_start_at).toISOString()"
                                                                class="text-xs text-gray-600"
                                                                x-text="new Date(deployment.actual_start_at).toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'})">
                                                            </time>
                                                            <span class="text-xs text-gray-500" aria-hidden="true">→</span>
                                                        </div>
                                                    </template>
                                                    <template x-if="!deployment.actual_start_at">
                                                        <span class="text-xs text-gray-400">—</span>
                                                    </template>
                                                    
                                                    <template x-if="deployment.actual_end_at">
                                                        <div class="flex flex-wrap items-center gap-x-1.5 gap-y-1">
                                                            <template x-if="!deployment.actual_start_at || new Date(deployment.actual_start_at).toDateString() !== new Date(deployment.actual_end_at).toDateString()">
                                                                <time 
                                                                    :datetime="new Date(deployment.actual_end_at).toISOString()"
                                                                    class="inline-flex items-center px-2 py-1 bg-green-50 text-green-700 text-xs rounded-md"
                                                                    x-text="new Date(deployment.actual_end_at).toLocaleDateString('fr-FR', {day: '2-digit', month: '2-digit', year: 'numeric'})">
                                                                </time>
                                                            </template>
                                                            <time 
                                                                :datetime="new Date(deployment.actual_end_at).toISOString()"
                                                                class="text-xs text-gray-600"
                                                                x-text="new Date(deployment.actual_end_at).toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'})">
                                                            </time>
                                                        </div>
                                                    </template>
                                                    <template x-if="!deployment.actual_end_at">
                                                        <span class="text-xs text-gray-400">—</span>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                        
                                        <!-- Franchisee -->
                                        <template x-if="deployment.franchisee">
                                            <div>
                                                <h4 class="text-xs font-medium uppercase text-gray-500 tracking-wide mb-2">
                                                    {{ __('ui.labels.franchisee') }}
                                                </h4>
                                                <p class="text-sm text-gray-900" x-text="deployment.franchisee"></p>
                                            </div>
                                        </template>
                                        
                                        <!-- Notes -->
                                        <template x-if="deployment.notes">
                                            <div>
                                                <h4 class="text-xs font-medium uppercase text-gray-500 tracking-wide mb-2">
                                                    {{ __('deployment.fields.notes') }}
                                                </h4>
                                                <p class="text-sm text-gray-700 line-clamp-2" x-text="deployment.notes"></p>
                                            </div>
                                        </template>
                                    </div>
                                    
                                    <!-- Card Footer with Actions -->
                                    @can('update', $truckModel ?? null)
                                    <div class="mt-1 px-4 py-3 bg-gray-50 border-t border-gray-200 flex flex-wrap items-center justify-end gap-2">
                                        <template x-if="deployment.status === 'planned' || !deployment.status">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <input type="datetime-local" 
                                                      :id="'start-date-' + deployment.id"
                                                      :value="deployment.planned_start_at ? adjustTimeForTimezone(deployment.planned_start_at) : adjustTimeForTimezone(new Date())" 
                                                      class="text-xs w-32 rounded border-gray-300 focus:border-orange-500 focus:ring-orange-500" 
                                                      required 
                                                      aria-label="{{ __('deployment.fields.actual_start_at') }}" />
                                                <button @click="openDeployment(deployment.id, $el.previousElementSibling.value)" 
                                                        :disabled="loading"
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-green-700 bg-green-50 rounded-lg border border-green-200 hover:bg-green-100 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    <span x-text="loading ? 'En cours...' : '{{ __('deployment.actions.open') }}'"></span>
                                                </button>
                                                <button @click="cancelDeployment(deployment.id)" 
                                                        :disabled="loading"
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 rounded-lg border border-red-200 hover:bg-red-100 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    {{ __('deployment.actions.cancel') }}
                                                </button>
                                            </div>
                                        </template>
                                        
                                        <template x-if="deployment.status === 'open'">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <input type="datetime-local" 
                                                      :id="'end-date-' + deployment.id"
                                                      :value="adjustTimeForTimezone(new Date())"
                                                      class="text-xs w-32 rounded border-gray-300 focus:border-orange-500 focus:ring-orange-500" 
                                                      required 
                                                      aria-label="{{ __('deployment.fields.actual_end_at') }}" />
                                                <button @click="closeDeployment(deployment.id, $el.previousElementSibling.value)" 
                                                        :disabled="loading"
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-orange-700 bg-orange-50 rounded-lg border border-orange-200 hover:bg-orange-100 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span x-text="loading ? 'En cours...' : '{{ __('deployment.actions.close') }}'"></span>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                    @endcan
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </section>
        </div>

        <!-- Maintenance Tab -->
        <div x-show="activeTab === 'maintenance'" class="p-6">
            @can('create', App\Models\MaintenanceLog::class)
            <div class="bg-gradient-to-r from-amber-50 to-yellow-50 rounded-xl border border-amber-200 p-6 mb-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-amber-100 rounded-lg">
                        <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('ui.actions.open_maintenance') }}</h3>
                </div>
                <form method="POST" action="{{ route('bo.maintenance.open', $truck['id']) }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                    @csrf
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('ui.maintenance.type.label') }}</label>
                        <select name="type" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                            <option value="preventive">{{ __('ui.maintenance.type.preventive') }}</option>
                            <option value="corrective">{{ __('ui.maintenance.type.corrective') }}</option>
                        </select>
                        @error('type')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('ui.maintenance.fields.description') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <input type="text" name="description" value="{{ old('description') }}" placeholder="{{ __('ui.maintenance.placeholder.description') }}" class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500" />
                        </div>
                        @error('description')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('ui.maintenance.fields.cost_cents') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-400 text-sm">€</span>
                            </div>
                            <input type="number" name="cost_cents" value="{{ old('cost_cents') }}" min="0" placeholder="0" class="pl-8 w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500" />
                        </div>
                        @error('cost_cents')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('ui.maintenance.fields.opened_at') }}</label>
                        <input type="datetime-local" name="opened_at" value="{{ old('opened_at') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500" />
                        @error('opened_at')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('ui.maintenance.fields.attachment') }}</label>
                        <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100" />
                        @error('attachment')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-6 flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-amber-600 text-white font-medium rounded-lg hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ __('ui.actions.open_maintenance') }}
                        </button>
                    </div>
                </form>
            </div>
            @endcan

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-amber-100 rounded-lg">
                                <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ __('ui.maintenance.history') }}</h3>
                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                {{ count($truck['maintenance'] ?? []) }} {{ __('ui.labels.total') }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ __('ui.maintenance.status.open') }}: <span class="font-bold text-amber-600">{{ count(array_filter($truck['maintenance'] ?? [], fn($m) => ($m['status'] ?? 'open') === 'open')) }}</span>
                        </div>
                    </div>
                </div>

                <div x-data="{ 
                        activeMaintenances: {{ Illuminate\Support\Js::from(array_filter(($truck['maintenance'] ?? []), fn($m) => ($m['status'] ?? 'open') === 'open')) }},
                        closedMaintenances: {{ Illuminate\Support\Js::from(array_filter(($truck['maintenance'] ?? []), fn($m) => ($m['status'] ?? 'open') === 'closed')) }},
                        view: 'active'
                     }" class="p-4">
                    
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex space-x-2">
                            <button 
                                @click="view = 'active'" 
                                :class="view === 'active' ? 'bg-amber-100 text-amber-800 border-amber-200' : 'bg-gray-100 text-gray-700 border-gray-200 hover:bg-gray-200'"
                                class="px-4 py-2 text-sm font-medium rounded-lg border transition-colors flex items-center gap-2">
                                <span class="inline-block w-2 h-2 rounded-full bg-yellow-500"></span>
                                {{ __('ui.maintenance.status.open') }} 
                                <span class="ml-1 px-2 py-0.5 bg-white bg-opacity-50 rounded-full text-xs">
                                    <span x-text="activeMaintenances.length"></span>
                                </span>
                            </button>
                            <button 
                                @click="view = 'closed'" 
                                :class="view === 'closed' ? 'bg-green-100 text-green-800 border-green-200' : 'bg-gray-100 text-gray-700 border-gray-200 hover:bg-gray-200'"
                                class="px-4 py-2 text-sm font-medium rounded-lg border transition-colors flex items-center gap-2">
                                <span class="inline-block w-2 h-2 rounded-full bg-green-500"></span>
                                {{ __('ui.maintenance.status.closed') }}
                                <span class="ml-1 px-2 py-0.5 bg-white bg-opacity-50 rounded-full text-xs">
                                    <span x-text="closedMaintenances.length"></span>
                                </span>
                            </button>
                        </div>
                        
                        <div>
                            <span class="text-sm text-gray-500">
                                {{ __('ui.maintenance.total_cost') }}: <span class="font-semibold text-amber-600">€
                                {{ number_format(array_reduce(($truck['maintenance'] ?? []), function($carry, $item) {
                                    return $carry + ($item['cost'] ?? 0);
                                }, 0) / 100, 2, ',', ' ') }}
                                </span>
                            </span>
                        </div>
                    </div>

                    <!-- Active Maintenance Cards -->
                    <div x-show="view === 'active'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <template x-if="activeMaintenances.length === 0">
                            <div class="col-span-full px-6 py-12 text-center bg-white rounded-lg border border-gray-200">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="p-4 bg-amber-50 rounded-full">
                                        <svg class="h-8 w-8 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-sm font-medium text-gray-500">{{ __('ui.empty.no_active_maintenance') }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ __('ui.empty.all_maintenance_completed') }}</p>
                                    </div>
                                </div>
                            </div>
                        </template>
                        
                        <template x-for="maintenance in activeMaintenances" :key="maintenance.id">
                            <div class="bg-white rounded-lg border border-yellow-200 shadow-sm overflow-hidden">
                                <div class="bg-gradient-to-r from-yellow-50 to-amber-50 px-4 py-3 border-b border-yellow-100">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <template x-if="maintenance.type === 'corrective'">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                    {{ __('ui.maintenance.type.corrective') }}
                                                </span>
                                            </template>
                                            <template x-if="maintenance.type === 'preventive' || !maintenance.type">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                                    {{ __('ui.maintenance.type.preventive') }}
                                                </span>
                                            </template>
                                        </div>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full border bg-yellow-100 text-yellow-800 border-yellow-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                                            {{ __('ui.maintenance.status.open') }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="p-4 space-y-3">
                                    <div>
                                        <h4 class="font-medium text-gray-900" x-text="maintenance.description || '—'"></h4>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2">
                                        <div>
                                            <h5 class="text-xs font-medium text-gray-500">{{ __('ui.maintenance.fields.opened_at') }}</h5>
                                            <p class="text-sm text-gray-900" x-text="maintenance.opened_at ? new Date(maintenance.opened_at).toLocaleDateString('fr-FR', {day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit'}) : '—'"></p>
                                        </div>
                                        
                                        <div>
                                            <h5 class="text-xs font-medium text-gray-500">{{ __('ui.cost') }}</h5>
                                            <div class="flex items-center gap-1 text-sm font-medium text-gray-900">
                                                <span class="text-gray-500">€</span>
                                                <span x-text="(maintenance.cost / 100).toLocaleString('fr-FR', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @can('update', $truckModel)
                                    <div class="pt-3 border-t border-gray-200">
                                        <form :action="'/bo/maintenance/' + maintenance.id + '/close'" method="POST" enctype="multipart/form-data" class="flex flex-wrap items-center gap-2">
                                            @csrf
                                            <input type="hidden" name="opened_at" :value="new Date(maintenance.opened_at).toISOString().slice(0, 16)" />
                                            <input type="datetime-local" name="closed_at" class="text-xs w-32 rounded border-gray-300 focus:border-amber-500 focus:ring-amber-500" required />
                                            <input type="text" name="resolution" placeholder="{{ __('ui.maintenance.fields.resolution') }}" class="text-xs flex-grow rounded border-gray-300 focus:border-amber-500 focus:ring-amber-500" required />
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-green-700 bg-green-50 rounded-lg border border-green-200 hover:bg-green-100 transition-colors">
                                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ __('ui.actions.close_maintenance') }}
                                            </button>
                                        </form>
                                    </div>
                                    @endcan
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Closed Maintenance Table -->
                    <div x-show="view === 'closed'" class="overflow-x-auto bg-white rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('ui.maintenance.fields.opened_at') }}</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('ui.maintenance.fields.closed_at') }}</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('ui.maintenance.type.label') }}</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('ui.maintenance.fields.description') }}</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('ui.cost') }}</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('ui.labels.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <template x-if="closedMaintenances.length === 0">
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center">
                                            <div class="flex flex-col items-center gap-3">
                                                <div class="p-3 bg-gray-100 rounded-full">
                                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                </div>
                                                <div class="text-center">
                                                    <p class="text-sm font-medium text-gray-500">{{ __('ui.empty.no_closed_maintenance') }}</p>
                                                    <p class="text-xs text-gray-400 mt-1">{{ __('ui.empty.maintenance_all_open') }}</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                
                                <template x-for="maintenance in closedMaintenances" :key="maintenance.id">
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-sm text-gray-900" x-text="maintenance.opened_at ? new Date(maintenance.opened_at).toLocaleDateString('fr-FR', {day: '2-digit', month: '2-digit', year: 'numeric'}) : '—'"></div>
                                            <div class="text-xs text-gray-500" x-text="maintenance.opened_at ? new Date(maintenance.opened_at).toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'}) : ''"></div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-sm text-gray-900" x-text="maintenance.closed_at ? new Date(maintenance.closed_at).toLocaleDateString('fr-FR', {day: '2-digit', month: '2-digit', year: 'numeric'}) : '—'"></div>
                                            <div class="text-xs text-gray-500" x-text="maintenance.closed_at ? new Date(maintenance.closed_at).toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'}) : ''"></div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <template x-if="maintenance.type === 'corrective'">
                                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                    {{ __('ui.maintenance.type.corrective') }}
                                                </span>
                                            </template>
                                            <template x-if="maintenance.type === 'preventive' || !maintenance.type">
                                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                                    {{ __('ui.maintenance.type.preventive') }}
                                                </span>
                                            </template>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-sm text-gray-900 truncate max-w-[200px]" x-text="maintenance.description || '—'"></div>
                                            <div class="text-xs text-gray-500 truncate max-w-[200px]" x-text="maintenance.resolution || '—'"></div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex items-center gap-1 text-sm font-medium text-gray-900">
                                                <span class="text-gray-500">€</span>
                                                <span x-text="(maintenance.cost / 100).toLocaleString('fr-FR', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right">
                                            <template x-if="maintenance.has_attachment">
                                                <a :href="'/bo/maintenance/' + maintenance.id + '/download'" class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-purple-700 bg-purple-50 rounded border border-purple-200 hover:bg-purple-100 transition-colors">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    {{ __('ui.actions.download_report') }}
                                                </a>
                                            </template>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Include new deployment modal -->
    @include('bo.trucks.modals.schedule_deployment_new', ['truck' => $truckModel])
    
@endsection
