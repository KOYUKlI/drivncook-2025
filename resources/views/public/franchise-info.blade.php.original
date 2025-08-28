<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('ui.franchise_information') }} - {{ config('app.name', 'Laravel') }}</title>
        <meta name="description" content="{{ __('ui.franchise_info_meta_description') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-white text-gray-900">
        
        <!-- Header -->
        <header class="bg-white border-b border-gray-200">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('home') }}" class="flex items-center gap-2">
                            <div class="h-8 w-8 bg-orange-500 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-sm">DC</span>
                            </div>
                            <span class="text-xl font-semibold text-gray-900">Driv'n Cook</span>
                        </a>
                    </div>

                    <div class="flex items-center gap-4">
                        @guest
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 text-sm font-medium">
                                {{ __('ui.login') }}
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    {{ __('ui.register') }}
                                </a>
                            @endif
                        @else
                            <a href="{{ route('dashboard') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                                {{ __('ui.dashboard') }}
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </header>

        <!-- Breadcrumb -->
        <nav class="bg-gray-50 py-4">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <ol class="flex items-center space-x-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900">{{ __('ui.home') }}</a></li>
                    <li class="text-gray-400">/</li>
                    <li class="text-gray-900 font-medium">{{ __('ui.franchise_information') }}</li>
                </ol>
            </div>
        </nav>

        <!-- Hero -->
        <section class="bg-gradient-to-r from-orange-50 to-orange-100 py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    {{ __('ui.franchise_information') }}
                </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    {{ __('ui.franchise_opportunity') }} - {{ __('ui.franchise_info_subtitle') }}
                </p>
            </div>
        </section>

        <!-- Detailed Information -->
        <section class="py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    
                    <!-- Left column: Details -->
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-6">{{ __('ui.investment_details') }}</h2>
                        
                        <div class="space-y-6">
                            <div class="bg-white p-6 border border-gray-200 rounded-lg shadow-sm">
                                <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('ui.initial_investment') }}</h3>
                                <ul class="space-y-2 text-gray-600">
                                    <li class="flex justify-between">
                                        <span>{{ __('ui.franchise_fee') }}</span>
                                        <span class="font-semibold">50 000€</span>
                                    </li>
                                    <li class="flex justify-between">
                                        <span>{{ __('ui.truck_equipment') }}</span>
                                        <span class="font-semibold">35 000€</span>
                                    </li>
                                    <li class="flex justify-between">
                                        <span>{{ __('ui.initial_stock') }}</span>
                                        <span class="font-semibold">8 000€</span>
                                    </li>
                                    <li class="flex justify-between border-t pt-2 font-semibold text-gray-900">
                                        <span>{{ __('ui.total_investment') }}</span>
                                        <span>93 000€</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="bg-white p-6 border border-gray-200 rounded-lg shadow-sm">
                                <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('ui.ongoing_costs') }}</h3>
                                <ul class="space-y-2 text-gray-600">
                                    <li class="flex justify-between">
                                        <span>{{ __('ui.royalty_fee') }}</span>
                                        <span class="font-semibold">4% {{ __('ui.of_revenue') }}</span>
                                    </li>
                                    <li class="flex justify-between">
                                        <span>{{ __('ui.marketing_fee') }}</span>
                                        <span class="font-semibold">2% {{ __('ui.of_revenue') }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Right column: Process -->
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-6">{{ __('ui.application_process') }}</h2>
                        
                        <div class="space-y-4">
                            @php
                            $steps = [
                                ['number' => 1, 'title' => __('ui.step_1_title'), 'description' => __('ui.step_1_description')],
                                ['number' => 2, 'title' => __('ui.step_2_title'), 'description' => __('ui.step_2_description')],
                                ['number' => 3, 'title' => __('ui.step_3_title'), 'description' => __('ui.step_3_description')],
                                ['number' => 4, 'title' => __('ui.step_4_title'), 'description' => __('ui.step_4_description')],
                            ];
                            @endphp

                            @foreach($steps as $step)
                            <div class="flex items-start gap-4 p-4 bg-white border border-gray-200 rounded-lg">
                                <div class="flex-shrink-0 w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center font-semibold">
                                    {{ $step['number'] }}
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-1">{{ $step['title'] }}</h3>
                                    <p class="text-gray-600 text-sm">{{ $step['description'] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="py-20 bg-gray-50">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">{{ __('ui.frequently_asked_questions') }}</h2>
                
                <div class="space-y-6" x-data="{ openFaq: null }">
                    @php
                    $faqs = [
                        ['q' => __('ui.faq_1_question'), 'a' => __('ui.faq_1_answer')],
                        ['q' => __('ui.faq_2_question'), 'a' => __('ui.faq_2_answer')],
                        ['q' => __('ui.faq_3_question'), 'a' => __('ui.faq_3_answer')],
                        ['q' => __('ui.faq_4_question'), 'a' => __('ui.faq_4_answer')],
                    ];
                    @endphp

                    @foreach($faqs as $index => $faq)
                    <div class="bg-white border border-gray-200 rounded-lg">
                        <button 
                            @click="openFaq = openFaq === {{ $index }} ? null : {{ $index }}"
                            class="w-full text-left p-6 flex items-center justify-between font-semibold text-gray-900 hover:bg-gray-50"
                        >
                            <span>{{ $faq['q'] }}</span>
                            <svg 
                                class="w-5 h-5 transform transition-transform"
                                :class="{ 'rotate-180': openFaq === {{ $index }} }"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="openFaq === {{ $index }}" x-transition class="px-6 pb-6">
                            <p class="text-gray-600">{{ $faq['a'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- CTA Final -->
        <section class="bg-orange-500 py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold text-white mb-4">
                    {{ __('ui.ready_to_apply') }}
                </h2>
                <p class="text-xl text-orange-100 mb-8">
                    {{ __('ui.application_cta_text') }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#" class="bg-white hover:bg-gray-100 text-orange-500 px-8 py-4 rounded-lg text-lg font-semibold transition-colors">
                        {{ __('ui.start_application') }}
                    </a>
                    <a href="mailto:contact@drivncook.fr" class="border border-white hover:bg-white hover:text-orange-500 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-colors">
                        {{ __('ui.contact_us') }}
                    </a>
                </div>
            </div>
        </section>

        <!-- Footer -->
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
