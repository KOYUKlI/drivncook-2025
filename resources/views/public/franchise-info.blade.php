<x-app-layout>
    <x-slot name="title">{{ __('ui.public.franchise_info.title') }}</x-slot>

    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-indigo-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="text-center">
                <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">
                    {{ __('ui.public.franchise_info.hero_title') }}
                </h1>
                <p class="mt-6 max-w-3xl mx-auto text-xl text-gray-600">
                    {{ __('ui.public.franchise_info.hero_subtitle') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Economic Model Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto">
                <h2 class="text-3xl font-bold text-gray-900 text-center">{{ __('ui.public.franchise_info.model_title') }}</h2>
                <div class="mt-12 space-y-8">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900">{{ __('ui.public.franchise_info.investment') }}</h3>
                        <p class="mt-2 text-gray-600">{{ __('ui.public.franchise_info.investment_desc') }}</p>
                        <div class="mt-4 bg-yellow-100 border border-yellow-200 rounded-md p-4">
                            <p class="text-sm text-yellow-800">
                                <strong>{{ __('ui.public.franchise_info.investment_requirement') }}</strong>
                                {{ __('ui.public.franchise_info.investment_amount') }}
                            </p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900">{{ __('ui.public.franchise_info.fees') }}</h3>
                        <p class="mt-2 text-gray-600">{{ __('ui.public.franchise_info.fees_desc') }}</p>
                        <div class="mt-4 bg-blue-100 border border-blue-200 rounded-md p-4">
                            <p class="text-sm text-blue-800">
                                <strong>{{ __('ui.public.franchise_info.fees_percentage') }}</strong>
                                {{ __('ui.public.franchise_info.fees_amount') }}
                            </p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900">{{ __('ui.public.franchise_info.ratio_title') }}</h3>
                        <p class="mt-2 text-gray-600">{{ __('ui.public.franchise_info.ratio_desc') }}</p>
                        <div class="mt-4 bg-green-100 border border-green-200 rounded-md p-4">
                            <p class="text-sm text-green-800">
                                <strong>{{ __('ui.public.franchise_info.ratio_requirement') }}</strong>
                                {{ __('ui.public.franchise_info.ratio_explanation') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto">
                <h2 class="text-3xl font-bold text-gray-900 text-center">{{ __('ui.public.franchise_info.faq_title') }}</h2>
                <div class="mt-12 space-y-6">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('ui.public.franchise_info.faq_q1') }}</h3>
                        <p class="mt-2 text-gray-600">{{ __('ui.public.franchise_info.faq_a1') }}</p>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('ui.public.franchise_info.faq_q2') }}</h3>
                        <p class="mt-2 text-gray-600">{{ __('ui.public.franchise_info.faq_a2') }}</p>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('ui.public.franchise_info.faq_q3') }}</h3>
                        <p class="mt-2 text-gray-600">{{ __('ui.public.franchise_info.faq_a3') }}</p>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('ui.public.franchise_info.faq_q4') }}</h3>
                        <p class="mt-2 text-gray-600">{{ __('ui.public.franchise_info.faq_a4') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-indigo-600">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-white">
                    {{ __('ui.public.franchise_info.cta_title') }}
                </h2>
                <p class="mt-4 text-xl text-indigo-100">
                    {{ __('ui.public.franchise_info.cta_subtitle') }}
                </p>
                <div class="mt-8">
                    <a href="{{ route('public.applications.create') }}" class="inline-flex items-center px-8 py-3 border border-transparent text-lg font-medium rounded-md text-indigo-600 bg-white hover:bg-gray-50 transition-colors duration-200">
                        {{ __('ui.public.franchise_info.cta_button') }}
                        <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
        
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
