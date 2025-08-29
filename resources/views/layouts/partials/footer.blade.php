@php
    $isAdminArea = auth()->check() && (str_starts_with(request()->route()->getName(), 'bo.') || str_starts_with(request()->route()->getName(), 'fo.'));
@endphp

<footer class="fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-orange-100 {{ $isAdminArea ? 'lg:pl-64' : '' }}">
    <div class="px-4 py-3 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-x-4 text-sm text-gray-500">
                <span>&copy; {{ date('Y') }} DrivnCook</span>
                <span class="text-orange-300">•</span>
                <span>v{{ config('app.version', '1.0') }}</span>
            </div>
            <div class="flex items-center gap-x-1 text-xs text-gray-400">
                <svg class="h-3 w-3 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.765 11.842 11.842 0 00.757.433 5.683 5.683 0 00.281.14l.018.008.006.003z" clip-rule="evenodd" />
                </svg>
                <span>Fait avec ❤️ pour DrivnCook</span>
            </div>
        </div>
    </div>
</footer>
