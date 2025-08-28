@props(['type' => 'info', 'message' => ''])

@php
$classes = [
    'success' => 'bg-green-50 border-green-200 text-green-800',
    'error' => 'bg-red-50 border-red-200 text-red-800',
    'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
    'info' => 'bg-blue-50 border-blue-200 text-blue-800',
];

$icons = [
    'success' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
    'error' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
    'warning' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
    'info' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
];

$bgClass = $classes[$type] ?? $classes['info'];
$iconPath = $icons[$type] ?? $icons['info'];
@endphp

@if(session()->has($type) || $message)
<div 
    x-data="{ show: true }" 
    x-show="show" 
    x-transition:enter="transition ease-out duration-300" 
    x-transition:enter-start="opacity-0 transform scale-95" 
    x-transition:enter-end="opacity-100 transform scale-100" 
    x-transition:leave="transition ease-in duration-200" 
    x-transition:leave-start="opacity-100 transform scale-100" 
    x-transition:leave-end="opacity-0 transform scale-95"
    class="mb-6 rounded-lg border {{ $bgClass }} p-4 shadow-sm"
>
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"></path>
            </svg>
        </div>
        <div class="ml-3 flex-1">
            <p class="text-sm font-medium">
                {{ $message ?: session($type) }}
            </p>
        </div>
        <div class="ml-auto pl-3">
            <button @click="show = false" class="inline-flex rounded-md p-1.5 hover:bg-black hover:bg-opacity-10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-current">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
</div>
@endif
