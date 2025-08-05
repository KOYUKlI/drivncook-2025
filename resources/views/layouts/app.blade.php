<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Driv'n Cook</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased">
    @auth
        <!-- Simple navigation bar -->
        <nav>
            @if (Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                <a href="{{ route('admin.trucks.index') }}">Manage Trucks</a>
                <a href="{{ route('admin.franchises.index') }}">Franchises</a>
                <!-- etc. other admin links -->
            @else
                <a href="{{ route('franchise.dashboard') }}">Dashboard</a>
                <a href="{{ route('franchise.trucks.index') }}">My Trucks</a>
                <a href="{{ route('franchise.warehouses.index') }}">My Warehouses</a>
                <a href="{{ route('franchise.orders.index') }}">Sales</a>
                <!-- etc. other franchise links -->
            @endif
            <a href="{{ route('profile.edit') }}">Profile</a>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf <button type="submit">Log Out</button>
            </form>
        </nav>
    @endauth

    <main class="container mx-auto mt-4">
        @yield('content')
    </main>
</body>

</html>
