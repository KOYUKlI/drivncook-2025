<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('ui.errors.429.title') }} - {{ config('app.name', 'DrivnCook') }}</title>
    
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">DrivnCook</h1>
        </div>

        <!-- Error Content -->
        <div class="w-full max-w-md px-6 py-8 bg-white shadow-xl rounded-2xl border border-gray-100 text-center">
            <div class="mb-6">
                <div class="text-6xl font-bold text-blue-600 mb-2">429</div>
                <h2 class="text-2xl font-semibold text-gray-800 mb-2">{{ __('ui.errors.429.heading') }}</h2>
                <p class="text-gray-600">{{ __('ui.errors.429.message') }}</p>
            </div>

            <!-- Timer Display -->
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-blue-700 font-semibold" id="timer">60</span>
                    <span class="text-blue-700 ml-1">{{ __('ui.errors.429.seconds') }}</span>
                </div>
                <p class="text-blue-700 text-sm">
                    {{ __('ui.errors.429.help') }}
                </p>
            </div>

            <!-- Navigation Links -->
            <div class="space-y-3">
                @auth
                    @if(auth()->user()->hasRole('franchisee'))
                        <a href="{{ route('fo.dashboard') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gradient-to-r from-orange-500 to-red-600 text-white font-semibold rounded-lg hover:from-orange-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200">
                            {{ __('ui.errors.429.back_fo') }}
                        </a>
                    @else
                        <a href="{{ route('bo.dashboard') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gradient-to-r from-orange-500 to-red-600 text-white font-semibold rounded-lg hover:from-orange-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200">
                            {{ __('ui.errors.429.back_bo') }}
                        </a>
                    @endif
                @else
                    <a href="{{ route('home') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gradient-to-r from-orange-500 to-red-600 text-white font-semibold rounded-lg hover:from-orange-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200">
                        {{ __('ui.errors.429.back_home') }}
                    </a>
                @endauth
                
                <button onclick="location.reload()" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200" id="retryButton" disabled>
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span id="retryText">{{ __('ui.errors.429.retry_disabled') }}</span>
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

    <script>
        let timeLeft = 60;
        const timer = document.getElementById('timer');
        const retryButton = document.getElementById('retryButton');
        const retryText = document.getElementById('retryText');
        
        const countdown = setInterval(() => {
            timeLeft--;
            timer.textContent = timeLeft;
            
            if (timeLeft <= 0) {
                clearInterval(countdown);
                retryButton.disabled = false;
                retryButton.classList.remove('bg-gray-600', 'hover:bg-gray-700');
                retryButton.classList.add('bg-blue-600', 'hover:bg-blue-700');
                retryText.textContent = '{{ __('ui.errors.429.retry_enabled') }}';
            }
        }, 1000);
    </script>
</body>
</html>
