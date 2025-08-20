@extends('layouts.guest')

@section('content')
<div class="relative py-12">
    <div class="absolute inset-0 -z-10 bg-gradient-to-b from-white to-gray-50"></div>
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-8">
            <span class="inline-flex items-center gap-2 text-amber-700 font-medium">
                <span class="inline-block h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                Rejoindre la franchise
            </span>
            <h1 class="mt-2 text-3xl sm:text-4xl font-bold tracking-tight">Déposez votre candidature</h1>
            <p class="mt-2 text-gray-600">Un membre de l’équipe vous recontactera rapidement après étude de votre dossier.</p>
        </div>

        @if (session('success'))
            <div class="mb-4 p-3 rounded-xl bg-green-50 text-green-800 border border-green-200">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-3 rounded-xl bg-red-50 text-red-800 border border-red-200">
                <strong>Veuillez corriger les erreurs ci-dessous.</strong>
            </div>
        @endif

        <form method="POST" action="{{ route('franchise.apply.post') }}" class="bg-white border rounded-3xl shadow-lg p-6 sm:p-8 space-y-8">
            @csrf

            <div class="space-y-1">
                <h2 class="text-lg font-semibold">Informations de contact</h2>
                <p class="text-sm text-gray-600">Dites-nous qui vous êtes et comment vous joindre.</p>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium">Nom complet <span class="text-red-600">*</span></label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}" placeholder="Prénom Nom" class="input w-full focus:ring-2 focus:ring-amber-500 focus:border-amber-500" required>
                    @error('full_name')<div class="mt-1 text-red-600 text-sm">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">Email <span class="text-red-600">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="vous@exemple.com" class="input w-full focus:ring-2 focus:ring-amber-500 focus:border-amber-500" required>
                    @error('email')<div class="mt-1 text-red-600 text-sm">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">Téléphone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="06 00 00 00 00" class="input w-full focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    @error('phone')<div class="mt-1 text-red-600 text-sm">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="space-y-1">
                <h2 class="text-lg font-semibold">Votre projet</h2>
                <p class="text-sm text-gray-600">Zone d’implantation souhaitée et budget estimatif.</p>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Ville envisagée</label>
                    <input type="text" name="city" value="{{ old('city') }}" placeholder="Ex: Lyon" class="input w-full focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    @error('city')<div class="mt-1 text-red-600 text-sm">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">Budget estimatif (EUR)</label>
                    <input type="number" name="budget" value="{{ old('budget') }}" placeholder="Ex: 60000" class="input w-full focus:ring-2 focus:ring-amber-500 focus:border-amber-500" min="0">
                    <p class="text-xs text-gray-500 mt-1">Recommandé : ≥ 50 000 €</p>
                    @error('budget')<div class="mt-1 text-red-600 text-sm">{{ $message }}</div>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium">Expérience (optionnel)</label>
                <textarea name="experience" rows="3" placeholder="Parlez-nous de votre expérience pertinente." class="input w-full focus:ring-2 focus:ring-amber-500 focus:border-amber-500">{{ old('experience') }}</textarea>
                @error('experience')<div class="mt-1 text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Motivation <span class="text-red-600">*</span></label>
                <textarea name="motivation" rows="5" placeholder="Pourquoi Driv'n Cook ? Quels sont vos objectifs ?" class="input w-full focus:ring-2 focus:ring-amber-500 focus:border-amber-500" required>{{ old('motivation') }}</textarea>
                @error('motivation')<div class="mt-1 text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>

            <div class="flex items-start gap-3 text-sm">
                <input type="checkbox" id="gdpr" name="gdpr" value="1" class="mt-1" required>
                <label for="gdpr">J’accepte que mes données soient utilisées pour l’étude de ma candidature.</label>
            </div>
            @error('gdpr')<div class="-mt-2 text-red-600 text-sm">{{ $message }}</div>@enderror

            <div class="pt-2 flex flex-col sm:flex-row gap-3">
                <button type="submit" class="btn btn-primary h-11 w-full sm:w-auto">Envoyer ma candidature</button>
                <a href="mailto:contact@drivncook.example" class="btn btn-secondary h-11 w-full sm:w-auto">Parler à un expert</a>
            </div>
        </form>
    </div>
    </div>
@endsection
