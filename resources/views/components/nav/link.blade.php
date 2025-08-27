@props(['active' => false, 'mobile' => false])

@php
$baseClasses = $mobile 
    ? 'block px-3 py-2 rounded-md text-base font-medium'
    : 'block px-3 py-2 rounded-md text-sm font-medium';

$classes = $active 
    ? $baseClasses . ' bg-orange-100 text-orange-700 sidebar-link-active'
    : $baseClasses . ' text-gray-700 hover:bg-gray-100 hover:text-gray-900 sidebar-link';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
