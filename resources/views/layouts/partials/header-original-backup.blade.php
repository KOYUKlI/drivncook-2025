{{-- Header moderne avec thème orangé subtil --}}
@auth
@php
    $user = Auth::user();
    $currentRoute = request()->route()->getName();
    $isAdminArea = str_starts_with($currentRoute, 'bo.') || str_starts_with($currentRoute, 'fo.');
    
    // Breadcrumbs intelligents
    $breadcrumbs = [];
    $pageTitle = '';
    
    if ($currentRoute === 'bo.dashboard') {
        $pageTitle = __('ui.nav.dashboard');
        $breadcrumbs = [['title' => __('ui.nav.dashboard')]];
    } elseif (str_contains($currentRoute, 'applications')) {
        $pageTitle = __('ui.nav.applications');
        $breadcrumbs = [
            ['title' => __('ui.nav.dashboard'), 'route' => 'bo.dashboard'],
            ['title' => __('ui.nav.applications')]
        ];
    } elseif (str_contains($currentRoute, 'franchisees')) {
        $pageTitle = __('ui.nav.franchisees');
        $breadcrumbs = [
            ['title' => __('ui.nav.dashboard'), 'route' => 'bo.dashboard'],
            ['title' => __('ui.nav.franchisees')]
        ];
    } elseif (str_contains($currentRoute, 'trucks')) {
        $pageTitle = __('ui.nav.trucks');
        $breadcrumbs = [
            ['title' => __('ui.nav.dashboard'), 'route' => 'bo.dashboard'],
            ['title' => __('ui.nav.trucks')]
        ];
    } elseif (str_contains($currentRoute, 'stock-items')) {
        $pageTitle = __('ui.nav.stock_items');
        $breadcrumbs = [
            ['title' => __('ui.nav.dashboard'), 'route' => 'bo.dashboard'],
            ['title' => __('ui.nav.stock_items')]
        ];
    } elseif (str_contains($currentRoute, 'purchase-orders')) {
        $pageTitle = __('ui.nav.purchase_orders');
        $breadcrumbs = [
            ['title' => __('ui.nav.dashboard'), 'route' => 'bo.dashboard'],
            ['title' => __('ui.nav.purchase_orders')]
        ];
    } elseif ($currentRoute === 'fo.dashboard') {
        $pageTitle = __('ui.nav.dashboard');
        $breadcrumbs = [['title' => __('ui.nav.dashboard')]];
    } elseif (str_contains($currentRoute, 'fo.sales')) {
        $pageTitle = __('ui.nav.fo_sales');
        $breadcrumbs = [
            ['title' => __('ui.nav.dashboard'), 'route' => 'fo.dashboard'],
            ['title' => __('ui.nav.fo_sales')]
        ];
    } else {
        $pageTitle = __('ui.nav.dashboard');
        $breadcrumbs = [['title' => __('ui.nav.dashboard')]];
    }
@endphp

{{-- Header fixe avec thème orange --}}
<header class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-orange-100 shadow-sm">
    <div class="flex h-16 items-center gap-x-4 px-4 sm:px-6 lg:px-8 {{ $isAdminArea ? 'lg:pl-72' : '' }}">
        
        {{-- Mobile menu button --}}
        @if($isAdminArea)
        <button type="button" 
                class="lg:hidden -m-2.5 p-2.5 text-gray-400 hover:text-orange-600 transition-colors"
                @click="sidebarOpen = true">
            <span class="sr-only">Ouvrir le menu</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>
        @endif

        {{-- Breadcrumbs et titre de page --}}
        <div class="flex-1 min-w-0">
            @if($isAdminArea && !empty($breadcrumbs))
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2">
                        @foreach($breadcrumbs as $index => $breadcrumb)
                            <li class="flex items-center">
                                @if($index > 0)
                                    <svg class="flex-shrink-0 h-4 w-4 text-orange-300 mx-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                                @if(isset($breadcrumb['route']))
                                    <a href="{{ route($breadcrumb['route']) }}" 
                                       class="text-sm font-medium text-orange-600 hover:text-orange-800 transition-colors">
                                        {{ $breadcrumb['title'] }}
                                    </a>
                                @else
                                    <span class="text-sm font-medium text-gray-900">{{ $breadcrumb['title'] }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </nav>
            @else
                <h1 class="text-lg font-semibold text-gray-900">{{ $pageTitle ?: 'DrivnCook' }}</h1>
            @endif
        </div>

        {{-- Notifications (simple indicateur) --}}
        @if($user->hasRole('admin'))
        <button type="button" class="relative p-2 text-gray-400 hover:text-orange-600 transition-colors">
            <span class="sr-only">Voir les notifications</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
            </svg>
            @if(($sidebarData['applications_count'] ?? 0) > 0)
                <span class="absolute -top-0.5 -right-0.5 h-4 w-4 bg-orange-500 text-white text-xs rounded-full flex items-center justify-center">
                    {{ min($sidebarData['applications_count'] ?? 0, 9) }}{{ ($sidebarData['applications_count'] ?? 0) > 9 ? '+' : '' }}
                </span>
            @endif
        </button>
        @endif

        {{-- User menu --}}
        <div class="relative" x-data="{ userMenuOpen: false }">
            <button type="button" 
                    class="flex items-center gap-x-3 p-1.5 text-sm leading-6 font-semibold text-gray-900 hover:bg-orange-50 rounded-lg transition-colors"
                    @click="userMenuOpen = !userMenuOpen">
                <div class="h-8 w-8 rounded-full bg-gradient-to-br from-orange-400 to-amber-500 flex items-center justify-center">
                    <span class="text-sm font-semibold text-white">{{ substr($user->name, 0, 1) }}</span>
                </div>
                <span class="hidden lg:block truncate max-w-32">{{ $user->name }}</span>
                <svg class="hidden lg:block h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
            </button>

            {{-- Dropdown menu --}}
            <div x-show="userMenuOpen" 
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 @click.away="userMenuOpen = false"
                 class="absolute right-0 z-10 mt-2 w-56 origin-top-right bg-white rounded-lg shadow-lg border border-orange-100 py-2"
                 style="display: none;">
                
                {{-- User info --}}
                <div class="px-4 py-3 border-b border-orange-50">
                    <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                    <p class="text-xs text-orange-600">{{ $user->email }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $user->getRoleNames()->first() }}</p>
                </div>

                {{-- Menu items --}}
                <div class="py-1">
                    <a href="{{ route('profile.edit') }}" 
                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-700 transition-colors">
                        <svg class="mr-3 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        {{ __('ui.nav.profile') }}
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 transition-colors">
                            <svg class="mr-3 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                            </svg>
                            {{ __('ui.auth.logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
@endauth
