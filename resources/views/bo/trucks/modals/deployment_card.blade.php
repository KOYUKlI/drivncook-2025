<!-- Deployment Card Component -->
@props(['deployment'])

<div class="flex flex-col p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
    <div class="flex items-center justify-between mb-2">
        <div class="flex items-center gap-2">
            <div class="p-2 bg-blue-100 rounded-lg">
                <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <h4 class="font-medium text-gray-900 truncate">{{ $deployment['location'] ?? '—' }}</h4>
        </div>
        
        @php
            $statusConfig = match($deployment['status'] ?? 'planned') {
                'open' => ['bg-blue-100 text-blue-800 border-blue-200', 'bg-blue-500'],
                'closed' => ['bg-green-100 text-green-800 border-green-200', 'bg-green-500'],
                'cancelled' => ['bg-gray-100 text-gray-800 border-gray-200', 'bg-gray-500'],
                default => ['bg-yellow-100 text-yellow-800 border-yellow-200', 'bg-yellow-500'],
            };
        @endphp
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full border {{ $statusConfig[0] }}">
            <span class="w-1.5 h-1.5 rounded-full {{ $statusConfig[1] }}"></span>
            {{ __('ui.deployment.status.' . ($deployment['status'] ?? 'planned')) }}
        </span>
    </div>
    
    <div class="mt-1">
        <h5 class="text-xs font-medium text-gray-500 mb-1">{{ __('deployment.fields.planned_start_at') }} / {{ __('deployment.fields.planned_end_at') }}</h5>
        <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-sm text-gray-900">
            <span class="inline-block bg-blue-50 text-blue-700 px-2 py-1 rounded-md text-xs">{{ $deployment['planned_start_at'] ? \Carbon\Carbon::parse($deployment['planned_start_at'])->format('d/m/Y') : '—' }}</span>
            <span class="inline-block text-gray-400 text-xs">{{ $deployment['planned_start_at'] ? \Carbon\Carbon::parse($deployment['planned_start_at'])->format('H:i') : '' }}</span>
            <span class="inline-block text-gray-400 text-xs">→</span>
            @if($deployment['planned_end_at'] && \Carbon\Carbon::parse($deployment['planned_start_at'])->format('d/m/Y') !== \Carbon\Carbon::parse($deployment['planned_end_at'])->format('d/m/Y'))
                <span class="inline-block bg-blue-50 text-blue-700 px-2 py-1 rounded-md text-xs">{{ \Carbon\Carbon::parse($deployment['planned_end_at'])->format('d/m/Y') }}</span>
            @endif
            <span class="inline-block text-gray-400 text-xs">{{ $deployment['planned_end_at'] ? \Carbon\Carbon::parse($deployment['planned_end_at'])->format('H:i') : '' }}</span>
        </div>
    </div>
    
    @if(!empty($deployment['actual_start_at']) || !empty($deployment['actual_end_at']))
    <div class="mt-3">
        <h5 class="text-xs font-medium text-gray-500 mb-1">{{ __('deployment.fields.actual_start_at') }} / {{ __('deployment.fields.actual_end_at') }}</h5>
        <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-sm text-gray-900">
            @if($deployment['actual_start_at'])
                <span class="inline-block bg-green-50 text-green-700 px-2 py-1 rounded-md text-xs">{{ \Carbon\Carbon::parse($deployment['actual_start_at'])->format('d/m/Y') }}</span>
                <span class="inline-block text-gray-600 text-xs">{{ \Carbon\Carbon::parse($deployment['actual_start_at'])->format('H:i') }}</span>
            @else
                <span class="inline-block text-gray-400 text-xs">—</span>
            @endif
            
            <span class="inline-block text-gray-400 text-xs">→</span>
            
            @if($deployment['actual_end_at'])
                @if(!$deployment['actual_start_at'] || \Carbon\Carbon::parse($deployment['actual_start_at'])->format('d/m/Y') !== \Carbon\Carbon::parse($deployment['actual_end_at'])->format('d/m/Y'))
                    <span class="inline-block bg-green-50 text-green-700 px-2 py-1 rounded-md text-xs">{{ \Carbon\Carbon::parse($deployment['actual_end_at'])->format('d/m/Y') }}</span>
                @endif
                <span class="inline-block text-gray-600 text-xs">{{ \Carbon\Carbon::parse($deployment['actual_end_at'])->format('H:i') }}</span>
            @else
                <span class="inline-block text-gray-400 text-xs">—</span>
            @endif
        </div>
    </div>
    @endif
    
    @if(!empty($deployment['franchisee']))
    <div class="mt-3">
        <h5 class="text-xs font-medium text-gray-500 mb-1">{{ __('ui.labels.franchisee') }}</h5>
        <p class="text-sm text-gray-900">{{ $deployment['franchisee'] }}</p>
    </div>
    @endif
    
    @can('update', $truckModel ?? null)
    <div class="mt-4 pt-3 border-t border-gray-200 flex flex-wrap items-center justify-end gap-2">
        @if(($deployment['status'] ?? 'planned') === 'planned')
            <form method="POST" action="{{ route('bo.deployments.open', $deployment['id']) }}" class="flex flex-wrap items-center gap-2">
                @csrf
                <input type="datetime-local" name="actual_start_at" value="{{ $deployment['planned_start_at'] ? \Carbon\Carbon::parse($deployment['planned_start_at'])->format('Y-m-d\\TH:i') : now()->format('Y-m-d\\TH:i') }}" class="text-xs w-32 rounded border-gray-300 focus:border-orange-500 focus:ring-orange-500" required />
                @error('actual_start_at')<p class="text-red-600 text-xs">{{ $message }}</p>@enderror
                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-green-700 bg-green-50 rounded-lg border border-green-200 hover:bg-green-100 transition-colors">
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ __('ui.actions.open') }}
                </button>
            </form>
            <form method="POST" action="{{ route('bo.deployments.cancel', $deployment['id']) }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 rounded-lg border border-red-200 hover:bg-red-100 transition-colors">
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ __('ui.actions.cancel') }}
                </button>
            </form>
        @elseif(($deployment['status'] ?? 'planned') === 'open')
            <form method="POST" action="{{ route('bo.deployments.close', $deployment['id']) }}" class="flex flex-wrap items-center gap-2">
                @csrf
                <input type="hidden" name="actual_start_at" value="{{ \Carbon\Carbon::parse($deployment['actual_start_at'])->format('Y-m-d\\TH:i') }}" />
                <input type="datetime-local" name="actual_end_at" class="text-xs w-32 rounded border-gray-300 focus:border-orange-500 focus:ring-orange-500" required />
                @error('actual_end_at')<p class="text-red-600 text-xs">{{ $message }}</p>@enderror
                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-orange-700 bg-orange-50 rounded-lg border border-orange-200 hover:bg-orange-100 transition-colors">
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('ui.actions.close') }}
                </button>
            </form>
        @endif
    </div>
    @endcan
</div>
