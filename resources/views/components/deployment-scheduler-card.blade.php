@props([
    'truck',
    'recentDeployment' => null,
    'title' => __('deployment.schedule_deployment'),
    'description' => __('deployment.messages.scheduled'),
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden']) }}>
    <div class="bg-gradient-to-br from-orange-600 to-amber-700 px-6 py-5">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-white/20 rounded-full text-white" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-white">{{ $title }}</h2>
                    <p class="text-sm text-orange-100 mt-0.5">{{ $truck->code ?? $truck['code'] ?? '—' }}</p>
                </div>
            </div>
            
            <button
                type="button"
                onclick="openDeploymentModal()"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-orange-700 font-medium rounded-lg hover:bg-orange-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-orange-600 transition-colors shadow-sm"
                aria-label="{{ __('deployment.actions.schedule') }}"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span>{{ __('deployment.actions.schedule') }}</span>
            </button>
        </div>
    </div>
    
    <div class="p-6">
        <p class="text-sm text-gray-600 mb-4">{{ $description }}</p>
        
        @if($recentDeployment)
            @php
                $statusConfig = match($recentDeployment['status'] ?? 'planned') {
                    'open' => [
                        'bg' => 'bg-orange-100 text-orange-800 border-orange-200', 
                        'dot' => 'bg-orange-500',
                        'label' => __('deployment.status.open')
                    ],
                    'closed' => [
                        'bg' => 'bg-green-100 text-green-800 border-green-200', 
                        'dot' => 'bg-green-500',
                        'label' => __('deployment.status.closed')
                    ],
                    'cancelled' => [
                        'bg' => 'bg-gray-100 text-gray-800 border-gray-200', 
                        'dot' => 'bg-gray-500',
                        'label' => __('deployment.status.cancelled')
                    ],
                    default => [
                        'bg' => 'bg-yellow-100 text-yellow-800 border-yellow-200', 
                        'dot' => 'bg-yellow-500',
                        'label' => __('deployment.status.planned')
                    ],
                };
            @endphp
            
            <div class="rounded-lg border border-gray-200 overflow-hidden">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 flex flex-wrap items-center justify-between gap-2 border-b border-gray-200">
                    <div class="flex items-center gap-2 truncate">
                        <div class="p-1.5 bg-orange-100 text-orange-700 rounded-md" aria-hidden="true">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="font-medium text-gray-900 truncate">{{ $recentDeployment['location'] ?? '—' }}</h3>
                    </div>
                    
                    <span 
                        class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full border {{ $statusConfig['bg'] }}"
                        aria-label="{{ __('deployment.fields.status') }}: {{ $statusConfig['label'] }}"
                    >
                        <span class="w-1.5 h-1.5 rounded-full {{ $statusConfig['dot'] }}" aria-hidden="true"></span>
                        <span>{{ $statusConfig['label'] }}</span>
                    </span>
                </div>
                
                <!-- Card Body -->
                <div class="p-4 space-y-3">
                    <!-- Planned Dates -->
                    <div>
                        <h4 class="text-xs font-medium uppercase text-gray-500 tracking-wide mb-2">
                            {{ __('deployment.fields.planned_start_at') }} / {{ __('deployment.fields.planned_end_at') }}
                        </h4>
                        <div class="flex flex-wrap items-center gap-1.5 text-sm">
                            @if(!empty($recentDeployment['planned_start_at']))
                                <span class="inline-flex items-center px-2 py-1 bg-orange-50 text-orange-700 text-xs rounded-md">
                                    {{ \Carbon\Carbon::parse($recentDeployment['planned_start_at'])->format('d/m/Y') }}
                                </span>
                                <span class="text-xs text-gray-600">
                                    {{ \Carbon\Carbon::parse($recentDeployment['planned_start_at'])->format('H:i') }}
                                </span>
                                <span class="text-xs text-gray-500" aria-hidden="true">→</span>
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                            
                            @if(!empty($recentDeployment['planned_end_at']))
                                @if(empty($recentDeployment['planned_start_at']) || \Carbon\Carbon::parse($recentDeployment['planned_start_at'])->format('d/m/Y') !== \Carbon\Carbon::parse($recentDeployment['planned_end_at'])->format('d/m/Y'))
                                    <span class="inline-flex items-center px-2 py-1 bg-orange-50 text-orange-700 text-xs rounded-md">
                                        {{ \Carbon\Carbon::parse($recentDeployment['planned_end_at'])->format('d/m/Y') }}
                                    </span>
                                @endif
                                <span class="text-xs text-gray-600">
                                    {{ \Carbon\Carbon::parse($recentDeployment['planned_end_at'])->format('H:i') }}
                                </span>
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Franchisee info if available -->
                    @if(!empty($recentDeployment['franchisee']))
                        <div>
                            <h4 class="text-xs font-medium uppercase text-gray-500 tracking-wide mb-2">
                                {{ __('ui.labels.franchisee') }}
                            </h4>
                            <p class="text-sm text-gray-900">{{ $recentDeployment['franchisee'] }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="p-6 text-center bg-gray-50 rounded-lg border border-gray-200">
                <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('deployment.messages.no_deployments') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('deployment.messages.scheduled') }}</p>
            </div>
        @endif
    </div>
</div>
