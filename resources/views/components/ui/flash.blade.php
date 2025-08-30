@props(['type' => 'info', 'message' => ''])

@php
$classes = [
    'success' => 'bg-green-50 border-green-500 text-green-800',
    'error' => 'bg-red-50 border-red-500 text-red-800',
    'warning' => 'bg-orange-50 border-orange-500 text-orange-800',
    'info' => 'bg-blue-50 border-blue-500 text-blue-800',
];

$icons = [
    'success' => '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />',
    'error' => '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />',
    'warning' => '<path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.516-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />',
    'info' => '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 102 0V6zm-1 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />',
];

$bgClass = $classes[$type] ?? $classes['info'];
$iconHtml = $icons[$type] ?? $icons['info'];

$borderColors = [
    'success' => 'border-l-4 border-green-500',
    'error' => 'border-l-4 border-red-500',
    'warning' => 'border-l-4 border-orange-500',
    'info' => 'border-l-4 border-blue-500',
];

$borderClass = $borderColors[$type] ?? $borderColors['info'];
@endphp

@if(session()->has($type) || $message)
<div 
    x-data="{ show: true }" 
    x-show="show" 
    x-transition:enter="transition ease-out duration-300" 
    x-transition:enter-start="opacity-0 transform translate-y-2" 
    x-transition:enter-end="opacity-100 transform translate-y-0" 
    x-transition:leave="transition ease-in duration-200" 
    x-transition:leave-start="opacity-100" 
    x-transition:leave-end="opacity-0"
    class="mb-6 rounded-lg {{ $borderClass }} {{ $bgClass }} p-4 shadow-sm"
    x-init="setTimeout(() => { show = false }, 6000)"
>
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                {!! $iconHtml !!}
            </svg>
        </div>
        <div class="ml-3 flex-1">
            <p class="text-sm font-medium">
                {{ $message ?: session($type) }}
            </p>
        </div>
        <div class="ml-auto pl-3">
            <button @click="show = false" class="inline-flex rounded-md p-1.5 hover:bg-black hover:bg-opacity-10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-current transition-colors duration-150">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
</div>
@endif
