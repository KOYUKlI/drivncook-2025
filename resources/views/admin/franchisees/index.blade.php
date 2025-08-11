@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="page-title">Franchisés</h1>
    <a href="{{ route('admin.franchisees.create') }}" class="btn-primary">Ajouter un franchisé</a>
    </div>

<div class="card">
    <div class="card-body p-0">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Camions</th>
                    <th>Entrepôts</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($franchises as $franchise)
                    <tr>
                        <td>{{ $franchise->name }}</td>
                        <td>{{ $franchise->trucks_count ?? $franchise->trucks()->count() }}</td>
                        <td>{{ $franchise->warehouses_count ?? $franchise->warehouses()->count() }}</td>
                        <td class="text-center space-x-2">
                            <a href="{{ route('admin.franchisees.show', ['franchisee' => $franchise->getRouteKey()]) }}" class="btn-link">Voir</a>
                            <a href="{{ route('admin.franchisees.edit', ['franchisee' => $franchise->getRouteKey()]) }}" class="btn-link">Éditer</a>
                            <a href="{{ route('admin.compliance.edit', ['franchisee' => $franchise->getRouteKey(), 'year' => now()->year, 'month' => now()->month]) }}" class="btn-link">Compliance</a>
                            <button type="button" class="btn-link text-red-600" x-data x-on:click="$dispatch('open-modal', 'delete-franchise-{{ $franchise->id }}')">Supprimer</button>
                            <x-confirm-delete :name="'delete-franchise-' . $franchise->id"
                                :action="route('admin.franchisees.destroy', ['franchisee' => $franchise->getRouteKey()])"
                                title="Supprimer le franchisé"
                                :message="'Supprimer ' . $franchise->name . ' ?'" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $franchises->links() }}
    </div>
@endsection
