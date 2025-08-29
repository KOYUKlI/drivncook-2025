{{-- Header avec thème orange DrivnCook --}}
@php
    $currentRoute = request()->route()->getName();
    $isAdminArea = str_starts_with($currentRoute, 'bo.') || str_starts_with($currentRoute, 'fo.');
    $isBoPage = str_starts_with($currentRoute, 'bo.');
    $isFoPage = str_starts_with($currentRoute, 'fo.');
    
    // Titre contextuel simple
    $pageTitle = match(true) {
        $currentRoute === 'bo.dashboard' => __('ui.nav.dashboard'),
        str_contains($currentRoute, 'applications') => __('ui.nav.applications'),
        str_contains($currentRoute, 'franchisees') => __('ui.nav.franchisees'),
        str_contains($currentRoute, 'trucks') => __('ui.nav.trucks'),
        str_contains($currentRoute, 'warehouses') => __('ui.nav.warehouses'),
        str_contains($currentRoute, 'stock-items') => __('ui.nav.stock_items'),
        str_contains($currentRoute, 'purchase-orders') => __('ui.nav.purchase_orders'),
        str_contains($currentRoute, 'reports') => __('ui.labels.reports'),
        $currentRoute === 'fo.dashboard' => __('ui.nav.dashboard'),
        str_contains($currentRoute, 'fo.sales') => __('ui.nav.fo_sales'),
        str_contains($currentRoute, 'fo.reports') => __('ui.nav.fo_reports'),
        default => __('ui.titles.home')
    };
@endphp

<header class="bg-white shadow-sm border-b border-gray-200 {{ $isAdminArea ? 'sticky top-0 z-40' : '' }}">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between items-center">
            
            <!-- Section gauche -->
            <div class="flex items-center gap-x-4">
                @if($isAdminArea)
                    {{-- Bouton mobile menu --}}
                    <button type="button" 
                            class="lg:hidden -m-2.5 p-2.5 text-orange-600 hover:text-orange-700"
                            @click="sidebarOpen = true">
                        <span class="sr-only">{{ __('ui.open_menu') }}</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    {{-- Titre de page pour admin --}}
                    <div class="lg:pl-64">
                        <h1 class="text-xl font-semibold text-gray-900">{{ $pageTitle }}</h1>
                    </div>
                @else
                    {{-- Logo pour les pages publiques --}}
                    <a href="{{ route('home') }}" class="flex items-center gap-x-2">
                        <div class="h-8 w-8 bg-orange-500 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-sm">DC</span>
                        </div>
                        <span class="text-xl font-semibold text-gray-900">Driv'n Cook</span>
                    </a>
                @endif
            </div>

            <!-- Section droite -->
            <div class="flex items-center gap-x-4">
                @if(!$isAdminArea)
                    {{-- Navigation publique --}}
                    <a href="{{ route('public.applications.create') }}" 
                       class="text-sm font-medium text-gray-700 hover:text-orange-600">
                        {{ __('ui.nav.apply') }}
                    </a>
                    <a href="{{ route('public.franchise') }}" 
                       class="text-sm font-medium text-gray-700 hover:text-orange-600">
                        {{ __('ui.nav.franchise_info') }}
                    </a>
                @endif

                @guest
                    {{-- Connexion pour les invités --}}
                    <a href="{{ route('login') }}" 
                       class="rounded-md bg-orange-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-500">
                        {{ __('ui.login') }}
                    </a>
                @else
                    {{-- Menu utilisateur --}}
                    <div class="relative" x-data="{ open: false }">
                        <button type="button" 
                                class="flex items-center gap-x-2 rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                                @click="open = !open"
                                :aria-expanded="open">
                            @if($isAdminArea)
                                <div class="h-8 w-8 rounded-full bg-gradient-to-r from-orange-500 to-orange-600 flex items-center justify-center">
                                    <span class="text-sm font-semibold text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <span class="text-sm font-semibold leading-6 text-gray-900">{{ Auth::user()->name }}</span>
                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        {{-- Dropdown menu --}}
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             @click.away="open = false"
                             class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5"
                             style="display: none;">
                            
                            {{-- User info --}}
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-sm text-gray-900 font-medium">{{ Auth::user()->name }}</p>
                                <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                            </div>

                            {{-- Menu items --}}
                            <a href="{{ route('profile.edit') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-orange-50">
                                <svg class="mr-3 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ __('ui.profile') }}
                            </a>

                            @if($isAdminArea)
                                <a href="{{ route('home') }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-orange-50">
                                    <svg class="mr-3 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    {{ __('ui.public_site') }}
                                </a>
                            @endif

                            <div class="border-t border-gray-100">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-orange-50">
                                        <svg class="mr-3 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        {{ __('ui.logout') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</header>
