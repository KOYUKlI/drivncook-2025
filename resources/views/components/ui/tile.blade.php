@props(['title', 'value', 'subtitle' => null, 'icon' => null, 'color' => 'blue'])

@php
$colorClasses = [
    'blue' => 'bg-blue-50 text-blue-700 border-blue-200',
    'green' => 'bg-green-50 text-green-700 border-green-200',
    'red' => 'bg-red-50 text-red-700 border-red-200',
    'orange' => 'bg-orange-50 text-orange-700 border-orange-200',
    'gray' => 'bg-gray-50 text-gray-700 border-gray-200',
];
@endphp

<div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
    <div class="flex items-center justify-between">
        <div>
            <dt class="text-sm font-medium text-gray-500">{{ $title }}</dt>
            <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $value }}</dd>
            @if($subtitle)
                <dd class="mt-1 text-sm text-gray-600">{{ $subtitle }}</dd>
            @endif
        </div>
        
        @if($icon)
            <div class="p-3 rounded-full {{ $colorClasses[$color] }}">
                {!! $icon !!}
            </div>
        @endif
    </div>
</div>
