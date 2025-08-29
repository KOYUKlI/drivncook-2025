@extends('layouts.app-shell')

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Test de la nouvelle sidebar --}}
    <div class="bg-white rounded-lg shadow-sm border border-orange-100 p-6">
        <div class="flex items-center gap-x-3 mb-6">
            <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-orange-500 to-amber-500 flex items-center justify-center">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Nouvelle Sidebar ✨</h1>
                <p class="text-orange-600">Test de la sidebar moderne avec thème orangé</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Improvements --}}
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <h3 class="font-semibold text-green-800 mb-3 flex items-center gap-x-2">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Améliorations
                </h3>
                <ul class="text-sm text-green-700 space-y-1">
                    <li>• Navigation épurée par rôle</li>
                    <li>• Thème orangé subtil</li>
                    <li>• Pas de superposition</li>
                    <li>• Design moderne et clean</li>
                    <li>• Mobile responsive</li>
                </ul>
            </div>

            {{-- Navigation structure --}}
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                <h3 class="font-semibold text-orange-800 mb-3 flex items-center gap-x-2">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    Navigation
                </h3>
                <ul class="text-sm text-orange-700 space-y-1">
                    <li>• Admin: 6 items essentiels</li>
                    <li>• Warehouse: 3 items</li>
                    <li>• Fleet/Tech: 2 items</li>
                    <li>• Franchisé: 2 items</li>
                    <li>• Breadcrumbs intelligents</li>
                </ul>
            </div>

            {{-- Technical details --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="font-semibold text-blue-800 mb-3 flex items-center gap-x-2">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Technique
                </h3>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li>• Position: fixed sans overlap</li>
                    <li>• Width: 256px (w-64)</li>
                    <li>• Header: h-16 (64px)</li>
                    <li>• Footer: h-16 (64px)</li>
                    <li>• Alpine.js pour mobile</li>
                </ul>
            </div>
        </div>

        {{-- Current user info --}}
        <div class="mt-8 bg-gray-50 rounded-lg p-4">
            <h3 class="font-semibold text-gray-800 mb-3">Informations utilisateur actuel</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="font-medium text-gray-600">Nom:</span>
                    <span class="text-gray-900">{{ Auth::user()->name ?? 'Non connecté' }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-600">Rôle:</span>
                    <span class="text-orange-600">{{ Auth::user()->getRoleNames()->first() ?? 'Aucun' }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-600">Route actuelle:</span>
                    <span class="text-gray-900">{{ request()->route()->getName() }}</span>
                </div>
            </div>
        </div>

        {{-- Navigation test links --}}
        <div class="mt-8">
            <h3 class="font-semibold text-gray-800 mb-4">Test de navigation</h3>
            <div class="flex flex-wrap gap-3">
                @if(Auth::user()->hasRole('admin'))
                    <a href="{{ route('bo.dashboard') }}" class="px-4 py-2 bg-orange-100 text-orange-700 rounded-lg hover:bg-orange-200 transition-colors">
                        Dashboard
                    </a>
                    <a href="{{ route('bo.applications.index') }}" class="px-4 py-2 bg-orange-100 text-orange-700 rounded-lg hover:bg-orange-200 transition-colors">
                        Applications
                    </a>
                    <a href="{{ route('bo.franchisees.index') }}" class="px-4 py-2 bg-orange-100 text-orange-700 rounded-lg hover:bg-orange-200 transition-colors">
                        Franchisés
                    </a>
                    <a href="{{ route('bo.trucks.index') }}" class="px-4 py-2 bg-orange-100 text-orange-700 rounded-lg hover:bg-orange-200 transition-colors">
                        Camions
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
