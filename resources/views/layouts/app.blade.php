<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Driv'n Cook</title>
    <!-- Inclure les assets TailwindCSS et JS compilés (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 antialiased">
    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
            <!-- Branding / Logo -->
            <div class="flex-shrink-0">
                <a href="{{ url('/') }}" class="text-xl font-bold text-gray-800">
                    Driv'n Cook
                </a>
            </div>
            <!-- Navigation Links -->
            <div class="hidden sm:flex space-x-8">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <!-- Liens pour l'admin -->
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard Admin</a>
                        <a href="{{ route('admin.trucks.index') }}" class="text-gray-700 hover:text-gray-900">Trucks</a>
                        <a href="{{ route('admin.warehouses.index') }}" class="text-gray-700 hover:text-gray-900">Warehouses</a>
                        <a href="{{ route('admin.franchisees.index') }}" class="text-gray-700 hover:text-gray-900">Franchisees</a>
                        <a href="{{ route('admin.sales.index') }}" class="text-gray-700 hover:text-gray-900">Sales</a>
                    @elseif(auth()->user()->role === 'franchise')
                        <!-- Liens pour le franchisé -->
                        <a href="{{ route('franchise.dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                        <a href="{{ route('franchise.trucks.index') }}" class="text-gray-700 hover:text-gray-900">My Trucks</a>
                        <a href="{{ route('franchise.stockorders.index') }}" class="text-gray-700 hover:text-gray-900">Stock Orders</a>
                    @endif
                    <!-- Lien de déconnexion -->
                    <a href="#" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                       class="text-red-600 hover:text-red-800">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                @else
                    <!-- Liens pour les visiteurs non connectés (si besoin) -->
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Login</a>
                    <a href="{{ route('register') }}" class="text-gray-700 hover:text-gray-900">Register</a>
                @endauth
            </div>
            <!-- Bouton menu mobile -->
            <div class="sm:hidden">
                <button id="navToggle" class="text-gray-700 hover:text-gray-900 focus:outline-none">
                    <!-- Icône hamburger -->
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
        <!-- Menu mobile (toggle en JS) -->
        <div id="mobileMenu" class="sm:hidden px-4">
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="block py-2 text-gray-700">Dashboard Admin</a>
                    <a href="{{ route('admin.trucks.index') }}" class="block py-2 text-gray-700">Trucks</a>
                    <a href="{{ route('admin.warehouses.index') }}" class="block py-2 text-gray-700">Warehouses</a>
                    <a href="{{ route('admin.franchisees.index') }}" class="block py-2 text-gray-700">Franchisees</a>
                    <a href="{{ route('admin.sales.index') }}" class="block py-2 text-gray-700">Sales</a>
                @elseif(auth()->user()->role === 'franchise')
                    <a href="{{ route('franchise.dashboard') }}" class="block py-2 text-gray-700">Dashboard</a>
                    <a href="{{ route('franchise.trucks.index') }}" class="block py-2 text-gray-700">My Trucks</a>
                    <a href="{{ route('franchise.stockorders.index') }}" class="block py-2 text-gray-700">Stock Orders</a>
                @endif
                <a href="#" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                   class="block py-2 text-red-600">Logout</a>
            @else
                <a href="{{ route('login') }}" class="block py-2 text-gray-700">Login</a>
                <a href="{{ route('register') }}" class="block py-2 text-gray-700">Register</a>
            @endauth
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto mt-4 px-4 py-2 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-7xl mx-auto mt-4 px-4 py-2 bg-red-100 text-red-800 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Page Content -->
    <main class="max-w-7xl mx-auto p-4">
        @yield('content')
    </main>
</body>
</html>
