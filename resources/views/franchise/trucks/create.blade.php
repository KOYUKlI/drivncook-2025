@extends('layouts.app')

@section('content')
<h1 class="page-title mb-4">Ajouter un camion</h1>

<form action="{{ route('franchise.trucks.store') }}" method="POST" class="max-w-md">
    @csrf
    <div class="form-group">
    <label class="form-label">Nom du camion</label>
        <input type="text" name="name" value="{{ old('name') }}" class="form-input">
        @error('name') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="form-group">
        <label class="form-label">Immatriculation (optionnel)</label>
        <input type="text" name="license_plate" value="{{ old('license_plate') }}" class="form-input" placeholder="AA-123-AA" pattern="[A-Z0-9\-\s]{1,15}">
        @error('license_plate') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <button type="submit" class="btn-primary">Ajouter</button>
    <a href="{{ route('franchise.trucks.index') }}" class="btn-link ml-3">Annuler</a>
</form>
@endsection