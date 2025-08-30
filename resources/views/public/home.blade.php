<x-app-layout>
    <x-slot name="title">{{ __('ui.titles.home') }}</x-slot>

    <!-- Hero -->
    <section class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-gray-900 py-20 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-orange-500/10 to-amber-500/10"></div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-6 leading-tight">
                {{ __('ui.titles.home') }}
            </h1>
            <p class="text-xl text-gray-200 max-w-3xl mx-auto mb-12 leading-relaxed">
                {{ __('ui.public.home.subtitle') }}
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('public.franchise') }}" class="group bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-10 py-4 rounded-xl text-lg font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <span class="flex items-center justify-center space-x-2">
                        <span>{{ __('ui.ctas.become_franchisee') }}</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </span>
                </a>
                <a href="{{ route('public.applications.create') }}" class="group border-2 border-white/20 hover:border-orange-400 text-white hover:text-orange-300 px-10 py-4 rounded-xl text-lg font-semibold transition-all duration-300 backdrop-blur-sm bg-white/5 hover:bg-orange-500/10">
                    {{ __('ui.ctas.apply_now') }}
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('Notre modèle de franchise') }}</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">{{ __('Découvrez les avantages de rejoindre notre réseau') }}</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="group relative bg-gradient-to-br from-gray-50 to-white p-8 rounded-2xl border border-gray-100 hover:border-orange-200 transition-all duration-300 hover:shadow-lg">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-100 to-orange-200 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">
                        {{ __('ui.sections.model') }}
                    </h3>
                    <p class="text-gray-600">{{ __('Un concept innovant et éprouvé dans le domaine de la restauration mobile') }}</p>
                </div>
                
                <div class="group relative bg-gradient-to-br from-gray-50 to-white p-8 rounded-2xl border border-gray-100 hover:border-orange-200 transition-all duration-300 hover:shadow-lg">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-100 to-orange-200 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">
                        {{ __('ui.sections.appro') }}
                    </h3>
                    <p class="text-gray-600">{{ __('Un approvisionnement optimisé et une logistique simplifiée') }}</p>
                </div>
                
                <div class="group relative bg-gradient-to-br from-gray-50 to-white p-8 rounded-2xl border border-gray-100 hover:border-orange-200 transition-all duration-300 hover:shadow-lg">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-100 to-orange-200 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">
                        {{ __('ui.sections.how_it_works') }}
                    </h3>
                    <p class="text-gray-600">{{ __('Un processus simple et structuré pour démarrer votre activité') }}</p>
                </div>
            </div>

            <div class="mt-16 text-center">
                <p class="text-sm text-gray-500 bg-gray-50 inline-block px-6 py-3 rounded-full">
                    {{ __('ui.notes.files_private') }}
                </p>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="relative bg-gradient-to-r from-orange-500 to-orange-600 py-20 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-orange-600/20 to-red-600/20"></div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-white mb-6">
                {{ __('ui.ready_to_start') }}
            </h2>
            <p class="text-xl text-orange-100 mb-10 max-w-2xl mx-auto">
                {{ __('Lancez-vous dans l\'aventure entrepreneuriale avec notre soutien') }}
            </p>
            <a href="{{ route('public.applications.create') }}" class="group inline-flex items-center bg-white hover:bg-gray-50 text-orange-600 px-10 py-4 rounded-xl text-lg font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                <span>{{ __('ui.start_application') }}</span>
                <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </a>
        </div>
    </section>
</x-app-layout>
