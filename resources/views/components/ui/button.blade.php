@props(['type' => 'button', 'variant' => 'primary', 'size' => 'md', 'disabled' => false])

@php
$classes = '';

// Base button classes
$baseClasses = 'inline-flex items-center border font-semibold uppercase tracking-widest transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 rounded-md';

// Variant classes
$variantClasses = [
    'primary' => 'px-4 py-2 bg-orange-600 border-transparent text-white text-xs hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900 focus:ring-orange-500',
    'secondary' => 'px-4 py-2 bg-white border-gray-300 text-gray-700 text-xs hover:bg-gray-50 focus:bg-gray-50 active:bg-gray-100 focus:ring-indigo-500',
    'danger' => 'px-4 py-2 bg-red-600 border-transparent text-white text-xs hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:ring-red-500',
];

// Size classes
$sizeClasses = [
    'sm' => 'px-3 py-1.5 text-xs',
    'md' => 'px-4 py-2 text-xs',
    'lg' => 'px-6 py-3 text-sm',
];

$classes = $baseClasses . ' ' . ($variantClasses[$variant] ?? $variantClasses['primary']);

// Override size if needed
if (isset($sizeClasses[$size])) {
    $classes = $baseClasses . ' ' . str_replace(['px-4 py-2 text-xs', 'px-3 py-1.5', 'px-6 py-3'], $sizeClasses[$size], $variantClasses[$variant] ?? $variantClasses['primary']);
}

if ($disabled) {
    $classes .= ' opacity-50 cursor-not-allowed';
}
@endphp

<button 
    type="{{ $type }}" 
    class="{{ $classes }}"
    @if($disabled) disabled @endif
    {{ $attributes }}
>
    {{ $slot }}
</button>
