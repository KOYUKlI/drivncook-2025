@php
    $currentRouteName = request()->route()->getName();
    $isBoPage = str_starts_with($currentRouteName, 'bo.');
    $isFoPage = str_starts_with($currentRouteName, 'fo.');
    $isAdminArea = $isBoPage || $isFoPage;
    
    // Déterminer le titre de la page actuelle
    $pageTitle = match(true) {
        $currentRouteName === 'bo.dashboard' => __('ui.nav.dashboard'),
        str_contains($currentRouteName, 'applications') => __('ui.nav.applications'),
        str_contains($currentRouteName, 'franchisees') => __('ui.nav.franchisees'),
        str_contains($currentRouteName, 'trucks') => __('ui.nav.trucks'),
        str_contains($currentRouteName, 'warehouses') => __('ui.nav.warehouses'),
        str_contains($currentRouteName, 'stock-items') => __('ui.nav.stock_items'),
        str_contains($currentRouteName, 'purchase-orders') => __('ui.nav.purchase_orders'),
        str_contains($currentRouteName, 'reports') => __('ui.labels.reports'),
        $currentRouteName === 'fo.dashboard' => __('ui.nav.dashboard'),
        str_contains($currentRouteName, 'fo.sales') => __('ui.nav.fo_sales'),
        str_contains($currentRouteName, 'fo.reports') => __('ui.nav.fo_reports'),
        default => __('ui.titles.home')
    };
@endphp

<header class="border-b bg-white shadow-sm z-10 {{ $isAdminArea ? 'sticky top-0' : '' }}">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <!-- Logo et titre / Breadcrumb pour admin -->
            <div class="flex items-center gap-4">
                @if($isAdminArea)
                    <!-- Bouton burger mobile pour admin -->
                    <button 
                        @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100"
                        aria-controls="mobile-sidebar"
                        :aria-expanded="sidebarOpen"
                    >
                        <span class="sr-only">{{ __('ui.open_menu') }}</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    
                    <!-- Navigation fil d'Ariane pour admin -->
                    <nav class="flex items-center space-x-2 text-sm">
                        <a href="{{ $isBoPage ? route('bo.dashboard') : route('fo.dashboard') }}" class="text-gray-500 hover:text-gray-700">
                            @role('admin')
                                {{ __('ui.titles.back_office') }}
                            @else
                                {{ __('ui.titles.franchise_office') }}
                            @endrole
                        </a>
                        @if($pageTitle !== __('ui.nav.dashboard'))
                            <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-gray-900 font-medium">{{ $pageTitle }}</span>
                        @endif
                    </nav>
                @else
                    <!-- Logo standard pour public -->
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <div class="h-8 w-8 bg-orange-500 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-sm">DC</span>
                        </div>
                        <span class="text-xl font-semibold text-gray-900">Driv'n Cook</span>
                    </a>
                @endif
            </div>

            <!-- Navigation utilisateur -->
            <div class="flex items-center gap-4">
                @if(!$isAdminArea)
                    <!-- Navigation publique -->
                    <a href="{{ route('public.applications.create') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 text-sm font-medium">
                        {{ __('ui.nav.apply') }}
                    </a>
                    <a href="{{ route('public.franchise') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 text-sm font-medium">
                        {{ __('ui.nav.franchise_info') }}
                    </a>
                @endif

                @guest
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 text-sm font-medium">
                        {{ __('ui.login') }}
                    </a>
                @else
                    @if($isAdminArea)
                        <!-- Notifications pour admin (si disponible) -->
                        <button class="p-2 text-gray-400 hover:text-gray-500">
                            <span class="sr-only">{{ __('ui.notifications') }}</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                        </button>
                    @endif

                    <!-- Menu utilisateur -->
                    <div class="relative" x-data="{ open: false }">
                        <button 
                            @click="open = !open"
                            class="flex items-center gap-2 text-sm text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md hover:bg-gray-100"
                        >
                            @if($isAdminArea)
                                <!-- Avatar pour admin -->
                                <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-medium text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <span>{{ Auth::user()->name }}</span>
                            @if($isAdminArea)
                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                    {{ Auth::user()->getRoleNames()->first() }}
                                </span>
                            @endif
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>

                        <div 
                            x-show="open" 
                            @click.away="open = false"
                            x-transition
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                        >
                            <div class="px-4 py-2 text-xs text-gray-500 border-b">
                                {{ Auth::user()->email }}
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <div class="flex items-center">
                                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ __('ui.profile') }}
                                </div>
                            </a>
                            @if($isAdminArea)
                                <a href="{{ route('home') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <div class="flex items-center">
                                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                        {{ __('ui.nav.public_site') }}
                                    </div>
                                </a>
                            @endif
                            <hr class="my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <div class="flex items-center">
                                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        {{ __('ui.logout') }}
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</header>
