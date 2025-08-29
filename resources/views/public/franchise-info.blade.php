<x-app-layout>
    <x-slot name="title">{{ __('ui.titles.franchise_info') }}</x-slot>

    <!-- Hero Section -->
    <section class="bg-white py-16">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                    {{ __('ui.titles.franchise_info') }}
                </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                    {{ __('ui.public.franchise_info.subtitle') }}
                </p>
                <div class="inline-flex items-center bg-orange-50 border border-orange-200 rounded-full px-6 py-3">
                    <svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <span class="text-orange-800 font-medium">{{ __('Opportunité d\'investissement') }}</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Investissement & Conditions -->
    <section class="py-16 bg-gray-50">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Investissement -->
                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-200">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ __('Investissement requis') }}</h2>
                    </div>
                    <div class="space-y-4">
                        <p class="text-gray-700 leading-relaxed">{{ __('ui.public.franchise_info.investment_desc') }}</p>
                        <div class="bg-orange-50 border-l-4 border-orange-400 p-4 rounded-r-lg">
                            <div class="flex">
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-orange-800">{{ __('ui.public.franchise_info.fees_desc') }}</p>
                                    <p class="text-lg font-bold text-orange-600 mt-1">{{ __('ui.public.franchise_info.fees_amount') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">{{ __('ui.sections.appro') }}</h3>
                            <p class="text-gray-700">{{ __('ui.public.franchise_info.ratio_desc') }} ({{ __('ui.public.franchise_info.ratio_title') }})</p>
                        </div>
                    </div>
                </div>

                <!-- Conditions d'éligibilité -->
                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-200">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ __('ui.eligibility_conditions') }}</h2>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-orange-100 rounded-full flex items-center justify-center mt-0.5">
                                <span class="text-orange-600 text-sm font-bold">1</span>
                            </div>
                            <p class="text-gray-700">{{ __('ui.eligibility_1') }}</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-orange-100 rounded-full flex items-center justify-center mt-0.5">
                                <span class="text-orange-600 text-sm font-bold">2</span>
                            </div>
                            <p class="text-gray-700">{{ __('ui.eligibility_2') }}</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-orange-100 rounded-full flex items-center justify-center mt-0.5">
                                <span class="text-orange-600 text-sm font-bold">3</span>
                            </div>
                            <p class="text-gray-700">{{ __('ui.eligibility_3') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Processus de candidature -->
    <section class="py-16 bg-white">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('ui.application_process') }}</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">{{ __('Votre parcours en 4 étapes simples') }}</p>
            </div>
            
            <div class="relative">
                <!-- Timeline line -->
                <div class="absolute top-8 left-8 right-8 h-0.5 bg-gray-200 hidden lg:block"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @php
                        $steps = [
                            ['n' => 1, 't' => __('ui.public.application.step_identity'), 'desc' => 'Complétez vos informations personnelles'],
                            ['n' => 2, 't' => __('ui.public.application.step_zone'), 'desc' => 'Choisissez votre territoire d\'activité'],
                            ['n' => 3, 't' => __('ui.public.application.step_acknowledgments'), 'desc' => 'Validez votre engagement'],
                            ['n' => 4, 't' => __('ui.public.application.step_documents'), 'desc' => 'Joignez vos documents'],
                        ];
                    @endphp
                    @foreach($steps as $s)
                        <div class="relative text-center">
                            <div class="w-16 h-16 bg-orange-500 text-white rounded-full flex items-center justify-center font-bold text-xl mx-auto mb-4 relative z-10">
                                {{ $s['n'] }}
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2">{{ $s['t'] }}</h3>
                            <p class="text-sm text-gray-600">{{ $s['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Documents requis -->
    <section class="py-16 bg-gray-50">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('ui.required_documents') }}</h2>
                <p class="text-lg text-gray-600">{{ __('Préparez ces documents essentiels') }}</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg p-6 border border-gray-200 hover:border-orange-300 transition-colors">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('ui.labels.cv') }}</h3>
                    </div>
                    <p class="text-gray-600 text-sm">{{ __('Votre parcours professionnel détaillé') }}</p>
                </div>
                
                <div class="bg-white rounded-lg p-6 border border-gray-200 hover:border-orange-300 transition-colors">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('ui.labels.identity') }}</h3>
                    </div>
                    <p class="text-gray-600 text-sm">{{ __('Carte d\'identité ou passeport') }}</p>
                </div>
                
                <div class="bg-white rounded-lg p-6 border border-gray-200 hover:border-orange-300 transition-colors">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('ui.labels.motivation_letter') }}</h3>
                    </div>
                    <p class="text-gray-600 text-sm">{{ __('Vos motivations pour rejoindre le réseau') }}</p>
                </div>
                
                <div class="bg-white rounded-lg p-6 border border-gray-200 hover:border-orange-300 transition-colors">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H9a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('ui.labels.financial_statement') }}</h3>
                    </div>
                    <p class="text-gray-600 text-sm">{{ __('Justificatifs de capacité financière') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="relative bg-gradient-to-r from-orange-500 to-orange-600 py-20 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-orange-600/20 to-red-600/20"></div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-white mb-6">{{ __('ui.public.franchise_info.cta_title') }}</h2>
            <p class="text-xl text-orange-100 mb-10 max-w-2xl mx-auto">{{ __('ui.public.franchise_info.cta_subtitle') }}</p>
            <a href="{{ route('public.applications.create') }}" class="group inline-flex items-center bg-white hover:bg-gray-50 text-orange-600 px-10 py-4 rounded-xl text-lg font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                <span>{{ __('ui.public.franchise_info.cta_button') }}</span>
                <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </a>
        </div>
    </section>
</x-app-layout>
