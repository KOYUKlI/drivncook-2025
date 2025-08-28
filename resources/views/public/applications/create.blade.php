<x-app-layout>
    <x-slot name="title">{{ __('ui.public.application.title') }}</x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Progress Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-900">{{ __('ui.public.application.wizard_title') }}</h1>
                <div class="text-sm text-gray-500">
                    {{ __('ui.public.application.step') }} <span x-text="currentStep"></span> {{ __('ui.public.application.of') }} 4
                </div>
            </div>
            
            <div class="mt-4">
                <div class="flex items-center">
                    <template x-for="step in 4" :key="step">
                        <div class="flex items-center" :class="step < 4 ? 'flex-1' : ''">
                            <div 
                                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium"
                                :class="step <= currentStep ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-600'"
                                x-text="step"
                            ></div>
                            <div 
                                class="h-1 flex-1 mx-2"
                                :class="step < 4 ? (step < currentStep ? 'bg-indigo-600' : 'bg-gray-200') : ''"
                                x-show="step < 4"
                            ></div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <form id="applicationForm" x-data="applicationWizard()" @submit.prevent="submitForm">
            @csrf
                    <div class="text-center">
                        <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">{{ __('Démarrage rapide') }}</h3>
                        <p class="text-orange-100">{{ __('Formation complète et accompagnement') }}</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">{{ __('Investissement sécurisé') }}</h3>
                        <p class="text-orange-100">{{ __('Modèle économique éprouvé') }}</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">{{ __('Support continu') }}</h3>
                        <p class="text-orange-100">{{ __('Équipe dédiée à votre succès') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire de candidature -->
    <div class="py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="px-8 py-8 bg-gradient-to-r from-gray-50 to-orange-50">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">{{ __('Votre candidature') }}</h2>
                    <p class="text-gray-600">{{ __('Remplissez ce formulaire pour candidater à notre programme de franchise') }}</p>
                </div>

                <div class="px-8 py-8">
                    @if ($errors->any())
                        <div class="mb-8 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <h3 class="font-medium text-red-800 mb-2">{{ __('Erreurs dans le formulaire') }}</h3>
                            <ul class="text-red-600 text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('public.applications.store') }}" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <!-- Informations personnelles -->
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-6 pb-2 border-b border-gray-200">
                                {{ __('Informations personnelles') }}
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Prénom') }} *
                                    </label>
                                    <input 
                                        id="first_name" 
                                        name="first_name" 
                                        type="text" 
                                        value="{{ old('first_name') }}" 
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                        placeholder="{{ __('Votre prénom') }}"
                                    />
                                </div>

                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Nom') }} *
                                    </label>
                                    <input 
                                        id="last_name" 
                                        name="last_name" 
                                        type="text" 
                                        value="{{ old('last_name') }}" 
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                        placeholder="{{ __('Votre nom') }}"
                                    />
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Email') }} *
                                    </label>
                                    <input 
                                        id="email" 
                                        name="email" 
                                        type="email" 
                                        value="{{ old('email') }}" 
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                        placeholder="{{ __('votre@email.com') }}"
                                    />
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Téléphone') }} *
                                    </label>
                                    <input 
                                        id="phone" 
                                        name="phone" 
                                        type="tel" 
                                        value="{{ old('phone') }}" 
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                        placeholder="{{ __('06 12 34 56 78') }}"
                                    />
                                </div>

                                <div class="md:col-span-2">
                                    <label for="territory" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Territoire souhaité') }} *
                                    </label>
                                    <select 
                                        id="territory" 
                                        name="territory" 
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                    >
                                        <option value="">{{ __('Sélectionnez un territoire') }}</option>
                                        <option value="paris-nord" {{ old('territory') == 'paris-nord' ? 'selected' : '' }}>Paris Nord</option>
                                        <option value="paris-sud" {{ old('territory') == 'paris-sud' ? 'selected' : '' }}>Paris Sud</option>
                                        <option value="lyon-centre" {{ old('territory') == 'lyon-centre' ? 'selected' : '' }}>Lyon Centre</option>
                                        <option value="marseille-sud" {{ old('territory') == 'marseille-sud' ? 'selected' : '' }}>Marseille Sud</option>
                                        <option value="toulouse-nord" {{ old('territory') == 'toulouse-nord' ? 'selected' : '' }}>Toulouse Nord</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Documents -->
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-6 pb-2 border-b border-gray-200">
                                {{ __('Documents requis') }}
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="cv" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('CV (PDF uniquement)') }} *
                                    </label>
                                    <div class="relative">
                                        <input 
                                            id="cv" 
                                            name="cv" 
                                            type="file" 
                                            accept="application/pdf" 
                                            required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100"
                                        />
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">{{ __('Taille max: 5MB') }}</p>
                                </div>

                                <div>
                                    <label for="identity" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Pièce d\'identité') }} *
                                    </label>
                                    <div class="relative">
                                        <input 
                                            id="identity" 
                                            name="identity" 
                                            type="file" 
                                            accept="application/pdf,image/jpeg,image/png" 
                                            required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100"
                                        />
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">{{ __('PDF, JPG ou PNG - Taille max: 5MB') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Bouton de soumission -->
                        <div class="pt-6 border-t border-gray-200">
                            <button 
                                type="submit" 
                                class="w-full md:w-auto bg-gradient-to-r from-orange-500 to-red-600 text-white font-semibold py-4 px-8 rounded-lg hover:from-orange-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transform transition hover:scale-[1.02] shadow-lg"
                            >
                                {{ __('Soumettre ma candidature') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold">DrivnCook</span>
                    </div>
                    <p class="text-gray-300 mb-4">
                        {{ __('La première plateforme de franchise de food trucks en France. Rejoignez notre réseau et lancez votre entreprise dans la restauration mobile.') }}
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">{{ __('Contact') }}</h3>
                    <div class="space-y-2 text-gray-300">
                        <p>{{ __('Email: franchise@drivncook.com') }}</p>
                        <p>{{ __('Tél: 01 23 45 67 89') }}</p>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">{{ __('Liens') }}</h3>
                    <div class="space-y-2">
                        <a href="{{ route('login') }}" class="block text-gray-300 hover:text-orange-400">{{ __('Espace franchisé') }}</a>
                        <a href="#" class="block text-gray-300 hover:text-orange-400">{{ __('À propos') }}</a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p class="text-gray-400">
                    {{ __('© 2025 DrivnCook. Tous droits réservés.') }}
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
