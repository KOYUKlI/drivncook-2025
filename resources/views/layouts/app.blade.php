<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', "Driv'n Cook") }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        document.documentElement.classList.add('js');
    </script>
    <style>[x-cloak]{display:none !important}</style>
    </head>
    <body class="min-h-screen bg-gray-50 text-gray-900 antialiased" x-data="{ sidebarOpen: false }">
        <!-- Global Header (full width) -->
        <header class="fixed top-0 inset-x-0 h-16 bg-white border-b border-gray-200 z-50 flex items-center px-4 gap-3">
            <button class="md:hidden p-2 rounded hover:bg-gray-100" @click="sidebarOpen = true" aria-label="Open sidebar">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <a href="/" class="flex items-center gap-2 font-semibold">
                <span class="inline-block h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                <span>Driv'n Cook</span>
            </a>
            <div class="ms-auto text-sm text-gray-600 hidden md:block">{{ auth()->user()->name ?? '' }}</div>
        </header>

        <div class="flex min-h-screen pt-16 pb-16">
    <!-- Sidebar (between header and footer) -->
    <aside class="sidebar w-60 md:w-56 fixed left-0 top-16 bottom-16 z-40 transform transition-transform duration-200 md:translate-x-0" :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }" x-cloak>
            <nav class="sidebar-nav overflow-y-auto">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <div>
                            <div class="sidebar-section-title">Admin</div>
                            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'sidebar-link-active' : '' }}">Dashboard</a>
                            <a href="{{ route('admin.trucks.index') }}" class="sidebar-link {{ request()->is('admin/trucks*') ? 'sidebar-link-active' : '' }}">Trucks</a>
                            <a href="{{ route('admin.warehouses.index') }}" class="sidebar-link {{ request()->is('admin/warehouses*') ? 'sidebar-link-active' : '' }}">Warehouses</a>
                            <a href="{{ route('admin.supplies.index') }}" class="sidebar-link {{ request()->is('admin/supplies*') ? 'sidebar-link-active' : '' }}">Supplies</a>
                            @if(Route::has('admin.suppliers.index'))
                                <a href="{{ route('admin.suppliers.index') }}" class="sidebar-link {{ request()->is('admin/suppliers*') ? 'sidebar-link-active' : '' }}">Suppliers</a>
                            @endif
                            @if(Route::has('admin.commissions.index'))
                                <a href="{{ route('admin.commissions.index') }}" class="sidebar-link {{ request()->is('admin/commissions*') ? 'sidebar-link-active' : '' }}">Commissions</a>
                            @endif
                            @if(Route::has('admin.franchisees.index'))
                                <a href="{{ route('admin.franchisees.index') }}" class="sidebar-link {{ request()->is('admin/franchisees*') ? 'sidebar-link-active' : '' }}">Franchisees</a>
                            @endif
                            @if(Route::has('admin.sales.index'))
                                <a href="{{ route('admin.sales.index') }}" class="sidebar-link {{ request()->is('admin/sales*') ? 'sidebar-link-active' : '' }}">Sales</a>
                            @endif
                        </div>
                    @elseif(auth()->user()->role === 'franchise')
                        <div>
                            <div class="sidebar-section-title">Franchise</div>
                            <a href="{{ route('franchise.dashboard') }}" class="sidebar-link {{ request()->routeIs('franchise.dashboard') ? 'sidebar-link-active' : '' }}">Dashboard</a>
                            <a href="{{ route('franchise.trucks.index') }}" class="sidebar-link {{ request()->is('franchise/trucks*') ? 'sidebar-link-active' : '' }}">My Trucks</a>
                            <a href="{{ route('franchise.stockorders.index') }}" class="sidebar-link {{ request()->is('franchise/stockorders*') ? 'sidebar-link-active' : '' }}">Stock Orders</a>
                            <a href="{{ route('franchise.maintenance.index') }}" class="sidebar-link {{ request()->is('franchise/maintenance*') ? 'sidebar-link-active' : '' }}">Maintenance</a>
                        </div>
                    @endif
                    <div class="mt-6 px-3">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="btn-secondary w-full">Logout</button>
                        </form>
                    </div>
                @else
                    <div>
                        <div class="sidebar-section-title">Account</div>
                        <a href="{{ route('login') }}" class="sidebar-link">Login</a>
                        <a href="{{ route('register') }}" class="sidebar-link">Register</a>
                    </div>
                @endauth
            </nav>
        </aside>

    <!-- Overlay for mobile -->
    <div class="fixed inset-0 bg-black/30 z-30 md:hidden" x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak></div>

    <!-- Main content -->
    <div class="flex-1 flex flex-col min-w-0 md:ml-56">

            <div class="ps-3 pe-4 md:ps-4 max-w-none">
                <!-- Optional header slot for component-based pages -->
                @if (!empty($header ?? null))
                    <div class="mt-4">
                        {{ $header }}
                    </div>
                @endif

                <!-- Flash messages -->
                <div class="mt-4">
                    <x-flash />
                </div>

                <!-- Page Content -->
                <main class="py-6 max-w-none">
                    @hasSection('content')
                        @yield('content')
                    @else
                        {{ $slot ?? '' }}
                    @endif
                </main>
            </div>

        </div>
    </div>
    <!-- Global Footer (full width) -->
    <footer class="fixed bottom-0 inset-x-0 h-16 bg-white border-t border-gray-200 z-50 flex items-center justify-center text-sm text-gray-500 px-4">
        <p>&copy; {{ date('Y') }} Driv'n Cook. All rights reserved.</p>
    </footer>
</body>
</html>
