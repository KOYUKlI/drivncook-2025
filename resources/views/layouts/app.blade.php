<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? __('ui.titles.home') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">
    <div id="app" class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <a href="{{ route('welcome') }}" class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-lg">DC</span>
                            </div>
                            <span class="font-bold text-xl text-gray-900">Driv'n Cook</span>
                        </a>
                    </div>

                    <!-- Public Navigation -->
                    @guest
                        <nav class="hidden md:flex space-x-8">
                            <a href="{{ route('welcome') }}" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('welcome') ? 'text-indigo-600 font-semibold' : '' }}">
                                {{ __('ui.nav.home') }}
                            </a>
                            <a href="{{ route('franchise-info') }}" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('franchise-info') ? 'text-indigo-600 font-semibold' : '' }}">
                                {{ __('ui.nav.franchise_info') }}
                            </a>
                            <a href="{{ route('applications.create') }}" class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                                {{ __('ui.nav.apply') }}
                            </a>
                            <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                                {{ __('ui.nav.login') }}
                            </a>
                        </nav>

                        <!-- Mobile menu button (guest) -->
                        <div class="md:hidden" x-data="{ open: false }">
                            <button @click="open = !open" type="button" class="text-gray-500 hover:text-gray-900 p-2 rounded-md">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            
                            <!-- Mobile menu (guest) -->
                            <div x-show="open" @click.away="open = false" x-transition class="absolute top-16 right-4 w-56 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                                <div class="py-1">
                                    <a href="{{ route('welcome') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('ui.nav.home') }}</a>
                                    <a href="{{ route('franchise-info') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('ui.nav.franchise_info') }}</a>
                                    <a href="{{ route('applications.create') }}" class="block px-4 py-2 text-sm text-white bg-indigo-600 hover:bg-indigo-700">{{ __('ui.nav.apply') }}</a>
                                    <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('ui.nav.login') }}</a>
                                </div>
                            </div>
                        </div>
                    @endguest

                    <!-- Language Switcher (for guests) -->
                    @guest
                        <div class="flex items-center space-x-4">
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center space-x-1 text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                    <span>{{ strtoupper(app()->getLocale()) }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-24 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                                    <div class="py-1">
                                        <a href="{{ route(Route::currentRouteName(), array_merge(request()->route()->parameters(), ['locale' => 'fr'])) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ app()->getLocale() === 'fr' ? 'bg-gray-50 font-semibold' : '' }}">FR</a>
                                        <a href="{{ route(Route::currentRouteName(), array_merge(request()->route()->parameters(), ['locale' => 'en'])) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ app()->getLocale() === 'en' ? 'bg-gray-50 font-semibold' : '' }}">EN</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endguest

                    <!-- User menu (authenticated) -->
                    @auth
                        <div class="flex items-center space-x-4">
                            <!-- Language Switcher (for authenticated users) -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center space-x-1 text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                    <span>{{ strtoupper(app()->getLocale()) }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-24 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                                    <div class="py-1">
                                        <a href="{{ route(Route::currentRouteName(), array_merge(request()->route()->parameters(), ['locale' => 'fr'])) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ app()->getLocale() === 'fr' ? 'bg-gray-50 font-semibold' : '' }}">FR</a>
                                        <a href="{{ route(Route::currentRouteName(), array_merge(request()->route()->parameters(), ['locale' => 'en'])) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ app()->getLocale() === 'en' ? 'bg-gray-50 font-semibold' : '' }}">EN</a>
                                    </div>
                                </div>
                            </div>

                            <!-- User Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center space-x-2 text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                        <span class="text-gray-600 font-medium text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                    <span class="hidden sm:block">{{ auth()->user()->name }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                                    <div class="py-1">
                                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('ui.nav.profile') }}</a>
                                        <form method="POST" action="{{ route('logout') }}" class="block">
                                            @csrf
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('ui.nav.logout') }}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 flex">
            <!-- Sidebar (only for authenticated users) -->
            @auth
                @include('components.sidebar')
            @endauth

            <!-- Page Content -->
            <div class="flex-1 {{ auth()->check() ? 'lg:ml-64' : '' }}">
                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow-sm border-b border-gray-200">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <div class="min-h-full">
                    @yield('content')
                    @isset($slot)
                        {{ $slot }}
                    @endisset
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 {{ auth()->check() ? 'lg:ml-64' : '' }}">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="py-6">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div class="text-sm text-gray-500">
                            © {{ date('Y') }} Driv'n Cook. {{ __('All rights reserved.') }}
                        </div>
                        <div class="flex items-center space-x-4 mt-4 md:mt-0">
                            <a href="#" class="text-sm text-gray-500 hover:text-gray-900">{{ __('Privacy Policy') }}</a>
                            <a href="#" class="text-sm text-gray-500 hover:text-gray-900">{{ __('Terms of Service') }}</a>
                            <a href="#" class="text-sm text-gray-500 hover:text-gray-900">{{ __('Contact') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
