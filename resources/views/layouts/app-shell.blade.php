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

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="flex flex-col min-h-screen bg-gray-50 text-gray-900" x-data="{ sidebarOpen: false }">
        
        <!-- Header pleine largeur -->
        @include('layouts.partials.header')

        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar fixe à gauche -->
            @hasSection('sidebar')
                @auth
                    <!-- Sidebar desktop -->
                    <aside class="hidden lg:block w-56 bg-white border-r border-gray-200 shadow-sm z-20">
                        <div class="h-full overflow-y-auto">
                            @yield('sidebar')
                        </div>
                    </aside>

                    <!-- Sidebar mobile (overlay) -->
                    <div 
                        x-show="sidebarOpen" 
                        class="fixed inset-0 z-40 lg:hidden"
                        x-transition:enter="transition-opacity ease-linear duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition-opacity ease-linear duration-300"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                    >
                        <div class="absolute inset-0 bg-gray-600 bg-opacity-75" @click="sidebarOpen = false"></div>
                        
                        <div 
                            class="fixed inset-y-0 left-0 flex flex-col w-64 bg-white shadow-lg"
                            x-transition:enter="transition ease-in-out duration-300 transform"
                            x-transition:enter-start="-translate-x-full"
                            x-transition:enter-end="translate-x-0"
                            x-transition:leave="transition ease-in-out duration-300 transform"
                            x-transition:leave-start="translate-x-0"
                            x-transition:leave-end="-translate-x-full"
                        >
                            <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                                <span class="text-lg font-medium">Menu</span>
                                <button @click="sidebarOpen = false" class="p-1 rounded-md hover:bg-gray-100 focus:outline-none">
                                    <svg class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="overflow-y-auto flex-1">
                                @yield('sidebar')
                            </div>
                        </div>
                    </div>
                @endauth
            @endif

            <!-- Contenu principal -->
            <main class="flex-1 flex flex-col overflow-auto">
                <div class="flex-1 p-4 md:p-6">
                    <!-- Flash Messages - Only shown if app.blade.php is not the parent layout -->
                    @if(!View::getSection('app_layout_loaded'))
                        <x-ui.flash type="success" />
                        <x-ui.flash type="error" />
                        <x-ui.flash type="warning" />
                        <x-ui.flash type="info" />
                    @endif
                    
                    @yield('content')
                </div>
            </main>
        </div>

        <!-- Footer pleine largeur en dehors du flex -->
        @include('layouts.partials.footer')

    @stack('scripts')
    </body>
</html>
