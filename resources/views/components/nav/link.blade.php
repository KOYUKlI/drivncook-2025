@props(['active' => false, 'mobile' => false, 'icon' => null, 'badge' => null])

@php
$baseClasses = $mobile 
    ? 'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold'
    : 'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold';

$classes = $active 
    ? $baseClasses . ' bg-indigo-50 text-indigo-600'
    : $baseClasses . ' text-gray-700 hover:text-indigo-600 hover:bg-gray-50';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon)
        @include('components.icons.' . $icon, ['class' => 'h-5 w-5 shrink-0 ' . ($active ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600')])
    @endif
    
    <span class="truncate">{{ $slot }}</span>
    
    @if($badge && $badge > 0)
        <span class="ml-auto w-6 h-6 text-xs font-medium text-white bg-indigo-600 rounded-full flex items-center justify-center">
            {{ $badge }}
        </span>
    @endif
</a>
