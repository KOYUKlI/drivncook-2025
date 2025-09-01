{{--
    Deployment Card Component
    
    Props:
    - deployment: Array with deployment data
    - truck-model: Model for permissions check
--}}
@props(['deployment', 'truckModel'])

@php
    // Ensure $deployment is an array
    if (!is_array($deployment)) {
        if (is_string($deployment)) {
            try {
                $deployment = json_decode($deployment, true);
            } catch (\Exception $e) {
                $deployment = [];
            }
        } else {
            $deployment = [];
        }
    }

    $statusConfig = match($deployment['status'] ?? 'planned') {
        'open' => [
            'bg' => 'bg-blue-100 text-blue-800 border-blue-200', 
            'dot' => 'bg-blue-500',
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

<div {{ $attributes->merge(['class' => 'bg-white overflow-hidden rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition duration-200']) }}>
    <!-- Card Header -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-4 py-3 flex flex-wrap items-center justify-between gap-2 border-b border-gray-200">
        <div class="flex items-center gap-2 truncate">
            <div class="p-1.5 bg-blue-100 text-blue-700 rounded-md" aria-hidden="true">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <h3 class="font-semibold text-gray-900 truncate">{{ $deployment['location'] ?? '—' }}</h3>
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
            <h4 class="text-xs font-medium uppercase text-gray-500 tracking-wide mb-1.5">
                {{ __('deployment.fields.planned_start_at') }} / {{ __('deployment.fields.planned_end_at') }}
            </h4>
            <div class="flex flex-wrap items-center gap-1.5 text-sm" role="group" aria-label="{{ __('deployment.fields.planned_dates') }}">
                @if($deployment['planned_start_at'])
                    <time 
                        datetime="{{ \Carbon\Carbon::parse($deployment['planned_start_at'])->toIso8601String() }}"
                        class="inline-flex items-center px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-md"
                    >
                        {{ \Carbon\Carbon::parse($deployment['planned_start_at'])->format('d/m/Y') }}
                    </time>
                    <time 
                        datetime="{{ \Carbon\Carbon::parse($deployment['planned_start_at'])->toIso8601String() }}"
                        class="text-xs text-gray-600"
                    >
                        {{ \Carbon\Carbon::parse($deployment['planned_start_at'])->format('H:i') }}
                    </time>
                    <span class="text-xs text-gray-500" aria-hidden="true">→</span>
                @else
                    <span class="text-xs text-gray-400">—</span>
                @endif
                
                @if($deployment['planned_end_at'])
                    @if(!$deployment['planned_start_at'] || \Carbon\Carbon::parse($deployment['planned_start_at'])->format('d/m/Y') !== \Carbon\Carbon::parse($deployment['planned_end_at'])->format('d/m/Y'))
                        <time 
                            datetime="{{ \Carbon\Carbon::parse($deployment['planned_end_at'])->toIso8601String() }}"
                            class="inline-flex items-center px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-md"
                        >
                            {{ \Carbon\Carbon::parse($deployment['planned_end_at'])->format('d/m/Y') }}
                        </time>
                    @endif
                    <time 
                        datetime="{{ \Carbon\Carbon::parse($deployment['planned_end_at'])->toIso8601String() }}"
                        class="text-xs text-gray-600"
                    >
                        {{ \Carbon\Carbon::parse($deployment['planned_end_at'])->format('H:i') }}
                    </time>
                @else
                    <span class="text-xs text-gray-400">—</span>
                @endif
            </div>
        </div>
        
        <!-- Actual Dates if available -->
        @if(!empty($deployment['actual_start_at']) || !empty($deployment['actual_end_at']))
        <div>
            <h4 class="text-xs font-medium uppercase text-gray-500 tracking-wide mb-1.5">
                {{ __('deployment.fields.actual_start_at') }} / {{ __('deployment.fields.actual_end_at') }}
            </h4>
            <div class="flex flex-wrap items-center gap-1.5 text-sm" role="group" aria-label="{{ __('deployment.fields.actual_dates') }}">
                @if($deployment['actual_start_at'])
                    <time 
                        datetime="{{ \Carbon\Carbon::parse($deployment['actual_start_at'])->toIso8601String() }}"
                        class="inline-flex items-center px-2 py-1 bg-green-50 text-green-700 text-xs rounded-md"
                    >
                        {{ \Carbon\Carbon::parse($deployment['actual_start_at'])->format('d/m/Y') }}
                    </time>
                    <time 
                        datetime="{{ \Carbon\Carbon::parse($deployment['actual_start_at'])->toIso8601String() }}"
                        class="text-xs text-gray-600"
                    >
                        {{ \Carbon\Carbon::parse($deployment['actual_start_at'])->format('H:i') }}
                    </time>
                    <span class="text-xs text-gray-500" aria-hidden="true">→</span>
                @else
                    <span class="text-xs text-gray-400">—</span>
                @endif
                
                @if($deployment['actual_end_at'])
                    @if(!$deployment['actual_start_at'] || \Carbon\Carbon::parse($deployment['actual_start_at'])->format('d/m/Y') !== \Carbon\Carbon::parse($deployment['actual_end_at'])->format('d/m/Y'))
                        <time 
                            datetime="{{ \Carbon\Carbon::parse($deployment['actual_end_at'])->toIso8601String() }}"
                            class="inline-flex items-center px-2 py-1 bg-green-50 text-green-700 text-xs rounded-md"
                        >
                            {{ \Carbon\Carbon::parse($deployment['actual_end_at'])->format('d/m/Y') }}
                        </time>
                    @endif
                    <time 
                        datetime="{{ \Carbon\Carbon::parse($deployment['actual_end_at'])->toIso8601String() }}"
                        class="text-xs text-gray-600"
                    >
                        {{ \Carbon\Carbon::parse($deployment['actual_end_at'])->format('H:i') }}
                    </time>
                @else
                    <span class="text-xs text-gray-400">—</span>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Franchisee if available -->
        @if(!empty($deployment['franchisee']))
        <div>
            <h4 class="text-xs font-medium uppercase text-gray-500 tracking-wide mb-1.5">
                {{ __('ui.labels.franchisee') }}
            </h4>
            <p class="text-sm text-gray-900">{{ $deployment['franchisee'] }}</p>
        </div>
        @endif
        
        <!-- Notes if available -->
        @if(!empty($deployment['notes']))
        <div>
            <h4 class="text-xs font-medium uppercase text-gray-500 tracking-wide mb-1.5">
                {{ __('deployment.fields.notes') }}
            </h4>
            <p class="text-sm text-gray-700 line-clamp-2">{{ $deployment['notes'] }}</p>
        </div>
        @endif
    </div>
    
    <!-- Card Footer with Actions -->
    @can('update', $truckModel ?? null)
    <div class="mt-1 px-4 py-3 bg-gray-50 border-t border-gray-200 flex flex-wrap items-center justify-end gap-2">
        @if(($deployment['status'] ?? 'planned') === 'planned')
            <form method="POST" action="{{ route('bo.deployments.open', $deployment['id']) }}" class="flex flex-wrap items-center gap-2">
                @csrf
                <input type="datetime-local" name="actual_start_at" value="{{ $deployment['planned_start_at'] ? \Carbon\Carbon::parse($deployment['planned_start_at'])->format('Y-m-d\\TH:i') : now()->format('Y-m-d\\TH:i') }}" class="text-xs w-32 rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500" required aria-label="{{ __('deployment.fields.actual_start_at') }}" />
                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-green-700 bg-green-50 rounded-lg border border-green-200 hover:bg-green-100 transition-colors">
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ __('deployment.actions.open') }}
                </button>
            </form>
            <form method="POST" action="{{ route('bo.deployments.cancel', $deployment['id']) }}" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 rounded-lg border border-red-200 hover:bg-red-100 transition-colors">
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ __('deployment.actions.cancel') }}
                </button>
            </form>
        @elseif(($deployment['status'] ?? 'planned') === 'open')
            <form method="POST" action="{{ route('bo.deployments.close', $deployment['id']) }}" class="flex flex-wrap items-center gap-2">
                @csrf
                <input type="hidden" name="actual_start_at" value="{{ \Carbon\Carbon::parse($deployment['actual_start_at'])->format('Y-m-d\\TH:i') }}" />
                <input type="datetime-local" name="actual_end_at" class="text-xs w-32 rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500" required aria-label="{{ __('deployment.fields.actual_end_at') }}" />
                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 rounded-lg border border-blue-200 hover:bg-blue-100 transition-colors">
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('deployment.actions.close') }}
                </button>
            </form>
        @endif
    </div>
    @endcan
</div>
