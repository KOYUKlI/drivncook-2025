<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('ui.become_franchisee') }} - {{ config('app.name', 'Laravel') }}</title>
        <meta name="description" content="{{ __('ui.franchise_meta_description') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-white text-gray-900">
        
        <!-- Header simple pour pages publiques -->
        <header class="bg-white border-b border-gray-200">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="h-8 w-8 bg-orange-500 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-sm">DC</span>
                        </div>
                        <span class="text-xl font-semibold text-gray-900">Driv'n Cook</span>
                    </div>

                    <div class="flex items-center gap-4">
                        @guest
                            <a href="{{ route('public.applications.create') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                                {{ __('ui.apply_now') }}
                            </a>
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 text-sm font-medium">
                                {{ __('ui.login') }}
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                                {{ __('ui.dashboard') }}
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="bg-gradient-to-r from-orange-50 to-orange-100 py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-gray-900 sm:text-5xl lg:text-6xl mb-6">
                        {{ __('ui.hero_title') }}
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                        {{ __('ui.hero_subtitle') }}
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('public.applications.create') }}" 
                           class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-colors">
                            {{ __('ui.become_franchisee') }}
                        </a>
                        <a href="#features" 
                           class="border border-gray-300 hover:border-gray-400 text-gray-700 hover:text-gray-900 px-8 py-4 rounded-lg text-lg font-semibold transition-colors">
                            {{ __('ui.learn_more') }}
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">
                        {{ __('ui.why_join_us') }}
                    </h2>
                    <p class="text-lg text-gray-600">
                        {{ __('ui.franchise_benefits') }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Droit d'entrée -->
                    <div class="text-center p-6 border border-gray-200 rounded-lg">
                        <div class="w-16 h-16 bg-orange-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('ui.franchise_fee') }}</h3>
                        <p class="text-3xl font-bold text-orange-600 mb-2">50k€</p>
                        <p class="text-gray-600">{{ __('ui.franchise_fee_description') }}</p>
                    </div>

                    <!-- Commission -->
                    <div class="text-center p-6 border border-gray-200 rounded-lg">
                        <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('ui.commission_rate') }}</h3>
                        <p class="text-3xl font-bold text-blue-600 mb-2">4%</p>
                        <p class="text-gray-600">{{ __('ui.commission_description') }}</p>
                    </div>

                    <!-- Règle 80/20 -->
                    <div class="text-center p-6 border border-gray-200 rounded-lg">
                        <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('ui.purchase_rule') }}</h3>
                        <p class="text-3xl font-bold text-green-600 mb-2">80/20</p>
                        <p class="text-gray-600">{{ __('ui.purchase_rule_description') }}</p>
                    </div>
                </div>

                <!-- Entrepôts -->
                <div class="mt-16 text-center">
                    <div class="inline-flex items-center gap-2 bg-gray-100 px-6 py-3 rounded-lg">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span class="text-lg font-semibold text-gray-700">
                            {{ __('ui.warehouses_available', ['count' => 4]) }}
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="bg-orange-500 py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold text-white mb-4">
                    {{ __('ui.ready_to_start') }}
                </h2>
                <p class="text-xl text-orange-100 mb-8">
                    {{ __('ui.start_application_text') }}
                </p>
                <a href="{{ route('public.applications.create') }}" 
                   class="bg-white hover:bg-gray-100 text-orange-500 px-8 py-4 rounded-lg text-lg font-semibold transition-colors inline-block">
                    {{ __('ui.start_application') }}
                </a>
            </div>
        </section>

        <!-- Footer simple -->
        <footer class="bg-gray-900 py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-center text-center">
                    <div class="text-gray-400">
                        <p>&copy; {{ date('Y') }} Driv'n Cook. {{ __('ui.all_rights_reserved') }}</p>
                    </div>
                </div>
            </div>
        </footer>

    </body>
</html>
