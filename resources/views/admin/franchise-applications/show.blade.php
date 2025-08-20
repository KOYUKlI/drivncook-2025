@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('admin.franchise-applications.index') }}" class="text-sm text-gray-600 hover:underline">← Retour</a>
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
            {{ $application->status === 'pending' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
            {{ $application->status === 'accepted' ? 'bg-green-50 text-green-700 border border-green-200' : '' }}
            {{ $application->status === 'rejected' ? 'bg-red-50 text-red-700 border border-red-200' : '' }}
        ">
            {{ ucfirst($application->status) }}
        </span>
    </div>
    <h1 class="text-2xl font-semibold mb-6">Candidature #{{ $application->id }}</h1>

    <div class="grid md:grid-cols-3 gap-6">
        <!-- Details -->
        <div class="md:col-span-2">
            <div class="bg-white border rounded-xl p-6 space-y-4">
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-gray-500">Nom</div>
                        <div class="font-medium">{{ $application->full_name }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Email</div>
                        <div class="font-medium">{{ $application->email }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Téléphone</div>
                        <div class="font-medium">{{ $application->phone ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Ville</div>
                        <div class="font-medium">{{ $application->city ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Budget</div>
                        <div class="font-medium">{{ $application->budget ? number_format($application->budget,0,',',' ') . ' €' : '—' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Expérience</div>
                        <div class="font-medium">{{ $application->experience ?? '—' }}</div>
                    </div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Motivation</div>
                    <div class="mt-1 whitespace-pre-line">{{ $application->motivation }}</div>
                </div>
            </div>
        </div>

        <!-- Approval Card -->
        <div>
            <div class="bg-white border rounded-xl p-6">
                <h2 class="text-lg font-semibold">Traitement</h2>
                @if($application->status === 'pending')
                    <form method="POST" action="{{ route('admin.franchise-applications.approve', $application->id) }}" class="mt-4 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium">Nom de la franchise <span class="text-red-600">*</span></label>
                            <input type="text" name="franchise_name" value="{{ old('franchise_name', $application->city ? ("Driv'n Cook — " . $application->city) : '') }}" class="input w-full" required>
                            <p class="text-xs text-gray-500 mt-1">Ex: Driv'n Cook — [Ville]</p>
                            @error('franchise_name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>
                        <button class="btn btn-primary w-full" onclick="return confirm('Approuver cette candidature ?')">Approuver</button>
                    </form>
                    <form method="POST" action="{{ route('admin.franchise-applications.reject', $application->id) }}" class="mt-3">
                        @csrf
                        <button class="btn btn-secondary w-full" onclick="return confirm('Rejeter cette candidature ?')">Rejeter</button>
                    </form>
                @else
                    <div class="mt-3 text-sm text-gray-600">Cette candidature est <strong>{{ ucfirst($application->status) }}</strong>.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
