@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="page-title">Franchisé: {{ $franchise->name }}</h1>
    <div class="flex gap-2">
        <a href="{{ route('admin.compliance.edit', ['franchisee' => $franchise->getRouteKey(), 'year' => now()->year, 'month' => now()->month]) }}" class="btn-secondary">Compliance</a>
        <a href="{{ route('admin.franchisees.edit', ['franchisee' => $franchise->getRouteKey()]) }}" class="btn-secondary">Éditer</a>
    </div>
    </div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="card">
    <div class="card-header"><h2 class="font-semibold">Résumé</h2></div>
        <div class="card-body">
            <dl class="text-sm text-gray-700 space-y-2">
                <div class="flex justify-between"><dt>Camions</dt><dd>{{ $franchise->trucks->count() }}</dd></div>
                <div class="flex justify-between"><dt>Entrepôts</dt><dd>{{ $franchise->warehouses->count() }}</dd></div>
                <div class="flex justify-between"><dt>Utilisateurs</dt><dd>{{ $franchise->users->count() }}</dd></div>
            </dl>
        </div>
    </div>

    <div class="card md:col-span-2">
    <div class="card-header"><h2 class="font-semibold">Camions</h2></div>
        <div class="card-body p-0">
            <table class="data-table">
                <thead><tr><th>Name</th><th>Plate</th></tr></thead>
                <tbody>
                    @forelse($franchise->trucks as $truck)
                        <tr><td>{{ $truck->name }}</td><td>{{ $truck->license_plate }}</td></tr>
                    @empty
                        <tr><td colspan="2" class="px-4 py-3 text-gray-500">Aucun camion.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mt-6">
    <div class="card-header"><h2 class="font-semibold">Entrepôts</h2></div>
    <div class="card-body p-0">
        <table class="data-table">
            <thead><tr><th>Name</th><th>Location</th></tr></thead>
            <tbody>
                @forelse($franchise->warehouses as $warehouse)
                    <tr><td>{{ $warehouse->name }}</td><td>{{ $warehouse->location }}</td></tr>
                @empty
                    <tr><td colspan="2" class="px-4 py-3 text-gray-500">Aucun entrepôt.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
    <div class="card">
        <div class="card-header"><h2 class="font-semibold">Rattacher un utilisateur</h2></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.franchisees.users.attach', ['franchisee' => $franchise->getRouteKey()]) }}" class="space-y-3 max-w-md">
                @csrf
                <div>
                    <label class="form-label">Email de l'utilisateur</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email') }}" placeholder="user@example.com">
                    @error('email') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="btn-primary">Rattacher</button>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h2 class="font-semibold">Utilisateurs rattachés</h2></div>
        <div class="card-body p-0">
            <table class="data-table">
                <thead><tr><th>Nom</th><th>Email</th><th class="text-center">Actions</th></tr></thead>
                <tbody>
                @forelse($franchise->users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td class="text-center">
                            <form method="POST" action="{{ route('admin.franchisees.users.detach', ['franchisee' => $franchise->getRouteKey(), 'user' => $user->id]) }}" onsubmit="return confirm('Détacher cet utilisateur ?')" class="inline">
                                @csrf @method('DELETE')
                                <button class="btn-link text-red-600" type="submit">Détacher</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-3 text-gray-500">Aucun utilisateur rattaché.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
