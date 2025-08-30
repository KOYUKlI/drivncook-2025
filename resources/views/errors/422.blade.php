<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('ui.errors.422.title') }} - {{ config('app.name', 'DrivnCook') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-blue-50 to-indigo-50 min-h-screen">
    <div class="min-h-screen flex flex-col justify-center items-center">
        <!-- Logo -->
        <div class="flex flex-col items-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mb-4 shadow-lg">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">DrivnCook</h1>
        </div>

        <!-- Error Content -->
        <div class="w-full max-w-md px-6 py-8 bg-white shadow-xl rounded-2xl border border-gray-100 text-center">
            <div class="mb-6">
                <div class="text-6xl font-bold text-blue-600 mb-2">422</div>
                <h2 class="text-2xl font-semibold text-gray-800 mb-2">{{ __('ui.errors.422.heading') }}</h2>
                <p class="text-gray-600">{{ __('ui.errors.422.message') }}</p>
            </div>

            <!-- Info Box -->
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-blue-700 text-sm">
                    {{ __('ui.errors.422.help') }}
                </p>
            </div>

            <!-- Navigation Links -->
            <div class="space-y-3">
                @auth
                    @if(auth()->user()->hasRole('franchisee'))
                        <a href="{{ route('fo.dashboard') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gradient-to-r from-orange-500 to-red-600 text-white font-semibold rounded-lg hover:from-orange-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200">
                            {{ __('ui.errors.422.back_fo') }}
                        </a>
                    @else
                        <a href="{{ route('bo.dashboard') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gradient-to-r from-orange-500 to-red-600 text-white font-semibold rounded-lg hover:from-orange-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200">
                            {{ __('ui.errors.422.back_bo') }}
                        </a>
                    @endif
                @else
                    <a href="{{ route('home') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gradient-to-r from-orange-500 to-red-600 text-white font-semibold rounded-lg hover:from-orange-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200">
                        {{ __('ui.errors.422.back_home') }}
                    </a>
                @endauth
                
                <button onclick="history.back()" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    {{ __('ui.errors.422.go_back') }}
                </button>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-500">
                {{ __('ui.auth.footer') }}
            </p>
        </div>
    </div>
</body>
</html>
