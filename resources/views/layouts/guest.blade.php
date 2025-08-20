<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', "Driv'n Cook") }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900">
        <div class="min-h-screen flex flex-col">
            <nav class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center">
                    <a href="{{ auth()->check() ? (auth()->user()->role === 'admin' ? route('admin.dashboard') : route('franchise.dashboard')) : url('/') }}" class="flex items-center gap-2 font-semibold">
                        <span class="inline-block h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                        <span>Driv'n Cook</span>
                    </a>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="flex-1 px-4 py-8">
                @hasSection('content')
                    <div class="max-w-7xl mx-auto">
                        @yield('content')
                    </div>
                @else
                    <div class="min-h-[70vh] flex items-center justify-center">
                        <div class="w-full max-w-md">
                            <div class="card">
                                <div class="card-body">
                                    {{ $slot ?? '' }}
                                    <div class="mt-6 text-sm text-center text-gray-600">
                                        <a href="{{ route('franchise.apply') }}" class="text-amber-700 hover:underline">Devenir franchisé</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </main>
        </div>
    </body>
</html>
