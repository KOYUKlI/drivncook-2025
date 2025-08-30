@props(['title', 'subtitle' => null, 'breadcrumbs' => []])

<div class="mb-6">
    @if(count($breadcrumbs) > 0)
        <x-ui.breadcrumbs :breadcrumbs="$breadcrumbs" />
    @endif
    
    <div class="md:flex md:items-center md:justify-between">
        <div class="min-w-0 flex-1">
            <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                {{ $title }}
            </h1>
            @if($subtitle)
                <p class="mt-1 text-sm text-gray-500">
                    {{ $subtitle }}
                </p>
            @endif
        </div>
        @isset($actions)
            <div class="mt-4 flex md:ml-4 md:mt-0">
                {{ $actions }}
            </div>
        @endisset
    </div>
</div>
