@extends('layouts.app-shell')

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('content')
<div x-data="{ activeTab: 'list' }">
    <div class="mb-6">
        <x-ui.breadcrumbs :items="[
            ['title' => __('ui.nav.dashboard'), 'url' => route('bo.dashboard')],
            ['title' => __('ui.nav.trucks'), 'url' => route('bo.trucks.index')],
            ['title' => $truck->plate, 'url' => route('bo.trucks.show', $truck->id)],
            ['title' => __('ui.deployment.management')]
        ]" />
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-orange-100 rounded-lg">
                    <svg class="h-5 w-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ __('ui.deployment.management') }}</h2>
                    <p class="text-sm text-gray-600">{{ $truck->plate }} - {{ $truck->name }}</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('bo.trucks.show', $truck->id) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700 transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('ui.actions.back') }}
                </a>
                
                <button onclick="showModal('schedule-deployment-modal')" type="button" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('ui.actions.schedule_deployment') }}
                </button>
            </div>
        </div>
        
        <!-- Statistics Overview -->
        <div class="p-6 bg-gray-50 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">{{ __('ui.deployment.utilization') }}</div>
                        <div class="text-xl font-bold text-gray-900">{{ $stats['utilization'] }}%</div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-indigo-100 rounded-lg">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">{{ __('ui.deployment.total') }}</div>
                        <div class="text-xl font-bold text-gray-900">{{ $stats['total'] }}</div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-amber-100 rounded-lg">
                        <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">{{ __('ui.deployment.active') }}</div>
                        <div class="text-xl font-bold text-gray-900">{{ $stats['active'] }}</div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">{{ __('ui.deployment.completed') }}</div>
                        <div class="text-xl font-bold text-gray-900">{{ $stats['completed'] }}</div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-gray-100 rounded-lg">
                        <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">{{ __('ui.deployment.cancelled') }}</div>
                        <div class="text-xl font-bold text-gray-900">{{ $stats['cancelled'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200">
            <nav class="flex">
                <button 
                    @click="activeTab = 'list'" 
                    :class="{ 'border-orange-500 text-orange-600': activeTab === 'list', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'list' }"
                    class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors"
                >
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        {{ __('ui.deployment.list_view') }}
                    </div>
                </button>
                
                <button 
                    @click="activeTab = 'calendar'" 
                    :class="{ 'border-orange-500 text-orange-600': activeTab === 'calendar', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'calendar' }"
                    class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors"
                >
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ __('ui.deployment.calendar_view') }}
                    </div>
                </button>
                
                <button 
                    @click="activeTab = 'map'" 
                    :class="{ 'border-orange-500 text-orange-600': activeTab === 'map', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'map' }"
                    class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors"
                >
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                        {{ __('ui.deployment.map_view') }}
                    </div>
                </button>
                
                <button 
                    @click="activeTab = 'timeline'" 
                    :class="{ 'border-orange-500 text-orange-600': activeTab === 'timeline', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'timeline' }"
                    class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors"
                >
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                        </svg>
                        {{ __('ui.deployment.timeline_view') }}
                    </div>
                </button>
            </nav>
        </div>
        
        <!-- List View Tab -->
        <div x-show="activeTab === 'list'" class="p-6">
            <div class="mb-6 flex items-center justify-between">
                <div class="relative w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" placeholder="{{ __('ui.actions.search') }}" class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-full focus:ring-orange-500 focus:border-orange-500">
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" type="button" class="inline-flex items-center gap-2 px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700 transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            {{ __('ui.actions.filter') }}
                        </button>
                        
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                            <!-- Filter options -->
                        </div>
                    </div>
                    
                    <a href="#" class="inline-flex items-center gap-2 px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        {{ __('ui.actions.export') }}
                    </a>
                </div>
            </div>
            
            <div class="overflow-x-auto border border-gray-200 rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.deployment.fields.location') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.deployment.fields.dates') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.deployment.fields.duration') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.labels.franchisee') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.labels.status') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.labels.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($deployments as $deployment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="p-1 bg-blue-100 rounded">
                                        <svg class="h-3 w-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $deployment->location_text }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $deployment->planned_start_at->format('d/m/Y H:i') }}</div>
                                <div class="text-sm text-gray-500">{{ $deployment->planned_end_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @php
                                        $start = $deployment->actual_start_at ?? $deployment->planned_start_at;
                                        $end = $deployment->actual_end_at ?? $deployment->planned_end_at;
                                        $diff = $start->diff($end);
                                        $hours = $diff->days * 24 + $diff->h;
                                        $duration = $hours > 0 ? "{$hours}h " : "";
                                        $duration .= $diff->i > 0 ? "{$diff->i}m" : "";
                                    @endphp
                                    {{ $duration }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $deployment->franchisee->name ?? '—' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusConfig = match($deployment->status) {
                                        'open' => ['bg-blue-100 text-blue-800 border-blue-200', 'bg-blue-500'],
                                        'closed' => ['bg-green-100 text-green-800 border-green-200', 'bg-green-500'],
                                        'cancelled' => ['bg-gray-100 text-gray-800 border-gray-200', 'bg-gray-500'],
                                        default => ['bg-yellow-100 text-yellow-800 border-yellow-200', 'bg-yellow-500'],
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full border {{ $statusConfig[0] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusConfig[1] }}"></span>
                                    {{ __('ui.deployment.status.' . $deployment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Actions based on status -->
                                    @if($deployment->status === 'planned')
                                        <form method="POST" action="{{ route('bo.deployments.open', $deployment->id) }}">
                                            @csrf
                                            <input type="hidden" name="actual_start_at" value="{{ now()->format('Y-m-d\\TH:i') }}">
                                            <button type="submit" class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-green-700 bg-green-50 rounded border border-green-200 hover:bg-green-100 transition-colors">
                                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                {{ __('ui.actions.open') }}
                                            </button>
                                        </form>
                                    @elseif($deployment->status === 'open')
                                        <form method="POST" action="{{ route('bo.deployments.close', $deployment->id) }}">
                                            @csrf
                                            <input type="hidden" name="actual_end_at" value="{{ now()->format('Y-m-d\\TH:i') }}">
                                            <button type="submit" class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-orange-700 bg-orange-50 rounded border border-orange-200 hover:bg-orange-100 transition-colors">
                                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ __('ui.actions.close') }}
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <button class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-gray-700 bg-gray-50 rounded border border-gray-200 hover:bg-gray-100 transition-colors">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        {{ __('ui.actions.details') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Calendar View Tab -->
        <div x-show="activeTab === 'calendar'" class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                    <button id="prev-month" class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    
                    <h3 id="calendar-title" class="text-lg font-semibold text-gray-900">Août 2025</h3>
                    
                    <button id="next-month" class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    
                    <button id="today" class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-600 text-sm">
                        {{ __('ui.actions.today') }}
                    </button>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <span class="text-xs text-gray-600">{{ __('deployment.status.planned') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-amber-500 rounded-full"></div>
                        <span class="text-xs text-gray-600">{{ __('deployment.status.open') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-xs text-gray-600">{{ __('deployment.status.closed') }}</span>
                    </div>
                </div>
            </div>
            
            <div id="calendar" class="border border-gray-200 rounded-lg"></div>
        </div>
        
        <!-- Map View Tab -->
        <div x-show="activeTab === 'map'" class="p-6">
            <div class="bg-gray-50 rounded-lg border border-gray-200 h-96 flex items-center justify-center">
                <div class="text-center">
                    <svg class="h-12 w-12 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mt-3">{{ __('ui.deployment.map_coming_soon') }}</h3>
                    <p class="text-gray-500 mt-1 max-w-md mx-auto">{{ __('ui.deployment.map_description') }}</p>
                </div>
            </div>
        </div>
        
        <!-- Timeline View Tab -->
        <div x-show="activeTab === 'timeline'" class="p-6">
            <div class="relative pb-12">
                <!-- Timeline vertical line -->
                <div class="absolute top-0 left-16 bottom-0 w-0.5 bg-gray-200"></div>
                
                @foreach($timelineDeployments as $deployment)
                <div class="relative mb-8">
                    <!-- Timeline dot -->
                    @php
                        $dotColor = match($deployment->status) {
                            'open' => 'bg-blue-500',
                            'closed' => 'bg-green-500',
                            'cancelled' => 'bg-gray-400',
                            default => 'bg-yellow-500',
                        };
                    @endphp
                    <div class="absolute left-16 mt-1.5 -ml-1.5 h-3 w-3 rounded-full border-2 border-white {{ $dotColor }}"></div>
                    
                    <!-- Timeline date -->
                    <div class="text-right pr-4 w-16 absolute top-0 left-0 text-xs text-gray-500">
                        {{ $deployment->planned_start_at->format('d/m/y') }}
                    </div>
                    
                    <!-- Timeline content -->
                    <div class="ml-24 bg-white rounded-lg border border-gray-200 p-4 hover:border-orange-200 hover:bg-orange-50 transition-colors">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $deployment->location_text }}</h4>
                                <div class="flex items-center gap-4 mt-1 text-sm text-gray-600">
                                    <div class="flex items-center gap-1">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span>{{ $deployment->planned_start_at->format('H:i') }} - {{ $deployment->planned_end_at->format('H:i') }}</span>
                                    </div>
                                    @if($deployment->franchisee)
                                    <div class="flex items-center gap-1">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span>{{ $deployment->franchisee->name }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ __('deployment.status.' . $deployment->status) }}
                            </span>
                        </div>
                        
                        @if($deployment->notes)
                        <div class="mt-2 text-sm text-gray-600">
                            {{ $deployment->notes }}
                        </div>
                        @endif
                        
                        <div class="mt-3 flex items-center justify-end gap-2">
                            @if($deployment->status === 'planned')
                                <form method="POST" action="{{ route('bo.deployments.open', $deployment->id) }}">
                                    @csrf
                                    <input type="hidden" name="actual_start_at" value="{{ now()->format('Y-m-d\\TH:i') }}">
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1 text-xs font-medium text-green-700 bg-green-50 rounded border border-green-200 hover:bg-green-100 transition-colors">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ __('ui.actions.open') }}
                                    </button>
                                </form>
                            @elseif($deployment->status === 'open')
                                <form method="POST" action="{{ route('bo.deployments.close', $deployment->id) }}">
                                    @csrf
                                    <input type="hidden" name="actual_end_at" value="{{ now()->format('Y-m-d\\TH:i') }}">
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1 text-xs font-medium text-orange-700 bg-orange-50 rounded border border-orange-200 hover:bg-orange-100 transition-colors">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ __('ui.actions.close') }}
                                    </button>
                                </form>
                            @endif
                            
                            <a href="#" class="inline-flex items-center gap-1 px-3 py-1 text-xs font-medium text-gray-700 bg-gray-50 rounded border border-gray-200 hover:bg-gray-100 transition-colors">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                {{ __('ui.actions.details') }}
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@include('bo.trucks.modals.schedule_deployment_new_improved', ['truck' => $truck])

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales-all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'fr',
            headerToolbar: false,
            dayMaxEvents: true,
            events: @json($calendarEvents),
            eventClick: function(info) {
                // Handle event click
                console.log('Event clicked:', info.event);
            },
            eventClassNames: function(arg) {
                // Add classes based on event status
                return [
                    'cursor-pointer',
                    'border-0',
                    arg.event.extendedProps.status === 'planned' ? 'bg-blue-500' : 
                    arg.event.extendedProps.status === 'open' ? 'bg-amber-500' : 
                    arg.event.extendedProps.status === 'closed' ? 'bg-green-500' : 'bg-gray-400'
                ];
            }
        });

        calendar.render();
        
        // Navigation buttons
        document.getElementById('prev-month')?.addEventListener('click', function() {
            calendar.prev();
            updateCalendarTitle();
        });
        
        document.getElementById('next-month')?.addEventListener('click', function() {
            calendar.next();
            updateCalendarTitle();
        });
        
        document.getElementById('today')?.addEventListener('click', function() {
            calendar.today();
            updateCalendarTitle();
        });
        
        function updateCalendarTitle() {
            const date = calendar.getDate();
            const formattedDate = new Intl.DateTimeFormat('fr-FR', {
                month: 'long',
                year: 'numeric'
            }).format(date);
            
            // Capitalize first letter
            const calendarTitle = document.getElementById('calendar-title');
            if (calendarTitle) {
                calendarTitle.textContent = formattedDate.charAt(0).toUpperCase() + formattedDate.slice(1);
            }
        }
        
        updateCalendarTitle();
    }
});
</script>
@endpush
@endsection
