@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="page-title">Détails du camion</h1>
    <a href="{{ route('franchise.trucks.edit', ['truck' => $truck]) }}" class="btn-secondary">Éditer</a>
</div>

<div class="card max-w-xl">
    <div class="card-body">
        <dl class="divide-y divide-gray-100">
            <div class="py-3 grid grid-cols-3 gap-4">
                <dt class="text-sm font-medium text-gray-500">Nom</dt>
                <dd class="col-span-2 text-sm text-gray-900">{{ $truck->name }}</dd>
            </div>
            <div class="py-3 grid grid-cols-3 gap-4">
                <dt class="text-sm font-medium text-gray-500">Immatriculation</dt>
                <dd class="col-span-2 text-sm text-gray-900">{{ $truck->license_plate ?? 'N/A' }}</dd>
            </div>
        </dl>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('franchise.trucks.index') }}" class="btn-link">← Retour à mes camions</a>
</div>
@endsection