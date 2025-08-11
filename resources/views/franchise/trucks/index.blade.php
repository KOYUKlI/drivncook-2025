@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="page-title">Mes camions</h1>
    <a href="{{ route('franchise.trucks.create') }}" class="btn-primary">Ajouter un camion</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Immatriculation</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($trucks as $truck)
                <tr>
                    <td>{{ $truck->name }}</td>
                    <td>{{ $truck->license_plate }}</td>
                    <td class="text-center space-x-2">
                        <a href="{{ route('franchise.trucks.show', ['truck' => $truck]) }}" class="btn-link">Voir</a>
                        <a href="{{ route('franchise.trucks.edit', ['truck' => $truck]) }}" class="btn-link">Éditer</a>
                        <button type="button" class="btn-link text-red-600" x-data x-on:click="$dispatch('open-modal', 'delete-truck-{{ $truck->id }}')">Supprimer</button>
                        <x-confirm-delete :name="'delete-truck-' . $truck->id"
                            :action="route('franchise.trucks.destroy', ['truck' => $truck])"
                            title="Supprimer le camion"
                            :message="'Supprimer ' . $truck->name . ' ?'" />
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection