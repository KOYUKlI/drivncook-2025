<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Suivi de candidature') }} - {{ config('app.name', 'DrivnCook') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-orange-50 via-white to-red-50 min-h-screen">
    <!-- Navigation publique -->
    <nav class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-gray-800">DrivnCook</span>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('public.applications.create') }}" class="text-gray-600 hover:text-orange-600 font-medium">
                        {{ __('Nouvelle candidature') }}
                    </a>
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-orange-600 font-medium">
                        {{ __('Se connecter') }}
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <div class="bg-gradient-to-r from-orange-500 to-red-600 text-white py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-3xl md:text-4xl font-bold mb-2">
                    {{ __('Suivi de votre candidature') }}
                </h1>
                <p class="text-lg text-orange-100 mb-4">
                    {{ __('Candidature #') }}{{ $application->id }}
                </p>
                <div class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 rounded-full">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">{{ __('Candidature reçue') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Statut actuel -->
            <div class="mb-8">
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-orange-50 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800">{{ __('Statut actuel') }}</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                @php
                                    $statusColors = [
                                        'submitted' => 'bg-blue-100 text-blue-800',
                                        'prequalified' => 'bg-yellow-100 text-yellow-800',
                                        'interview' => 'bg-purple-100 text-purple-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                    ];
                                    $currentColor = $statusColors[$application->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $currentColor }}">
                                    {{ ucfirst($application->status) }}
                                </span>
                                <p class="text-gray-600 mt-2">{{ __('Dernière mise à jour') }} : {{ $application->updated_at->format('d/m/Y à H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">{{ __('Soumise le') }}</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $application->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations principales -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                
                <!-- Informations personnelles -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-orange-50 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ __('Informations personnelles') }}
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600 font-medium">{{ __('Nom complet') }}</span>
                            <span class="text-gray-800 font-semibold">{{ $application->full_name }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600 font-medium">{{ __('Email') }}</span>
                            <span class="text-gray-800">{{ $application->email }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600 font-medium">{{ __('Téléphone') }}</span>
                            <span class="text-gray-800">{{ $application->phone }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-600 font-medium">{{ __('Territoire souhaité') }}</span>
                            <span class="text-gray-800 font-semibold">{{ $application->desired_area }}</span>
                        </div>
                    </div>
                </div>

                <!-- Documents -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-orange-50 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            {{ __('Documents soumis') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        @forelse($application->documents as $doc)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ ucfirst($doc->kind) }}</p>
                                        <p class="text-sm text-gray-500">{{ $doc->created_at->format('d/m/Y à H:i') }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ __('Reçu') }}
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-gray-500">{{ __('Aucun document soumis') }}</p>
                            </div>
                        @endforelse
                        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                            <p class="text-xs text-blue-700">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                {{ __('Pour des raisons de sécurité, les documents ne sont pas accessibles publiquement.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline des événements -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-orange-50 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ __('Historique de votre candidature') }}
                    </h2>
                </div>
                <div class="p-6">
                    @forelse($application->events as $event)
                        <div class="relative pl-8 pb-8 last:pb-0">
                            <div class="absolute left-0 top-0 w-4 h-4 bg-orange-500 rounded-full border-4 border-white shadow-md"></div>
                            <div class="absolute left-2 top-4 w-px h-full bg-gray-200 last:hidden"></div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="font-semibold text-gray-800">
                                        {{ $event->from_status }} → <span class="text-orange-600">{{ $event->to_status }}</span>
                                    </h3>
                                    <time class="text-sm text-gray-500">{{ $event->created_at->format('d/m/Y à H:i') }}</time>
                                </div>
                                @if($event->message)
                                    <p class="text-gray-600 text-sm">{{ $event->message }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-gray-500">{{ __('Aucun événement pour le moment') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Prochaines étapes -->
            <div class="mt-8 bg-gradient-to-r from-orange-500 to-red-600 rounded-xl shadow-lg text-white p-6">
                <h2 class="text-2xl font-bold mb-4">{{ __('Prochaines étapes') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="text-lg font-bold">1</span>
                        </div>
                        <h3 class="font-semibold mb-2">{{ __('Évaluation') }}</h3>
                        <p class="text-orange-100 text-sm">{{ __('Notre équipe examine votre dossier') }}</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="text-lg font-bold">2</span>
                        </div>
                        <h3 class="font-semibold mb-2">{{ __('Entretien') }}</h3>
                        <p class="text-orange-100 text-sm">{{ __('Rencontre avec notre équipe') }}</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="text-lg font-bold">3</span>
                        </div>
                        <h3 class="font-semibold mb-2">{{ __('Démarrage') }}</h3>
                        <p class="text-orange-100 text-sm">{{ __('Formation et lancement') }}</p>
                    </div>
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
                        {{ __('La première plateforme de franchise de food trucks en France.') }}
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
                        <a href="{{ route('public.applications.create') }}" class="block text-gray-300 hover:text-orange-400">{{ __('Candidater') }}</a>
                        <a href="{{ route('login') }}" class="block text-gray-300 hover:text-orange-400">{{ __('Espace franchisé') }}</a>
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
