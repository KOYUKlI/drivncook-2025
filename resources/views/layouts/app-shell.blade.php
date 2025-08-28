<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen flex flex-col bg-gray-50 text-gray-900" x-data="{ sidebarOpen: false }">
        
        @include('layouts.partials.header')

        <main class="flex-1">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
                <!-- Flash Messages -->
                <x-ui.flash type="success" />
                <x-ui.flash type="error" />
                <x-ui.flash type="warning" />
                <x-ui.flash type="info" />
                
                @hasSection('sidebar')
                    <div class="lg:flex lg:gap-6">
                        <aside class="lg:w-64 lg:flex-shrink-0 mb-6 lg:mb-0">
                            @yield('sidebar')
                        </aside>
                        
                        <section id="content" class="lg:flex-1">
                            @yield('content')
                        </section>
                    </div>
                @else
                    <section id="content">
                        @yield('content')
                    </section>
                @endif
            </div>
        </main>

        @include('layouts.partials.footer')
    </body>
</html>
