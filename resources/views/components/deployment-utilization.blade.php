<!-- Deployment Utilization Widget -->
@props(['truck'])

@php
    // Calculate utilization for the last 30 days
    $utilizationRate = \App\Models\TruckDeployment::calculateUtilization($truck->id);
    
    // Determine color based on utilization rate
    $colorClass = match(true) {
        $utilizationRate >= 70 => 'text-green-600',
        $utilizationRate >= 40 => 'text-amber-600',
        default => 'text-red-600',
    };
@endphp

<div class="bg-white rounded-lg shadow p-4 border-t-4 border-indigo-500">
    <h3 class="text-lg font-semibold text-gray-700 mb-2">{{ __('deployment.fields.utilization') }}</h3>
    
    <div class="flex items-center mb-2">
        <div class="w-full bg-gray-200 rounded-full h-2.5">
            <div class="h-2.5 rounded-full {{ $colorClass == 'text-green-600' ? 'bg-green-600' : ($colorClass == 'text-amber-600' ? 'bg-amber-600' : 'bg-red-600') }}" style="width: {{ $utilizationRate }}%"></div>
        </div>
    </div>
    
    <p class="text-xl font-bold {{ $colorClass }}">
        {{ str_replace(':rate', $utilizationRate, __('deployment.messages.utilization_rate')) }}
    </p>
    
    <div class="mt-2 text-sm text-gray-600">
        {{ __('deployment.messages.utilization_info') }}
    </div>
</div>
