<x-app-layout>
    <x-slot name="title">{{ __('ui.public.application.title') }}</x-slot>

    <!-- Hero Section Simplifié -->
    <div class="bg-gradient-to-r from-orange-50 to-amber-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                {{ __('Candidature de franchise') }}
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                {{ __('Complétez le formulaire ci-dessous pour démarrer votre parcours entrepreneurial') }}
            </p>
        </div>
    </div>

    <!-- Application Form Section -->
    <div class="bg-white py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="p-8">
                    @if ($errors->any())
                        <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-400 rounded-r-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">{{ __('Veuillez corriger les erreurs suivantes :') }}</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('public.applications.store') }}" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <!-- Informations personnelles -->
                        <div class="space-y-6">
                            <h3 class="text-xl font-semibold text-gray-900 border-b border-gray-200 pb-3">
                                {{ __('Informations personnelles') }}
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="first_name" class="block text-sm font-medium text-gray-700">
                                        {{ __('Prénom') }} <span class="text-orange-500">*</span>
                                    </label>
                                    <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                        placeholder="{{ __('Votre prénom') }}" />
                                </div>

                                <div class="space-y-2">
                                    <label for="last_name" class="block text-sm font-medium text-gray-700">
                                        {{ __('Nom') }} <span class="text-orange-500">*</span>
                                    </label>
                                    <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                        placeholder="{{ __('Votre nom') }}" />
                                </div>

                                <div class="space-y-2">
                                    <label for="email" class="block text-sm font-medium text-gray-700">
                                        {{ __('Adresse email') }} <span class="text-orange-500">*</span>
                                    </label>
                                    <input id="email" name="email" type="email" value="{{ old('email') }}" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                        placeholder="{{ __('votre@email.com') }}" />
                                </div>

                                <div class="space-y-2">
                                    <label for="phone" class="block text-sm font-medium text-gray-700">
                                        {{ __('Numéro de téléphone') }} <span class="text-orange-500">*</span>
                                    </label>
                                    <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                        placeholder="{{ __('06 12 34 56 78') }}" />
                                </div>

                                <div class="md:col-span-2 space-y-2">
                                    <label for="territory" class="block text-sm font-medium text-gray-700">
                                        {{ __('Territoire souhaité') }} <span class="text-orange-500">*</span>
                                    </label>
                                    <select id="territory" name="territory" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors bg-white">
                                        <option value="">{{ __('Sélectionnez votre territoire préféré') }}</option>
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
                        <div class="space-y-6">
                            <h3 class="text-xl font-semibold text-gray-900 border-b border-gray-200 pb-3">
                                {{ __('Documents requis') }}
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="cv" class="block text-sm font-medium text-gray-700">
                                        {{ __('Curriculum Vitae') }} <span class="text-orange-500">*</span>
                                    </label>
                                    <input id="cv" name="cv" type="file" accept="application/pdf" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100" />
                                    <p class="text-xs text-gray-500">{{ __('Format PDF uniquement, taille maximum 5MB') }}</p>
                                </div>

                                <div class="space-y-2">
                                    <label for="identity" class="block text-sm font-medium text-gray-700">
                                        {{ __('Pièce d\'identité') }} <span class="text-orange-500">*</span>
                                    </label>
                                    <input id="identity" name="identity" type="file" accept="application/pdf,image/jpeg,image/png" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100" />
                                    <p class="text-xs text-gray-500">{{ __('PDF, JPG ou PNG, taille maximum 5MB') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-6 border-t border-gray-200">
                            <div class="flex flex-col sm:flex-row gap-4 justify-between items-center">
                                <p class="text-sm text-gray-600">
                                    {{ __('En soumettant ce formulaire, vous acceptez nos conditions d\'utilisation.') }}
                                </p>
                                <button type="submit"
                                    class="w-full sm:w-auto bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold py-3 px-8 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all shadow-md hover:shadow-lg">
                                    {{ __('Envoyer ma candidature') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
