@props(['active' => false, 'mobile' => false])

@php
$baseClasses = $mobile 
    ? 'block px-3 py-2 rounded-md text-base font-medium'
    : 'block px-3 py-2 rounded-md text-sm font-medium';

$classes = $active 
    ? $baseClasses . ' bg-orange-50 text-orange-700 border-l-2 border-orange-500 sidebar-link-active'
    : $baseClasses . ' text-gray-700 hover:bg-gray-50 hover:text-orange-700 hover:border-l-2 hover:border-orange-300 sidebar-link';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
