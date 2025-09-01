@props(['title' => null, 'padding' => 'p-6'])

<div class="bg-white rounded-lg border border-gray-200 shadow-sm {{ $padding }}">
    @if($title)
        <div class="mb-4 pb-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">{{ $title }}</h3>
        </div>
    @endif
    
    {{ $slot }}
</div>
